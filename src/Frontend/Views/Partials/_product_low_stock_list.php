<div class="table-content-wrapper">
    <h2>⚠️ Productos con Stock bajo</h2>
    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Fecha de llegada</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($lowStockProducts as $product) : ?>
                <tr>
                    <td> <?= htmlspecialchars($product->ProductoNombre) ?> </td>
                    <td> <?= htmlspecialchars($product->stock) ?> </td>
                    <td> <?= date('d-m-Y', strtotime(date('d-m-Y') . sprintf("+ %s days", $product->diasPedido))) ?> </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>