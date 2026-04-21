<?php
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($query->num_rows > 0) {
        $user = $query->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['siswa_id'] = $user['siswa_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "User tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Login</h2>

<?php if (isset($error)) echo $error; ?>

<form method="POST">
    <input type="text" name="username" required><br><br>
    <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>