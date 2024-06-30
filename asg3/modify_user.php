<?php
require 'db.php';
// start by checking if a userId is provided
$userId = isset($_GET['userId']) ? $_GET['userId'] : null;
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = connectDB();
    $imageURL = $_POST['imageURL'];
    $follows = isset($_POST['follows']) ? $_POST['follows'] : [];

    // Update image URL
    if (strlen($imageURL) <= 100) {
        $sqlUpdate = "UPDATE user SET image = :image WHERE userid = :userId";
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->execute(['image' => $imageURL, 'userId' => $userId]);
        $message = "User updated successfully.";
    } else {
        $message = "Image URL exceeds 100 characters.";
    }

    // Update follows
    $sqlDeleteFollows = "DELETE FROM follows WHERE follower = :userId";
    $stmt = $pdo->prepare($sqlDeleteFollows);
    $stmt->execute(['userId' => $userId]);

    foreach ($follows as $followUserId) {
        if ($followUserId != $userId) { // Prevent following yourself
            $sqlInsertFollow = "INSERT INTO follows (follower, following, followyear) VALUES (:follower, :following, :followyear) ON DUPLICATE KEY UPDATE followyear = VALUES(followyear)";
            $stmt = $pdo->prepare($sqlInsertFollow);
            $stmt->execute([
                'follower' => $userId,
                'following' => $followUserId,
                'followyear' => date('Y')
            ]);
        }
    }

    disconnectDB($pdo);
}

if ($userId) {
    // Get user details
    $pdo = connectDB();
    $sqlUser = "SELECT * FROM user WHERE userid = :userId";
    $stmt = $pdo->prepare($sqlUser);
    $stmt->execute(['userId' => $userId]);
    $user = $stmt->fetch();

    // Get all users for follow options
    $sqlAllUsers = "SELECT userid, firstname, lastname FROM user WHERE userid != :userId";
    $stmt = $pdo->prepare($sqlAllUsers);
    $stmt->execute(['userId' => $userId]);
    $allUsers = $stmt->fetchAll();

    // Get users the user is following
    $sqlFollowing = "SELECT following FROM follows WHERE follower = :userId";
    $stmt = $pdo->prepare($sqlFollowing);
    $stmt->execute(['userId' => $userId]);
    $following = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    disconnectDB($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Modify User</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($userId && $user): ?>
    <form method="post">
        <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <label for="imageURL">Image URL:</label>
        <input type="text" id="imageURL" name="imageURL" value="<?php echo htmlspecialchars($user['image'] ?? ''); ?>" maxlength="100"><br>

        <h3>Follow Users</h3>
        <?php foreach ($allUsers as $otherUser): ?>
            <label>
                <input type="checkbox" name="follows[]" value="<?php echo htmlspecialchars($otherUser['userid']); ?>" <?php echo in_array($otherUser['userid'], $following) ? 'checked' : ''; ?>>
                <?php echo htmlspecialchars($otherUser['firstname'] . ' ' . $otherUser['lastname']); ?>
            </label><br>
        <?php endforeach; ?>

        <button type="submit">Update User</button>
    </form>
    <?php else: ?>
        <p>User not found or no user selected for modification.</p>
    <?php endif; ?>
</body>
</html>
