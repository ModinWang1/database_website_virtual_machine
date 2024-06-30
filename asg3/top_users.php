<!-- Author: 72 -->
<!-- Shows top 3 most followed users -->

<?php
require 'db.php';

// Function to fetch top 3 users (by followers)
function fetchTopFollowedUsers() {
    $pdo = connectDB();
    // SQL query to find top 3 users with the most followers
    $sql = "SELECT u.userid, u.firstname, u.lastname, u.image, COUNT(f.follower) AS followerCount
            FROM user u
            LEFT JOIN follows f ON u.userid = f.following
            GROUP BY u.userid
            ORDER BY followerCount DESC
            LIMIT 3";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $topUsers = $stmt->fetchAll();
    disconnectDB($pdo);
    return $topUsers;
}

$topFollowedUsers = fetchTopFollowedUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Followed Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Top 3 Users With Most Followers</h2>

    <?php if (count($topFollowedUsers) > 0): ?>
        <ul>
            <?php foreach ($topFollowedUsers as $user): ?>
                <li>
                    <img src="<?= htmlspecialchars($user['image'] ?: 'default_profile_picture.png') ?>" alt="Profile Picture" style="width: 50px; height: 50px; object-fit: cover;">
                    <?= htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) ?>
                    (UserID: <?= htmlspecialchars($user['userid']) ?>) - 
                    Followers: <?= $user['followerCount'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</body>
</html>
