<?php
use Backend\Model\{Cart, CartItem, Product, ProductType};

$isShoppingTable = false;
$AllProductsAndQuantities = null;
$currencySymbol = Product::getCurrencySymbol();

if (isset($_GET['productType']) && !empty($_GET['productType'])) {
    $productTypeId = $_GET['productType'];
    $cart = Cart::getLastUnshoppedCartByUser($user->getId());
    $AllProductsAndQuantities = array();
    if ($cart)
        $AllProductsAndQuantities = Product::getAllProductsByProductTypeAndQuantitiesOfEachProductByCartId($cart->getId(), $productTypeId);
    else {
        $allProductsByType = Product::getAllByProductType($productTypeId);
        foreach ($allProductsByType as $product)
            $AllProductsAndQuantities[] = [0, $product];
    }
    $isShoppingTable = true;
}
?>

<section class="table-section">
    <h2>Productos</h2>
    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Unidad</th>
                <th>Descripción</th>
                <th>PVP</th>
                <th>Descuento</th>
                <?php if (!$isShoppingTable) : ?>
                    <th>Eliminar</th>
                <?php else: ?>
                    <th>Cantidad</th>
                    <th></th>
                    <th></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($isShoppingTable ? $AllProductsAndQuantities : Product::getAll() as $product) : ?>
                <form
                    <?php if ($isShoppingTable) : ?>
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($_GET)) ?>"
                        method="POST"
                    <?php else: ?>
                        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                        method="POST"
                        onsubmit="return confirm('¿Deseas elimiar este producto?')"
                    <?php endif; ?>
                >
                    <tr>
                        <td> <?= htmlspecialchars($isShoppingTable ? $product[1]->getName() : $product->getName()) ?> </td>
                        <td> <?= htmlspecialchars(ProductType::getById($isShoppingTable ? $product[1]->getType() : $product->getType())->getDescription()) ?> </td>
                        <td> <?= htmlspecialchars($isShoppingTable ? $product[1]->getUnity() : $product->getUnity()) ?> </td>
                        <td> <?= htmlspecialchars($isShoppingTable ? $product[1]->getDescription() : $product->getDescription()) ?> </td>
                        <td> <?= htmlspecialchars(number_format($isShoppingTable ? $product[1]->getPrice() : $product->getPrice(), 2, ",", ".") . " " . $currencySymbol) ?> </td>
                        <td> <?= htmlspecialchars(number_format($isShoppingTable ? $product[1]->getDiscount() : $product->getDiscount(), 2, ",", ".") . " %") ?>
                        </td>
                        <?php if (!$isShoppingTable) : ?>
                            <input type="hidden" name="product_id" value=<?php echo $product->getId() ?>>
                            <td>
                                <button
                                        type="submit"
                                        name="remove_product"
                                        value="remove_product"
                                >
                                    ❌
                                </button>
                            </td>
                        <?php else: ?>
                            <td>
                                <label>
                                    <input type="number" name="quantity[<?php echo $product[1]->getId() ?>]" size=2
                                           min="1"
                                           value="<?php echo intval($product[0]) ?>">
                                </label>
                            </td>
                            <input
                                    type="hidden"
                                    name="cart_item_id"
                                    value=
                                    <?php if ($cart) : ?>
                                        <?php echo CartItem::getByCartAndProductId($cart->getId(), $product[1])?->getId() ?>
                                    <?php else: ?>
                                        <?php echo null ?>
                                    <?php endif; ?>
                            >
                            <input type="hidden" name="product_id" value=<?php echo $product[1]->getId() ?>>
                            <td>
                                <button type="submit" name="add_to_cart" value="add_to_cart">Añadir</button>
                            </td>
                            <td>
                                <button type="submit" name="remove_from_cart_table" value="remove_from_cart_table">
                                    Eliminar
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                </form>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>