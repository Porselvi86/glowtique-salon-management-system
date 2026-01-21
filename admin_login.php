<?php
session_start();
if(isset($_SESSION['admin'])){
    header("Location: admin_dashboard.php");
    exit;
}

$error = '';
if(isset($_POST['login'])){
    $conn = new mysqli("localhost","root","","glowtique_db");
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username,$password);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0){
        $_SESSION['admin']=$username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error="Invalid username or password";
    }
}
?>

<form method="POST">
<h2>Admin Login</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>