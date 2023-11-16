<?php
    require 'config/config.php';
    require 'config/database.php';

    $db = new Database();
    $con = $db -> conectar();

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';

    if($id == '' || $token == '')
    {
        echo 'Error al procesar la petición';
        exit;
    }
    else
    {
        $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

        if($token == $token_tmp)
        {
            $sql = $con -> prepare("SELECT count(id_producto) FROM productos WHERE id_producto=? AND activo = 1");
            $sql -> execute([$id]);

            if($sql -> fetchColumn() > 0)
            {
                $sql = $con -> prepare("SELECT Nombre, Descripcion, Precio, Descuento, ID_CategoriaProducto FROM productos WHERE id_producto=? AND activo = 1 LIMIT 1");
                $sql -> execute([$id]);
                $row = $sql -> fetch(PDO::FETCH_ASSOC);

                $nombre = $row['Nombre'];
                $descripcion = $row['Descripcion'];
                $precio = $row['Precio'];

                $descuento = $row['Descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $id_categoria = $row['ID_CategoriaProducto'];

                $imagen = 'img/productos/' . $id_categoria . '/' . $id . '/principal.jpg';

                if(!file_exists($imagen))
                {
                    $imagen = 'img/no-photo.jpg';
                }
            }
        }
        else
        {
            echo 'Error al procesar la petición';
            exit;
        }
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
    <link rel="stylesheet" href="css/detalles.css">
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

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1 mb-4" style="margin-top: 60px; display: flex; justify-content: center; align-items: center;">
                    <div class="image-container">
                        <img src="<?php echo $imagen; ?>" style="max-width: 90%; max-height: 90%; width: auto; height: auto;">
                    </div>
                </div>
                <div class="col-md-5 order-md-2 mt-5">
                    <p class="card-title"><?php echo $nombre ?></p>
                    <?php if($descuento > 0) { ?>
                            <p class="card-money-descuento"><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                            <h2 class="descuento-t">
                                <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                                <small class="text-success"><?php echo $descuento; ?>% descuento</small>
                            </h2>
                    <?php } else { ?>
                        <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
                    <?php } ?>
                    <p class="lead">
                        <?php echo $descripcion; ?>
                    </p>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="custom-buttom-carrito" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito <i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function addProducto(id, token)
        {
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                method: 'POST', 
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok) 
                {
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                }
            })
        }
    </script>
</body>
</html>