------------------------------------------------------------
-- Drop old schema
------------------------------------------------------------

DROP SCHEMA IF EXISTS lbaw2265 CASCADE;
CREATE SCHEMA lbaw2265;

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
    description TEXT,
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
CREATE INDEX task_group_project ON task_group (project, position);

-- IDX103
CREATE INDEX task_task_group ON task (task_group, position);

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
            SELECT * FROM task WHERE id = NEW.task;
        UPDATE task SET fts_search=(
            setweight(to_tsvector('english', (SELECT name FROM curr_task)), 'A') || 
            setweight(to_tsvector('english', COALESCE((SELECT description FROM curr_task), '')), 'B') ||
            setweight(to_tsvector('english', COALESCE((SELECT task_tag_names FROM fts_task_tag WHERE task_id = (SELECT id FROM curr_task)), '')), 'C')
        ) WHERE id = (SELECT id FROM curr_task);
    END IF;
    IF TG_OP = 'DELETE' THEN
        CREATE TEMP TABLE curr_task AS
            SELECT * FROM task WHERE id = OLD.task;
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
CREATE INDEX project_fts_idx ON project USING GIN (fts_search);

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

--------------------------------------------------------------------------------------------------------------------------------
-- Populate
--------------------------------------------------------------------------------------------------------------------------------

INSERT INTO
    user_profile(name, email, password, blocked, is_admin)
VALUES
    (
        'Gleda Goodram',
        'ggoodram0@booking.com',
        'vvAKpaezQOO',
        false,
        true
    ),
    (
        'Gareth Edinburough',
        'gedinburough1@gnu.org',
        'd0U1Xs',
        false,
        true
    ),
    (
        'Brita Behrend',
        'bbehrend2@google.co.jp',
        'AT2Kwhvo4WNb',
        false,
        false
    ),
    (
        'Dev Camin',
        'dcamin3@gmpg.org',
        'vfW1BNW12T',
        false,
        false
    ),
    (
        'Syd McCerery',
        'smccerery4@nymag.com',
        'reEn1CiKgadp',
        false,
        false
    ),
    (
        'Edee Spillane',
        'espillane5@google.de',
        'Xg6elqWh2usd',
        false,
        false
    ),
    (
        'Emmi Pestor',
        'epestor6@google.co.uk',
        'sn6pA7E',
        true,
        false
    ),
    (
        'Hillard Britton',
        'hbritton7@woothemes.com',
        '9o1pW1hlQk13',
        false,
        false
    ),
    (
        'Katuscha Scarrisbrick',
        'kscarrisbrick8@ox.ac.uk',
        'yo2iNxDv',
        false,
        false
    ),
    (
        'Romain Kernell',
        'rkernell9@tripadvisor.com',
        'IfEHbvYoa',
        false,
        false
    ),
    (
        'Elly Cairns',
        'ecairnsa@nhs.uk',
        'pDn5hM1j6oq',
        false,
        false
    ),
    (
        'Jo ann Matchett',
        'jannb@businessinsider.com',
        'mdqH9Ti4T',
        true,
        false
    ),
    (
        'Bonita Cosbey',
        'bcosbeyc@census.gov',
        '2O05TUi3Y',
        false,
        false
    ),
    (
        'Sherm Zelley',
        'szelleyd@t.co',
        'InNwoChwfW8',
        false,
        false
    ),
    (
        'Harlen Sollam',
        'hsollame@google.com.br',
        'PT5X8HedpJ',
        false,
        false
    ),
    (
        'Belicia Thurlby',
        'bthurlbyf@adobe.com',
        'npaeAW3Oujw',
        false,
        false
    ),
    (
        'Jacquelin Reading',
        'jreadingg@youtu.be',
        'iDSqqCU746',
        true,
        false
    ),
    (
        'Grady Samwayes',
        'gsamwayesh@qq.com',
        'gFFhcZKueg',
        false,
        false
    ),
    (
        'Jamaal Lunam',
        'jlunami@marriott.com',
        '882qPKiuYor',
        true,
        false
    ),
    (
        'Kessiah Marcoolyn',
        'kmarcoolynj@ask.com',
        'EuuD1c9wqZz',
        true,
        false
    );

INSERT INTO
    project(
        name,
        creation_date,
        last_modification_date,
        archived,
        coordinator
    )
VALUES
    (
        'Wyman Inc',
        '2021-08-26',
        NULL,
        false,
        8
    ),
    (
        'Borer LLC',
        '2021-09-09',
        NULL,
        false,
        7
    ),
    (
        'Abshire and Sons',
        '2022-02-04',
        '2022-05-19',
        true,
        4
    );

INSERT INTO
    project_member(user_profile, project, is_favorite)
VALUES
    (3, 2, 'false'),
    (4, 3, 'false'),
    (5, 2, 'true'),
    (6, 3, 'false'),
    (7, 2, 'false'),
    (8, 2, 'false'),
    (9, 2, 'true'),
    (10, 2, 'true'),
    (11, 2, 'true'),
    (14, 2, 'false'),
    (15, 3, 'true'),
    (16, 1, 'true'),
    (17, 1, 'false'),
    (18, 1, 'true'),
    (19, 1, 'true'),
    (20, 3, 'false');

INSERT INTO
    task_group(
        name,
        description,
        creation_date,
        position,
        project
    )
VALUES
    (
        'To be done',
        'Task that needs to be done',
        '2022-02-04',
        1,
        3
    ),
    (
        'Doing',
        'Task in progress',
        '2022-02-04',
        2,
        3
    ),
    (
        'Done',
        'Task already completed',
        '2022-02-04',
        3,
        3
    ),
    (
        'In revision',
        'Task that needs to be reviewed',
        '2022-02-04',
        4,
        3
    ),
    (
        'To-Do',
        'Task that needs to be done',
        '2021-06-09',
        1,
        1
    ),
    (
        'In Progress',
        'Task in progress',
        '2021-06-09',
        2,
        1
    ),
    (
        'Done',
        'Task already completed',
        '2021-06-09',
        3,
        1
    ),
    (
        'Product Backlog',
        'Tasks that can be added to future releases',
        '2022-03-28',
        1,
        2
    ),
    (
        'Iteration Backlog',
        'Task that need to be completed before the end of the current iteration',
        '2022-03-28',
        2,
        2
    ),
    (
        'In Progress',
        'Task in progress',
        '2022-03-28',
        3,
        2
    ), 
    (
        'In Review',
        'Tasks that need to be reviewed',
        '2022-03-28',
        4,
        2
    ), (
        'Done',
        'Task that have been completed',
        '2022-03-28',
        5,
        2
    );

INSERT INTO
    project_invitation(
        expiration_date, 
        creator, 
        project)
VALUES
    ('2021-09-27', 11, 1),
    ('2021-09-03', 10, 2),
    ('2022-01-15', 4, 3),
    ('2022-03-04', 4, 3),
    ('2021-07-09', 6, 3),
    ('2022-04-28', 2, 2),
    ('2022-03-19', 11, 2),
    ('2021-09-21', 1, 1),
    ('2021-10-09', 7, 2),
    ('2022-07-08', 1, 1),
    ('2022-08-19', 3, 1),
    ('2021-10-24', 11, 1);
INSERT INTO
    task(
        name,
        description,
        creation_date,
        edit_date,
        state,
        creator,
        position,
        task_group
    )
VALUES
    (
        'Information search',
        'Nothing done',
        '2021-06-09',
        '2021-06-13',
        'created',
        4,
        1,
        1
    ),
    (
        'Information analysis',
        'stil things left to do',
        '2022-02-02',
        NULL,
        'member_assigned',
        6,
        2,
        1
    ),
    (
        'Information Architecture',
        'Develop the architecture',
        '2021-06-07',
        NULL,
        'member_assigned',
        10,
        3,
        1
    ),
    (
        'Potential Clients',
        'Search for potential clients',
        '2022-02-02',
        '2022-05-18',
        'created',
        4,
        3,
        4
    ),
    (
        'Design review',
        'Design review with stakeholders',
        '2021-06-09',
        '2021-06-13',
        'created',
        8,
        2,
        12
    ),
    (
        'Implementation',
        'Implement landing page',
        '2022-02-04',
        '2022-02-05',
        'created',
        14,
        4,
        12
    ),
    (
        'User research',
        'Summarize user reseach insights',
        '2022-02-04',
        NULL,
        'created',
        12,
        2,
        7
    ),
    (
        'Finalize use cases',
        'Sign off user quotes',
        '2022-02-04',
        NULL,
        'completed',
        9,
        1,
        7
    ),
    (
        'Info search',
        'Need more info',
        '2022-02-04',
        '2022-03-09',
        'member_assigned',
        20,
        1,
        9
    ),
    (
        'Design new website',
        'Is missing the last item',
        '2022-02-04',
        '2022-02-05',
        'created',
        15,
        3,
        9
    ),
    (
        'Design',
        'Do the page design',
        '2021-03-02',
        '2021-03-03',
        'completed',
        17,
        1,
        4
    ),
    (
        'Front',
        'Frontend',
        '2021-05-05',
        NULL,
        'completed',
        11,
        2,
        4
    ),
    (
        'Implement',
        'Implementing frontend',
        '2021-05-04',
        NULL,
        'completed',
        16,
        4,
        4
    ),
    (
        'Project definition',
        'divide tasks',
        '2021-02-06',
        '2021-02-07',
        'created',
        13,
        5,
        4
    ),
    (
        'info analysis',
        'more more',
        '2022-05-03',
        NULL,
        'created',
        10,
        2,
        3
    ),
    (
        'info search',
        'need more info',
        '2022-04-23',
        NULL,
        'member_assigned',
        5,
        1,
        3
    ),
    (
        'potential members',
        'look for potential members',
        '2022-07-23',
        '2022-07-24',
        'created',
        19,
        4,
        3
    ),
    (
        'information Architecture',
        'architecture development',
        '2022-04-23',
        '2022-04-24',
        'created',
        18,
        1,
        12
    ),
    (
        'generic research',
        'everything',
        '2021-10-31',
        NULL,
        'completed',
        12,
        1,
        8
    ),
    (
        'user research',
        'summarize user research',
        '2021-11-08',
        NULL,
        'completed',
        15,
        2,
        8
    );

INSERT INTO
    task_comment(
        content,
        creation_date,
        edit_date,
        author,
        task
    )
VALUES
    (
        'Need Improvement',
        '2022-04-15',
        '2022-04-16',
        3,
        10
    ),
    (
        'Things to do',
        '2022-04-17',
        NULL,
        3,
        18
    ),
    (
        'So far so Good',
        '2021-06-17',
        '2021-06-18',
        20,
        3
    ),
    (
        'Need some attention',
        '2021-06-17',
        '2021-06-18',
        3,
        5
    ),
    (
        'More information needed',
        '2021-06-12',
        NULL,
        15,
        1
    ),
    (
        'Some errors',
        '2021-06-12',
        NULL,
        6,
        1
    ),
    (
        'Errors', 
        '2022-03-05', 
        NULL, 
        14, 
        6
    );

INSERT INTO
    tag(
        title,
        description,
        color,
        project
    )
VALUES
    (
        'molestie in, tempus',
        'Cras dictum ultricies ligula. Nullam enim.',
        x'f4b8b7'::COLOR,
        3
    ),
    (
        'tellus eu augue',
        'dui lectus rutrum urna, nec luctus',
        x'8ed863'::COLOR,
        2
    ),
    (
        'varius et, euismod',
        'risus. Duis a mi fringilla mi',
        x'8fd1e0'::COLOR,
        3
    ),
    (
        'magna. Duis dignissim',
        'id ante dictum cursus. Nunc mauris',
        x'3282ad'::COLOR,
        1
    ),
    (
        'iaculis quis, pede.',
        'facilisi. Sed neque. Sed eget lacus.',
        x'75dd77'::COLOR,
        1
    ),
    (
        'dictum magna. Ut',
        'Fusce aliquet magna a neque. Nullam',
        x'35ea5d'::COLOR,
        2
    ),
    (
        'augue ac ipsum.',
        'molestie in, tempus eu, ligula. Aenean',
        x'009179'::COLOR,
        2
    ),
    (
        'parturient montes, nascetur',
        'Morbi accumsan laoreet ipsum. Curabitur consequat,',
        x'14369b'::COLOR,
        3
    ),
    (
        'eu enim. Etiam',
        'ut, nulla. Cras eu tellus eu',
        x'4dc429'::COLOR,
        2
    ),
    (
        'lacus pede sagittis',
        'Curae Phasellus ornare. Fusce mollis. Duis',
        x'b5eeff'::COLOR,
        1
    );

INSERT INTO
    task_tag
VALUES
    (1, 1),
    (1, 3),
    (2, 3),
    (5, 2),
    (5, 6),
    (5, 7),
    (6, 6),
    (10, 2),
    (10, 7),
    (12, 1),
    (14, 1),
    (14, 3);

INSERT INTO
    report(
        creation_date,
        reason,
        user_profile,
        creator
    )
VALUES
    ('2022-01-01', 'bad conduct', 8, 11),
    ('2021-03-03', 'poor work', 9, 7),
    ('2022-04-19', 'personal reasons', 7, 4),
    ('2022-07-28', 'bad behavior', 7, 5),
    ('2022-09-09', 'bad conduct', 7, 12),
    ('2021-10-09', 'poor work', 10, 4);

INSERT INTO
    report(
        creation_date,
        reason,
        project,
        creator
    )
VALUES
    ('2022-01-01', 'bad conduct', 1, 11),
    ('2021-03-03', 'poor work', 2, 7),
    ('2022-04-19', 'personal reasons', 3, 4),
    ('2022-07-28', 'bad behavior', 1, 5),
    ('2022-09-09', 'bad conduct', 2, 12),
    ('2021-10-09', 'poor work', 3, 4);

INSERT INTO
    task_assignee(user_profile, task)
VALUES
    (3, 9),
    (15, 12),
    (17, 8);
