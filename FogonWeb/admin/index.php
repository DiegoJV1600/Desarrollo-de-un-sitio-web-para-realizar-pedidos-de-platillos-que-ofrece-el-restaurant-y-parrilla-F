<?php 
    require '../config/database.php';

    $db = new Database();

    $con = $db -> conectar();

    $sql = $con -> prepare("SELECT ID_Categoria, Categoria FROM categorias");
    $sql -> execute();

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
        <h2>Categorías</h2>
            <div class="search-container">
                <a href="nuevacategoria.php" class="button-agregar">Nueva Categoría</a>
            </div>
            <table id="tabla-categorias">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <th>Productos</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resultado as $row) { ?>
                        <?php 
                            $id = $row['ID_Categoria'];
                            $imagen = "../img/productos/" . $id . "/principal.jpg";

                            if(!file_exists($imagen))
                            {
                                $imagen = "../img/no-photo.jpg";
                            }
                        ?>
                        <tr>
                            <td><?php echo $row["ID_Categoria"]; ?></td>
                            <td><?php echo $row["Categoria"]; ?></td>
                            <td><img src="<?php echo $imagen; ?>" style="width: 250px; height: auto;"></td>
                            <td>
                                <a href="productos.php?id=<?php echo $row['ID_Categoria'] ?>" class="button-verproductos"><i class="fa-solid fa-clipboard-list fa-lg"></i> Ver lista de productos</a>
                            </td>
                            <td>
                                <a href="modificarcategoria.php?id=<?php echo $row['ID_Categoria']; ?>" class="button-modificar"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                <a href="eliminarcategoria.php?id=<?php echo $row['ID_Categoria']; ?>" class="button-eliminar"><i class="fa-solid fa-trash fa-lg"></i></a>
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