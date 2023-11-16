<?php
    require '../config/database.php';

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

    $sql = $con -> prepare("SELECT ID_Producto, Nombre, Descripcion, Precio, Descuento, ID_CategoriaProducto FROM productos WHERE ID_CategoriaProducto = ? AND Activo = 1");
    $sql -> execute([$id_categoria]);

    $resultado = $sql -> fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fogón Norteño</title>
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <div id="contain">
        <div class="container">
        <h1>Administración de insumos del Fogón Norteño Web</h1>
        <h2>Productos</h2>
            <div class="search-container">
                <a href="nuevaproducto.php" class="button-agregar">Nuevo Producto</a>
            </div>
            <table id="tabla-productos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Descuento</th>
                        <th>Imagen</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resultado as $row) { ?>
                        <?php 
                            $id = $row['ID_Producto'];
                            $id_categoria = $row['ID_CategoriaProducto'];

                            $imagen = "../img/productos/" . $id_categoria . "/" . $id . "/principal.jpg";

                            if(!file_exists($imagen))
                            {
                                $imagen = "../img/no-photo.jpg";
                            }
                        ?>
                        <tr>
                            <td><?php echo $row["ID_Producto"]; ?></td>
                            <td><?php echo $row["Nombre"]; ?></td>
                            <td><?php echo $row["Descripcion"]; ?></td>
                            <td><?php echo $row["Precio"]; ?></td>
                            <td><?php echo $row["Descuento"]; ?></td>
                            <td><img src="<?php echo $imagen; ?>" style="width: 250px; height: auto;"></td>
                            <td>
                                <a href="modificarproducto.php?id=<?php echo $row['ID_Producto']; ?>" class="button-modificar"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                <a href="eliminarproducto.php?id=<?php echo $row['ID_Producto']; ?>" class="button-eliminar"><i class="fa-solid fa-trash fa-lg"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>