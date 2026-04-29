<?php 
include 'db_config.php';

$id = $_GET['id'];
$get_user = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($get_user);

if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = $_POST['role'];

    $update_sql = "UPDATE users SET username='$username', role='$role' WHERE id=$id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: index");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Edit User</title>
</head>
<body>
    <div class="form-card">
        <h2>Update User #<?php echo $id; ?></h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            
            <label>Role:</label>
            <select name="role">
                <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            
            <button type="submit" name="update" class="btn-save">Update Changes</button>
            <a href="index" class="btn-cancel">Back</a>
        </form>
    </div>
</body>
</html>