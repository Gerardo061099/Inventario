<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Bodega</title>
    <!-- <link rel="stylesheet" href="css/navbar.css"> -->
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script><!--CDN swal(sweatalert)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<body class="pag">
<?php
session_start();
ob_start();
    if (isset($_POST['btn1'])) {
        $_SESSION['sesion']=0;//No a inisiado sesion
        $mail = $_POST['user'];
        $pwd = $_POST['pass'];
        if ($mail == "" || $pwd == "") {//Revisamos si algun campo está vacio
            $_SESSION['sesion']=2;
        }
        else{
            include("abrir_conexion.php");
            $_SESSION['sesion']=3;
            $resultado = mysqli_query($conexion,"SELECT * FROM $tbu_db1 WHERE user = '$mail' AND pass = PASSWORD('$pwd')");
            while($consulta = mysqli_fetch_array($resultado)){
                //echo "Bienvenido ".$consulta['user']." has iniciado sesion";
                $_SESSION['sesion']=1;
            }
            include("cerrar_conexion.php");
        }
    }
    if ($_SESSION['sesion']<>1) {
        header("Location:index.php");
    }
?>
    <!-- Image and text -->
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            ALUXSA S.A de C.V
        </a>
    </nav>
    <center>
        <div>
            <div class="info">
                <p class="info-p">
                    <h1>Herramientas</h1>
                </p>
            </div>
            <center>
                <div id="resultado"></div>
                <div class="form-update">
                    <form class="needs-validation" novalidate>
                        <div class="form-row" id="items">
                            <div class="col-md-4 mb-3">
                                <label for="id_h"># registro</label>
                                <select class="custom-select" id="id_h">
                                <option selected>Choose...</option>
                                    <?php
                                    include("abrir_conexion.php");
                                    $query = $conexion -> query ("SELECT * FROM $tbherr_db7");
                                        while ($valores = mysqli_fetch_array($query)) {
                                            echo ('<option value="'.$valores['id_Herramienta'].'">'.$valores['id_Herramienta'].'</option>');
                                        }
                                    include("cerrar_conexion.php");
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cantidadnew">Cantidad</label>
                                <input type="text" class="form-control" id="cantidadn">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" onclick="update(event);">Actualizar</button>
                    </form>
                </div>
            </center>
            <div class="tb-herramientas">
                    <div class="opciones">
                        <a href="registro_h.php" class="badge badge-success">Nuevo registro</a>
                        <a href=" " class="badge badge-secondary">Refrescar pagina</a>
                        <a class="navbar-brand" href="#">
                            <?php
                                //Contamos la cantidad que hay en el almacen
                                include("abrir_conexion.php");
                                $resul = mysqli_query($conexion,"SELECT SUM(cantidad) as herramientas FROM $tbherr_db7");
                                while($consulta = mysqli_fetch_array($resul)){
                                    echo "  <button type=\"button\" class=\"btn btn-primary\">
                                                <strong>N° Herramientas:</strong> <span class=\"badge badge-light\">".$consulta['herramientas']."</span>
                                            </button>
                                        ";
                                }
                                include("cerrar_conexion.php");
                            ?>
                        </a>
                        <a class="navbar-brand" href="herramienta_agotada.php">
                            <?php
                                //Contamos la cantidad que hay en el almacen
                                include("abrir_conexion.php");
                                $resul = mysqli_query($conexion,"SELECT Count(id_herramienta) as faltantes FROM $tbherr_db7 WHERE cantidad < 3");
                                while($consulta = mysqli_fetch_array($resul)){
                                    echo "  <button type=\"button\" class=\"btn btn-danger\">
                                                <strong>Herramientas agotadas:</strong> <span class=\"badge badge-light\">".$consulta['faltantes']."</span>
                                            </button>
                                        ";
                                }
                                include("cerrar_conexion.php");
                            ?>
                        </a>
                    </div>
                    <div style="margin: 0px 10px; background: #FDFEFE;">
                        <h1 class="titulos" style="text-align:left;"><strong>Listado de herramientas</strong></h1>
                    </div>
                    <div class="tabla-herramientas">
                        <?php
                            include("abrir_conexion.php");// conexion con la BD
                            $resultados = mysqli_query($conexion,"SELECT h.id_herramienta,h.Nombre,c.material,c.descripcion,g.Num_gavilanes,m.Ancho,m.Largo,h.preciocompra,h.cantidad,h.total,h.fecha_hora FROM $tbherr_db7 h inner join $tbcat_db3 c on h.id_categoria = c.id_categoria inner join $tbgav_db6 g on h.id_gavilanes = g.id_gav inner join $tbmed_db9 m on h.id_medidas = m.id_medidas ORDER BY h.id_herramienta");
                            //Unimos tabla Herramientas con categorias y medidas
                            echo "
                            <table class=\"table\" id=\"herramientas\">
                                        <thead class=\"thead-dark\">
                                            <tr>
                                                <th><center>#</center></th>
                                                <th><center>Nombre</center></th>
                                                <th><center>Material</center></th>
                                                <th><center>Descripcion</center></th>
                                                <th><center>Gavilanes</center></th>
                                                <th><center>Ancho</center></th>
                                                <th><center>Largo</center></th>
                                                <th><center>Cantidad</center></th>
                                                <th><center>Fecha_Hora</center></th>
                                                <th><center>Estado</center></th>
                                                <th><center></center></th>
                                            </tr>
                                        </thead>
                                ";
                                while($consulta = mysqli_fetch_array($resultados)){
                                echo 
                                "<tbody class=\"body-tb\">
                                    <tr>
                                        <td><center>".$consulta['id_herramienta']."</center></td>
                                        <td><center>".$consulta['Nombre']."</center></td>
                                        <td><center>".$consulta['material']."</center></td>
                                        <td><center>".$consulta['descripcion']."</center></td>
                                        <td><center>".$consulta['Num_gavilanes']."</center></td>
                                        <td><center>".$consulta['Ancho']."</center></td>
                                        <td><center>".$consulta['Largo']."</center></td>
                                        <td><center>".$consulta['cantidad']."</center></td>
                                        <td><center>".$consulta['fecha_hora']."</center></td>
                                        <th><center>";?>
                                        <?php
                                        //mostramos un aviso segun la cantidad de piezas 
                                        if($consulta['cantidad']<2){//condicionamos var cantidad a 2 o menor para mostrar un mesaje 
                                            if ($consulta['cantidad']==1) {
                                                echo "<span class=\"badge badge-warning\">Compra más</span>";
                                            }
                                            else{
                                                if ($consulta['cantidad']==0) {
                                                    echo "<span class=\"badge badge-danger\">Insuficiente</span>";
                                                }
                                            }
                                        }//si la cantidad es mayor a 2 no se requiere comprar más
                                        else{
                                            if ($consulta['cantidad']>=2) {
                                                echo "<span class=\"badge badge-success\">Suficiente</span>";
                                            }
                                        }
                                        ?>
                                        </center></th>
                                        <th><center><a class="btn btn-danger btn-sm" href="eliminar.php?id=<?php echo $consulta['id_herramienta']?>" role="button">Eliminar</a></center></th>
                                    </tr>
                                </tbody>
                            <?php
                            }
                            include("cerrar_conexion.php");
                            ?>
                                </table><br>
                    </div>
            </div>
        </div>
    </center>
    <div>
        <div class="botones">
            <center>
                <form method="POST" action="inventario.php">
                    <div class="form-row align-items-center">
                        <div class="col-auto my-1">
                            <label class="mr-sm-2 sr-only" for="seleccion">Preference</label>
                            <select class="custom-select mr-sm-2" id="seleccion" name="herramienta">
                                <option selected>Choose...</option>
                                <option value="Cortador">Cortador</option>
                                <option value="Buril">Buril</option>
                                <option value="Broca">Broca</option>
                            </select>
                        </div>
                        <div class="col-auto my-1">
                            <label class="mr-sm-2 sr-only" for="seleccion">Preference</label>
                            <select class="custom-select mr-sm-2" id="medida" name="medida">
                                <option selected>Choose...</option>
                                <?php
                                include("abrir_conexion.php");
                                $consulta = mysqli_query($conexion,"SELECT ancho FROM $tbherr_db7 h INNER JOIN $tbmed_db9 m WHERE h.id_Medidas = m.id_Medidas ORDER BY h.id_herramienta ");
                                while($res = mysqli_fetch_array($consulta)){
                                    echo '<option value="'.$res['ancho'].'">'.$res['ancho'].'</option>';   
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-auto my-1">
                            <button type="submit" class="btn btn-primary" name="consulta" onclick="consultar(event)">Consultar</button>
                        </div>
                    </div>
                </form>
            </center>
        </div>
    </div>
            <?php
                include("abrir_conexion.php");
                $name1 = $_POST['herramienta'];
                $medida = $_POST['medida'];
                if ($name1 != "" && $medida != "") {
                $res = mysqli_query($conexion, "SELECT h.id_herramienta,h.Nombre,c.material,c.descripcion,g.Num_gavilanes,m.Ancho,m.Largo,h.preciocompra,h.cantidad,h.total,h.rutaimg,h.fecha_hora FROM $tbherr_db7 h inner join $tbcat_db3 c on h.id_categoria = c.id_categoria inner join $tbgav_db6 g on h.id_gavilanes = g.id_gav inner join $tbmed_db9 m on h.id_medidas = m.id_medidas WHERE h.Nombre LIKE '%$name1%' AND m.Ancho LIKE '%$medida%' ");
            ?>
                <div class="contador-h" style="margin: 10px 10px; background: #FDFEFE; padding: 5px; border-radius: 5px; height: max-content; width: 95%;">
                <div><center><h1>Herramientas Almacenadas</h1></center></div>
                <?php
                    while ($consulta = mysqli_fetch_array($res)) {
                ?>      <center>
                            <div class="conten">
                            <img src="<?php echo $consulta['rutaimg'];?>" id="imgs" class="" alt="imagen no encontrada">
                                <div class = "infor">
                                    <h1 class="subt">Caracteristicas</h1>
                                    <p><?php echo ' # '.$consulta['id_herramienta'].' Nombre: '.$consulta['Nombre'].' de '.$consulta['material'].' '.$consulta['descripcion']?></p>
                                    <p> <?php echo 'Medidas: '.$consulta['Ancho'].' Ancho x '.$consulta['Largo'].' Largo'?></p>
                                    <p><?php echo'# gavilanes: '.$consulta['Num_gavilanes'].' Cantidad: '.$consulta['cantidad']?></p>
                                </div>
                            </div>
                        </center>
                        <?php
                    }
                }else{
                    echo "La consula no se pudo ejecutar";
                }
                ?>
                </div>
                <?php
                include("cerrar_conexion.php");
            ?>
    <nav aria-label="Page navigation example" style="margin: 10px 10px;">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="pagina_principal.php">Previous</a>
            </li>
            <li class="page-item disabled"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="registros.php">2</a></li>
            <li class="page-item"><a class="page-link" href="herramienta_agotada.php">3</a></li>
            <li class="page-item">
                <a class="page-link" href="registros.php">Next</a>
            </li>
        </ul>
    </nav>
</body>
<script src="js/app.js"></script>
</html>