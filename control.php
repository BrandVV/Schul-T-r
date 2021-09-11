<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Tür-Steuerung</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel=icon href=bilder/info_logo.png sizes="any" type="image/png">
        <meta name="Informatik-Raum" content="Das ist eine Fehrnsteuerung für unseren Informatik-Raum">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link href="css/control.css" rel="stylesheet">

        <?php
            session_start();
            $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $get_door_status = false;
            $_SESSION['showSelect'] = true;

            if (!isset($_SESSION['userid'])) {
                die('Bitte zuerst <a href="login.php">einloggen</a>');
            }

            if (!isset($_SESSION['email'])) {
                die('Bitte zuerst <a href="login.php">einloggen</a>');
            }

            $email = $_SESSION['email'];
            $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $result = $statement->execute(array('email' => $email));
            $user = $statement->fetch();

            $statement2 = $pdo->prepare("SELECT oeffnungs_anzahl FROM actions WHERE email = :email ORDER BY oeffnungs_anzahl DESC LIMIT 1");
            $result2 = $statement2->execute(array('email' => $email));
            $anzahl = $statement2->fetch();

            if ($anzahl !== false) {
                $anzahl_tür = intval($anzahl['oeffnungs_anzahl']);
                $anzahl_tür_int = $anzahl_tür + 1;
                $anzahl_tür = strval($anzahl_tür_int);
            } else {
                $anzahl_tür = 1;
            }

            $userid = $_SESSION['userid'];

            if (isset($_GET['id'])) {
                $time = $_POST['time'];

                $statement = $pdo->prepare("INSERT INTO actions (
                    email,
                    vorname,
                    nachname,
                    offene_zeit,
                    oeffnungs_anzahl
                    ) VALUES (
                        :email,
                        :vorname,
                        :nachname,
                        :offene_zeit,
                        :oeffnungs_anzahl
                    )");
                $result = $statement->execute(array(
                    'email' => $email,
                    'vorname' => $user['vorname'],
                    'nachname' => $user['nachname'],
                    'offene_zeit' => $_POST['time'],
                    'oeffnungs_anzahl' => $anzahl_tür,
                ));

                if($result) {
                    $showFormular = true;
                    if ($_POST['time'] !== null) {
                        $url = array('http://192.168.188.21/relay/0?turn=on&timer='.$_POST['time']);
                        @fopen(strval($url), 'r');
                        $get_door_status = true;
                    } else {
                        $url = array('http://192.168.188.21/relay/0?turn=on&timer=5');
                        @fopen(strval($url), 'r');
                        $get_door_status = true;
                    }

                } else {
                    echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
                }
            }
        ?>
    </head>

    <body>
        <form action="?id=<?php echo $_SESSION['userid']; ?>" method="post">
            <div class="container">
                <p class="heading">Tür-Steuerung</p>
                <div class="box">
                    <p><?php
                        echo "Hey, ".$user['vorname'];
                        if ($get_door_status === true) {
                            echo "<br><a>Die Tür ist auf!</a>";
                        }
                    ?></p>
                    <button class="loginBtn"><a class="btnText" href="register.php">Account-Management</a></button>
                </div>

                <div class="box">
                    <p>Zeit: </p>
                    <div>
                        <input type="number" placeholder="00" size="10" maxlength="10" name="time">
                    </div>
                </div>
                <button class="loginBtn" type="submit"><a class="btnText">Öffnen</a></button>
            </div>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    </body>
</html>