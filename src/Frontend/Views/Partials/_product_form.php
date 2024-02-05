<?php use Backend\Model\ProductType;?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="productForm">
    <h2>Producto Nuevo</h2>
    <?php if (isset($_POST['add_product'])) : ?>
        <p class="warning"><?php echo $errors['insert_error'] ?? '' ?></p>
        <p class="warning"><?php echo $errors['delete_error'] ?? '' ?></p>
    <?php endif ?>
    <div class="from-fields-wrapper">
        <div class="form-col">
            <div class="form-row">
                <label for="name">Nombre</label>
                <div>
                    <input
                            type="text"
                            name="name"
                            id="name"
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_product'])) : ?>
                    <small class="warning"><?php echo $errors['name'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="description">Descripción</label>
                <div>
                    <input
                            type="text"
                            name="description"
                            id="description"
                            value="<?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_product'])) : ?>
                    <small class="warning"><?php echo $errors['description'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="type">Tipo</label>
                <div>
                    <select name="type" id="type">
                        <?php foreach (ProductType::getAll() as $type) : ?>
                            <option
                                    value="<?php echo $type->getId() ?>"
                                <?php if (isset($_POST['type']) && $_POST['type'] == $type->getId()): ?>
                                    selected="selected"
                                <?php endif; ?>
                            >
                                <?= htmlspecialchars($type->getDescription()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-col">
            <div class="form-row">
                <label for="price">Precio €</label>
                <div>
                    <input
                            type="number"
                            name="price"
                            id="price"
                            min="0"
                            value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_product'])) : ?>
                    <small class="warning"><?php echo $errors['price'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="discount">Descuento</label>
                <div>
                    <input
                            type="number"
                            name="discount"
                            id="discount"
                            min="0"
                            max="100"
                            value="<?php echo isset($_POST['discount']) ? htmlspecialchars($_POST['discount'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_product'])) : ?>
                    <small class="warning"><?php echo $errors['discount'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="unity">Unidad</label>
                <div>
                    <input
                            type="text"
                            name="unity"
                            id="unity"
                            value="<?php echo isset($_POST['unity']) ? htmlspecialchars($_POST['unity'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_product'])) : ?>
                    <small class="warning"><?php echo $errors['unity'] ?? '' ?></small>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="center">
        <button type="submit" id="btn--add_product" name="add_product" value="add_product">Añadir producto</button>
    </div>
</form>