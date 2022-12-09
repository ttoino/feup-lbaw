------------------------------------------------------------
-- Triggers
------------------------------------------------------------

-- TRIGGER01
CREATE OR REPLACE FUNCTION reorder_task_groups() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE task_group
        SET position = position + SIGN(OLD.position - NEW.position)
        WHERE id <> OLD.id AND position BETWEEN SYMMETRIC OLD.position AND NEW.position;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS reorder_task_groups ON task_group;
CREATE TRIGGER reorder_task_groups
    BEFORE UPDATE OF position ON task_group
    FOR EACH ROW
    WHEN (pg_trigger_depth() = 0)
    EXECUTE FUNCTION reorder_task_groups();

-- TRIGGER02
CREATE OR REPLACE FUNCTION reorder_tasks() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF OLD.task_group = NEW.task_group THEN
        UPDATE task
            SET position = position + SIGN(OLD.position - NEW.position)
            WHERE id <> OLD.id AND task_group = OLD.task_group
            AND position BETWEEN SYMMETRIC OLD.position AND NEW.position;
    ELSE
        UPDATE task
            SET position = position - 1
            WHERE id <> OLD.id AND task_group = OLD.task_group AND position > OLD.position;
        UPDATE task
            SET position = position + 1
            WHERE id <> OLD.id AND task_group = NEW.task_group AND position >= NEW.position;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS reorder_tasks ON task;
CREATE TRIGGER reorder_tasks
    BEFORE UPDATE OF position, task_group ON task
    FOR EACH ROW
    WHEN (pg_trigger_depth() = 0)
    EXECUTE FUNCTION reorder_tasks();

-- TRIGGER03
CREATE OR REPLACE FUNCTION invalid_task_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (
            SELECT p.id
            FROM tag
            JOIN project p ON tag.project = p.id
            WHERE tag.id = NEW.tag
        )
        <>
        (
            SELECT p.id
            FROM task
            JOIN task_group ON task.task_group = task_group.id
            JOIN project p ON task_group.project = p.id
            WHERE task.id = NEW.task
        ) THEN
        RAISE EXCEPTION 'Cannot apply tag to task of another project!';
    ELSE
        RETURN NEW;
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS invalid_task_tag ON task_tag;
CREATE TRIGGER invalid_task_tag
    BEFORE INSERT ON task_tag
    FOR EACH ROW
    EXECUTE FUNCTION invalid_task_tag();

-- TRIGGER04
CREATE OR REPLACE FUNCTION validate_notification_type() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- in the future we can support multiple entities referenced.
    IF ((NEW.invitation IS NOT NULL)::INTEGER + (NEW.thread IS NOT NULL)::INTEGER + (NEW.thread_comment IS NOT NULL)::INTEGER + (NEW.task IS NOT NULL)::INTEGER + (NEW.task_comment IS NOT NULL)::INTEGER + (NEW.project IS NOT NULL)::INTEGER = 1) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Invalid notification data for %!', NEW.type;
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_notification_type ON notification;
CREATE TRIGGER validate_notification_type
    BEFORE INSERT ON notification
    FOR EACH ROW
    EXECUTE FUNCTION validate_notification_type();

-- TRIGGER05
CREATE OR REPLACE FUNCTION validate_assignee_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project_member pm JOIN task t ON t.id = NEW.task JOIN task_group tg ON tg.id = t.task_group WHERE pm.user_profile = NEW.user_profile AND tg.project = pm.project) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Task assignee must be a member of the task''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_assignee_project_member ON notification;
CREATE TRIGGER validate_assignee_project_member
    BEFORE INSERT OR UPDATE ON task_assignee
    FOR EACH ROW
    EXECUTE FUNCTION validate_assignee_project_member();

-- TRIGGER06
CREATE OR REPLACE FUNCTION validate_task_comment_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project_member pm JOIN task t ON t.id = NEW.task JOIN task_group tg ON tg.id = t.task_group WHERE pm.user_profile = NEW.author AND tg.project = pm.project) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Task comment author must be a member of the task''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_task_comment_author_project_member ON notification;
CREATE TRIGGER validate_task_comment_author_project_member
    BEFORE INSERT ON task_comment
    FOR EACH ROW
    EXECUTE FUNCTION validate_task_comment_author_project_member();

-- TRIGGER07
CREATE OR REPLACE FUNCTION validate_thread_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project_member WHERE project = NEW.project AND user_profile = NEW.author) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Thread author must be a member of the thread''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_thread_author_project_member ON notification;
CREATE TRIGGER validate_thread_author_project_member
    BEFORE INSERT ON thread
    FOR EACH ROW
    EXECUTE FUNCTION validate_thread_author_project_member();

-- TRIGGER08
CREATE OR REPLACE FUNCTION validate_thread_comment_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project_member pm JOIN thread t ON NEW.thread = t.id WHERE t.project = pm.project AND pm.user_profile = NEW.author) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Thread author must be a member of the thread''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_thread_comment_author_project_member ON notification;
CREATE TRIGGER validate_thread_comment_author_project_member
    BEFORE INSERT ON thread_comment
    FOR EACH ROW
    EXECUTE FUNCTION validate_thread_comment_author_project_member();

-- TRIGGER09
CREATE OR REPLACE FUNCTION add_coordinator_as_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO project_member VALUES (NEW.coordinator, NEW.id, 'false');
    END IF;
    IF TG_OP = 'UPDATE' THEN
        UPDATE project_member SET user_profile = NEW.coordinator WHERE user_profile = OLD.coordinator AND project = NEW.id;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS add_coordinator_as_project_member ON project;
CREATE TRIGGER add_coordinator_as_project_member
    AFTER INSERT OR UPDATE OF coordinator ON project
    FOR EACH ROW
    EXECUTE FUNCTION add_coordinator_as_project_member();
