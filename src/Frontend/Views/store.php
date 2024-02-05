<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////

use Backend\Auth\SessionManager;
use Backend\model\{Cart, CartItem, ProductType};

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

$user = SessionManager::read('USER', true);

////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($user->isAdmin()) {
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

if (isset($_POST['add_to_cart']) && !empty($_POST['add_to_cart']) && $_POST['add_to_cart'] == 'add_to_cart') {
    $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    if ($cart == null) {
        $rowCount = Cart::createNewCart($user->getId());
        $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    }
    if ($cart == null) {
        echo "<script type='text/javascript'>alert('Error al añadir el producto al carrito');</script>";
        die();
    }
    $cartItemId = $_POST['cart_item_id'];
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity'][$productId]);
    if (empty($cartItemId)) {
        $cartItem = new CartItem();
        $cartItem->setCartId($cart->getId());
        $cartItem->setProductId($productId);
        $cartItem->setQuantity($quantity);
        $rowCount = CartItem::insert($cartItem);
        if (is_null($rowCount) || $rowCount < 1) {
            echo "<script type='text/javascript'>alert('Error al añadir el producto al carrito');</script>";
        }
    } else {
        $rowCount = CartItem::updateCartItemQuantity($cartItemId, $quantity);
        if (is_null($rowCount) || $rowCount < 1) {
            echo "<script type='text/javascript'>alert('Error al modificar la cantidad del producto');</script>";
        }
    }
}

if (isset($_POST['remove_from_cart_table']) && !empty($_POST['remove_from_cart_table']) && $_POST['remove_from_cart_table'] == 'remove_from_cart_table') {
    $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    $productId = $_POST['product_id'];
    $rowCount = CartItem::deleteCartItemFromCart($cart->getId(), $productId);
    if (is_null($rowCount) || $rowCount < 1) {
        echo "<script type='text/javascript'>alert('Error al eliminar el producto del carrito');</script>";
    }
}

if (isset($_POST['remove_from_cart_cart']) && !empty($_POST['remove_from_cart_cart']) && $_POST['remove_from_cart_cart'] == 'remove_from_cart_cart') {
    $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    $productId = $_POST['product_id'];
    $rowCount = CartItem::deleteCartItemFromCart($cart->getId(), $productId);
    if (is_null($rowCount) || $rowCount < 1) {
        echo "<script type='text/javascript'>alert('Error al eliminar el producto del carrito');</script>";
    }
}

if (isset($_POST['order']) && !empty($_POST['order']) && $_POST['order'] == 'order') {
    $cartId = $_POST['cart_id'];
    $rowCount = Cart::buy($cartId);
    if (is_null($rowCount) || $rowCount < 1) {
        echo "<script type='text/javascript'>alert('Error al realizar el pedido');</script>";
    }
}

if (isset($_POST['change_currency'])) {
    $queryString = filter_input(INPUT_POST, 'query_string');
    $currency = filter_input(INPUT_POST, 'change_currency');
    setcookie('currency', $currency, time() + (365 * 24 * 60 * 60), "/");
    header("Location: store.php?" . $queryString);
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
    <link rel="stylesheet" href="../CSS/store.css">
    <link rel="shortcut icon" href="https://ikastaroak.birt.eus/theme/image.php/birt/theme/1641981368/favicon">
    <title>Tienda Birt - Tienda</title>
</head>
<body>
<main>
    <?php include('Partials/_header.php') ?>
    <nav>
        <ul class="breadcrumb">
            <li><a href="principal.php">Inicio</a></li>
            <li>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($_GET)) ?>">
                    <?php echo ProductType::getById(htmlspecialchars($_GET['productType']))->getDescription() ?>
                </a>
            </li>
        </ul>
    </nav>
    <div class="content-wrapper">
        <?php include('Partials/_product_list.php') ?>
        <?php include('Partials/_cart.php') ?>
    </div>
    <?php include('Partials/_footer.php') ?>
</main>
</body>
</html>