<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    header("Location: login.php");
    exit;
}
?>
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
    <script src="./js/cal.js"></script>
</head>
<body onload="init()">
<main>
    <noscript>
        <h2>Włącz JavaScript aby używać aplikacji e-grafik</h2>
    </noscript>
    <div id="loadingBox">
        <h2>Ładowanie...</h2>
    </div>
    <div class="notificationBox" id="notification">

    </div>
    <div class="container">
        <a href="index.php">
            <div class="box small-width small-height">
                <i class="bi bi-house-door"></i>
                Strona Główna
            </div>
        </a>
        <a href="<?php
        if ($_GET['month'] == 1) {
            $_monthPrevius = 12;
            $_yearPrevius = $_GET['year'] - 1;
            echo "calendar.php?month=12&year=" . ($_GET['year'] - 1);
        } else {
            $_monthPrevius = $_GET['month'] - 1;
            $_yearPrevius = $_GET['year'];
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
            $_monthNext = 1;
            $_yearNext = $_GET['year'] + 1;
            echo "calendar.php?month=1&year=" . ($_GET['year'] + 1);
        } else {
            $_monthNext = 1;
            $_yearNext = $_GET['year'];
            echo "calendar.php?year=" . $_GET['year'] . "&month=" . ($_GET['month'] + 1);
        }
        ?>">
            <div class="box small-width small-height">
                <i class="bi bi-arrow-right"></i>
                <p>Następny</p>
            </div>
        </a>
        <?php
        require_once "config.php";
        $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) {
            die('Błąd odczytu danych');
        }
        $_edit = true;
        $dzienBlokady = 1;
        $conn->query("set names utf8;");
        $sql = "SELECT date as dataBlokady FROM blokada WHERE month=" . $month . " AND year=" . $year;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dzienBlokady = strtotime($row['dataBlokady']);
                date_default_timezone_set('Europe/Warsaw');
                $date = strtotime(date('Y-m-d'));
                $_edit = !($dzienBlokady < $date);
                //echo "Blokada: <strong>" . date('d', $dzienBlokady) . "-" . date('m', $dzienBlokady) . "-" . date('Y', $dzienBlokady) . "</strong>";
            }
        } else {
            $monthA = $_monthPrevius;
            $yearA = $_yearPrevius;
            $dayBlokady = 20;
            $ostatni = strtotime($dayBlokady . "-" . $monthA . "-" . $yearA);
            $dzienSlowo = date('l', $ostatni);
            switch (substr($dzienSlowo, 0, 3)) {
                case "Mon":
                    $blank = 3;
                    break;
                case "Tue":
                    $blank = 4;
                    break;
                case "Wed":
                    $blank = 5;
                    break;
                case "Thu":
                    $blank = 6;
                    break;
                case "Fri":
                    $blank = 0;
                    break;
                case "Sat":
                    $blank = 1;
                    break;
                case "Sun":
                    $blank = 2;
                    break;
            }
            $dayBlokady -= $blank;
            $dzienBlokady = strtotime($dayBlokady . "-" . $monthA . "-" . $yearA);
            date_default_timezone_set('Europe/Warsaw');
            $date = strtotime(date('Y-m-d'));
            $_edit = !($dzienBlokady < $date);
            //echo "Blokada: <strong>" . date('d', $dzienBlokady) . "-" . date('m', $dzienBlokady) . "-" . date('Y', $dzienBlokady) . "</strong>";
        }
        $conn->close();
        if (!$_edit) {
            echo '
            <div class="box small-width small-height locked">
            <i class="bi bi-calendar-x"></i>
            <p>Edycja Niemożliwa</p>
        </div>';
        } else {
            echo '
            <div class="box small-width small-height unlocked">
            <i class="bi bi-calendar-check"></i>
            <p><strong>' . date('d', $dzienBlokady) . "-" . date('m', $dzienBlokady) . "-" . date('Y', $dzienBlokady) . '</strong></p>
        </div>';
        }
        ?>

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
        <div id="selector" class="box daysOptions">
            <h3 id="fullDaySelectorInf">Wybrano Dzień <b><span id="selectedDay">1</span> <?php
                    $namesMonth = ["Stycznia", "Lutego", "Marca", "Kwietnia", "Maja", "Czerwca", "Lipca", "Sierpnia", "Września", "Października", "Listopada", "Grudnia"];
                    $month = $_GET['month'];
                    if (substr($_GET['month'], 0, 1) == "0") {
                        $month = substr($_GET['month'], 1, 1);
                    }
                    echo $namesMonth[$month - 1];
                    ?></b></h3>
            <div class="container">
                <div id="remover" class="option remover" onclick="setForActualDay(-1)">
                    <p>Wyczyść</p>
                </div>
                <?php
                require_once "config.php";
                $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                if ($conn->connect_error) {
                    die('Błąd odczytu danych' . $conn->connect_error);
                }
                $conn->query("set names utf8;");
                $sql = "SELECT s1.etykieta as etykieta, s1.id as id, s1.kolor as kolor from typyDni as s1 LEFT JOIN uprawnieniaDniDlaGrup as s2 on s1.id = s2.typDnia WHERE s2.grupa = " . $_SESSION['grupaZawodowa'];
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="option" onmouseover="overStyle(this,`' . $row['kolor'] . '`)" onmouseout="outStyle(this)" id="selectOption' . $row['id'] . '" onclick="setForActualDay(' . $row['id'] . ')"><p>' . $row['etykieta'] . '</p></div>';
                    }
                } else {
                    echo 'baza typów jest pusta<br>Skontaktoj się z administratorem systemu';
                }
                $conn->close();
                ?>

            </div>
        </div>
    </div>
</main>
</body>
</html>
<script>
    <?php

    require_once "config.php";
    $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die('Błąd odczytu danych' . $conn->connect_error);
    }
    $conn->query("set names utf8;");
    $sql = "SELECT s1.etykieta as etykieta, s1.id as id, s1.kolor as kolor from typyDni as s1 LEFT JOIN uprawnieniaDniDlaGrup as s2 on s1.id = s2.typDnia WHERE s2.grupa = " . $_SESSION['grupaZawodowa'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $print = 'const types=[';
        while ($row = $result->fetch_assoc()) {
            $print .= '{"id":' . $row['id'] . ',"color":"' . $row['kolor'] . '","name":"'.$row['etykieta'].'"},';
        }
        $print = substr($print, 0, -1);
        $print .= "]\n";
        echo $print;
    } else {
        echo 'types=[]\n';
    }
    $conn->close();

    ?>
    <?php

    require_once "config.php";
    $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die('Błąd odczytu danych' . $conn->connect_error);
    }
    $conn->query("set names utf8;");
    $maxDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if ($_GET['year'] % 4 == 0) {
        $maxDaysInMonth[1] = 29;
    }
    if ($_GET['month'] > 0 && $_GET['month'] < 13 && $_GET['year'] > 2000 && $_GET['year'] < 2200) {
        $sql = "SELECT date,typeDay FROM daneDni WHERE date between '" . $_GET['year'] . "-" . $_GET['month'] . "-1' AND '" . $_GET['year'] . "-" . $_GET['month'] . "-" . $maxDaysInMonth[$_GET['month'] - 1] . "' AND user=" . $_SESSION['id'];
    } else {
        $sql = "SELECT date,typeDay FROM daneDni WHERE date between '2021-01-01' AND '2021-01-01' AND user=" . $_SESSION['id'];
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $print = 'var data=[';
        while ($row = $result->fetch_assoc()) {
            $print .= '{"day":' . explode("-", $row['date'])[2] . ',"type":' . $row['typeDay'] . '},';
        }
        $print = substr($print, 0, -1);
        $print .= "]\n";
        echo $print;
    } else {
        echo "var data = []\n";
    }
    $conn->close();
    echo "month=" . $_GET['month'] . "\n";
    echo "year=" . $_GET['year'] . "\n";
    ?>
    function editable() {
        <?php
        if ($_edit) {
            echo "return true";
        } else {
            echo "return false";
        }
        ?>
    }

    function initCal() {
        <?php
        if ($_GET) {
            if (isset($_GET['day'])) {
                echo "choseDay(" . $_GET['day'] . ")";
            } else {
                echo "choseDay(1)";
            }
        } else {
            echo "choseDay(1)";
        }
        ?>
    }
</script>
