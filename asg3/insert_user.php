<!-- Author: 72 -->
<!-- Inserts user based on fields filled by the user -->

<?php
require 'db.php'; 

function fetchAllUsers() {
    $pdo = connectDB();
    $sql = "SELECT userid, firstname, lastname FROM user";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
    disconnectDB($pdo);
    return $users;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = connectDB();

    // Collect all of the input data
    $userid = $_POST['userid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $imageurl = $_POST['imageurl'];
    $following = $_POST['following'] ?? [];

    // Check if the user ID is unique
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE userid = :userid");
    $stmt->execute(['userid' => $userid]);
    $exists = $stmt->fetchColumn() > 0;

    if ($exists) {

        // Display error message
        $error = "User ID already in use. Please choose a different User ID.";
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO user (userid, firstname, lastname, image) VALUES (:userid, :firstname, :lastname, :imageurl)");
        $stmt->execute([
            'userid' => $userid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'imageurl' => $imageurl
        ]);

        // Insert follow relationships
        foreach ($following as $followUserId) {
            $stmt = $pdo->prepare("INSERT INTO follows (follower, following, followyear) VALUES (:follower, :following, :followyear)");
            $stmt->execute(['follower' => $userid, 'following' => $followUserId, 'followyear'=>date('Y')]); // use current year
        }

        // Success message
        $success = "User successfully added!";
    }

    // disconnect from database
    disconnectDB($pdo);
}

$allUsers = fetchAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert New User</title>
    <link rel="stylesheet" href="insert_user.css">
</head>
<body>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<?php if (isset($success)): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>

<form action="insert_user.php" method="post">
    <label for="userid">User ID:</label>
    <input type="text" id="userid" name="userid" required>
    <br>
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required>
    <br>
    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required>
    <br>
    <label for="imageurl">Image URL:</label>
    <input type="text" id="imageurl" name="imageurl">
    <br>
    <label>Users to Follow:</label>
    <?php foreach ($allUsers as $user): ?>
        <div>
            <input type="checkbox" id="follow-<?= $user['userid'] ?>" name="following[]" value="<?= $user['userid'] ?>">
            <label for="follow-<?= $user['userid'] ?>"><?= $user['firstname'] . ' ' . $user['lastname'] ?></label>
        </div>
    <?php endforeach; ?>
    <br>
    <button type="submit">Insert User</button>
</form>

</body>
</html>
