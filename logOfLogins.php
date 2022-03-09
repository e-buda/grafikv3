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
if (!isset($_GET['page']) || $_GET['page'] == '') {
    header("Location: logOfLogins.php?page=1");
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
    <title>Log - e-grafik</title>
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
        <h1>Dziennik logowań</h1>
        <?php

        require "config.php";
        $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) {
            die('Błąd odczytu danych ' . $conn->connect_error);
        }
        $conn->query("set names utf8;");
        if(is_numeric($_GET['page'])){
            $sql = "SELECT logLogowan.timestamp as timestamp,users.name as name,users.surname as surname FROM logLogowan INNER JOIN users ON users.id=logLogowan.user ORDER BY logLogowan.id DESC LIMIT ".(($_GET['page']-1)*14).",14";
        }
        else{
            $sql = "SELECT logLogowan.timestamp as timestamp,users.name as name,users.surname as surname FROM logLogowan INNER JOIN users ON users.id=logLogowan.user ORDER BY logLogowan.id DESC";
        }
        $result = $conn->query("SELECT COUNT(*) as count FROM logLogowan");
        $maxVal = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $maxVal=$row['count'];
            }
        }
        $result = $conn->query($sql);
        $_data = "";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_data .= ' <tr><td>' . $row['timestamp'] . '</td><td>' . $row['name'] . '</td><td>' . $row['surname'] . '</td></tr> ';
            }
        } else {
            $_data .= 'Brak logów!';
        }
        $conn->close();

        element("index.php", "Strona Główna", "bi bi-house-door");
        element("#","Strona ".$_GET['page']."/".ceil($maxVal/14),"bi bi-collection");
        if ($_GET['page'] > 1) {
            element("logOfLogins.php?page=" . ($_GET['page'] - 1), "Poprzedni", "bi bi-arrow-left");
        }
        if($_GET['page']<ceil($maxVal/14)) {
            element("logOfLogins.php?page=" . ($_GET['page'] + 1), "Następny", "bi bi-arrow-right");
        }
        ?>
        <div class="box mainBox">
            <table class="table1">
                <tr>
                    <th>
                        Data
                    </th>
                    <th>
                        Imię
                    </th>
                    <th>
                        Nazwisko
                    </th>
                </tr>
                <?php
                echo $_data;
                ?>
            </table>
            <hr>
            <p>Daty i godziny są pobierane w momencie logowania według UTC nie sterfy czasowej klienta</p>
        </div>
    </div>
</main>
</body>
</html>

