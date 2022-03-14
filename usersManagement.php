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
    <script src="js/admin.js"></script>
    <?php
    require_once "functions.php";
    links();
    ?>
</head>
<body>
<div class="modal" id="modifyUserModal">
    <div class="box">
        <h2>Edytuj użytkownika</h2>
        <input placeholder="Imię..." id="editionName">
        <input placeholder="Nazwisko..." id="editionSurname">
        <input placeholder="Login..." id="editionLogin">
        <select name="grupaZawodowa" id="editionWorkGroup" class="textInput">
            <?php
            require_once "config.php";
            $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die('<option value="err">BŁĄD ODCZYTU DANYCH ODŚWIERZ STRONĘ</option>');
            }
            $conn->set_charset("utf8");
            $sql = "SELECT * from grupyZawodowe";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['Etykieta'] . '</option>';
                }
            } else {
                echo '<option value="err">BRAK GRUP ZAWODOWYCH!</option>';
            }
            ?>
        </select>
        <input placeholder="e-mail..." id="editionMail">
        <label for="editionIsAdmin">Administrator</label>
        <input id="editionIsAdmin" type="checkbox" placeholder="Administrator">
        <button onclick="document.getElementById('modifyUserModal').style.display = 'none';">Zamknij Formularz</button>
        <button class="main" onclick="saveUser(id)">Zapisz</button>
    </div>
</div>
<div class="modal" id="newUserModal">
    <div class="box">
        <h2>Tworzenie użytkownika</h2>
        <input placeholder="Imię..." id="creationName">
        <input placeholder="Nazwisko..." id="creationSurname">
        <input placeholder="Login..." id="creationLogin">
        <input placeholder="Hasło..." id="creationPassword">
        <select name="grupaZawodowa" id="creationWorkGroup" class="textInput">
            <?php
            require_once "config.php";
            $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die('<option value="err">BŁĄD ODCZYTU DANYCH ODŚWIERZ STRONĘ</option>');
            }
            $conn->set_charset("utf8");
            $sql = "SELECT * from grupyZawodowe";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['Etykieta'] . '</option>';
                }
            } else {
                echo '<option value="err">BRAK GRUP ZAWODOWYCH!</option>';
            }
            ?>
        </select>
        <input placeholder="e-mail..." id="creationMail">
        <button onclick="document.getElementById('newUserModal').style.display = 'none';">Zamknij Formularz</button>
        <button class="main" onclick="createUser(document.getElementById('creationName').value,document.getElementById('creationSurname').value,document.getElementById('creationLogin').value,document.getElementById('creationPassword').value,document.getElementById('creationWorkGroup').value,document.getElementById('creationMail').value))">Utwórz</button>
    </div>
</div>
<main>
    <div class="notificationBox" id="notification">
    </div>
    <noscript>
        <h2>Włącz JavaScript aby używać aplikacji e-grafik</h2>
    </noscript>
    <div class="container">
        <h1>Zarządzanie użytkownikami</h1>
        <?php
        element("index.php", "Strona Główna", "bi bi-house-door");
        elementOnClick("document.getElementById('newUserModal').style.display = 'flex'", "Dodaj Użytkownika", "bi bi-person-plus");

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
                $_usersData .= ' <tr onclick="modifyUser(' . $row['id'] . ')"><td>' . $row['name'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['user'] . '</td><td>' . $row['grupaZawodowa'] . '</td><td>' . $row['mail'] . '</td><td><i class="bi bi-pencil-square"></i></td></tr> ';
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
        <div class="box mainBox wrap">
            <h2>Użytkownicy do akceptacji</h2>
        </div>
    </div>
</main>
</body>
</html>

