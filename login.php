<?php
session_start();
require_once 'db.php';

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "select * from users where email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
    $stmt->close();
}

include_once 'header.php';



?>

<h1>
Login
</h1>

<form action="" method="post">
    <div>
        Email: 
        <input type="text" name="email" />
    </div>
    <div>
        Password: 
        <input type="password" name="password" />
    </div>
    <button type="submit" name="login">Loin</button>
    <div>
        <a href="register.php">Don't have an account? Register.</a>
    </div>
</form>


<?php
include_once 'footer.php';
?>