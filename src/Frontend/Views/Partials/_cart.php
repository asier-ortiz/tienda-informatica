<?php
Use Backend\Model\{Cart, Product};

$cart = Cart::getLastUnshoppedCartByUser($user->getId());
$cartProductsAndQuantities = [];
if ($cart != null) {
    $cartId = $cart->getId();
    $cartProductsAndQuantities = Product::getProductsAndQuantitiesByCart($cartId);
}
?>

<?php if (!is_null($cartProductsAndQuantities) && empty($cartProductsAndQuantities)) : ?>
    <aside>
        <p><b>No hay artículos en tu pedido</b></p>
    </aside>

<?php elseif (is_null($cartProductsAndQuantities)) : ?>
    <aside>
        <p>
            <b>Error al recuperar tu carrito </b>
            <button onclick="window.location.reload()">&#8634;</button>
        </p>
    </aside>

<?php else : ?>
    <?php
    $total = 0;
    $currencySymbol = Product::getCurrencySymbol();
    ?>
    <aside>
        <h2>Tu pedido</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>PVP</th>
                    <th>Descuento</th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cartProductsAndQuantities as $productQuantities) : ?>
                    <?php
                    $quantity = $productQuantities[0];
                    $product = $productQuantities[1];
                    ?>
                    <form
                        <?php if (isset($_GET['productType']) && !empty($_GET['productType'])) : ?>
                            action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($_GET)) ?>"
                        <?php else : ?>
                            action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                        <?php endif; ?>
                            method="POST"
                            onsubmit="return confirm('<?php echo sprintf('¿Deseas eliminar %s de tu carrito?', $product->getName()) ?>')"
                    >
                        <tr>
                            <td> <?= htmlspecialchars($product->getName()) ?> </td>
                            <td> <?= htmlspecialchars(number_format($product->getPrice(), 2, ",", ".") . " " . $currencySymbol) ?> </td>
                            <td> <?= htmlspecialchars(number_format($product->getDiscount(), 2, ",", ".") . " %") ?>  </td>
                            <td> <?= htmlspecialchars($quantity) ?> </td>
                            <td> <?= htmlspecialchars(number_format($product->getPriceWithDiscount() * $quantity, 2, ",", ".") . " " . $currencySymbol) ?>  </td>
                            <?php $total += $product->getPriceWithDiscount() * $quantity ?>
                            <input type="hidden" name="product_id" value=<?php echo $product->getId() ?>>
                            <td>
                                <button type="submit" name="remove_from_cart_cart" value="remove_from_cart_cart">❌
                                </button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= htmlspecialchars(number_format($total, 2, ",", ".") . " " . $currencySymbol) ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <form
            <?php if (isset($_GET['productType']) && !empty($_GET['productType'])) : ?>
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($_GET)) ?>"
            <?php else : ?>
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
            <?php endif; ?>
                method="POST"
                onsubmit="return confirm('<?php echo sprintf('¿Realizar pedido por valor de %s %s?', $total, $currencySymbol) ?>')"
                id="orderForm"
        >
            <input type="hidden" name="cart_id" value=<?php echo $cart->getId() ?>>
            <button type="submit" name="order" value="order" title="Comprar">Comprar 🛒</button>
        </form>
    </aside>
<?php endif; ?>