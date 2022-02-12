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
    <div class="container">
        <h1>Witaj, <?php echo $_SESSION['imie'] ?></h1>
        <a href="calendar.php">
        <div class="box small-width small-height"><i class="bi bi-calendar-week"></i>
            <p>Kalendarz</p></div>
        </a>
        <a href="pdfData.php">
            <div class="box small-width small-height"><i class="bi bi-file-earmark-pdf"></i>
                <p>Export PDF</p></div>
        </a>
        <a href="excelData.php">
            <div class="box small-width small-height"><i class="bi bi-file-earmark-spreadsheet"></i>
                <p>Export Excel</p></div>
        </a>
        <hr>
        <a href="logout.php">
            <div class="box small-width small-height"><i class="bi bi-door-open"></i>
                <p>Wyloguj się</p></div>
        </a>
    </div>
</main>
</body>
</html>

