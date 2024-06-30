-- ---------------------------------
-- SCRIPT 1

-- Set up the database

SHOW DATABASES;
DROP DATABASE IF EXISTS assign2db;
CREATE DATABASE assign2db;
USE assign2db; 


-- Create the tables for the database

SHOW TABLES;

CREATE TABLE user(userid VARCHAR(8) NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, image VARCHAR(100), PRIMARY KEY(userid));

CREATE TABLE post (postid CHAR(3) NOT NULL, posttext VARCHAR(500) NOT NULL, postdate DATE NOT NULL,image VARCHAR(100), userid VARCHAR(8), PRIMARY KEY(postid), FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE);

CREATE TABLE hashtag (hashtagid CHAR(3) NOT NULL, hashtagtext VARCHAR(30) NOT NULL, trending TINYINT, hashtagdate DATE NOT NULL, PRIMARY KEY(hashtagid));

CREATE TABLE follows (follower VARCHAR(8), following VARCHAR(8), followyear SMALLINT NOT NULL, PRIMARY KEY (follower,following), FOREIGN KEY(follower) REFERENCES user(userid), FOREIGN KEY(following) REFERENCES user(userid));

CREATE TABLE comments (userid VARCHAR(8) NOT NULL, postid CHAR(3) NOT NULL, commentdate DATE NOT NULL, commenttext VARCHAR(200) NOT NULL, PRIMARY KEY (userid, postid), FOREIGN KEY(userid) REFERENCES user(userid), FOREIGN KEY(postid) REFERENCES post(postid));

CREATE TABLE hashonpost (hashtagid CHAR(3) NOT NULL, postid CHAR(3) NOT NULL, PRIMARY KEY (hashtagid, postid), FOREIGN KEY(hashtagid) REFERENCES hashtag(hashtagid), FOREIGN KEY(postid) REFERENCES post(postid));

SHOW TABLES;

-- ------------------------------------
-- insert some data

-- insert into the user table
SELECT * FROM user;
INSERT INTO user (userid, firstname, lastname, image) VALUES
('rgell', 'Ross', 'Geller', NULL),
('rgree', 'Rachel', 'Green', NULL),
('mgell', 'Monica', 'Geller', NULL),
('jtrib3', 'Joey', 'Tribbiani', NULL),
('pbuff', 'Phoebe', 'Buffay', NULL),
('cbing33', 'Chandler', 'Bing', NULL);
INSERT INTO user (userid, firstname, lastname, image) VALUES
('hsimp', 'Homer', 'Simpson', NULL),
('msimp', 'Marge', 'Simpson', NULL),
('tflan', 'Todd', 'Flanders', NULL),
('nflan', 'Ned', 'Flanders', NULL);

SELECT * FROM user;

-- insert into the post Table
SELECT * FROM post;

INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('201', 'Life is beautiful! Enjoy every moment.', '2023-11-03', NULL, 'rgree');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('202', 'Spread positivity and kindness today!', '2023-11-03', NULL, 'rgree');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('203', 'Believe in yourself and your dreams.', '2023-11-03', NULL, 'rgree');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('204', 'Be a good neighbor and help others.', '2023-11-03', NULL, 'tflan');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('205', 'Always keep a positive attitude.', '2023-11-03', NULL, 'tflan');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('206', 'Family is everything; cherish the moments.', '2023-11-03', NULL, 'tflan');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('207', 'Dream big and work hard to achieve your goals.', '2023-11-03', NULL, 'jtrib3');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('208', 'Laughter is the best medicine.', '2023-11-03', NULL, 'jtrib3');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('209', 'Stay positive and keep smiling!', '2023-11-03', NULL, 'jtrib3');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('210', 'Embrace change and don\'t be afraid to be yourself.', '2023-11-03', NULL, 'cbing33');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('211', 'Make your own luck in life.', '2023-11-03', NULL, 'cbing33');
INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('212', 'Cleaning is good for the soul', '2021-10-03', NULL, 'mgell');
SELECT * FROM post;

-- insert into the hashtag Table
SELECT * FROM hashtag;
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('001', '#Inspiration', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('002', '#PositiveVibes', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('003', '#DreamBig', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('004', '#KindnessMatters', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('005', '#FamilyLove', 0, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('006', '#LaughMore', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('007', '#ChaseYourDreams', 0, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('008', '#StayPositive', 1, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('009', '#EmbraceChange', 0, '2023-11-03');
INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('010', '#BeYourself', 1, '2023-11-03');
SELECT * FROM hashtag;

-- insert into the taable that shows which hashtags are on which posts
SELECT * FROM hashonpost;
INSERT INTO hashonpost (hashtagid, postid) VALUES ('001', '201');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('002', '201');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('002', '202');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('004', '202');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('006', '202');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('006', '206');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('008', '208');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('008', '201');
INSERT INTO hashonpost (hashtagid, postid) VALUES ('010', '208');
SELECT * FROM hashonpost;

-- insert into the comment table.
SELECT * FROM comments;
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('rgell', '201', '2023-11-03', 'Beautiful message, Rachel! Keep spreading positivity.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('pbuff', '209', '2023-11-03', 'Joey, your posts always make me smile.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('hsimp', '210', '2023-11-03', 'Chandler, you never fail to make us laugh!');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('hsimp', '211', '2023-11-03', 'Chandler, your positive energy is contagious.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('msimp', '201', '2023-11-03', 'Ross, I needed this message today. Thank you!');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('tflan', '203', '2010-11-03', 'Monica, your words of wisdom inspire me.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('rgree', '210', '2023-11-03', 'Chandler, you always bring a smile to my face.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('jtrib3', '204', '2023-11-03', 'Todd, being a good neighbor is a great philosophy.');
INSERT INTO comments (userid, postid, commentdate, commenttext) VALUES ('tflan', '201', '2023-11-03', 'Rachel, you radiate positivity!');
SELECT * FROM comments;

-- insert into the follow table
SELECT * from follows;
INSERT INTO follows (follower, following, followyear) VALUES ('rgell', 'rgree', 2015);
INSERT INTO follows (follower, following, followyear) VALUES ('rgell', 'jtrib3', 2018);
INSERT INTO follows (follower, following, followyear) VALUES ('rgell', 'cbing33', 2019);
INSERT INTO follows (follower, following, followyear) VALUES ('jtrib3', 'rgree', 2015);
INSERT INTO follows (follower, following, followyear) VALUES ('jtrib3', 'cbing33', 2019);
INSERT INTO follows (follower, following, followyear) VALUES ('hsimp', 'rgree', 2019);
INSERT INTO follows (follower, following, followyear) VALUES ('tflan', 'hsimp', 2017);
SELECT * from follows;
