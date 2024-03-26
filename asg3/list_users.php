<!-- Author: 72 -->
<!-- Just list users and allows for different ordering -->
<!-- Also links to other page in which you can view a users details -->

<?php
include 'db.php'; 

// Function to fetch users
function fetchUsers($orderBy = 'lastName', $orderDirection = 'ASC') {
    $pdo = connectDB(); //get pdo object

    $sql = "SELECT userid, firstName, lastName FROM user ORDER BY $orderBy $orderDirection"; // Ensure your table and column names match your database schema
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Check if the form has been submitted for ordering
$orderBy = isset($_POST['orderBy']) ? $_POST['orderBy'] : 'lastName';
$orderDirection = isset($_POST['orderDirection']) ? $_POST['orderDirection'] : 'ASC';

$users = fetchUsers($orderBy, $orderDirection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Users</title>
    <link rel="stylesheet" href="list_users.css"> 
</head>
<body>
<div class="container">
    <h1>User List</h1>
    <form action="list_users.php" method="post">
        <label><input type="radio" name="orderBy" value="firstName" <?php echo $orderBy === 'firstName' ? 'checked' : ''; ?>> First Name</label>
        <label><input type="radio" name="orderBy" value="lastName" <?php echo $orderBy === 'lastName' ? 'checked' : ''; ?>> Last Name</label>
        <label><input type="radio" name="orderDirection" value="ASC" <?php echo $orderDirection === 'ASC' ? 'checked' : ''; ?>> Ascending</label>
        <label><input type="radio" name="orderDirection" value="DESC" <?php echo $orderDirection === 'DESC' ? 'checked' : ''; ?>> Descending</label>
        <button type="submit">Sort</button>
    </form>

    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Details</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr onclick="window.location='user_details.php?userid=<?php echo $user['userid']; ?>';" style="cursor:pointer">
                <td><?php echo htmlspecialchars($user['userid']); ?></td>
                <td><?php echo htmlspecialchars($user['firstName']); ?></td>
                <td><?php echo htmlspecialchars($user['lastName']); ?></td>
                <td>View Details</td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


</body>
</html>
