<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Registrierung</title>
        <link rel=icon href=bilder/info_logo.png sizes="any" type="image/png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Informatik-Raum" content="Das ist eine Fehrnsteuerung für unseren Informatik-Raum">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link href="css/account-control.css" rel="stylesheet">

        <?php
            session_start();
            $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');

            if (!isset($_SESSION['email'])) {
                die('Bitte zuerst <a href="index.php">einloggen</a>');
            }

            if (!isset($_SESSION['userid'])) {
                die('Bitte zuerst <a href="index.php">einloggen</a>');
            }

            if (!isset($_SESSION['get_admin'])) {
                die('Bitte zuerst <a href="index.php">einloggen</a>');
            }

            if ($_SESSION['get_admin'] === 'off') {
                die('Du musst ein Admin sein, um auf das Account - Management zuzugreifen <a href="control.php"> zum Anfang</a>');
            }

            $Namen = "";
            $sql = "SELECT vorname, nachname FROM users";
        ?>
    </head>

    <body>
    <?php
        $showFormularSelect = true;
        $showFormularRegister = false;
        $showFormularDelete = false;
        $showAccountDelete = false;
        $showAccountRegister = false;

        if (isset($_GET['select'])) {
            $showFormularSelect = false;
            if ($_POST['action'] === 'löschen') {
                $showFormularDelete = true;
            } else {
                if ($_POST['action'] === 'anlegen') {
                    $showFormularRegister = true;
                } else {
                    if ($_POST['action'] === 'anfang') {
                        header("Location: /control.php");
                    }
                }
            }
        }

        if (isset($_GET['delete'])) {
            $delete_statement = $pdo->prepare("SELECT * FROM users WHERE nachname = :nachname");
            $delete_result = $delete_statement->execute(array('nachname' => $_POST['delete']));
            $delete_user = $delete_statement->fetch();

            $statement = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $statement->execute(array('id' => $delete_user['id']));

            $showFormularSelect = false;
            $showAccountDelete = true;
        }

        if (isset($_GET['register'])) {
            if ($_SESSION['get_admin'] === 'off') {
                die('Du musst ein Admin sein, um neue Accounts zu erstellen!');
            } else {
                $error = false;
                $Vorname = $_POST['vorname'];
                $Nachname = $_POST['nachname'];
                $email = $_POST['email'];
                $passwort = $_POST['passwort'];
                $passwort2 = $_POST['passwort2'];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
                    $error = true;
                }

                if (strlen($passwort) == 0) {
                    echo 'Bitte ein Passwort angeben<br>';
                    $error = true;
                }

                if ( $passwort != $passwort2) {
                    echo 'Die Passwörter müssen übereinstimmen<br>';
                    $error = true;
                }

                if (!$error) {
                    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                    $result = $statement->execute(array('email' => $email));
                    $user = $statement->fetch();

                    if($user !== false) {
                        echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
                        $error = true;
                    }
                }

                if (!$error) {
                    $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

                    $statement = $pdo->prepare("INSERT INTO users (email, passwort, vorname, nachname, get_admin) VALUES (:email, :passwort, :vorname, :nachname, :get_admin)");
                    $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'vorname' => $Vorname, 'nachname' => $Nachname, 'get_admin' => 'off'));

                    if($result) {
                        $showFormularSelect = false;
                        $showAccountRegister = true;
                    } else {
                        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
                    }
                }
            }
        }

        if ($showFormularRegister) {
        ?>

        <form action="?register=1" method="post">
            <div class="container">
                <p class="heading">Account - Registrieren</p>

                <div class="box">
                    <p>Vorname</p>
                    <div>
                        <input type="text" placeholder="Max" size="40" maxlength="250" name="vorname">
                    </div>
                </div>

                <div class="box">
                    <p>Nachname</p>
                    <div>
                        <input type="text" placeholder="Musterman" size="40" maxlength="250" name="nachname">
                    </div>
                </div>

                <div class="box">
                    <p>E-Mail</p>
                    <div>
                        <input type="email" placeholder="max.musterman@gmx.de" size="40" maxlength="250" name="email">
                    </div>
                </div>

                <div class="box">
                    <p>Password</p>
                    <div>
                        <input type="password" placeholder="IchBinEinPasswort" size="40"  maxlength="250" name="passwort">
                    </div>
                </div>

                <div class="box">
                    <p>Password - Check</p>
                    <div>
                        <input type="password" placeholder="IchBinEinPasswort" size="40"  maxlength="250" name="passwort2">
                    </div>
                </div>
                <input class="loginBtn" type="submit" value="Abschicken">
            </div>
        </form>

        <?php
            }
        ?>

        <?php
        if ($showFormularSelect) {
            ?>

            <form action="?select=<?php echo $_SESSION['userid']; ?>" method="post">
                <div class="container">
                    <p class="heading">Account - Management</p>

                    <div class="box">
                        <p>Auswahl</p>
                        <div>
                            <select name="action" size="1">
                                <option value="löschen" name="delete">Account - Löschen</option>
                                <option value="anlegen" name="register">Account - Registrieren</option>
                                <option value="anfang" name="start">Zurück zur Tür</option>
                            </select>
                        </div>
                    </div>

                    <input class="loginBtn" type="submit" value="Wählen">
                </div>
            </form>

            <?php
                }
            ?>

        <?php
        if ($showFormularDelete) {
            ?>

            <form action="?delete=<?php echo $_SESSION['userid']; ?>" method="post">
                <div class="container">
                    <p class="heading">Account - Löschen</p>
                    <!-- Datenbank - Abfrage und dann Auswahlmenü zum Löschen -->
                    <div class="box">
                        <p>Account - Auswahl</p>
                        <div>
                            <select name="delete" size="1">
                                <?php
                                    foreach ($pdo->query($sql) as $row) {
                                        echo "<option value='".$row['nachname']."' name='delete'>Name: ".$row['vorname']." ".$row['nachname']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <input class="loginBtn" type="submit" value="Wählen">
                </div>
            </form>

            <?php
                }
            ?>

            <?php
                if ($showAccountDelete) {
                ?>

            <form action="?delete_erfolgreich=<?php echo $_SESSION['userid']; ?>" method="post">
                <div class="container">
                    <p class="heading">Account - Management</p>

                    <div class="box">
                        <p>Status</p>
                        <div>
                            <select name="action" size="1">
                                <option value="löschen" name="delete">Erfolgreich Gelöscht!</option>
                            </select>
                        </div>
                    </div>

                    <input class="loginBtn" type="submit" value="Zum Anfang" href="account-control.php">
                </div>
            </form>

            <?php
                }
            ?>

            <?php
                if ($showAccountRegister) {
                ?>

            <form action="?register_erfolgreich=<?php echo $_SESSION['userid']; ?>" method="post">
                <div class="container">
                    <p class="heading">Account - Management</p>

                    <div class="box">
                        <p>Status</p>
                        <div>
                            <select name="action" size="1">
                                <option value="angelegt" name="registered">Erfolgreich Angelegt!</option>
                            </select>
                        </div>
                    </div>

                    <input class="loginBtn" type="submit" value="Zum Anfang" href="account-control.php">
                </div>
            </form>

            <?php
                }
            ?>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    </body>
</html>