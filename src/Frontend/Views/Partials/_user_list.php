<?php use Backend\Model\User; ?>
<section class="table-section">
    <h2>Usuarios</h2>
    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Nombre de usuario</th>
                <th>Contraseña</th>
                <th>Administrador</th>
                <th>Eliminar</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (User::getAll() as $u) : ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST"
                      onsubmit="return confirm('¿Deseas elimiar a este usuario?')">
                    <tr>
                        <td> <?= htmlspecialchars($u->getName()) ?> </td>
                        <td> <?= htmlspecialchars($u->getSurnames()) ?> </td>
                        <td> <?= htmlspecialchars($u->getUsername()) ?> </td>
                        <td> <?= htmlspecialchars($u->getPassword()) ?> </td>
                        <td> <?= htmlspecialchars($u->isAdmin() ? "Si" : "No") ?> </td>
                        <?php if ($user->getId() !== $u->getId()) : ?>
                            <input type="hidden" name="user_id" value=<?php echo $u->getId() ?>>
                            <td>
                                <button type="submit" name="remove_user" value="remove_user">❌</button>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                </form>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>