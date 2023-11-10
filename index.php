<?php 
    session_start();

    include("database.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $reenter = filter_input(INPUT_POST, "reenter", FILTER_SANITIZE_SPECIAL_CHARS);

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['reenter'] = $reenter;

        if (empty($username)) {
            $message = "Please enter a username";
        }
        elseif(empty($password)) {
            $message = "Please enter a password";
        }
        elseif($password != $reenter) {
            $message = "Password does not match";
        }
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user, password)
                    VALUES ('$username', '$hash')";
           
           try {
                mysqli_query($conn, $sql);
                $_SESSION['username'] = null;
                $_SESSION['password'] = null;
                $_SESSION['reenter'] = null;
                $message = "You are now registered!";
           }
           catch (mysqli_sql_exception){
                $message = "That username is taken";
           }
           
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1> MyFake</h2>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <h2>Register</h2>
        Username <br>
        <input type="text" name="username" <?php if(isset($_SESSION['username']) && $_SESSION['username']!=""){echo " value='".$_SESSION['username']."'";} ?>><br>
        Password <br>
        <input type="password" name="password" <?php if(isset($_SESSION['password']) && $_SESSION['password']!=""){echo " value='".$_SESSION['password']."'";} ?>><br>
        Re-enter Password <br>
        <input type="password" name="reenter" <?php if(isset($_SESSION['reenter']) && $_SESSION['reenter']!=""){echo " value='".$_SESSION['reenter']."'";} ?>><br>
        <input type="submit" class="submit" name="submit" value="Register"><br>
        <div class="error"><?php if(isset($message) && $message != ""){echo "$message";}?></div>
        
 
    </form>
</body>
</html>

<?php 
   

    mysqli_close($conn)
?>