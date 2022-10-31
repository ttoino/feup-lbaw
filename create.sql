------------------------------------------------------------
-- Drop old schema
------------------------------------------------------------

DROP TABLE IF EXISTS
    user_profile,
    project,
    project_invitation,
    project_timeline_action,
    task_group,
    task,
    task_comment,
    tag,
    thread,
    thread_comment,
    notification,
    report,
    project_member,
    task_assignee,
    task_tag
CASCADE;

DROP TYPE IF EXISTS TODAY, TASK_STATE, COLOR, NOTIFICATION_TYPE CASCADE;

------------------------------------------------------------
-- Types
------------------------------------------------------------

CREATE DOMAIN TODAY AS TIMESTAMP DEFAULT CURRENT_TIMESTAMP CHECK (VALUE <= CURRENT_TIMESTAMP);

CREATE TYPE TASK_STATE AS ENUM ('created', 'member_assigned', 'completed');
CREATE TYPE NOTIFICATION_TYPE AS ENUM (
    'invitation_notification',
    'thread_notification',
    'thread_comment_notification',
    'task_notification',
    'task_comment_notification',
    'project_notification'
);

CREATE DOMAIN COLOR AS INTEGER;

------------------------------------------------------------
-- Tables
------------------------------------------------------------

CREATE TABLE user_profile (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    blocked BOOLEAN NOT NULL DEFAULT false,
    is_admin BOOLEAN NOT NULL DEFAULT false
);

CREATE TABLE project (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    creation_date TODAY NOT NULL,
    last_modification_date TIMESTAMP,
    archived BOOLEAN NOT NULL DEFAULT false,
    coordinator INTEGER NOT NULL,
    FOREIGN KEY (coordinator) REFERENCES user_profile ON DELETE RESTRICT
);

CREATE TABLE task_group (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    creation_date TODAY NOT NULL,
    position INTEGER NOT NULL,
    project INTEGER NOT NULL,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE,
    UNIQUE (position, project) DEFERRABLE INITIALLY DEFERRED
);

CREATE TABLE project_invitation (
    id SERIAL PRIMARY KEY,
    expiration_date TIMESTAMP NOT NULL,
    creator INTEGER NOT NULL,
    project INTEGER NOT NULL,
    FOREIGN KEY (creator) REFERENCES user_profile ON DELETE CASCADE,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE
);

CREATE TABLE project_timeline_action (
    id SERIAL PRIMARY KEY,
    timestamp TODAY NOT NULL,
    description TEXT NOT NULL,
    project INTEGER NOT NULL,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE
);

CREATE TABLE task (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    creation_date TODAY NOT NULL,
    edit_date TIMESTAMP CHECK (edit_date <= CURRENT_TIMESTAMP),
    state TASK_STATE NOT NULL DEFAULT 'created',
    creator INTEGER,
    position INTEGER NOT NULL,
    task_group INTEGER NOT NULL,
    FOREIGN KEY (creator) REFERENCES user_profile ON DELETE SET NULL,
    FOREIGN KEY (task_group) REFERENCES task_group ON DELETE CASCADE,
    UNIQUE (position, task_group) DEFERRABLE INITIALLY DEFERRED
);

CREATE TABLE task_comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    creation_date TODAY NOT NULL,
    edit_date TIMESTAMP CHECK (edit_date <= CURRENT_TIMESTAMP),
    author INTEGER,
    task INTEGER NOT NULL,
    FOREIGN KEY (author) REFERENCES user_profile ON DELETE SET NULL,
    FOREIGN KEY (task) REFERENCES task ON DELETE CASCADE
);

CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT,
    color COLOR NOT NULL,
    project INTEGER NOT NULL,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE
);

CREATE TABLE thread (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    creation_date TODAY NOT NULL,
    edit_date TIMESTAMP CHECK (edit_date <= CURRENT_TIMESTAMP),
    author INTEGER,
    project INTEGER NOT NULL,
    FOREIGN KEY (author) REFERENCES user_profile ON DELETE SET NULL,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE
);

CREATE TABLE thread_comment (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    creation_date TODAY NOT NULL,
    edit_date TIMESTAMP CHECK (edit_date <= CURRENT_TIMESTAMP),
    thread INTEGER NOT NULL,
    author INTEGER,
    FOREIGN KEY (thread) REFERENCES thread ON DELETE CASCADE,
    FOREIGN KEY (author) REFERENCES user_profile ON DELETE SET NULL
);

CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    type NOTIFICATION_TYPE NOT NULL,
    creation_date TODAY NOT NULL,
    dismissed BOOLEAN NOT NULL DEFAULT FALSE,
    notified_user INTEGER NOT NULL,
    invitation INTEGER,
    thread INTEGER,
    thread_comment INTEGER,
    task INTEGER,
    task_comment INTEGER,
    project INTEGER,
    FOREIGN KEY (notified_user) REFERENCES user_profile ON DELETE CASCADE,
    FOREIGN KEY (invitation) REFERENCES project_invitation ON DELETE CASCADE,
    FOREIGN KEY (thread) REFERENCES thread ON DELETE CASCADE,
    FOREIGN KEY (thread_comment) REFERENCES thread_comment ON DELETE CASCADE,
    FOREIGN KEY (task) REFERENCES task ON DELETE CASCADE,
    FOREIGN KEY (task_comment) REFERENCES task_comment ON DELETE CASCADE,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE,
    CHECK (
        (invitation IS NOT NULL)::INTEGER + (thread IS NOT NULL)::INTEGER +
        (thread_comment IS NOT NULL)::INTEGER + (task IS NOT NULL)::INTEGER +
        (task_comment IS NOT NULL)::INTEGER + (project IS NOT NULL)::INTEGER = 1
    )
);

CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    creation_date TODAY NOT NULL,
    reason TEXT NOT NULL,
    project INTEGER,
    user_profile INTEGER,
    creator INTEGER,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE,
    FOREIGN KEY (user_profile) REFERENCES user_profile ON DELETE CASCADE,
    FOREIGN KEY (creator) REFERENCES user_profile ON DELETE SET NULL,
    CHECK ((project IS NULL)::INTEGER + (user_profile IS NULL)::INTEGER = 1)
);

CREATE TABLE project_member (
    user_profile INTEGER,
    project INTEGER,
    is_favorite BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (user_profile, project),
    FOREIGN KEY (user_profile) REFERENCES user_profile ON DELETE CASCADE,
    FOREIGN KEY (project) REFERENCES project ON DELETE CASCADE
);

CREATE TABLE task_assignee (
    user_profile INTEGER,
    task INTEGER,
    PRIMARY KEY (user_profile, task),
    FOREIGN KEY (user_profile) REFERENCES user_profile ON DELETE CASCADE,
    FOREIGN KEY (task) REFERENCES task ON DELETE CASCADE
);

CREATE TABLE task_tag (
    task INTEGER,
    tag INTEGER,
    PRIMARY KEY (task, tag),
    FOREIGN KEY (task) REFERENCES task ON DELETE CASCADE,
    FOREIGN KEY (tag) REFERENCES tag ON DELETE CASCADE
);

------------------------------------------------------------
-- Indices
------------------------------------------------------------

-- IDX101
CREATE INDEX notification_search_idx ON notification USING HASH (notified_user);

-- IDX102
CREATE INDEX task_group_project ON task_group USING HASH (project);

-- IDX103
CREATE INDEX task_task_group ON task USING HASH (task_group);

-- IDX201
-- introduce auxiliary field to support FTS
ALTER TABLE task ADD COLUMN fts_search TSVECTOR;
-- helper View to facilitate collecting tags and respective tasks
DROP VIEW IF EXISTS fts_task_tag;
CREATE VIEW fts_task_tag AS
    SELECT tsk_tg.task AS task_id, string_agg(tg.title, ' ') AS task_tag_names
    FROM task_tag tsk_tg
    JOIN tag tg ON tg.id = tsk_tg.tag
    GROUP BY tsk_tg.task;
-- automatically keep ts_vectors up to date so that FTS is up to date
CREATE OR REPLACE FUNCTION task_fts_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.fts_search = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
            setweight(to_tsvector('english', (SELECT task_tag_names FROM fts_task_tag WHERE task_id = NEW.id)), 'C')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.fts_search = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
                setweight(to_tsvector('english', (SELECT task_tag_names FROM fts_task_tag WHERE task_id = NEW.id)), 'C')
            );  
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;
--
CREATE OR REPLACE FUNCTION task_tag_fts_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        CREATE TEMP TABLE curr_task AS
            SELECT * FROM task WHERE id = NEW.task;
        UPDATE task SET fts_search=(
            setweight(to_tsvector('english', (SELECT name FROM curr_task)), 'A') || 
            setweight(to_tsvector('english', COALESCE((SELECT description FROM curr_task), '')), 'B') ||
            setweight(to_tsvector('english', (
                SELECT task_tag_names FROM fts_task_tag WHERE task_id = (SELECT id FROM curr_task)
            )), 'C')
        ) WHERE id = (SELECT id FROM curr_task);
    END IF;
    IF TG_OP = 'DELETE' THEN
        CREATE TEMP TABLE curr_task AS
            SELECT * FROM task WHERE id = OLD.task;
        UPDATE task SET fts_search=(
            setweight(to_tsvector('english', (SELECT name FROM curr_task)), 'A') || 
            setweight(to_tsvector('english', COALESCE((SELECT description FROM curr_task), '')), 'B') ||
            setweight(to_tsvector('english', (
                SELECT task_tag_names FROM fts_task_tag WHERE task_id = (SELECT id FROM curr_task)
            )), 'C')
        ) WHERE id = (SELECT id FROM curr_task);  
    END IF;
    -- cleanup
    DROP TABLE IF EXISTS curr_task;
    RETURN NEW;
END $$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS task_search_update ON task;
CREATE TRIGGER task_search_update
    BEFORE INSERT OR UPDATE of name, description ON task
    FOR EACH ROW
    EXECUTE PROCEDURE task_fts_update();
--
DROP TRIGGER IF EXISTS task_tag_search_update ON task_tag;
CREATE TRIGGER task_tag_search_update
    AFTER INSERT OR DELETE ON task_tag
    FOR EACH ROW
    EXECUTE PROCEDURE task_tag_fts_update();
-- create index to make searches performant on the FTS auxiliary field
CREATE INDEX task_fts_idx ON task USING GIN (fts_search);

-- IDX202
-- introduce auxiliary field to support FTS
ALTER TABLE project ADD COLUMN fts_search TSVECTOR;
-- automatically keep ts_vectors up to date so that FTS is up to date
CREATE OR REPLACE FUNCTION project_fts_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.fts_search = (
            setweight(to_tsvector('english', NEW.name), 'A') || 
            setweight(to_tsvector('english', (
                SELECT name FROM user_profile WHERE id = NEW.coordinator
            )), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.coordinator <> OLD.coordinator) THEN
            NEW.fts_search = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', (
                    SELECT name FROM user_profile WHERE id = NEW.coordinator
                )), 'B')
            );  
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;
--
DROP TRIGGER IF EXISTS project_search_update ON project;
CREATE TRIGGER project_search_update
    BEFORE INSERT OR UPDATE ON project
    FOR EACH ROW
    EXECUTE PROCEDURE project_fts_update();
-- create index to make searches performant on the FTS auxiliary field
CREATE INDEX project_fts_idx ON task USING GIN (fts_search);

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
