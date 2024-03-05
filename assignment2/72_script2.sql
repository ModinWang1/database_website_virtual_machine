-- Part 1 SQL Updates
USE assign2db;

SELECT * FROM user;

UPDATE user SET image="https://static.wikia.nocookie.net/simpsons/images/8/8d/Swimsuit_Homer.png" WHERE firstname="Homer";

SELECT * FROM post;

UPDATE post SET postdate ='2020-08-24' WHERE userid IN (SELECT userid FROM user WHERE lastname='Bing');

SELECT * FROM user;

SELECT * FROM post;


-- Part 2 SQL Inserts
SELECT * FROM user;

INSERT INTO user (userid, firstname, lastname, image) VALUES ('amaxw', 'Anita', 'Maxwynn', NULL);

SELECT * FROM user;

SELECT * FROM post;

INSERT INTO post (postid, posttext, postdate, image, userid) VALUES ('999', 'I need a max win!', '2023-09-01', NULL, 'amaxw');

SELECT * FROM post;

SELECT * FROM hashtag;

INSERT INTO hashtag (hashtagid, hashtagtext, trending, hashtagdate) VALUES ('023', '#AnitaMaxwynn', 1, '2023-11-14');

SELECT * FROM hashtag;

SELECT * FROM hashonpost;

INSERT INTO hashonpost (hashtagid, postid) VALUES ('023', '999');

INSERT INTO hashonpost (hashtagid, postid) VALUES ('023', '201');

INSERT INTO hashonpost (hashtagid, postid) VALUES ('023', '202');

SELECT * FROM hashonpost;


-- Part 3 SQL Queries

-- Query 1: Show the last names of all the users
SELECT lastname FROM user;

-- Query 2: Show the last names of all the users with no repeats
SELECT DISTINCT lastname FROM user;

-- Query 3: Show all the data in the user table, but show them in order of their last names from A to Z
SELECT * FROM user ORDER BY lastname;

-- Query 4: Show the hashtag text and date for any hash tags that are trending (1 means trending, 0 is not trending). 
SELECT hashtagtext, hashtagdate FROM hashtag WHERE trending = 1;

-- Query 5: List the post id(the key), the post text, the userid, and the first name of each user who has made a post
SELECT post.postid, post.posttext, post.userid, user.firstname FROM post JOIN user ON post.userid = user.userid;

-- Query 6: Show the hashtag text and the post text for all posts that are associated with a hashtag order by the hashtag text. 
SELECT hashtag.hashtagtext, post.posttext FROM hashonpost JOIN hashtag ON hashonpost.hashtagid = hashtag.hashtagid JOIN post ON hashonpost.postid = post.postid ORDER BY hashtag.hashtagtext;

-- Query 7: Show the hashtag text, the post text and the first and last name of the person who made the post for any post with the hashtag of #PositiveVibes or #BeYourself
SELECT hashtag.hashtagtext, post.posttext, user.firstname, user.lastname FROM hashonpost JOIN hashtag ON hashonpost.hashtagid = hashtag.hashtagid JOIN post ON hashonpost.postid = post.postid JOIN user ON post.userid = user.userid WHERE hashtag.hashtagtext IN ('#PositiveVibes', '#BeYourself');

-- Query 8: Show the post text, the comment text and the first and last name of the person who made the comment for any posts created by Chandler BIng. Hint: you will need to use the user table twice, something like this: user as commenter, user as poster and then do your join carefully.
SELECT post.posttext, comments.commenttext, user.firstname, user.lastname
FROM comments 
JOIN post ON comments.postid = post.postid
JOIN user ON comments.userid = user.userid
WHERE post.userid = (SELECT userid FROM user WHERE firstname = 'Chandler' AND lastname ='Bing');

-- Query 9: Show all the hashtag columns for any hashtags that are trending but are not associated with any posts. 
SELECT * 
FROM hashtag 
WHERE trending = 1
AND hashtagid NOT IN (SELECT DISTINCT hashtagid FROM hashonpost);

-- Query 10: Display the first and last name of the person who made the post, the post text, postid  and the number of comments it received.  Only show posts that had at least 1 comment.
SELECT user.firstname, user.lastname, post.posttext, post.postid, COUNT(comments.commenttext) AS CommentsCount 
FROM post 
JOIN user ON post.userid = user.userid
JOIN comments ON post.postid = comments.postid
GROUP BY post.postid
HAVING CommentsCount > 0;

-- Query 11: Figure out if there are any weird situations where a post was created AFTER the comment was created. Display the posttext, the comment text and the post date and the comment date. 
SELECT post.posttext, comments.commenttext, post.postdate, comments.commentdate
FROM post
JOIN comments ON post.postid = comments.postid
WHERE post.postdate > comments.commentdate;

-- Query 12: Figure out the popularity of people (put the most popular people at the top) by displaying their first name and last name and userid of each user and show how many followers they have in order by number of followers. Rename the column showing the number of followers they have to be called "FollowedBy" (hint: that will help you with the ordering!)
SELECT user.firstname, user.lastname, user.userid, COUNT(follows.following) AS FollowedBy
FROM user
LEFT JOIN follows ON user.userid = follows.following
GROUP BY user.userid
ORDER BY FollowedBy DESC;

-- Query 13: Display the first and last name of the user who has the post with the most comments.  Also display the post text, postid as well and the number of comments it has. 

CREATE VIEW queryten AS
SELECT user.firstname, user.lastname, post.posttext, post.postid, COUNT(comments.commenttext) AS num 
FROM post 
JOIN user ON post.userid = user.userid
JOIN comments ON post.postid = comments.postid
GROUP BY post.postid;

-- Show the view
SELECT * FROM queryten;

SELECT firstname, lastname, posttext, postid, num
FROM queryten
WHERE num = (
    SELECT MAX(num) FROM queryten
);


-- Query 14: Find the user who was the first person to have followers.  Display their first and last name, user id and the year they got their first follower.  Do not show repeats. 
SELECT user.firstname, user.lastname, user.userid, MIN(follows.followyear) AS FirstFollowerYear
FROM user
JOIN follows ON user.userid = follows.following
GROUP BY user.userid
ORDER BY FirstFollowerYear
LIMIT 1;

-- Query 15: Display the number of first names and number of comments for all users ordered by the number of comments they have made
SELECT user.firstname, user.lastname, COUNT(comments.commenttext) AS NumberOfComments
FROM user
LEFT JOIN comments ON user.userid = comments.userid
GROUP BY user.userid
ORDER BY NumberOfComments DESC;

-- Part 4 SQL Views/Deletes
-- Create view to list the followers of each user
CREATE VIEW FollowersList AS
SELECT following_user.userid AS following_userid, following_user.firstname AS following_firstname, following_user.lastname AS following_lastname, follower_user.userid AS follower_userid, follower_user.firstname AS follower_firstname, follower_user.lastname AS follower_lastname
FROM follows
LEFT JOIN user AS following_user ON follows.following = following_user.userid
LEFT JOIN user AS follower_user ON follows.follower = follower_user.userid
ORDER BY following_user.lastname;

-- Proof that the view works by selecting all rows from it
SELECT * FROM FollowersList;

-- Query to show all user table information
SELECT * FROM user;

-- Delete the user with the userid of nflan
DELETE FROM user WHERE userid = 'nflan';

-- Proof that the user was deleted
SELECT * FROM user;

-- Query to count the number of posts in the post table
SELECT COUNT(*) AS num_posts FROM post;

-- Delete the user with the userid of mgell
DELETE FROM user WHERE userid = 'mgell';

-- Show the number of posts again
SELECT COUNT(*) AS num_posts FROM post;












