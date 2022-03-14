<?php
require_once "config.php";
$resp = array('ok' => false, 'errors' => [], 'data' => []);
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true || $_SESSION['isAdmin'] != true & isset($_SESSION['isAdmin'])) {
    $resp['errors'][] = array('inf' => 'Unauthorized Operation');
} else {
    if ($_GET) {
        switch ($_GET['action']) {
            case "GET":
                if (is_numeric($_GET['id'])) {
                    $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                    if ($conn->connect_error) {
                        $resp['errors'][] = array('inf' => 'DataBase connection error');
                    }
                    $conn->set_charset("utf8");
                    $sql = "SELECT user,name,surname,mail,isAdmin,grupaZawodowa FROM users WHERE isDisabled=0 AND id=".$_GET['id']." LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $isAdmin = $row['isAdmin']=='1';
                            $resp['data'][] = array('user'=>$row['user'],'name'=>$row['name'],'surname'=>$row['surname'],'mail'=>$row['mail'],'isAdmin'=>$isAdmin,'workGroup'=>$row['grupaZawodowa']);
                            $resp['ok'] = true;
                        }

                    }
                    else{
                        $resp['errors'][] = array('inf' => 'User not found');
                    }
                    $conn->close();
                } else {
                    $resp['errors'][] = array('inf' => 'Enter full data');
                }
                break;
            case "DELETE":
                if (is_numeric($_GET['id'])) {
                    //Here Del Code
                } else {
                    $resp['errors'][] = array('inf' => 'Enter full data');
                }
                break;
            case "SET":
                //here save code
                break;
            case "CREATE":
                //here creaion code
                break;
            default:
                $resp['errors'][] = array('inf' => 'Undefined action');
                break;
        }
    } else {
        $resp['errors'][] = array('inf' => 'No Data On GET param');
    }
}
echo json_encode($resp);