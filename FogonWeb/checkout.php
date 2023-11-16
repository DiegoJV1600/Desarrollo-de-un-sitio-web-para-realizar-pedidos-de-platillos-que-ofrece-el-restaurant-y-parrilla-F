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
            <div class="table-responsive">
                <table class="table-custom">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
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
                        <td class="precio-columna"><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
                        <td>
                            <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                        </td>
                        <td class="subtotal-columna">
                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                            </div>
                        </td>
                        <td>
                            <a id="eliminar" class="btn custom-boton-quitar" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal"><i class="fa-solid fa-delete-left custom-delete-icon"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">
                                <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </table>
            </div>
            <?php if($lista_carrito != null) { ?>
            <div class="row">
                <div class="col-md-5 offset-md-7 d-grid gap-2">
                        <a href="pago.php" class="btn custom-boton-pagar"><i class="fa-solid fa-money-check-dollar"></i> Realizar pago</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </main>
    
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar el producto del carrito?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()"><i class="fa-solid fa-trash"></i> Eliminar</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')

            buttonElimina.value = id
        })

        function actualizaCantidad(cantidad, id)
        {
            let url = 'clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'agregar')
            formData.append('id', id)
            formData.append('cantidad', cantidad)

            fetch(url, {
                method: 'POST', 
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok) 
                {
                    let divsubtotal = document.getElementById('subtotal_' + id)
                    divsubtotal.innerHTML = data.sub

                    let total = 0.00
                    let list = document.getElementsByName('subtotal[]')

                    for(let i = 0; i < list.length; i++)
                    {
                        total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                    }

                    total = new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2
                    }).format(total)

                    document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                }
            })
        }

        function eliminar()
        {
            let botonElimina = document.getElementById('btn-elimina')
            let id = botonElimina.value
            
            let url = 'clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'eliminar')
            formData.append('id', id)

            fetch(url, {
                method: 'POST', 
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
            .then(data => {
                if(data.ok) 
                {
                    location.reload()
                }
            })
        }
    </script>
</body>
</html>