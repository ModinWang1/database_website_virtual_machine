<!-- Author: 72 -->
<!-- Main menu, starting point for application -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="mainmenu.css"> 
</head>
<body>
    <div class="container">
        <h1>Welcome to the User Management System</h1><br>

        <div class="menu">
            <button onclick="location.href='list_users.php';">View and Sort Users</button>
            <button onclick="location.href='insert_user.php';">Insert New User</button>
            <button onclick="location.href='delete_user.php';">Delete User</button>
            <button onclick="location.href='list_user_modification.php';">Modify User</button>
            <button onclick="location.href='view_posts_by_hashtag.php';">View Posts by Hashtag</button>
            <button onclick="location.href='view_comments.php';">View Comments for a Post</button>
            <button onclick="location.href='top_users.php';">Top Users by Followers</button>
        </div>
    </div>
</body>
</html>
