<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header("Location: login.php");
    exit;
}
if (!$_SESSION['isAdmin']) {
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
    <title>Zarządzanie - e-grafik</title>
    <?php
    require_once "functions.php";
    links();
    ?>
</head>
<body>
<main>
    <noscript>
        <h2>Włącz JavaScript aby używać aplikacji e-grafik</h2>
    </noscript>
    <div class="container">
        <h1>Zarządzanie użytkownikami</h1>
        <?php
        element("index.php", "Strona Główna", "bi bi-house-door");
        elementOnClick("addUserBox()", "Dodaj Użytkownika", "bi bi-person-plus");

        require "config.php";
        $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) {
            die('Błąd odczytu danych ' . $conn->connect_error);
        }
        $conn->query("set names utf8;");
        $sql = "SELECT users.id as id, name as name, user as user, surname as surname, mail as mail, isAdmin as isAdmin, Etykieta as grupaZawodowa FROM users INNER JOIN grupyZawodowe ON grupyZawodowe.id = grupaZawodowa WHERE isDisabled=0";
        $result = $conn->query($sql);
        $_usersData = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_usersData .= ' <tr onclick="editUser()"><td>' . $row['name'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['user'] . '</td><td>' . $row['grupaZawodowa'] . '</td><td>' . $row['mail'] . '</td><td><i class="bi bi-pencil-square"></i></td></tr> ';
            }
        } else {
            $_usersData .= '<hr>Brak Użytkowników';
        }
        $conn->close();

        ?>
        <hr>
        <div class="box mainBox wrap">
            <h2>Użytkownicy</h2>
            <hr>
            <table class="table1 smallTable">
                <tr>
                    <th>
                        Imię
                    </th>
                    <th>
                        Nazwisko
                    </th>
                    <th>
                        Login
                    </th>
                    <th>
                        Grupa zawodowa
                    </th>
                    <th>
                        Mail
                    </th>
                    <th>

                    </th>
                </tr>
                <?php
                echo $_usersData;
                ?>
            </table>
        </div>
        <div class="box mainBox">
            <h2>Użytkownicy do akceptacji</h2>
        </div>
    </div>
</main>
</body>
</html>

