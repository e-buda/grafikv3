<?php
$resp = array('ok' => false, 'errors' => []);
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true) {
    $resp['errors'][] = array('inf' => 'Unauthorized Operation');
}
else {
    require_once "config.php";
    require_once "calFunctions.php";
    if (is_numeric($_GET['day']) && is_numeric($_GET['month']) && is_numeric($_GET['year']) && is_numeric($_GET['type'])) {
        if ($_GET['month'] > 0 && $_GET['month'] <= 12 && $_GET['day'] > 0 && $_GET['day'] <= cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year'])) {
            if (checkLock($_GET['month'], $_GET['year'], false)) {
                $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                if ($conn->connect_error) {
                    $resp['errors'][] = array('inf' => 'Baza danych udaje trupa');
                }
                else {
                    $conn->set_charset("utf8");
                    $sql = "SELECT * FROM uprawnieniaDniDlaGrup uprawnienia LEFT JOIN typyDni typy ON uprawnienia.typDnia = typy.id  WHERE typy.id=".$_GET['type']." AND uprawnienia.grupa=".$_SESSION['grupaZawodowa']." AND typy.disabled=0";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $resp['ok'] = true;
                    }
                    else{
                        $resp['errors'][] = array('inf' =>"Nieodpowiednia Zmiana");
                    }

                }
                $conn->close();
            } else {
                $resp['errors'][] = array('inf' => 'Wybrana data jest niemodyfikowalna');
            }
        } else {
            $resp['errors'][] = array('inf' => 'Data on input is out of range');
        }
    } else {
        $resp['errors'][] = array('inf' => 'Invalid data on input');
    }
}
echo json_encode($resp);