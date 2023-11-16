<?php
    require 'config/config.php';
    require 'config/database.php';

    $db = new Database();
    $con = $db -> conectar();

    if(isset($_GET['id']))
    {
        $id_categoria = $_GET['id'];
    }
    else 
    {
        header('Location: index.php');
        exit;
    }

    $sql = $con -> prepare("SELECT ID_Producto, Nombre, Precio FROM productos WHERE ID_CategoriaProducto = ? AND Activo = 1");
    $sql -> execute([$id_categoria]);

    $resultado = $sql -> fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/productos.css">
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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach($resultado as $row) { ?>
                    <div class="col">
                        <div class="card custom-card shadow-sm mt-3">
                            <div class="card-body">
                                <p class="card-title"><?php echo $row['Nombre']; ?></p>
                                <p class="card-text">$ <?php echo number_format($row['Precio'], 2, '.', ','); ?></p>
                                <div class="d-flex justify-content-between aling-items-center">
                                    <div class="btn-group">
                                        <a href="detalles.php?id=<?php echo $row['ID_Producto']; ?>&token=<?php echo hash_hmac('sha1', $row['ID_Producto'], KEY_TOKEN); ?>" class="custom-buttom-detalles">Detalles</a>
                                    </div>
                                    <button class="custom-buttom-agregar" type="button" onclick="addProducto(<?php echo $row['ID_Producto']; ?>, '<?php echo hash_hmac('sha1', $row['ID_Producto'], KEY_TOKEN); ?>')"><i class="fa-solid fa-plus custom-plus-icon"></i><i class="fa-solid fa-cart-shopping custom-cart-icon"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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