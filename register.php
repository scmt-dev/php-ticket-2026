<?php
include_once 'header.php';
require_once 'db.php';
$isSubmitted = isset($_POST['register']);
$message = '';
$error = false;

$fileName = 'logs/error-'.date('Y-m-d').'.log';

if($isSubmitted) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if(!$name) {
        $message = "Name is required.";
        $error = true;
    }
    if (!$email) {
        $message = "Email is required.";
        $error = true;
    }
    if (!$password) {
        $message = "Password is required.";
        $error = true;
    }
    if(strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $error = true;
    }
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $error = true;
    }

    // check email uniqueness
    $sql = "select * from users where email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $message = "Email already exists.";
        $error = true;
    }
    $stmt->close();

    if(!$error) {
        $sql = "insert into users (name, email, password) values (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $stmt->execute();
        $stmt->close();
        $message = "User registered successfully!";
    }

    if($error) {
        $logData = date('Y-m-d H:i:s').'|'.
        $_SERVER['REMOTE_ADDR']."|$message\n";
        file_put_contents($fileName, $logData, FILE_APPEND);
    }

    
}
?>

<h1>
Register
</h1>

<form action="" method="post">
    <div>
        Name: 
        <input type="text" name="name" />
    </div>
    <div>
        Email: 
        <input type="text" name="email" />
    </div>
    <div>
        Password: 
        <input type="password" name="password" />
    </div>
     <div>
        Confirm Password: 
        <input type="password" name="confirm_password" />
    </div>
    <div>
        <?php echo $message; ?>
    </div>
    <button type="submit" name="register">Register</button>
    <div>
        <a href="login.php">Already have an account? Login.</a>
    </div>
</form>


<?php
include_once 'footer.php';
?>