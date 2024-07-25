function listarUsuariosTodos() {
    tabla = $('#tbllistado').dataTable({
        aProcessing: true, //actiavmos el procesamiento de datatables
        aServerSide: true, //paginacion y filtrado del lado del serevr
        dom: 'Bfrtip', //definimos los elementos del control de tabla
        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
        ajax: {
            url: '../controller/datosController.php?op=listar_para_tabla',
            type: 'post',
            dataType: 'json',
            error: function (e) {
                console.log(e.responseText);
            },
            bDestroy: true,
            iDisplayLength: 5,
        },
    });
}

$(function () {
    //$('#formulario_update').hide();
    listarUsuariosTodos();
});

$('#usuario_add').on('submit', function (event) {
    event.preventDefault();
    console.log('Hola');
    $('#btnRegistar').prop('disabled', true);
    var formData = new FormData($('#usuario_add')[0]);
    $.ajax({
        url: '../controllers/datosController.php?op=insertar',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            switch (datos) {
                case '1':
                    toastr.success(
                        'Usuario registrado'
                    );
                    $('#usuario_add')[0].reset();
                    //tabla.api().ajax.reload();
                    break;

                case '2':
                    toastr.error(
                        'El correo ya existe... Corrija e inténtelo nuevamente...'
                    );
                    break;

                case '3':
                    toastr.error('Hubo un error al tratar de ingresar los datos.');
                    break;
                /*
                case '4':
                  toastr.success('Usuario registrado exitosamente.');
                  $('#usuario_add')[0].reset();
                  tabla.api().ajax.reload();
                  toastr.error('Error al enviar el correo.');
                  break;*/

                default:
                    toastr.error(datos);
                    break;
            }
            $('#btnRegistar').removeAttr('disabled');
        },
    });
});