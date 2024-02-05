<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////

use Backend\Auth\SessionManager;
use Backend\Model\{Cart, CartItem, ProductType};

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

if (isset($_POST['close_session']) && !empty($_POST['close_session']) && $_POST['close_session'] == 'close_session') {
    SessionManager::destroy();
    header('Location: ../login.php');
    exit;
}

if (isset($_POST['remove_from_cart_cart']) && !empty($_POST['remove_from_cart_cart']) && $_POST['remove_from_cart_cart'] == 'remove_from_cart_cart') {
    $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    $productId = intval($_POST['product_id']);
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
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="shortcut icon" href="https://ikastaroak.birt.eus/theme/image.php/birt/theme/1641981368/favicon">
    <title>Tienda Birt</title>
</head>
<body>
<main>
    <?php include('Partials/_header.php') ?>
    <?php if ($user->isAdmin()) : ?>
        <nav>
            <ul>
                <li><a href="#">Familia</a></li>
                <li><a href="#">Tipo</a></li>
                <li><a href="product.php">Producto</a></li>
                <li><a href="user.php">Usuario</a></li>
            </ul>
        </nav>
    <?php else: ?>
        <div class="empty-nav"></div>
    <?php endif; ?>
    <?php if ($user->isAdmin()) : ?>
        <?php
        require_once("../../../vendor/econea/nusoap/src/nusoap.php");
        $serviceScriptPath = str_replace($_SERVER['DOCUMENT_ROOT'], "", realpath("./../../Backend/Services/servicio.php"));
        $client = new nusoap_client("http://localhost" . $serviceScriptPath, false);
        $response = $client->call("getLowStockProducts", array());
        $lowStockProducts = json_decode($response);
        ?>
        <?php if ($lowStockProducts > 0) : ?>
            <?php include('Partials/_product_low_stock_list.php'); ?>
        <?php else: ?>
            <div class="empty-content-wrapper"></div>
        <?php endif; ?>
    <?php else: ?>
        <div class="content-wrapper">
            <section>
                <ul>
                    <?php foreach (ProductType::getAll() as $type) : ?>
                        <li>
                            <a href="store.php?productType=<?php echo $type->getId(); ?> ">
                                <?= htmlspecialchars($type->getDescription()) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php include('Partials/_cart.php') ?>
        </div>
    <?php endif; ?>
    <?php include('Partials/_footer.php') ?>
</main>
</body>
</html>