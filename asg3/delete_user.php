<!-- Author: 72 -->
<!-- Deletes user by promting user to select from a drop down -->
<?php
require 'db.php';

function fetchUsers() {
    $pdo = connectDB();
    $sql = "SELECT userid, CONCAT(firstname, ' ', lastname) AS name FROM user ORDER BY lastname, firstname";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    disconnectDB($pdo);
    return $users;
}

// Function to check relationships with other users
function checkUserInFollows($userid) {
    $pdo = connectDB();
    // Check both if the user is following someone and if someone is following the user
    $sql = "SELECT COUNT(*) FROM follows WHERE follower = :userid1 OR following = :userid2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userid1' => $userid, 'userid2' => $userid]);
    $count = $stmt->fetchColumn();
    disconnectDB($pdo);

    // Return true is the user is following or being followed by anyone
    return $count > 0;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm']) && $_POST['confirm'] == 'Yes') {
    $userid = $_POST['userid'];
    if (checkUserInFollows($userid)) {
        $message = "You cannot delete this user at this point as they are either following someone or being followed.";
    } else {
        $pdo = connectDB();
        $sql = "DELETE FROM user WHERE userid = :userid";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['userid' => $userid]);
        disconnectDB($pdo);
        if ($result) {
            $message = "User successfully deleted.";
        } else {
            $message = "An error occurred. User not deleted.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User</title>
    <script>
        // A little js function I wrote to prompt confirm deletion
        function confirmDeletion() {
            var userid = document.getElementById('userid').value;
            return confirm('Are you sure you want to delete user with ID: ' + userid + '?');
        }
    </script>
    <link rel="stylesheet" href="delete_user.css">
</head>
<body>
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" onsubmit="return confirmDeletion();">
        <label for="userid">Select User to Delete:</label>
        <select name="userid" id="userid" required>
            <?php
                $users = fetchUsers();
                foreach ($users as $user) {
                    echo "<option value=\"".htmlspecialchars($user['userid'])."\">".htmlspecialchars($user['name'])."</option>";
                }
            ?>
        </select>
        <input type="hidden" name="confirm" value="Yes">
        <button type="submit">Delete User</button>
    </form>
</body>
</html>
