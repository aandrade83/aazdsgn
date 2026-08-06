$(document).ready(function() {
    // Initialize Dropify and disable it
    
    console.log('TEST');
    $('.dropify').dropify().closest('.dropify-wrapper').addClass('disabled');

    $(".edit-btn").on("click", function() {
        var $table = $(this).closest(".editable-table-container").find(".editable-table");
        //var $firstRow = $table.find("tbody tr").first();
        var $firstTwoRows = $table.find("tbody tr").slice(0, 2);
        var $tds = $firstTwoRows.find("td.editable");

        // Enable form fields
        document.getElementById("categoria").disabled = false;
        $(".service").prop("disabled", false);
        $(".img_name").prop("disabled", false);

        $tds.each(function() {
            var $td = $(this);
            var content = $td.text().trim();
            if ($td.find("a").length > 0) {
                content = $td.find("a").text().trim();
            }

            // Get current size before inserting textarea
            var tdWidth = $td.width();
            var tdHeight = $td.height();

            var $textarea = $('<textarea class="form-control">' + content + '</textarea>');
            $textarea.css({
                'width': tdWidth,
                'height': tdHeight,
                'resize': 'both'
            });

            $td.html($textarea);
        });

        // Enable Dropify
        $('.dropify').closest('.dropify-wrapper').removeClass('disabled');

        $(this).hide();
        $(this).siblings(".save-btn").show();
    });

    $(".save-btn").on("click", function() {
        var $table = $(this).closest(".editable-table-container").find(".editable-table");
        //var $firstRow = $table.find("tbody tr").first();
        var $firstTwoRows = $table.find("tbody tr").slice(0, 2);
        var $tds = $firstTwoRows.find("td.editable");
        var data = {};

        // Disable fields again
        $(".service").prop("disabled", true);
        $(".img_name").prop("disabled", true);

        // Disable category select
        var $categoriaSelect = $(".cat select");
        if ($categoriaSelect.length) {
            $categoriaSelect.prop("disabled", true);
        }

        // Get ID
        var id = $firstTwoRows.find(".id-cell").text();
        data.id = id;

        // Get selected category
        var categoriaSeleccionada = $categoriaSelect.val();
        data.categoria = categoriaSeleccionada; 

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
                    data.area = value;
                    break;
                case 5:
                    data.ubicacion = value;
                    break;
                case 6:
                    data.year = value;
                    break;
                case 7:
                    data.video = value;
                    break;    
                case 8:
                    data.pos = value;
                    break;    
            }
        });

        // Capture selected radio button values
        var serviceValues = [];
        $(".service-row input.service:checked").each(function() {
            serviceValues.push($(this).val());
        });

        data.services = serviceValues.join(",");

        // Capture input values for images
        var imgValues = [];
        $(".img_name").each(function() {
            imgValues.push($(this).val().trim());
        });

        data.imgnames = imgValues.join(",");

        console.log('DATA 1:');
        console.log(data); // Debugging
        console.log(' FIN DATA 1:');

        // Handling image uploads with Dropify
        var formData = new FormData();

        $('.dropify').each(function(index) {
            var fileInput = this;
            if (fileInput.files.length > 0) {
                formData.append('image' + (index + 1), fileInput.files[0]);
            }
        });

        // Append form data
        formData.append('id', data.id);
        formData.append('titulo', data.titulo);
        formData.append('titulo_en', data.titulo_en);
        formData.append('detalle', data.detalle);
        formData.append('detalle_en', data.detalle_en);
        formData.append('area', data.area);
        formData.append('ubicacion', data.ubicacion);
        formData.append('year', data.year);
        formData.append('services', data.services);
        formData.append('img_name', data.imgnames);
        formData.append('categoria', data.categoria);
        formData.append('video', data.video);
        formData.append('pos', data.pos);
        formData.append('ac', 'Projects_edit');

        // Send data using fetch
        fetch(BASE_URL+'/V01/apps/projects/proccess/actions.php', {
            method: 'POST',
            body: formData
        })


        .then(async response => {
    const text = await response.text();
     console.log("RAW RESPONSE FROM PHP:", text); // <---- este es el secreto
    try {

         //const text = await response.text();
         //console.log("RAW RESPONSE FROM PHP:", text); // <---- este es el secreto

        const data = JSON.parse(text);
        console.log("Respuesta recibida:", data);

        if (data.control === 1) {
            Swal.fire({
                icon: 'success',
                title: '¡Genial!',
                text: 'Cambios guardados correctamente',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload(); // Recarga la página después de aceptar
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: 'Hubo un problema al guardar los cambios',
                confirmButtonText: 'Intentar de nuevo'
            });
        }
    } catch (err) {
        console.error("Error al parsear JSON:", err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Respuesta inválida del servidor',
            confirmButtonText: 'Cerrar'
        });
    }
})
.catch((error) => {
    console.error("Fetch error:", error);
    Swal.fire({
        icon: 'error',
        title: 'Error de red',
        text: 'No se pudo completar la solicitud',
        confirmButtonText: 'Cerrar'
    });
});


      /*

       .then(async response => {
    const text = await response.text();
    console.log("Raw response:", text);
    try {
        return JSON.parse(text);
    } catch (err) {
        console.error("Error al parsear JSON:", err);
        return { error: "Invalid JSON response", raw: text };
    }
    })
.then(data => {
    if (data.error) {
        console.error("Error:", data.error);
        console.log("Contenido recibido:", data.raw);
    } else {
        console.log('Response from server:', data);
    }
})
.catch((error) => {
    console.error('Fetch error:', error);
})



        //
        .then(response => response.json().catch(() => ({ error: "Invalid JSON response" })))
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                console.log('Response from server:', data);
            }
        })
        .catch((error) => {
            console.error('Fetch error:', error);
        });


        */




        $(this).hide();
        $(this).siblings(".edit-btn").show();

        // Disable Dropify again
        $('.dropify').closest('.dropify-wrapper').addClass('disabled');
    });
});

$(document).ready(function() {
    // Initialize Dropify and disable it
    $('.dropify').dropify().closest('.dropify-wrapper').addClass('disabled');

    $(".edit-btn").on("click", function() {
        var $table = $(this).closest(".editable-table-container").find(".editable-table");
        //var $firstRow = $table.find("tbody tr").first();
        var $firstTwoRows = $table.find("tbody tr").slice(0, 2);

        var $tds = $firstTwoRows.find("td.editable");

        // Enable form fields
        document.getElementById("categoria").disabled = false;
        $(".service").prop("disabled", false);
        $(".img_name").prop("disabled", false);

        $tds.each(function() {
            var $td = $(this);
            var content = $td.text().trim();
            if ($td.find("a").length > 0) {
                content = $td.find("a").text().trim();
            }

            // Get current size before inserting textarea
            var tdWidth = $td.width();
            var tdHeight = $td.height();

            var $textarea = $('<textarea class="form-control">' + content + '</textarea>');
            $textarea.css({
                'width': tdWidth,
                'height': tdHeight,
                'resize': 'both'
            });

            $td.html($textarea);
        });

        // Enable Dropify
        $('.dropify').closest('.dropify-wrapper').removeClass('disabled');

        $(this).hide();
        $(this).siblings(".save-btn").show();
    });



});


$(document).on("click", ".delete-btn", function () {
    const id = $(this).data("id");

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esto eliminará el proyecto y todas sus imágenes",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProject(id);
        }
    });
});
/*
function deleteProject(id) {

    const url = BASE_URL+'/V01/apps/projects/proccess/actions.php';

    const params = new URLSearchParams();
    params.append('id', id);
    params.append('ac', 'Project_delete');

    const fullUrl = url + '?' + params.toString();

    alert('DELETE URL:'+fullUrl);
    console.log('DELETE URL:'+fullUrl);

}

*/
function deleteProject(id) {

    const formData = new FormData();
    formData.append('id', id);
    formData.append('ac', 'Project_delete');

    fetch(BASE_URL+'/V01/apps/projects/proccess/actions.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        console.log("RAW DELETE RESPONSE:", text);

        try {
            const data = JSON.parse(text);

            if (data.control === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: 'Proyecto eliminado correctamente'
                }).then(() => {
                    window.location.href = window.location.pathname;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar el proyecto'
                });
            }

        } catch (err) {
            console.error("JSON ERROR:", err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Respuesta inválida del servidor'
            });
        }
    })
    .catch(err => {
        console.error("FETCH ERROR:", err);
    });
}

