<!-- Author: 72 -->
<!-- Allows for user to select a post and see all the comments for that post and which user (first name and last name and date) made the comment. -->

<?php
require 'db.php';

// Function to fetch all posts
function fetchPosts() {
    $pdo = connectDB();
    $sql = "SELECT postid, posttext FROM post ORDER BY postdate DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    disconnectDB($pdo);
    return $posts;
}

// Function to fetch comments for the post
function fetchCommentsByPost($postId) {
    $pdo = connectDB();
    $sql = "SELECT c.commenttext, c.commentdate, u.firstname, u.lastname 
            FROM comments c
            JOIN user u ON c.userid = u.userid
            WHERE c.postid = :postId
            ORDER BY c.commentdate";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['postId' => $postId]);
    $comments = $stmt->fetchAll();
    disconnectDB($pdo);
    return $comments;
}

$selectedPost = isset($_GET['post']) ? $_GET['post'] : null;
$comments = $selectedPost ? fetchCommentsByPost($selectedPost) : [];
$posts = fetchPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Comments by Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>View Comments by Post</h2>

    <form action="view_comments.php" method="get">
        <label for="post">Choose a post:</label>
        <select name="post" id="post" onchange="this.form.submit()">
            <option value="">Select a post</option>
            <?php foreach ($posts as $post): ?>
                <option value="<?= htmlspecialchars($post['postid']) ?>" <?= $selectedPost == $post['postid'] ? 'selected' : '' ?>>

                    <!-- substr function to limit # of chars used to prevent overflow -->
                    <?= substr(htmlspecialchars($post['posttext']), 0, 50) . '...' ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedPost && count($comments) > 0): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <strong><?= htmlspecialchars($comment['firstname']) . ' ' . htmlspecialchars($comment['lastname']) ?>:</strong>
                    <?= htmlspecialchars($comment['commenttext']) ?> - <em><?= $comment['commentdate'] ?></em>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($selectedPost): ?>
        <p>No comments found for this post.</p>
    <?php endif; ?>
</body>
</html>
