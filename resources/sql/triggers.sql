------------------------------------------------------------
-- Triggers
------------------------------------------------------------

SET search_path TO lbaw2265;

-- TRIGGER01
CREATE OR REPLACE FUNCTION reorder_task_groups() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE lbaw2265.task_group
        SET position = position + SIGN(OLD.position - NEW.position)
        WHERE id <> OLD.id AND project_id = OLD.project_id AND position BETWEEN SYMMETRIC OLD.position AND NEW.position;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS reorder_task_groups ON lbaw2265.task_group;
CREATE TRIGGER reorder_task_groups
    BEFORE UPDATE OF position ON lbaw2265.task_group
    FOR EACH ROW
    WHEN (pg_trigger_depth() = 0)
    EXECUTE FUNCTION reorder_task_groups();

-- TRIGGER02
CREATE OR REPLACE FUNCTION reorder_tasks() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF OLD.task_group_id = NEW.task_group_id THEN
        UPDATE lbaw2265.task
            SET position = position + SIGN(OLD.position - NEW.position)
            WHERE id <> OLD.id AND task_group_id = OLD.task_group_id
            AND position BETWEEN SYMMETRIC OLD.position AND NEW.position;
    ELSE
        UPDATE lbaw2265.task
            SET position = position - 1
            WHERE id <> OLD.id AND task_group_id = OLD.task_group_id AND position > OLD.position;
        UPDATE lbaw2265.task
            SET position = position + 1
            WHERE id <> OLD.id AND task_group_id = NEW.task_group_id AND position >= NEW.position;
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS reorder_tasks ON lbaw2265.task;
CREATE TRIGGER reorder_tasks
    BEFORE UPDATE OF position, task_group_id ON lbaw2265.task
    FOR EACH ROW
    WHEN (pg_trigger_depth() = 0)
    EXECUTE FUNCTION reorder_tasks();

-- TRIGGER03
CREATE OR REPLACE FUNCTION invalid_task_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (
            SELECT p.id
            FROM lbaw2265.tag
            JOIN lbaw2265.project p ON tag.project_id = p.id
            WHERE tag.id = NEW.tag_id
        )
        <>
        (
            SELECT p.id
            FROM lbaw2265.task
            JOIN lbaw2265.task_group ON task.task_group_id = task_group.id
            JOIN lbaw2265.project p ON task_group.project_id = p.id
            WHERE task.id = NEW.task_id
        ) THEN
        RAISE EXCEPTION 'Cannot apply tag to task of another project!';
    ELSE
        RETURN NEW;
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS invalid_task_tag ON lbaw2265.task_tag;
CREATE TRIGGER invalid_task_tag
    BEFORE INSERT ON lbaw2265.task_tag
    FOR EACH ROW
    EXECUTE FUNCTION invalid_task_tag();

-- TRIGGER05
CREATE OR REPLACE FUNCTION validate_assignee_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM lbaw2265.project_member pm JOIN lbaw2265.task t ON t.id = NEW.task_id JOIN lbaw2265.task_group tg ON tg.id = t.task_group_id WHERE pm.user_profile_id = NEW.user_profile_id AND tg.project_id = pm.project_id) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Task assignee must be a member of the task''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_assignee_project_member ON lbaw2265.task_assignee;
CREATE TRIGGER validate_assignee_project_member
    BEFORE INSERT OR UPDATE ON lbaw2265.task_assignee
    FOR EACH ROW
    EXECUTE FUNCTION validate_assignee_project_member();

-- TRIGGER06
CREATE OR REPLACE FUNCTION validate_task_comment_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM lbaw2265.project_member pm JOIN lbaw2265.task t ON t.id = NEW.task_id JOIN lbaw2265.task_group tg ON tg.id = t.task_group_id WHERE pm.user_profile_id = NEW.author_id AND tg.project_id = pm.project_id) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Task comment author must be a member of the task''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_task_comment_author_project_member ON lbaw2265.task_comment;
CREATE TRIGGER validate_task_comment_author_project_member
    BEFORE INSERT ON lbaw2265.task_comment
    FOR EACH ROW
    EXECUTE FUNCTION validate_task_comment_author_project_member();

-- TRIGGER07
CREATE OR REPLACE FUNCTION validate_thread_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM lbaw2265.project_member WHERE project_id = NEW.project_id AND user_profile_id = NEW.author_id) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Thread author must be a member of the thread''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_thread_author_project_member ON lbaw2265.thread;
CREATE TRIGGER validate_thread_author_project_member
    BEFORE INSERT ON lbaw2265.thread
    FOR EACH ROW
    EXECUTE FUNCTION validate_thread_author_project_member();

-- TRIGGER08
CREATE OR REPLACE FUNCTION validate_thread_comment_author_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM lbaw2265.project_member pm JOIN lbaw2265.thread t ON NEW.thread_id = t.id WHERE t.project_id = pm.project_id AND pm.user_profile_id = NEW.author_id) THEN
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Thread author must be a member of the thread''s project!';
    END IF;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS validate_thread_comment_author_project_member ON lbaw2265.thread_comment;
CREATE TRIGGER validate_thread_comment_author_project_member
    BEFORE INSERT ON lbaw2265.thread_comment
    FOR EACH ROW
    EXECUTE FUNCTION validate_thread_comment_author_project_member();

-- TRIGGER09
CREATE OR REPLACE FUNCTION add_coordinator_as_project_member() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO lbaw2265.project_member VALUES (NEW.coordinator_id, NEW.id, 'false');
    END IF;
    IF TG_OP = 'UPDATE' THEN
        UPDATE lbaw2265.project_member SET user_profile_id = NEW.coordinator_id WHERE user_profile_id = OLD.coordinator_id AND project_id = NEW.id;
    END IF;

    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS add_coordinator_as_project_member ON lbaw2265.project;
CREATE TRIGGER add_coordinator_as_project_member
    AFTER INSERT OR UPDATE OF coordinator_id ON lbaw2265.project
    FOR EACH ROW
    EXECUTE FUNCTION add_coordinator_as_project_member();
