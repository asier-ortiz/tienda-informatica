<header>
    <h1>Tienda Birt</h1>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="currencyForm">
        <label for="currencies"></label>
        <select name="change_currency" id="currencies" onchange="this.form.submit()">
            <option
                    value="EUR"
                <?php if (isset($_COOKIE['currency']) && $_COOKIE['currency'] === 'EUR') : ?>
                    selected="selected"
                <?php endif; ?>
            >
                🇪🇺 EUR €
            </option>
            <option
                    value="USD"
                <?php if (isset($_COOKIE['currency']) && $_COOKIE['currency'] === 'USD') : ?>
                    selected="selected"
                <?php endif; ?>
            >
                🇺🇸 USD ＄
            </option>
            <option
                    value="GBP"
                <?php if (isset($_COOKIE['currency']) && $_COOKIE['currency'] === 'GBP') : ?>
                    selected="selected"
                <?php endif; ?>
            >
                🇬🇧 GBP £
            </option>
            <option
                    value="SEK"
                <?php if (isset($_COOKIE['currency']) && $_COOKIE['currency'] === 'SEK') : ?>
                    selected="selected"
                <?php endif; ?>
            >
                🇸🇪 SEK kr
            </option>
        </select>
        <input type="hidden" name="query_string" value="<?php echo htmlspecialchars($_SERVER['QUERY_STRING']); ?>">
    </form>
    <div>
        <?php if ($user->isAdmin()) : ?>
            <?php echo "<strong>Administrador</strong>" . $user->getUsername() ?>
        <?php else: ?>
            <?php echo "<strong>Cliente</strong>" . $user->getUsername() ?>
        <?php endif ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="return confirm('¿Deseas cerrar la sesión?')">
            <button type="submit" name="close_session" value="close_session" title="Salir">⇥</button>
        </form>
    </div>
</header>