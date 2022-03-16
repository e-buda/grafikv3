<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header("Location: login.php");
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
    <title>Ustawienia - e-grafik</title>
    <?php
    require_once "functions.php";
    links();
    ?>
    <script src="js/passPower.js"></script>
</head>
<body>
<main>
    <noscript>
        <h2>Włącz JavaScript aby używać aplikacji e-grafik</h2>
    </noscript>
    <div class="notificationBox" id="notification">
    </div>
    <?php
    if($_POST){
        switch ($_POST['action']){
            case "mail":
                if($_SESSION['email']!=$_POST['mail']){
                    $_SESSION['email'] = $_POST['mail'];
                    echo '<script>notification("Ustawiono nowy e-mail","rgba(0,255,0,0.3)",4000,true)</script>';
                }
                else{
                    echo '<script>notification("Nowy e-mail nie może być takie samo jak stare","rgba(255,0,0,0.3)",4000,true)</script>';
                }
                break;
            case "password":
                if($_POST['newPass']!="") {
                    if ($_POST['newPass'] == $_POST['reNewPass']) {
                        require "config.php";
                        $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                        if ($conn->connect_error) {
                            die('Błąd odczytu danych ' . $conn->connect_error);
                        }
                        $conn->query("set names utf8;");
                        $sql = "SELECT password FROM users WHERE id=" . $_SESSION['id'];
                        $result = $conn->query($sql);
                        $ok = false;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if (password_verify($_POST['actualPass'], $row['password'])) {
                                    $ok = true;
                                    $hash = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
                                    $conn->query("UPDATE users SET password='" . $hash . "' WHERE id=" . $_SESSION['id']);
                                    echo '<script>notification("Ustawiono nowe hasło","rgba(0,255,0,0.3)",4000,true)</script>';
                                }
                            }
                            if (!$ok) {
                                echo '<script>notification("Aktualne hasło jest niewłaściwe","rgba(255,0,0,0.3)",4000,true)</script>';
                            }
                        } else {
                            echo '<script>notification("Błąd po stronie bazy danych","rgba(255,0,0,0.3)",4000,true)</script>';
                        }
                        $conn->close();
                    } else {
                        echo '<script>notification("Nowe hasła są niezgodne","rgba(255,0,0,0.3)",4000,true)</script>';
                    }
                }
                else{
                    echo '<script>notification("Nowe hasło jest puste","rgba(255,0,0,0.3)",4000,true)</script>';
                }
                break;
            default:
                echo '<script>notification("Nie rozpoznano typu","rgba(255,0,0,0.3)",4000,true)</script>';
                break;
        }
    }
    ?>
    <div class="container">
        <h1>Ustawienia</h1>
        <?php
        element("index.php", "Strona Główna", "bi bi-house-door");
        ?>
        <hr>
        <div class="box halfBox wrap">
            <h2>Hasło</h2>
            <form method="post">
                <input placeholder="Aktualne hasło..." type="password" id="actualPass" name="actualPass">
                <input placeholder="Nowe hasło..." id="newPass" name="newPass" type="password" oninput="checkPassPower(this,'<?php echo $_SESSION['username'] ?>')">
                <div id="passPowerBox">
                    <span id="passPowerInf">Hasło Słabe</span>
                </div>
                <input placeholder="Powtórz nowe hasło..." id="reNewPass" name="reNewPass" type="password">
                <input value="password" type="hidden" name="action">
                <input type="reset" value="Wyczyść">
                <input type="submit" class="mainBtn" value="Zapisz">
            </form>
        </div>
        <div class="box halfBox wrap">
            <h2>e-mail</h2>
            <form method="post">
                <input placeholder="e-mail" type="mail" id="mail" name="mail" value="<?php echo $_SESSION['email'] ?>">
                <input value="mail" name="action" type="hidden">
                <input type="reset" value="Wyczyść">
                <input type="submit" class="mainBtn" value="Zapisz">
            </form>
        </div>
    </div>

</main>
</body>
</html>

