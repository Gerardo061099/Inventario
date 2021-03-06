function obtener(e) {
    console.log("Datos obtenidos: ");
    e.preventDefault();
    //obtenemos los valores ingresados por el usuario del documento registro_h.php
    //por su id incluyendo la imagen
    var nom = document.getElementById('nombre').value;
    var can = document.getElementById('cantidad').value;
    var precio = document.getElementById('precio').value;
    var total = document.getElementById('total').value;
    var img = document.getElementById('subir_imagen').files[0]; //obtenemos un objeto
    var select = document.getElementById('medidas').value;
    var cate = document.getElementById('categoria').value;
    var gav = document.getElementById('gavilanes').value;
    if (nom == "" && can == "" && precio == "" && total == "" && img == "" && select == "" && cate == "" && gav == "") {
        swal({
            title: "Campos Vacios",
            text: "Debes llenar todos los campos",
            icon: "warning",
        });
    } else {
        console.log("Nombre del producto: " + nom);
        console.log("Cantidad: " + can);
        console.log("Precio: " + precio);
        console.log("Total: " + total);
        console.log("Ruta de la imagen: " + img);
        console.log("Id de la medida es: " + select);
        console.log("Id de la categoria es: " + cate);
        console.log("Id de los gavilanes son: " + gav);
        var datos = new FormData();
        datos.append("nombre", nom);
        datos.append("cantidad", can);
        datos.append("precio", precio);
        datos.append("total", total);
        datos.append("img", img);
        datos.append("medidas", select);
        datos.append("categoria", cate);
        datos.append("gavilanes", gav);
        console.log("Subiendo datos");
        $.ajax({
            url: "add_h.php",
            type: "POST",
            data: datos,
            processData: false,
            Cache: false,
            contentType: false,
            before: function(mensaje) {
                $('#cargar').html('<div><img src="cargando.gif"></img></div>');
            },
            success: function(mensaje) {
                if (mensaje == "campos vacios") {
                    swal({
                        title: "Debes llenar todos los campos!!",
                        text: "Se inserto una nueva herramienta a la base de datos",
                        icon: "warning"
                    });
                } else {
                    swal({
                        title: "Insercion exitosa",
                        text: "Puedes consultar la informacion en la lista de herramientas",
                        icon: "success"
                    });
                }
            }
        });
    }
} //fin function obtener();

// funcion para actualizar campo cantidad de una herramienta
function update(e) {
    e.preventDefault();
    var id_herramienta = document.getElementById('id_h').value;
    var cantidadn = document.getElementById('cantidadn').value;
    console.log("# herramienta:" + id_herramienta);
    console.log("Cantidad: " + cantidadn);
    var files = new FormData();
    files.append("numero_h", id_herramienta);
    files.append("can", cantidadn);
    $.ajax({
        url: "update.php",
        type: "POST",
        data: files,
        processData: false,
        Cache: false,
        contentType: false,
        beforeSend: function() {
            $('#cargar').html('<div><img src="cargando.gif"></img></div>');
        },
        success: function(mensaje) {
            $('#resultado').html(mensaje);
            if (mensaje == "Actualizacion exitosa") {
                swal({
                    title: "Actualizacion exitosa!!",
                    text: "Se realizo un actualizacion de manera exitosa!!",
                    icon: "success"
                });
            } else {
                swal({
                    title: "Oh oh ",
                    text: "Ocurrio un error",
                    icon: "error"
                });
            }
        }
    });
} //fin function update();
function consultar(e) {
    //e.preventDefault();
    var nombre = document.getElementById("seleccion").value;
    var medida = document.getElementsByName("medida").value;
    var url = "inventario.php";
    if (nombre != "" && medida != "") {
        console.log("Los datos enviados no estan vacios");
        console.log("------------------------------------------------");
        console.log("Nombre: " + nombre);
        console.log("Medida de la herramienta: " + medida);
        var data = new FormData();
        data.append("herramienta", nombre);
        data.append("medida", medida);
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            processData: false,
            Cache: false,
            contentType: false,
            beforeSend: function() {
                $('#cargar').html('<div><img src="cargando.gif"></img></div>');
            },
            success: function(mensaje) {
                if (mensaje == "La consula no se pudo ejecutar") {
                    swal({
                        title: "Oh oh ",
                        text: "Ocurrio un error",
                        icon: "error"
                    });
                } else {
                    swal({
                        title: "Consulta exitosa!!",
                        text: "Deslice para abajo para ver los resultados de la busqueda!!",
                        icon: "success"
                    });
                }
            }
        });
    }
}

function convertir() {
    console.log("Function convertir");
    var $screenshot = document.body;
    html2pdf()
        .set({
            margin: 0.2,
            marginTop: 0.8,
            filename: "Reportes.pdf",
            image: {
                type: "jpg",
                quality: 0.98,
            },
            html2canvas: {
                scale: 3,
                letterRendering: true
            },
            jsPDF: {
                unit: "in",
                format: "a3",
                orientation: "portrait" //portrait o landscape
            }
        })
        .from($screenshot)
        .save()
        .catch(error => console.log(error))
        .finally()
        .then(() => {
            swal({
                title: "Conversion exitosa!!",
                text: "Se a generado el PDF",
                icon: "success"
            });
        });
}

function subirsolicitud(e) {
    e.preventDefault();
    var nombre = document.getElementById("nombre").value;
    var apellidos = document.getElementById("ap").value;
    var n_empleado = document.getElementById("n_empleado").value;
    var genero = document.getElementById("genero").value;
    console.log("Nombre empleado: " + nombre);
    console.log("Apellidos: " + apellidos);
    console.log("N?? empleado: " + n_empleado);
    console.log("Genero: " + genero);
    var datos = new FormData();
    datos.append("Nombre", nombre);
    datos.append("Apellidos", apellidos);
    datos.append("N_empleado", n_empleado);
    datos.append("Genero", genero);
    $.ajax({
        url: "add_solicitante.php",
        type: "POST",
        data: datos,
        processData: false,
        Cache: false,
        contentType: false,
        beforeSend: function() {
            $('#cargar').html('<div><img src="img/cargando.gif"></img><br><br>Cargando...</div>');
        },
        success: function(mensaje) {
            if (mensaje == "Insercion exitosa!!") {
                swal({
                    title: "Insercion exitosa",
                    text: "Los datos han sido insertados",
                    icon: "success"
                });
                window.location.href = "add_solicitud.php";
            } else if (mensaje == "La insercion no se pudo ejecutar") {
                swal({
                    title: "Oh oh",
                    text: "Ocurrio un problema",
                    icon: "warning"
                });
            }
        }
    });
}

function RegistrarSoli(e) {
    e.preventDefault();
    var herramienta = document.getElementById("herramienta").value;
    var maquina = document.getElementById("maquina").value;
    var cantidad = document.getElementById("cantidad").value;
    console.log("id_herramienta: " + herramienta);
    console.log("id_Maquina: " + maquina);
    console.log("Cantidad: " + cantidad);
    var data = new FormData();
    data.append("N_herramienta", herramienta);
    data.append("N_maquina", maquina);
    data.append("cantidad", cantidad);
    $.ajax({
        url: "fin_solicitud.php",
        type: "POST",
        data: data,
        processData: false,
        Cache: false,
        contentType: false,
        beforeSend: function() {
            $('#load').html('<div><img src="img/cargando2.gif"></img><br><br>Cargando...</div>');
        },
        success: function(message) {
            if (message == "Registro realizado") {
                swal({
                    title: "Registro Exitoso",
                    text: "Se a registrado la solicitud de forma exitosa!!",
                    icon: "success"
                });
                window.location.href = "salidas_almacen.php";
            } else {
                swal({
                    title: "Error",
                    text: "Ocurrio un error inesperado",
                    icon: "warning"
                });
            }
        }
    });


}