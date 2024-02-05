<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////

use Backend\Auth\SessionManager;
use Backend\Model\Product;

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
const TYPE_REQUIRED = 'Introduce un tipo de producto';
const DESCRIPTION_REQUIRED = 'Introduce una descripción';
const UNITY_REQUIRED = 'Introduce un tipo de unidad';
const PRICE_REQUIRED = 'Introduce un precio';
const DISCOUNT_INVALID = 'Introduce un porcentaje válido';
const INSERT_FAILED = 'Error en la base de datos. No se ha podido insertar el nuevo producto';
const DELETE_FAILED = 'Error en la base de datos. No se ha podido borrar el producto';
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

if (isset($_POST['remove_product']) && !empty($_POST['remove_product']) && $_POST['remove_product'] == 'remove_product') {
    $productId = intval($_POST['product_id']);
    $rowCount = Product::delete($productId);
    if (is_null($rowCount) || $rowCount < 1)
        $errors['delete_error'] = DELETE_FAILED;
}

if (isset($_POST['add_product']) && !empty($_POST['add_product']) && $_POST['add_product'] == 'add_product') {
    $name = $_POST['name'];
    if (!$name) $errors['name'] = NAME_REQUIRED;
    $type = $_POST['type'];
    if (!$name) $errors['type'] = TYPE_REQUIRED;
    $unity = $_POST['unity'];
    if (!$unity) $errors['unity'] = UNITY_REQUIRED;
    $description = $_POST['description'];
    if (!$name) $errors['description'] = DESCRIPTION_REQUIRED;
    $price = $_POST['price'];
    if (!$price || !is_numeric($price) || intval($price) <= 0) $errors['price'] = PRICE_REQUIRED;
    $discount = $_POST['discount'] ?? 0;
    if (!is_numeric($discount) || intval($discount) < 0 || intval($discount) > 100) $errors['discount'] = DISCOUNT_INVALID;
    if (count($errors) == 0) {
        $product = new Product();
        $product->setName($name);
        $product->setType($type);
        $product->setUnity($unity);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setDiscount($discount);
        $rowCount = Product::insert($product);
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
    <title>Tienda Birt - Productos</title>
</head>
<body>
<main>
    <?php include('Partials/_header.php') ?>
    <nav>
        <ul class="breadcrumb">
            <li><a href="principal.php">Inicio</a></li>
            <li><a href="product.php">Producto</a></li>
        </ul>
    </nav>
    <?php include('Partials/_product_list.php') ?>
    <?php include('Partials/_product_form.php') ?>
    <?php include('Partials/_footer.php') ?>
</main>
</body>
</html>