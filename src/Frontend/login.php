<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////

use Backend\Auth\SessionManager;
use Backend\Model\User;

require_once('../../vendor/autoload.php');
Dotenv\Dotenv::createImmutable(realpath("./../../"))->load();

////////////////////////////////////////////////////////////////////////////////////////////////////////

try {
    SessionManager::start();
} catch (Exception $e) {
    $errors['session_unavailable'] = $e->getMessage();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////

const CREDENTIALS_INVALID = 'Usuario o contraseña incorrecto/s';
$errors = [];

////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET['session_unavailable'])) {
    $errors['session_unavailable'] = $_GET['session_unavailable'];
    $_GET = [];
}

if (isset($_POST['login']) && !empty($_POST['login']) && $_POST['login'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $user = User::getByUsername($username);
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $curTime = time();
                SessionManager::write('USER', $user);
                SessionManager::write('LAST_ACTIVE', $curTime);
                header("Location: Views/principal.php");
            } else {
                $errors['credentials_invalid'] = CREDENTIALS_INVALID;
            }
        } else {
            $errors['credentials_invalid'] = CREDENTIALS_INVALID;
        }
    } catch (Exception $e) {
        $errors['database_connection'] = $e->getMessage();
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="shortcut icon" href="https://ikastaroak.birt.eus/theme/image.php/birt/theme/1641981368/favicon">
    <title>Tienda Birt - Login</title>
</head>
<body>
<main>
    <?php if (isset($_SESSION['SESSION_INVALID']) || array_key_exists('session_unavailable', $errors) || array_key_exists('database_connection', $errors)) : ?>
        <div class="flashes_wrapper">
            <?php if (isset($_SESSION['SESSION_INVALID'])) : ?>
                <div class="flash_container">
                    <small class="warning">
                        <?php
                        echo $_SESSION['SESSION_INVALID'];
                        unset($_SESSION['SESSION_INVALID']);
                        ?>
                    </small>
                </div>
            <?php endif ?>
            <?php if (array_key_exists('session_unavailable', $errors)) : ?>
                <div class="flash_container">
                    <small class="warning"><?php echo $errors['session_unavailable'] ?></small>
                </div>
            <?php endif ?>
            <?php if (array_key_exists('database_connection', $errors)) : ?>
                <div class="flash_container">
                    <small class="warning"><?php echo $errors['database_connection'] ?></small>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="title_container">
            <h1>Tienda Birt</h1>
        </div>
        <div class="container">
            <label for="username"><b>Usuario</b></label>
            <input id="username" name="username" type="text" placeholder="Introduce tu nombre de usuario" required>
            <label for="password"><b>Contraseña</b></label>
            <input id="password" name="password" type="password" placeholder="Introduce tu contraseña" required>
            <button type="submit" value="login" name="login">ENTRAR</button>
            <?php if (isset($_POST['login']) && $errors['credentials_invalid']) : ?>
                <div class="error_container">
                    <small class="warning"><?php echo $errors['credentials_invalid'] ?></small>
                </div>
            <?php endif ?>
        </div>
    </form>
</main>
</body>
</html>