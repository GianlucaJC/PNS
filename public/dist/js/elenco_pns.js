$(document).ready( function () {
    $('#tbl_pns tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_pns').DataTable({
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf'
		],		
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },
        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun prodotto trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti prodotti',
            infoFiltered: '(Filtrati da _MAX_ prodotti totali)',
        },

		
    });
	
} );




function dele_element(value) {
	codice=$("#id_descr"+value).data("codice")	
	html=""
	html+=`
		<p>Sicuri di eliminare il codice <b>`+codice+`</b>?</p><hr>
	
		<div class="form-group">
			<label for="motivazione_dele">Motivazione</label>
			<textarea class="form-control" id="motivazione_dele" name="motivazione_dele" rows="3" required></textarea>
		</div>
		<button type="submit" class="btn btn-success" onclick="$('#dele_contr').val(`+value+`)">Elimina</button>
		<button type="button" class="btn btn-secondary ml-2" onclick="$('#div_alert').empty();$('#div_alert').hide()">Annulla</button>
	`
	
	$("#div_alert").html(html)
	$("#div_alert").show(200)

}

function restore_element(value) {
	codice=$("#id_descr"+value).data("codice")	
	html=""
	html+=`
		<p>Sicuri di ripristinare il codice <b>`+codice+`</b>?</p><hr>
	
		<div class="form-group">
			<label for="motivazione_ripr">Motivazione</label>
			<textarea class="form-control" id="motivazione_ripristino" name="motivazione_ripristino" rows="3" required></textarea>
		</div>
		<button type="submit" class="btn btn-success" onclick="$('#restore_contr').val(`+value+`)">Ripristina</button>
		<button type="button" class="btn btn-secondary ml-2" onclick="$('#div_alert').empty();$('#div_alert').hide()">Annulla</button>
	`
	
	$("#div_alert").html(html)
	$("#div_alert").show(200)

}