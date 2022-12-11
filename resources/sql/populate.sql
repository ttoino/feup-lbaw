--------------------------------------------------------------------------------------------------------------------------------
-- Populate
--------------------------------------------------------------------------------------------------------------------------------

SET search_path TO lbaw2265;

INSERT INTO user_profile(
  name, email, password, blocked, is_admin
) 
VALUES 
  (
    'Gleda Goodram', 'ggoodram0@booking.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, true
  ), 
  (
    'Gareth Edinburough', 'gedinburough1@gnu.org', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, true
  ), 
  (
    'Brita Behrend', 'bbehrend2@google.co.jp', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Dev Camin', 'dcamin3@gmpg.org', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Syd McCerery', 'smccerery4@nymag.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Edee Spillane', 'espillane5@google.de', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Emmi Pestor', 'epestor6@google.co.uk', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    true, false
  ), 
  (
    'Hillard Britton', 'hbritton7@woothemes.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Katuscha Scarrisbrick', 'kscarrisbrick8@ox.ac.uk', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Romain Kernell', 'rkernell9@tripadvisor.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Elly Cairns', 'ecairnsa@nhs.uk', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Jo ann Matchett', 'jannb@businessinsider.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    true, false
  ), 
  (
    'Bonita Cosbey', 'bcosbeyc@census.gov', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Sherm Zelley', 'szelleyd@t.co', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Harlen Sollam', 'hsollame@google.com.br', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Belicia Thurlby', 'bthurlbyf@adobe.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Jacquelin Reading', 'jreadingg@youtu.be', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    true, false
  ), 
  (
    'Grady Samwayes', 'gsamwayesh@qq.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    false, false
  ), 
  (
    'Jamaal Lunam', 'jlunami@marriott.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    true, false
  ), 
  (
    'Kessiah Marcoolyn', 'kmarcoolynj@ask.com', 
    '$2a$12$HXIQ3hqLzAE/t6Z4.aL1Ne75CR26WL7JESGTuaKmiUF78ZPA13d9e', 
    true, false
  );

INSERT INTO project(
  name, creation_date, last_modification_date, 
  archived, coordinator_id
) 
VALUES 
  (
    'Wyman Inc', '2021-08-26', NULL, false, 
    8
  ), 
  (
    'Borer LLC', '2021-09-09', NULL, false, 
    7
  ), 
  (
    'Abshire and Sons', '2022-02-04', 
    '2022-05-19', true, 4
  );

INSERT INTO project_member(
  user_profile_id, project_id, is_favorite
) 
VALUES 
  (3, 2, 'false'), 
  (5, 2, 'true'), 
  (6, 3, 'false'), 
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

INSERT INTO task_group(
  name, description, creation_date, 
  position, project_id
) 
VALUES 
  (
    'To be done', 'Task that needs to be done', 
    '2022-02-04', 1, 3
  ), 
  (
    'Doing', 'Task in progress', '2022-02-04', 
    2, 3
  ), 
  (
    'Done', 'Task already completed', 
    '2022-02-04', 3, 3
  ), 
  (
    'In revision', 'Task that needs to be reviewed', 
    '2022-02-04', 4, 3
  ), 
  (
    'To-Do', 'Task that needs to be done', 
    '2021-06-09', 1, 1
  ), 
  (
    'In Progress', 'Task in progress', 
    '2021-06-09', 2, 1
  ), 
  (
    'Done', 'Task already completed', 
    '2021-06-09', 3, 1
  ), 
  (
    'Product Backlog', 'Tasks that can be added to future releases', 
    '2022-03-28', 1, 2
  ), 
  (
    'Iteration Backlog', 'Task that need to be completed before the end of the current iteration', 
    '2022-03-28', 2, 2
  ), 
  (
    'In Progress', 'Task in progress', 
    '2022-03-28', 3, 2
  ), 
  (
    'In Review', 'Tasks that need to be reviewed', 
    '2022-03-28', 4, 2
  ), 
  (
    'Done', 'Task that have been completed', 
    '2022-03-28', 5, 2
  );

INSERT INTO project_invitation(
  expiration_date, creator_id, project_id
) 
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

INSERT INTO task(
  name, description, creation_date, 
  edit_date, state, creator_id, position, 
  task_group_id
) 
VALUES 
  (
    'Information search', 'Nothing done', 
    '2021-06-09', '2021-06-13', 'created', 
    4, 1, 1
  ), 
  (
    'Information analysis', 'stil things left to do', 
    '2022-02-02', NULL, 'member_assigned', 
    6, 2, 1
  ), 
  (
    'Information Architecture', 'Develop the architecture', 
    '2021-06-07', NULL, 'member_assigned', 
    10, 3, 1
  ), 
  (
    'Potential Clients', 'Search for potential clients', 
    '2022-02-02', '2022-05-18', 'created', 
    4, 3, 4
  ), 
  (
    'Design review', 'Design review with stakeholders', 
    '2021-06-09', '2021-06-13', 'created', 
    8, 2, 12
  ), 
  (
    'Implementation', 'Implement landing page', 
    '2022-02-04', '2022-02-05', 'created', 
    14, 4, 12
  ), 
  (
    'User research', 'Summarize user reseach insights', 
    '2022-02-04', NULL, 'created', 12, 
    2, 7
  ), 
  (
    'Finalize use cases', 'Sign off user quotes', 
    '2022-02-04', NULL, 'completed', 
    9, 1, 7
  ), 
  (
    'Info search', 'Need more info', 
    '2022-02-04', '2022-03-09', 'member_assigned', 
    20, 1, 9
  ), 
  (
    'Design new website', 'Is missing the last item', 
    '2022-02-04', '2022-02-05', 'created', 
    15, 3, 9
  ), 
  (
    'Design', 'Do the page design', '2021-03-02', 
    '2021-03-03', 'completed', 17, 1, 
    4
  ), 
  (
    'Front', 'Frontend', '2021-05-05', 
    NULL, 'completed', 11, 2, 4
  ), 
  (
    'Implement', 'Implementing frontend', 
    '2021-05-04', NULL, 'completed', 
    16, 4, 4
  ), 
  (
    'Project definition', 'divide tasks', 
    '2021-02-06', '2021-02-07', 'created', 
    13, 5, 4
  ), 
  (
    'info analysis', 'more more', '2022-05-03', 
    NULL, 'created', 10, 2, 3
  ), 
  (
    'info search', 'need more info', 
    '2022-04-23', NULL, 'member_assigned', 
    5, 1, 3
  ), 
  (
    'potential members', 'look for potential members', 
    '2022-07-23', '2022-07-24', 'created', 
    19, 3, 3
  ), 
  (
    'information Architecture', 'architecture development', 
    '2022-04-23', '2022-04-24', 'created', 
    18, 1, 12
  ), 
  (
    'generic research', 'everything', 
    '2021-10-31', NULL, 'completed', 
    12, 1, 8
  ), 
  (
    'user research', 'summarize user research', 
    '2021-11-08', NULL, 'completed', 
    15, 2, 8
  );

INSERT INTO task_comment(
  content, creation_date, edit_date, 
  author_id, task_id
) 
VALUES 
  (
    'Need Improvement', '2022-04-15', 
    '2022-04-16', 3, 10
  ), 
  (
    'Things to do', '2022-04-17', NULL, 
    3, 18
  ), 
  (
    'So far so Good', '2021-06-17', '2021-06-18', 
    20, 3
  ), 
  (
    'Need some attention', '2021-06-17', 
    '2021-06-18', 3, 5
  ), 
  (
    'More information needed', '2021-06-12', 
    NULL, 15, 1
  ), 
  (
    'Some errors', '2021-06-12', NULL, 
    6, 1
  ), 
  (
    'Errors', '2022-03-05', NULL, 14, 6
  );

INSERT INTO tag(
  title, description, color, project_id
) 
VALUES 
  (
    'molestie in, tempus', 'Cras dictum ultricies ligula. Nullam enim.', 
    x'f4b8b7' :: COLOR, 3
  ), 
  (
    'tellus eu augue', 'dui lectus rutrum urna, nec luctus', 
    x'8ed863' :: COLOR, 2
  ), 
  (
    'varius et, euismod', 'risus. Duis a mi fringilla mi', 
    x'8fd1e0' :: COLOR, 3
  ), 
  (
    'magna. Duis dignissim', 'id ante dictum cursus. Nunc mauris', 
    x'3282ad' :: COLOR, 1
  ), 
  (
    'iaculis quis, pede.', 'facilisi. Sed neque. Sed eget lacus.', 
    x'75dd77' :: COLOR, 1
  ), 
  (
    'dictum magna. Ut', 'Fusce aliquet magna a neque. Nullam', 
    x'35ea5d' :: COLOR, 2
  ), 
  (
    'augue ac ipsum.', 'molestie in, tempus eu, ligula. Aenean', 
    x'009179' :: COLOR, 2
  ), 
  (
    'parturient montes, nascetur', 'Morbi accumsan laoreet ipsum. Curabitur consequat,', 
    x'14369b' :: COLOR, 3
  ), 
  (
    'eu enim. Etiam', 'ut, nulla. Cras eu tellus eu', 
    x'4dc429' :: COLOR, 2
  ), 
  (
    'lacus pede sagittis', 'Curae Phasellus ornare. Fusce mollis. Duis', 
    x'b5eeff' :: COLOR, 1
  );

INSERT INTO task_tag 
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

INSERT INTO report(
  creation_date, reason, user_profile_id, 
  creator_id
) 
VALUES 
  (
    '2022-01-01', 'bad conduct', 8, 11
  ), 
  ('2021-03-03', 'poor work', 9, 7), 
  (
    '2022-04-19', 'personal reasons', 
    7, 4
  ), 
  (
    '2022-07-28', 'bad behavior', 7, 5
  ), 
  (
    '2022-09-09', 'bad conduct', 7, 12
  ), 
  ('2021-10-09', 'poor work', 10, 4);

INSERT INTO report(
  creation_date, reason, project_id, creator_id
) 
VALUES 
  (
    '2022-01-01', 'bad conduct', 1, 11
  ), 
  ('2021-03-03', 'poor work', 2, 7), 
  (
    '2022-04-19', 'personal reasons', 
    3, 4
  ), 
  (
    '2022-07-28', 'bad behavior', 1, 5
  ), 
  (
    '2022-09-09', 'bad conduct', 2, 12
  ), 
  ('2021-10-09', 'poor work', 3, 4);

INSERT INTO task_assignee(user_profile_id, task_id) 
VALUES 
  (3, 9), 
  (15, 12), 
  (17, 8);
