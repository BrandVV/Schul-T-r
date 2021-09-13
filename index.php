<!doctype html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Anmeldung</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel=icon href=bilder/info_logo.png sizes="any" type="image/png">
        <meta name="Informatik-Raum" content="Das ist eine Fehrnsteuerung für unseren Informatik-Raum">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link href="css/index.css" rel="stylesheet">

        <?php
            session_start();
            $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
            $_SESSION['userid'] = null;

            if (isset($_GET['id'])) {
                $email = $_POST['email'];
                $_SESSION['email'] = $email;
                $passwort = $_POST['passwort'];

                $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $result = $statement->execute(array('email' => $email));
                $user = $statement->fetch();

                if ($user !== false && password_verify($passwort, $user['passwort'])) {
                    $_SESSION['get_admin'] = $user['get_admin'];
                    $_SESSION['userid'] = $user['id'];
                    header("refresh:0; control.php");
                } else {
                    $errorMessage = "<a class='error'>E-Mail oder Passwort ist ungültig</a><br>";
                }

            }
        ?>
    </head>

    <body>
        <?php
            if (isset($errorMessage)) {
                echo $errorMessage;
            }
        ?>
        <form action="?id=<?php echo $_SESSION['userid']; ?>" method="post">
            <div class="container">
                <p class="heading">Log-In</p>
                <div class="box">
                    <p>Email</p>
                    <div>
                        <input type="email" placeholder="max.musterman@mustermail.com" size="40" maxlength="250" name="email">
                    </div>
                </div>

                <div class="box">
                    <p>Password</p>
                    <div>
                        <input type="password"  placeholder="IchBinEinPasswort" size="40"  maxlength="250" name="passwort"><br>
                    </div>
                </div>
                <button class="loginBtn" type="submit"><a class="btnText">Login</a></button>
            </div>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    </body>
</html>