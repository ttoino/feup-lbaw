------------------------------------------------------------
-- Indexes
------------------------------------------------------------

SET search_path TO lbaw2265;

-- IDX101
CREATE INDEX notification_search_idx ON notification USING HASH (notifiable_id);

-- IDX102
CREATE INDEX task_group_project ON task_group (project_id, position);

-- IDX103
CREATE INDEX task_task_group ON task (task_group_id, position);

-- IDX201
-- introduce auxiliary field to support FTS
ALTER TABLE task ADD COLUMN fts_search TSVECTOR;
-- helper View to facilitate collecting tags and respective tasks
DROP VIEW IF EXISTS fts_task_tag;
CREATE VIEW fts_task_tag AS
    SELECT tsk_tg.task_id AS task_id, string_agg(tg.title, ' ') AS task_tag_names
    FROM task_tag tsk_tg
    JOIN tag tg ON tg.id = tsk_tg.tag_id
    GROUP BY tsk_tg.task_id;
-- automatically keep ts_vectors up to date so that FTS is up to date
CREATE OR REPLACE FUNCTION task_fts_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.fts_search = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT task_tag_names FROM fts_task_tag WHERE task_id = NEW.id), '')), 'C')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
            NEW.fts_search = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
                setweight(to_tsvector('english', COALESCE((SELECT task_tag_names FROM fts_task_tag WHERE task_id = NEW.id), '')), 'C')
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
            SELECT * FROM task WHERE id = NEW.task_id;
        UPDATE task SET fts_search=(
            setweight(to_tsvector('english', (SELECT name FROM curr_task)), 'A') || 
            setweight(to_tsvector('english', COALESCE((SELECT description FROM curr_task), '')), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT task_tag_names FROM fts_task_tag WHERE task_id = (SELECT id FROM curr_task)), '')), 'C')
        ) WHERE id = (SELECT id FROM curr_task);
    END IF;
    IF TG_OP = 'DELETE' THEN
        CREATE TEMP TABLE curr_task AS
            SELECT * FROM task WHERE id = OLD.task_id;
        UPDATE task SET fts_search=(
            setweight(to_tsvector('english', (SELECT name FROM curr_task)), 'A') || 
            setweight(to_tsvector('english', COALESCE((SELECT description FROM curr_task), '')), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT task_tag_names FROM fts_task_tag WHERE task_id = (SELECT id FROM curr_task)), '')), 'C')
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
CREATE FUNCTION project_fts_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.fts_search = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
            setweight(to_tsvector('english', (SELECT name FROM user_profile WHERE id = NEW.coordinator_id)), 'C')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.coordinator_id <> OLD.coordinator_id) THEN
            NEW.fts_search = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', COALESCE(NEW.description, '')), 'B') ||
                setweight(to_tsvector('english', (SELECT name FROM user_profile WHERE id = NEW.coordinator_id)), 'C')
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
CREATE INDEX project_fts_idx ON project USING GIN (fts_search);
