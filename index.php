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
        <h1>Witaj, USER</h1>
        <a href="calendar.php">
        <div class="box small-width small-height"><i class="bi bi-calendar-week"></i>
            <p>Kalendarz</p></div>
        </a>
        <a href="calendar.php">
            <div class="box small-width small-height"><i class="bi bi-file-earmark-arrow-down"></i>
                <p>Data Export</p></div>
        </a>
    </div>
</main>
</body>
</html>

