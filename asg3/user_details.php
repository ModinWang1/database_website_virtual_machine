<?php
require 'db.php'; 

// Function to fetch the detailed information about a user
function fetchUserDetails($userId) {
    $pdo = connectDB();

    //user information
    $sqlUser = "SELECT * FROM user WHERE userid = :userId";
    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->execute(['userId' => $userId]);
    $user = $stmtUser->fetch();

    //users that the selected user is following
    $sqlFollowing = "SELECT u.userid, u.firstname, u.lastname FROM follows f JOIN user u ON f.following = u.userid WHERE f.follower = :userId";
    $stmtFollowing = $pdo->prepare($sqlFollowing);
    $stmtFollowing->execute(['userId' => $userId]);
    $following = $stmtFollowing->fetchAll();

    // users that are following the selected user
    $sqlFollowers = "SELECT u.userid, u.firstname, u.lastname FROM follows f JOIN user u ON f.follower = u.userid WHERE f.following = :userId";
    $stmtFollowers = $pdo->prepare($sqlFollowers);
    $stmtFollowers->execute(['userId' => $userId]);
    $followers = $stmtFollowers->fetchAll();

    // Disconnect database
    disconnectDB($pdo);
    return [$user, $following, $followers];
}

// Check if a userid is provided and fetch the details
if (isset($_GET['userid'])) {
    $userId = $_GET['userid'];
    list($user, $following, $followers) = fetchUserDetails($userId);
} else {
    echo "No user ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <link rel="stylesheet" href="user_details.css"> 
</head>
<body>

<div class="user-info">
    <h2>User Details</h2>
    <?php if ($user): ?>
        <p>UserID: <?= htmlspecialchars($user['userid']) ?></p>
        <p>Name: <?= htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']) ?></p>
        
        <!--Display either the image or the default profile picture-->
        <img src="<?= $user['image'] ? htmlspecialchars($user['image']) : 'default_profile_picture.png' ?>" alt="User Image">
    <?php else: ?>
        <!--catch case where userid is entered wrong (shouldn't happen in our case)-->
        <p>User not found.</p>
    <?php endif; ?>
</div>

<!--show following-->
<div class="following">
    <h3>Following</h3>
    <ul>
        <?php foreach ($following as $person): ?>
            <li><?= htmlspecialchars($person['userid']) . ': ' . htmlspecialchars($person['firstname']) . ' ' . htmlspecialchars($person['lastname']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<!--Show follewers-->
<div class="followers">
    <h3>Followers</h3>
    <ul>
        <?php foreach ($followers as $person): ?>
            <li><?= htmlspecialchars($person['userid']) . ': ' . htmlspecialchars($person['firstname']) . ' ' . htmlspecialchars($person['lastname']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
