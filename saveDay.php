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
                        $maxVal = -1;
                        $maxGroupVal = -1;
                        $sql = "SELECT IFNULL((SELECT val FROM maxVal WHERE userGroup = 3 AND type = 1), -1) val";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $maxVal = $row['val'];
                            }
                        }
                        $sql = "SELECT IFNULL((SELECT maxValsGroups.val FROM typyDni INNER JOIN maxValsGroups ON typyDni.maxValGroup = maxValsGroups.id WHERE typyDni.id = ".$_GET['type']." AND maxValsGroups.userGroup = ".$_SESSION['grupaZawodowa']."),-1) val;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $maxGroupVal = $row['val'];
                            }
                        }
                        $resp['ok'] = true;
                        if($maxVal != -1){
                            $sql = "SELECT typyDni.etykieta AS name, users.name AS firstName, users.surname AS LastName FROM daneDni INNER JOIN users ON users.id = daneDni.user INNER JOIN typyDni ON typyDni.id = daneDni.typeDay INNER JOIN maxVal ON users.grupaZawodowa = maxVal.userGroup AND daneDni.typeDay = maxVal.type WHERE date = '".$_GET['year']."-".$_GET['month']."-".$_GET['day']."' AND daneDni.typeDay = ".$_GET['type']." AND users.grupaZawodowa = ".$_SESSION['grupaZawodowa'];
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $resp['ok'] = false;
                                $lockedBy = [];
                                $name = "";
                                while ($row = $result->fetch_assoc()) {
                                    $maxGroupVal = $row['val'];
                                    $lockedBy[] = $row['firstName']." ".$row['LastName'];
                                    $name = $row['name'];

                                }
                                 $resp['errors'][] = array('inf'=>'blocked', 'lockedBy'=>$lockedBy,'isGroup'=>false,'name'=>$name);
                            }
                        }
                        if( $maxGroupVal != -1){
                            $sql = "SELECT maxValsGroups.name AS name, users.name AS firstName, users.surname AS LastName FROM daneDni INNER JOIN typyDni ON typyDni.id = daneDni.typeDay INNER JOIN users ON users.id = daneDni.user INNER JOIN maxValsGroups ON maxValsGroups.id = typyDni.maxValGroup AND users.grupaZawodowa = maxValsGroups.userGroup WHERE date = '".$_GET['year']."-".$_GET['month']."-".$_GET['day']."' AND users.grupaZawodowa = ".$_SESSION['grupaZawodowa']." AND(SELECT maxValGroup FROM typyDni INNER JOIN maxValsGroups ON typyDni.maxValGroup = maxValsGroups.id AND maxValsGroups.userGroup = ".$_SESSION['grupaZawodowa']." WHERE typyDni.id = ".$_GET['type'].") = maxValsGroups.id;";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $resp['ok'] = false;
                                $lockedBy = [];
                                $name = "";
                                while ($row = $result->fetch_assoc()) {
                                    $maxGroupVal = $row['val'];
                                    $lockedBy[] = $row['firstName']." ".$row['LastName'];
                                    $name = $row['name'];

                                }
                                $resp['errors'][] = array('inf'=>'blocked', 'lockedBy'=>$lockedBy,'isGroup'=>true, 'name'=> $name);
                            }
                        }
                        if($resp['ok']){
                            if(!$conn->query("INSERT INTO daneDni (user, typeDay, date) VALUES (".$_SESSION['id'].",".$_GET['type'].",'".$_GET['year']."-".$_GET['month']."-".$_GET['day']."') ")){
                                $resp['errors'][] = array('inf' =>"Błąd zapisu");
                                $resp['ok'] = false;
                            }
                        }
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