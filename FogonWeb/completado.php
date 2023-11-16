<?php 
    require 'config/config.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fogón Norteño</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg custom-navbar navbar-dark fixed-top">
            <div class="container">
                <div class="logo">
                    <img src="img/FNLogo.png" alt="FNLogo">
                </div>
                <a href="index.php" class="navbar-brand">
                    <strong>Fogón Norteño Web</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">Menú del restaurante</a>
                        </li>
                        <li class="nav-item">
                            <a href="contacto.php" class="nav-link">Contactanos</a>
                        </li>
                        <li class="nav-item">
                            <a href="sucursales.php" class="nav-link">Sucursales</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="link-light custom-cart-icon">
                        <i class="fa-solid fa-cart-shopping"></i><span id="num_cart" class="badge custom-badge"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <h2>
        Gracias por su pago
    </h2>
</body>
</html>