$(document).ready(function() {
    // Inicializar Dropify y deshabilitarlo
    $('.dropify').dropify();
    $('.dropify').prop('disabled', true).trigger('dropify.destroy');

    $(".edit-btn").on("click", function() {
        var $table = $(this).closest(".editable-table-container").find(".editable-table");
        var $firstRow = $table.find("tbody tr").first();
        var $tds = $firstRow.find("td.editable");

        $tds.each(function() {
            var $td = $(this);
            var content = $td.text().trim();
            if ($td.find("a").length > 0) {
                content = $td.find("a").text().trim();
            }

            // Obtener el tamaño del td antes de insertar el textarea
            var tdWidth = $td.width();
            var tdHeight = $td.height();

            var $textarea = $('<textarea class="form-control">' + content + '</textarea>');
            $textarea.css({
                'width': tdWidth,
                'height': tdHeight,
                'resize': 'both' // Permitir redimensionar en ambas direcciones
            });

            $td.html($textarea);
        });

        // Habilitar Dropify
        $('.dropify').prop('disabled', false);
        $('.dropify').dropify();

        $(this).hide();
        $(this).siblings(".save-btn").show();
    });

    $(".save-btn").on("click", function() {
        var $table = $(this).closest(".editable-table-container").find(".editable-table");
        var $firstRow = $table.find("tbody tr").first();
        var $tds = $firstRow.find("td.editable");
        var data = {};

        // Obtiene el ID
        var id = $firstRow.find(".id-cell").text();
        data.id = id;

        $tds.each(function(index) {
            var $td = $(this);
            var value = $td.find("textarea").val();
            $td.html(value);

            switch (index) {
                case 0:
                    data.titulo = value;
                    break;
                case 1:
                    data.titulo_en = value;
                    break;
                case 2:
                    data.detalle = value;
                    break;
                case 3:
                    data.detalle_en = value;
                    break;
                case 4:
                    data.texto_link = value;
                    break;
                case 5:
                    data.texto_link_en = value;
                    break;
                case 6:
                    data.nombre_imagen = value;
                    break;
                case 7:
                    data.link = value;
                    break;
            }
        });

        // Manejo de la imagen con Dropify
        var formData = new FormData();
        var fileInput1 = $table.find('.dropify').eq(0)[0];
        var file1 = fileInput1.files[0];
        var fileInput2 = $table.find('.dropify').eq(1)[0];
        var file2 = fileInput2.files[0];

        if (file1) {
            formData.append('image1', file1);
        }

        if (file2) {
            formData.append('image2', file2);
        }

        // Agregar datos del formulario a FormData
        formData.append('id', data.id);
        formData.append('titulo', data.titulo);
        formData.append('titulo_en', data.titulo_en);
        formData.append('detalle', data.detalle);
        formData.append('detalle_en', data.detalle_en);
        formData.append('texto_link', data.texto_link);
        formData.append('texto_link_en', data.texto_link_en);
        formData.append('nombre_imagen', data.nombre_imagen);
        formData.append('link', data.link);
        formData.append('ac', 'mainPage');  // Añadir el parámetro ac con el valor mainPage

        // Envía los datos a action.php usando fetch
         fetch(BASE_URL+'/V01/apps/mainPage/proccess/actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response from server:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });

        $(this).hide();
        $(this).siblings(".edit-btn").show();

        // Deshabilitar Dropify
        $('.dropify').prop('disabled', true).trigger('dropify.destroy');
    });
});


