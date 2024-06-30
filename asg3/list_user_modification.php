<!-- Author: 72 -->
<!-- This is the list of users that you click on to modify -->
<!-- Links to the actual modify page (modify_user.php) -->

<?php
require 'db.php';

function fetchAllUsers() {
    $pdo = connectDB();
    $sql = "SELECT userid, firstname, lastname FROM user ORDER BY lastname, firstname";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    disconnectDB($pdo);
    return $users;
}

$users = fetchAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Users for modification</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Select Which Users You Would Like to Modify</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                - <a href="modify_user.php?userId=<?php echo htmlspecialchars($user['userid']); ?>">Modify</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
