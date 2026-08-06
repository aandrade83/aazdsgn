
$(document).ready(function() {
    $(".edit-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $tds = $row.find("td.editable");

        $tds.each(function() {
            var $td = $(this);
            var content = $td.text();
            $td.html('<input type="text" value="' + content + '">');
        });

        $(this).hide();
        $row.find(".save-btn").show();
    });

    $(".save-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $tds = $row.find("td.editable");
        var data = {};

        $tds.each(function(index) {
            var $td = $(this);
            var value = $td.find("input").val();
            var id = $row.find(".id-cell").text();
            data.id = id;
            data.ac = 'save';

            $td.html(value);

            switch (index) {
            case 0:
                data.menu = value;
                break;
            case 1:
                data.eng = value;
                break;
            case 2:
                data.link = value;
                break;
            case 3:
                data.pos = value;
                break;
            }



        });

        $.ajax({
            url: BASE_URL+'/V01/apps/menu/proccess/actions.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(response);
            }
        });

        $(this).hide();
        $row.find(".edit-btn").show();
    });
});

/*
$(document).ready(function() {
    $(".edit-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $tds = $row.find("td.editable");
        
        $tds.each(function() {
            var $td = $(this);
            var content = $td.text();
            $td.html('<input type="text" value="' + content + '">');
        });

        $(this).hide();
        $row.find(".save-btn").show();
    });

    $(".save-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $tds = $row.find("td.editable");
        var data = {};

        $tds.each(function(index) {
            var $td = $(this);
            var value = $td.find("input").val();
            $td.html(value);
            data.ac = 'check';
            
            switch (index) {
                case 0:
                    data.menu = value;
                    break;
                case 1:
                    data.eng = value;
                    break;
                case 2:
                    data.link = value;
                    break;
                case 3:
                    data.pos = value;
                    break;
            }
        });

        $.ajax({
            url: BASE_URL+'/V01/apps/menu/proccess/actions.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(response);
            }
        });

        $(this).hide();
        $row.find(".edit-btn").show();
    });

    // Initially hide all save buttons
    $(".save-btn").hide();
});
/*
  $(document).ready(function() {
            $('#example-table').Tabledit({
                url: BASE_URL+'/V01/apps/menu/proccess/actions.php',
                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'Nombre'], [2, 'Apellido']]
                },
                restoreButton: false,
                onAjax: function(action, serialize) {
                    // Agregar el parámetro ac con el valor check
                    serialize += '&ac=check';
                    
                    // Mostrar el valor viejo y nuevo en la consola
                    var data = serialize.split('&');
                    var oldValue = data.find(d => d.startsWith('old_value')).split('=')[1];
                    var newValue = data.find(d => d.startsWith('new_value')).split('=')[1];
                    console.log('Old Value:', decodeURIComponent(oldValue));
                    console.log('New Value:', decodeURIComponent(newValue));
                    console.log('Parameter ac:', 'check');

                    // Hacer la solicitud AJAX manualmente con el parámetro adicional
                    $.post('action.php', serialize, function(response) {
                        console.log('Server response:', response);
                    });

                    // Prevenir la solicitud AJAX automática de Tabledit
                    return false;
                }
            });
        });
/*
 $(document).ready(function() {
            $('#example-table').Tabledit({
                url: '',  // Esta URL es necesaria para realizar las actualizaciones
                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'Nombre'], [2, 'Apellido']]
                },
                restoreButton: false,
                onDraw: function() {
                    console.log('onDraw()');
                },
                onSuccess: function(data, textStatus, jqXHR) {
                    console.log('onSuccess()', data);
                },
                onFail: function(jqXHR, textStatus, errorThrown) {
                    console.log('onFail()', textStatus);
                },
                onAlways: function() {
                    console.log('onAlways()');
                },
               onAjax: function(action, serialize) {
                    console.log('onAjax(action, serialize)', action, serialize);
                    
                    // Agregar el parámetro ac con el valor check
                    serialize += '&ac=check';
                    
                    // Mostrar el valor viejo y nuevo en la consola
                    var data = serialize.split('&');
                    var oldValue = data.find(d => d.startsWith('old_value')).split('=')[1];
                    var newValue = data.find(d => d.startsWith('new_value')).split('=')[1];
                    console.log('Old Value:', decodeURIComponent(oldValue));
                    console.log('New Value:', decodeURIComponent(newValue));
                    console.log('Parameter ac:', 'check');

                    // Hacer la solicitud AJAX manualmente con el parámetro adicional
                    $.post('action.php', serialize, function(response) {
                        console.log('Server response:', response);
                    });

                    // Prevenir la solicitud AJAX automática de Tabledit
                    return false;
                }
            });

            // Capturar el evento de edición y guardar los cambios manualmente
            $('#example-table').on('change', 'input', function() {
                var $input = $(this);
                var newValue = $input.val();
                var $cell = $input.closest('td');
                var $row = $cell.closest('tr');
                var columnName = $cell.data('name');
                var rowId = $row.find('td:first').text();

                console.log('Saving value', {
                    rowId: rowId,
                    columnName: columnName,
                    newValue: newValue
                });

                // Aquí podrías enviar el valor al servidor o guardarlo en localStorage
            });
        });     

/*
function load_main(){
  
  val = $('#sel_active').val();
  var table = $('#dataTables').DataTable();
  $('#dataTables tbody').empty();
  table.destroy();
  
 fetch(BASE_URL+'/V01/apps/books/proccess/actions.php?ac=main&value='+val)
		.then(function(response) {
		  return response.json();//json
		}).then(function(data){

        $('#dataTables tbody').html(data.html);	
        $('[data-plugin="switchery"]').each(function() {
			new Switchery(this);  }); 
        $('#dataTables').DataTable({
          	"pageLength": 25, // 
             //	 responsive: {
      	     //	details: false //  +   / -
                   //  },
    		 dom: 'Bfrtip', // Mostrar solo los botones (sin búsqueda, paginación, etc.)
   			 buttons: [
        		'copy', 'csv', 'excel', 'pdf', 'print' // Opciones de exportación disponibles
    		 ]
  			});


		}).catch(function(error){
			alert("Ha sucedido un error 1.");
		});

}


  // Capturar el evento de edición y guardar los cambios manualmente
            $('#inline-editable').on('change', 'input', function() {
                var $input = $(this);
                var newValue = $input.val();
                var $cell = $input.closest('td');
                var $row = $cell.closest('tr');
                var columnName = $cell.data('name');
                var rowId = $row.find('td:first').text();

                // Guardar el nuevo valor (puede ser en una variable, localStorage, etc.)
                console.log('Saving value', {
                    rowId: rowId,
                    columnName: columnName,
                    newValue: newValue,

                });

                // Aquí podrías enviar el valor al servidor o guardarlo en localStorage
            });

     */       
