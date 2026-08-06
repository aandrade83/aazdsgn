
window.onload = (event) => {
  
   console.log('The page has fully loaded');
   load_main();


}

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


function saveChanges(id,company){

var element = $("#check_"+id+'_'+company);
var value = 0;

 if ( element.is(':checked') ){
     value = 1;
  } else{
  	 value = 0;
  	 
  }

   $.get(BASE_URL+"/V01/apps/books/proccess/actions.php?ac=update&id="+id+"&c="+company+"&value="+value, function(data)  {


  });

	show_saving_message();
}



