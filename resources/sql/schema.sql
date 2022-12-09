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
    is_admin BOOLEAN NOT NULL DEFAULT false,
    remember_token TEXT
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
