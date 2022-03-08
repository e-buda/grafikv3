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
    <title>Dashboard - e-grafik</title>
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
        <h1>Witaj, <?php echo $_SESSION['imie'] ?></h1>
        <?php
        element("calendar.php", "Kalendarz", "bi bi-calendar-week");
        element("pdfData.php", "Export PDF", "bi bi-file-earmark-pdf");
        element("excelData.php", "Export Excel", "bi bi-file-earmark-spreadsheet");
        echo "<hr>";
        element("logout.php", "Wyloguj się", "bi bi-door-open");
        element("dataEdition.php", "Edycja danych", "bi bi-sliders");
        if($_SESSION['isAdmin']) {
            echo '<hr><h3>Ustawienia Aplikacji</h3>';
            element("daysTypes.php", "Typy dni", "bi bi-list-check");
            element("daysLimits.php", "Ograniczenia Wpisów", "bi bi-x-octagon");
            element("usersManagement.php", "Zarządzanie Użytkownikami", "bi bi-person-plus");
            element("logOfLogins.php", "Dziennik Logowań", "bi bi-list-columns-reverse");
            element("calendarLocks.php", "Blokady kalendarza", "bi bi-calendar-x");
            element("worksGroups.php", "Grupy Zawodowe", "bi bi-people");
        }
        ?>
    </div>
</main>
</body>
</html>

