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
    <link rel="shortcut icon" href="https://ikastaroak.birt.eus/theme/image.php/birt/theme/1641981368/favicon">
    <title>Tienda Birt - Error <?php echo $code ?></title>
</head>
<style>
    *,
    *::after,
    *::before {
        margin: 0;
        padding: 0;
        box-sizing: inherit;
    }

    body {
        font-family: 'Roboto Light', sans-serif;
        box-sizing: border-box;
        font-weight: 400;
    }

    main {
        position: relative;
        height: 100vh;
        background: #6699CC;
        text-align: center;
    }

    div {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    h1 {
        font-size: 300px;
    }

    p {
        font-size: 40px;
        color: rgba(0, 0, 0, .5);
    }
</style>
<body>
<main>
    <div>
        <h1><?php echo $code ?></h1>
        <p><?php echo $msg ?></p>
    </div>
</main>
</body>
</html>