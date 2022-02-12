<?php
session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zaloguj się - e-grafik</title>
    <?php
    require_once "functions.php";
    links();
    ?>
</head>
<body>
<main>
    <div class="container">
        <div class="box logRegBox">
            <h1>Zaloguj się</h1>
            <form method="post">
                <input placeholder="Login..." name="login">
                <input placeholder="Hasło..." type="password" name="password">
                <input type="submit" value="Zaloguj się">
                <a href="register.php">Zarejstruj się</a>
                <a href="forgotPass.php">Zresetuj hasło</a>
                <?php
                if ($_POST) {
                    if (isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] != '' && $_POST['password'] != '') {
                        $email = strpos($_POST['login'], "@");
                        $loginHex = bin2hex($_POST['login']);
                        require_once "config.php";
                        $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                        if ($conn->connect_error) {
                            die('<p>Bład Bazy</p>');
                        }
                        $conn->set_charset("utf8");
                        if ($email) {
                            $sql = "SELECT * from users WHERE mail=UNHEX('" . $loginHex . "')";
                        } else {
                            $sql = "SELECT * from users WHERE user=UNHEX('" . $loginHex . "')";
                        }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if(password_verify($_POST['password'], $row['password'])) {
                                    $conn->query("INSERT INTO logLogowan (user,timestamp) values (" . $row['id'] . ",now())");
                                    $_SESSION['id'] = $row['id'];
                                    $_SESSION['username'] = $row['user'];
                                    $_SESSION['email'] = $row['mail'];
                                    $_SESSION['grupaZawodowa'] = $row['grupaZawodowa'];
                                    $_SESSION['isAdmin'] = $row['isAdmin'] == 1;
                                    $_SESSION['logged'] = true;
                                    $_SESSION['imie'] = $row['name'];
                                    header("Location: index.php");
                                    exit;
                                }
                                else{
                                    echo '<p>Hasło niepoprawne</p>';
                                }
                            }
                        } else {
                            if ($email) {
                                $sql = "SELECT * from akceptaction WHERE mail=unhex('" . $loginHex . "')";
                            } else {
                                $sql = "SELECT * from akceptaction WHERE user=UNHEX('" . $loginHex . "')";
                            }
                            $result2 = $conn->query($sql);
                            if ($result2->num_rows > 0) {
                                echo '<p>Użytkownik nieakceptowany</p>';
                            } else {
                                echo '<p>Nieznany użytkownik</p>';
                            }
                        }
                        $conn->close();
                    } else {
                        echo 'Braki w danych';
                    }
                }
                ?>
            </form>
        </div>
    </div>
</main>
</body>
</html>

