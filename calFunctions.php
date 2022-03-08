<?php
function checkLock($month,$year,$withData)
{
    if ($month == 1) {
        $monthPrevius = 12;
        $yearPrevius = $year - 1;
    } else {
        $monthPrevius = $month - 1;
        $yearPrevius = $year;
    }
    require "config.php";
    $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die('Błąd odczytu danych');
    }
    $edit = true;
    $dzienBlokady = 1;
    $conn->query("set names utf8;");
    $sql = "SELECT date as dataBlokady FROM blokada WHERE month=" . $month . " AND year=" . $year." ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    $conn->close();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dzienBlokady = strtotime($row['dataBlokady']);
            date_default_timezone_set('Europe/Warsaw');
            $date = strtotime(date('Y-m-d'));
            $edit = !($dzienBlokady < $date);
        }
    } else {
        $monthA = $monthPrevius;
        $yearA = $yearPrevius;
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
        $edit = !($dzienBlokady < $date);
    }
    if($withData){
        return [$dzienBlokady,$edit];
    }
    return $edit;
}