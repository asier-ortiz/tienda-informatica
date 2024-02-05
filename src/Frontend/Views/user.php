<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////

use Backend\Auth\SessionManager;
use Backend\Model\User;

require_once('../../../vendor/autoload.php');
Dotenv\Dotenv::createImmutable(realpath("./../../../"))->load();

////////////////////////////////////////////////////////////////////////////////////////////////////////

try {
    SessionManager::start();
} catch (Exception $e) {
    $msg = urlencode($e->getMessage());
    header("Location:../login.php?session_unavailable=" . $msg);
    die;
}

try {
    SessionManager::checkSessionIsValid();
} catch (Exception $e) {
    session_unset();
    $msg = $e->getMessage();
    SessionManager::write('SESSION_INVALID', $msg);
    header('Location: ../login.php');
    exit;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////

const NAME_REQUIRED = 'Introduce un nombre';
const SURNAMES_REQUIRED = 'Introduce los apellidos';
const USERNAME_REQUIRED = 'Introduce un nombre de usuario';
const PASSWORD_REQUIRED = 'Introduce una contraseña';
const INSERT_FAILED = 'Error en la base de datos. No se ha podido insertar al nuevo usuario';
const DELETE_FAILED = 'Error en la base de datos. No se ha podido borrar al usuario';
$errors = [];
$user = SessionManager::read('USER', true);

////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!$user->isAdmin()) {
    header("HTTP/1.1 401 Unauthorized");
    $code = 401;
    $msg = "Acceso restringido";
    include("Errors/401.php");
    exit;
}

if (isset($_POST['close_session']) && !empty($_POST['close_session']) && $_POST['close_session'] == 'close_session') {
    SessionManager::destroy();
    header('Location: ../login.php');
    exit;
}

if (isset($_POST['remove_user']) && !empty($_POST['remove_user']) && $_POST['remove_user'] == 'remove_user') {
    $userId = intval($_POST['user_id']);
    $rowCount = User::delete($userId);
    if ($rowCount < 1)
        $errors['delete_error'] = DELETE_FAILED;
}

if (isset($_POST['add_user']) && !empty($_POST['add_user']) && $_POST['add_user'] == 'add_user') {
    $name = $_POST['name'];
    if (!$name) $errors['name'] = NAME_REQUIRED;
    $surnames = $_POST['surnames'];
    if (!$surnames) $errors['surnames'] = SURNAMES_REQUIRED;
    $username = $_POST['username'];
    if (!$username) $errors['username'] = USERNAME_REQUIRED;
    $password = $_POST['password'];
    if (!$password) $errors['password'] = PASSWORD_REQUIRED;
    isset($_POST['admin']) ? $isAdmin = 1 : $isAdmin = 0;
    if (count($errors) == 0) {
        $newUser = new User();
        $newUser->setName($name);
        $newUser->setSurnames($surnames);
        $newUser->setUsername($username);
        $newUser->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $newUser->setIsAdmin($isAdmin);
        $rowCount = User::insert($newUser);
        if (is_null($rowCount) || $rowCount < 1) {
            $errors['insert_error'] = INSERT_FAILED;
        } else {
            $_POST = [];
            $errors = [];
        }
    }
}

if (isset($_POST['change_currency'])) {
    $currency = filter_input(INPUT_POST, 'change_currency');
    setcookie('currency', $currency, time() + (365 * 24 * 60 * 60), "/");
    header("Location: " . $_SERVER['PHP_SELF']);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
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
    <link rel="stylesheet" href="../CSS/product&user.css">
    <link rel="shortcut icon" href="https://ikastaroak.birt.eus/theme/image.php/birt/theme/1641981368/favicon">
    <title>Tienda Birt - Usuarios</title>
</head>
<body>
<main>
    <?php include('Partials/_header.php') ?>
    <nav>
        <ul class="breadcrumb">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="user.php">Usuarios</a></li>
        </ul>
    </nav>
    <?php include('Partials/_user_list.php') ?>
    <?php include('Partials/_user_form.php') ?>
    <?php include('Partials/_footer.php') ?>
</main>
</body>
</html>