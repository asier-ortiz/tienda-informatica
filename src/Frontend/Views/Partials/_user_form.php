<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="userForm">
    <h2>Usuario Nuevo</h2>
    <?php if (isset($_POST['add_user'])) : ?>
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
                <?php if (isset($_POST['add_user'])) : ?>
                    <small class="warning"><?php echo $errors['name'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="surnames">Apellidos</label>
                <div>
                    <input
                            type="text"
                            name="surnames"
                            id="surnames"
                            value="<?php echo isset($_POST['surnames']) ? htmlspecialchars($_POST['surnames'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_user'])) : ?>
                    <small class="warning"><?php echo $errors['surnames'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="username">Nombre de usuario</label>
                <div>
                    <input
                            type="text"
                            name="username"
                            id="username"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_user'])) : ?>
                    <small class="warning"><?php echo $errors['username'] ?? '' ?></small>
                <?php endif ?>
            </div>
        </div>
        <div class="form-col">
            <div class="form-row">
                <label for="password">Contraseña</label>
                <div>
                    <input
                            type="password"
                            name="password"
                            id="password"
                            value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>"
                    >
                </div>
                <?php if (isset($_POST['add_user'])) : ?>
                    <small class="warning"><?php echo $errors['password'] ?? '' ?></small>
                <?php endif ?>
            </div>
            <div class="form-row">
                <label for="admin">Administrador</label>
                <div>
                    <input
                            type="checkbox"
                            name="admin"
                            id="admin"
                            value="admin"
                        <?php if (isset($_POST['admin']) && $_POST['admin']) echo "checked='checked'" ?>
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="center">
        <button type="submit" id="btn--add_user" name="add_user" value="add_user">Añadir usuario</button>
    </div>
</form>