<!-- Author: 72 -->
<!-- Fetches all hashtags then allows user to select the hashtag and shows the connected posts -->

<?php
require 'db.php';

// Function to fetch all hashtags
function fetchHashtags() {
    $pdo = connectDB();
    $sql = "SELECT hashtagid, hashtagtext FROM hashtag ORDER BY hashtagtext";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $hashtags = $stmt->fetchAll();
    disconnectDB($pdo);
    return $hashtags;
}

// Function to fetch posts for a selected hashtag
function fetchPostsByHashtag($hashtagId) {
    $pdo = connectDB();
    $sql = "SELECT p.posttext, p.postdate, u.firstname, u.lastname 
            FROM post p
            JOIN user u ON p.userid = u.userid
            JOIN hashonpost h ON p.postid = h.postid
            WHERE h.hashtagid = :hashtagId
            ORDER BY p.postdate DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['hashtagId' => $hashtagId]);
    $posts = $stmt->fetchAll();
    disconnectDB($pdo);
    return $posts;
}

$selectedHashtag = isset($_GET['hashtag']) ? $_GET['hashtag'] : null;
$posts = $selectedHashtag ? fetchPostsByHashtag($selectedHashtag) : [];
$hashtags = fetchHashtags();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Posts by Hashtag</title>
    <link rel="stylesheet" href="view_posts_by_hashtag.css">
</head>
<body>
    <h2>View Posts by Hashtag</h2>

    <form action="view_posts_by_hashtag.php" method="get">
        <label for="hashtag">Choose a hashtag:</label>
        <select name="hashtag" id="hashtag" onchange="this.form.submit()">
            <option value="">Select a hashtag</option>
            <?php foreach ($hashtags as $hashtag): ?>
                <option value="<?= htmlspecialchars($hashtag['hashtagid']) ?>" <?= $selectedHashtag == $hashtag['hashtagid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($hashtag['hashtagtext']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedHashtag && count($posts) > 0): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <strong><?= htmlspecialchars($post['firstname']) . ' ' . htmlspecialchars($post['lastname']) ?>:</strong>
                    <?= htmlspecialchars($post['posttext']) ?> - <em><?= $post['postdate'] ?></em>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($selectedHashtag): ?>
        <p>No posts found for this hashtag.</p>
    <?php endif; ?>
</body>
</html>
