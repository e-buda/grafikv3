<?php
if (!$_GET) {
    header("Location: calendar.php?month=" . date("m") . "&year=" . date("Y"));
    exit;
}
if (!is_numeric($_GET['month']) || !is_numeric($_GET['year']) || $_GET['month'] > 12 || $_GET['month'] < 1) {
    header("Location: calendar.php?month=" . date("m") . "&year=" . date("Y"));
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
    <title>Kalendarz - e-grafik</title>
    <?php
    require_once "functions.php";
    links();
    ?>
</head>
<body>
<main>
    <div class="container">
        <div class="box small-width small-height unlocked">
            <i class="bi bi-calendar-x"></i>
            <p>Edycja Niemożliwa</p>
        </div>
        <a href="<?php
        if ($_GET['month'] == 1) {
            echo "calendar.php?month=12&year=" . ($_GET['year'] - 1);
        } else {
            echo "calendar.php?year=" . $_GET['year'] . "&month=" . ($_GET['month'] - 1);
        }
        ?>">
            <div class="box small-width small-height">
                <i class="bi bi-arrow-left"></i>
                <p>Poprzedni</p>
            </div>
        </a>
        <a href="<?php
        if ($_GET['month'] == 12) {
            echo "calendar.php?month=1&year=" . ($_GET['year'] + 1);
        } else {
            echo "calendar.php?year=" . $_GET['year'] . "&month=" . ($_GET['month'] + 1);
        }
        ?>">
            <div class="box small-width small-height">
                <i class="bi bi-arrow-right"></i>
                <p>Następny</p>
            </div>
        </a>
        <div class="box small-width small-height">
            <i class="bi bi-calendar-check"></i>
        </div>


        <div class="box calBox">
            <div class="calendar">
                <p class="mc"><?php
                    $namesMonth = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
                    $month = $_GET['month'];
                    if (substr($_GET['month'], 0, 1) == "0") {
                        $month = substr($_GET['month'], 1, 1);
                    }
                    echo $namesMonth[$month - 1] . " " . $_GET['year'];
                    ?></p>
                <table>
                    <tr>
                        <th>
                            Poniedziałek
                        </th>
                        <th>
                            Wtorek
                        </th>
                        <th>
                            Środa
                        </th>
                        <th>
                            Czwartek
                        </th>
                        <th>
                            Piątek
                        </th>
                        <th>
                            Sobota
                        </th>
                        <th>
                            Niedziela
                        </th>
                    </tr>
                    <?php
                    $daysCount = cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']);
                    $firstDayOfWeek = date('w', strtotime("01-" . $_GET['month'] . "-" . $_GET['year']));
                    if ($firstDayOfWeek == 0) {
                        $firstDayOfWeek = 7;
                    }
                    $firstDayOfWeek -= 1;
                    $lastDayOfWeek = ($daysCount + $firstDayOfWeek - 1) % 7;
                    $runs = $daysCount + ($firstDayOfWeek + 1) + (6 - ($lastDayOfWeek + 1));
                    for ($x = 0; $x < $runs; $x++) {
                        if ($x % 7 == 0) {
                            echo "<tr>";
                        }
                        if ($x >= $firstDayOfWeek && $x < $firstDayOfWeek + $daysCount) {
                            echo "<td class='day' id='day" . (($x - $firstDayOfWeek) + 1) . "' onclick='choseDay(" . (($x - $firstDayOfWeek) + 1) . ")'>";
                            echo ($x - $firstDayOfWeek) + 1;
                        } else {
                            echo "<td>";
                        }
                        echo "</td>";
                        if ($x % 7 == 6) {
                            echo "</tr>";
                        }

                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>
