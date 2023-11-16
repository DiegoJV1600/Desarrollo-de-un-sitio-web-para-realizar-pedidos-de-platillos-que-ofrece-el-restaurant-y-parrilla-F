<?php
    require 'config/config.php';
    require 'config/database.php';

    $db = new Database();
    $con = $db -> conectar();

    $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

    $lista_carrito = array();

    if($productos != null)
    {
        foreach($productos as $clave => $cantidad) 
        {
            $sql = $con -> prepare("SELECT ID_Producto, Nombre, Precio, Descuento, $cantidad AS cantidad FROM productos WHERE ID_Producto = ? AND Activo = 1");
            $sql -> execute([$clave]);
            $lista_carrito[] = $sql -> fetch(PDO::FETCH_ASSOC);
        }
    }
    else 
    {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fogón Norteño</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/checkout.css">
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
                            <a href="index.php" class="nav-link">Menú del restaurante</a>
                        </li>
                        <li class="nav-item">
                            <a href="contacto.php" class="nav-link">Contactanos</a>
                        </li>
                        <li class="nav-item">
                            <a href="sucursales.php" class="nav-link">Sucursales</a>
                        </li>
                    </ul>
                    <a href="checkout.php" class="link-light custom-cart-icon-act">
                        <i class="fa-solid fa-cart-shopping"></i><span id="num_cart" class="badge custom-badge-act"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="pago-detalles">
                        <h4>Detalles de Pago</h4>
                        <div id="paypal-button-container"></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table-custom">
                        <tr>
                            <th>Producto</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                        <?php 
                            if($lista_carrito == null) 
                            {
                                echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                            }
                            else
                            {
                                $total = 0;

                                foreach($lista_carrito as $producto)
                                {
                                    $_id = $producto['ID_Producto'];
                                    $nombre = $producto['Nombre'];
                                    $precio = $producto['Precio'];
                                    $descuento = $producto['Descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                        ?>
                        <tr>
                            <td class="nombre-columna"><?php echo $nombre; ?></td>
                            <td class="subtotal-columna">
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                    <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2">
                                    <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
    <script>
        paypal.Buttons({
            style:
            {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create ({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
                        }
                    }]
                }); 
            },
            onApprove: function(data, actions)
            {
                let URL = 'clases/captura.php'
                actions.order.capture().then(function(detalles){
                    console.log(detalles)
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container')
    </script>
</body>
</html>