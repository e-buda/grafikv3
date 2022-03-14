<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zarejstruj się - e-grafik</title>
    <?php
    require_once "functions.php";
    links();
    ?>
    <script src="./js/passPower.js"></script>
</head>
<body>
<main>
    <noscript>
        <h2>Włącz JavaScript aby używać aplikacji e-grafik</h2>
    </noscript>
    <div class="container">
        <div class="box logRegBox">
            <h1>Zarejstruj się</h1>
            <form method="post">
                <input placeholder="Login..." id="login" name="login">
                <input placeholder="Imię..." name="name">
                <input placeholder="Nazwisko..." name="lastName">
                <input placeholder="E-mail..." type="email" name="mail">
                <select name="grupaZawodowa" id="grupaZawodowa" class="textInput">
                    <?php
                    require_once "config.php";
                    $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                    if ($conn->connect_error) {
                        die('<option value="err">BŁĄD ODCZYTU DANYCH ODŚWIERZ STRONĘ</option>');
                    }
                    $conn->set_charset("utf8");
                    $sql = "SELECT * from grupyZawodowe";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo '<option value="NONE">---Wybierz grupę zawodową---</option>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['Etykieta'] . '</option>';
                        }
                    } else {
                        echo '<option value="err">BRAK GRUP ZAWODOWYCH!</option>';
                    }
                    ?>
                </select>
                <input placeholder="Hasło..." oninput="checkPassPower(this,document.getElementById(`login`).value)"
                       type="password" name="password">
                <div id="passPowerBox">
                    <span id="passPowerInf">Hasło Słabe</span>
                </div>
                <input placeholder="Powtórz hasło..." type="password" name="repassword">
                <input type="submit" value="Zarejstruj się">
                <a href="login.php">Zaloguj się</a>
                <?php

                if ($_POST) {
                    if (isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['grupaZawodowa']) && $_POST['grupaZawodowa'] != "" && isset($_POST['login']) && isset($_POST['mail']) && $_POST['mail'] != '' && isset($_POST['password']) && $_POST['login'] != '' && $_POST['password'] != '' && isset($_POST['repassword']) && $_POST['repassword'] != ''&& isset($_POST['lastName']) && $_POST['lastName'] != '') {
                        if ($_POST['grupaZawodowa'] == "err") {
                            echo '<p>Błąd Wewnętrzny</p>';
                        } else if ($_POST['grupaZawodowa'] == "NONE") {
                            echo '<p>Wybierz grupę zawodową</p>';
                        } else {
                            if ($_POST['password'] != $_POST['repassword']) {
                                echo '<p>Hasła niezgodne</p>';
                            } else {
                                require_once "config.php";
                                $conn = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
                                if ($conn->connect_error) {
                                    die('<p>Baza nie żyje :C</p>');
                                }
                                $nameHex = bin2hex($_POST['name']);
                                $lastNameHex = bin2hex($_POST['lastName']);
                                $loginHex = bin2hex($_POST['login']);
                                $mailHex = bin2hex($_POST['mail']);
                                $sql = "SELECT * FROM akceptaction WHERE user = UNHEX('" . $loginHex . "') OR mail=UNHEX('" . $mailHex . "') UNION SELECT * FROM users WHERE isDisabled=0 AND (user = UNHEX('" . $loginHex . "') OR mail=UNHEX('" . $mailHex . "'))";
                                $result = $conn->query($sql);
                                if ($result->num_rows == 0) {
                                    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                    if ($conn->query("INSERT INTO akceptaction (user,mail,password,grupaZawodowa,name,surname,isAdmin) VALUES (UNHEX('" . $loginHex . "'),UNHEX('" . $mailHex . "'),'" . $hash . "'," . $_POST['grupaZawodowa'] . ",UNHEX('" . $nameHex . "'),UNHEX('" . $lastNameHex . "'),0)") === TRUE) {
                                        echo '<p style="color: green">Zarejstrowano</p>';
                                        require_once 'vendor/autoload.php';
                                        $mail = new PHPMailer\PHPMailer\PHPMailer();
                                        $mail->IsSMTP();
                                        $mail->Mailer = "smtp";
                                        $mail->SMTPDebug = 0;
                                        $mail->SMTPAuth = TRUE;
                                        $mail->SMTPSecure = "tls";
                                        $mail->Port = $smtpport;
                                        $mail->Host = $smtpserver;
                                        $mail->Username = $smtpuser;
                                        $mail->Password = $smtppass;
                                        $mail->IsHTML(true);
                                        $mail->CharSet = "UTF-8";
                                        $mail->AddAddress($_POST['mail'], $_POST['name']);
                                        $mail->SetFrom($sendFormMail, "e-grafik by e-buda");
                                        $mail->AddReplyTo($replayToMail, $replayToName);
                                        $mail->Subject = "Wysłano prośbę";
                                        $content = "<h1>Witaj <strong>" . $_POST['name'] . "</strong></h1><br>Piszemy żeby cię poinformować, że twoja prośba o założenie konta w systemie została zapisana w bazie i przekazana administratorom platformy e-grafik.<br>Miłego korzystanoia z systemu<br><b>e-buda Systems</b>";
                                        $mail->MsgHTML($content);
                                        if (!$mail->Send()) {
                                            echo '<p>błąd wysyłanie emial</p>';
                                        }
                                    } else {
                                        echo '<p>Błąd Przetwarzania danych</p>'."INSERT INTO akceptaction (user,mail,password,grupaZawodowa,name,surname,isAdmin) VALUES (UNHEX('" . $loginHex . "'),UNHEX('" . $mailHex . "'),'" . $hash . "'," . $_POST['grupaZawodowa'] . ",UNHEX('" . $nameHex . "'),UNHEX('" . $lastNameHex . "'),0)";
                                    }
                                } else {
                                    echo '<p>Nazwa użytkownika zajęta</p>';
                                }
                                $conn->close();
                            }
                        }
                    } else {
                        echo '<p>Niepełne dane</p>';
                    }
                }
                ?>
            </form>
        </div>
    </div>
</main>
</body>
</html>

