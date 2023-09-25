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
	

    $('#tbl_log tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_log').DataTable({
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
            zeroRecords: 'Nessun LOG trovato per questo PNS',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Log per questo PNS',
            infoFiltered: '(Filtrati da _MAX_ LOG totali)',
        },

		
    });	
	
	var table = $('#tbl_pns').DataTable();
	$('#tbl_pns').on( 'page.dt', function () {
		var info = table.page.info();
		page=parseInt(info.page)
		$("#cur_page").val(page)
	})
	
	cur_page=$("#cur_page").val()
	page=parseInt(cur_page)
	//$("#tbl_pns").dataTable().fnPageChange(cur_page,true);
	table.page(page).draw(false);
	
	
	
} );


function view_doc() {

	id_pns=0;from=0;resource_file="";sign_qa=""
	if( typeof view_doc.from != 'undefined' ) from=view_doc.from
	if( typeof view_doc.id_pns != 'undefined' ) id_pns=view_doc.id_pns
	if( typeof view_doc.resource_file != 'undefined' ) 
		resource_file=view_doc.resource_file
	if( typeof view_doc.sign_qa != 'undefined' ) 
			sign_qa=view_doc.sign_qa	
	
	$("#btn_sign").hide();
	

	title_doc="Definizione etichetta";
	if (from=="1") title_doc="Definizione Etichetta";
	if (from=="2") title_doc="Definizione Scheda tecnica";
	if (from=="3") title_doc="Definizione Scheda sicurezza";
	if (from=="4") title_doc="Definizione Certificato";
	if (from=="5") title_doc="Definizione Altri documenti";

	$("#title_doc").html(title_doc)	
	
	html="";
	if (from==1) 
		html=`<div class="alert alert-warning" role="alert">
				File associato all'etichetta mancante!
			</div>`;

	$("#bodyvalue").html(html)
	
	$('#modalvalue').modal('show')		
	
	
	
	if (from==1 && resource_file.length>0) {
		filex="allegati/"+id_pns+"/etic/"+resource_file
		html=`
			<a href='`+filex+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri file etichetta</button>
			</a>
		`	

		if (sign_qa.length==0) {
			html+=`<button onclick="$('#div_remove_etic').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma e file etichetta</button>
				
				
				<div id='div_remove_etic' class='form-group mt-3'  style='display:none'>
					<label for="motivazione_elimina_etic">Motivazione elimina etichetta e firma*</label>
					<textarea class="form-control" id="motivazione_elimina_etic"  name="motivazione_elimina_etic" rows="3"></textarea>
					<input type='hidden' name='id_remove_etic' id='id_remove_etic' value='`+id_pns+`'>
					
					<button type="submit" onclick='remove_sign_etic()' class="btn btn-primary mt-2" name='btn_remove_etic' value='remove'>Conferma operazione di rimozione firma e file etichetta</button>					
				</div>
			`;
		}
		
	}
	
	if (from==2) {
		url_scheda_t=resource_file
		html=`
			<a href='`+url_scheda_t+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri Scheda tecnica</button>
			</a>
		`	

			
			if (sign_qa.length==0) {
				html+=`<button onclick="$('#div_remove').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma e URL scheda tecnica</button>
				
				
				<div id='div_remove' class='form-group mt-3'  style='display:none'>
					<label for="motivazione_elimina_sceda_t">Motivazione elimina scheda tecnica e firma*</label>
					<textarea class="form-control" id="motivazione_elimina_sceda_t"  name="motivazione_elimina_sceda_t" rows="3"></textarea>
					<input type='hidden' name='id_remove_scheda_t' id='id_remove_scheda_t' value='`+id_pns+`'>
					
					<button type="submit" onclick='remove_sign_scheda_t()' class="btn btn-primary mt-2" name='btn_remove_scheda_t' value='remove'>Conferma operazione di rimozione firma e URL scheda tecnica</button>					
				</div>
				`;		
			}		
	}
	
	if (from==3) {
		url_scheda_s=resource_file
		html=`
			<a href='`+url_scheda_s+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri Scheda sicurezza</button>
			</a>`

			
			if (sign_qa.length==0) {
				html+=`<button onclick="$('#div_remove').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma e URL scheda sicurezza</button>
				
				
				<div id='div_remove' class='form-group mt-3'  style='display:none'>
					<label for="motivazione_elimina_sceda_s">Motivazione elimina scheda sicurezza e firma*</label>
					<textarea class="form-control" id="motivazione_elimina_sceda_s"  name="motivazione_elimina_sceda_s" rows="3"></textarea>
					<input type='hidden' name='id_remove_scheda_s' id='id_remove_scheda_s' value='`+id_pns+`'>
					
					<button type="submit" onclick='remove_sign_scheda_s()' class="btn btn-primary mt-2" name='btn_remove_scheda_s' value='remove'>Conferma operazione di rimozione firma e URL scheda sicurezza</button>					
				</div>
				
				`;	
			}		
	}	
	
	if (from==4) {
		url_cert=resource_file
		html=`
			<a href='`+url_cert+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri Certificato</button>
			</a>`

		if (sign_qa.length==0) {
			html+=`<button onclick="$('#div_remove').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma e URL Certificato</button>
			
			
			<div id='div_remove' class='form-group mt-3'  style='display:none'>
				<label for="motivazione_elimina_cert">Motivazione elimina Certificato e firma*</label>
				<textarea class="form-control" id="motivazione_elimina_cert"  name="motivazione_elimina_cert" rows="3"></textarea>
				<input type='hidden' name='id_remove_cert' id='id_remove_cert' value='`+id_pns+`'>
				
				<button type="submit" onclick='remove_sign_cert()' class="btn btn-primary mt-2" name='btn_remove_cert' value='remove'>Conferma operazione di rimozione firma e URL Certificato</button>					
			</div>
			
			`;
		}			
	}	
	$("#bodyvalue").html(html)		
	
}

function ins_doc() {
	
	
	id_pns=0;from=0;resource_file="";sign_qa=""
	if( typeof ins_doc.from != 'undefined' ) from=ins_doc.from
	if( typeof ins_doc.id_pns != 'undefined' ) id_pns=ins_doc.id_pns
	if( typeof ins_doc.resource_file != 'undefined' ) 
		resource_file=ins_doc.resource_file
	if( typeof ins_doc.sign_qa != 'undefined' ) 
			sign_qa=ins_doc.sign_qa	
	

	title_doc="Definizione etichetta";
	if (from=="1") title_doc="Definizione Etichetta";
	if (from=="2") title_doc="Definizione Scheda tecnica";
	if (from=="3") title_doc="Definizione Scheda sicurezza";
	if (from=="4") title_doc="Definizione Certificato";
	if (from=="5") title_doc="Definizione Altri documenti";

	$("#title_doc").html(title_doc)
	
	
	$("#btn_sign").show();
	if (from=="1") {
		html=`<button type="submit" class="btn btn-outline-success"  onclick='sign_etic()' id='btn_sign' name='btn_sign' value='sign_etic' disabled>Firma</button>`
		$("#div_save").html(html)
	}
 

	if (from=="1") {
		
		html=""
		html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";

		$("#bodyvalue").html(html)
		$('#modalvalue').modal('show')
		base_path = $("#url").val();

		fetch('class_allegati.php', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: 'operazione=refresh_tipo'
		})
		.then(response => {
			if (response.ok) {
			   return response.text();
			}
			
		})
		.then(resp=>{
			//$("#div_sezione"+sezione).html(resp);
			$("#bodyvalue").html(resp);
			//function set_class_allegati() in demo-config.js
			set_class_allegati.from=from
			set_class_allegati.id_pns=id_pns
			

			set_class_allegati(); 
		})
		.catch(status, err => {
			
			return console.log(status, err);
		})
	}
	
	if (from=="2") {
		html=`
			<input type='hidden' name='id_pns_scheda_t' value='`+id_pns+`'>
			<div class='form-group '>
				<div class="row mt-2">
					<div class="col-md-8">
						<label for="url_scheda_t">URL scheda tecnica*</label>
						<input type='text' class="form-control" id="url_scheda_t"  name="url_scheda_t" placeholder='Example: https://www.liofilchemstore.it/...' required>
					</div>

			
					<div class="col-md-4">
						<label for="data_scheda_t">Data Scheda tecnica</label>
						<input type="date" class="form-control" id="data_scheda_t" name="data_scheda_t"  required>
					</div>
				</div>
			</div>
		`;
		
		$("#bodyvalue").html(html)
		html=`
			<button type="submit" class="btn btn-success"   id='btn_sign' name='btn_sign' value='sign_scheda_t'>Firma</button>
		`;
		$("#div_save").html(html)		
		$('#modalvalue').modal('show')		
	}
	
	if (from=="3") {
		html=`
			<input type='hidden' name='id_pns_scheda_s' value='`+id_pns+`'>
			<div class='form-group '>
				<div class="row mt-2">
					<div class="col-md-8">
						<label for="url_scheda_s">URL scheda sicurezza*</label>
						<input type='text' class="form-control" id="url_scheda_s"  name="url_scheda_s" placeholder='Example: https://www.liofilchemstore.it/...' required>
					</div>

			
					<div class="col-md-4">
						<label for="data_scheda_s">Data Scheda sicurezza</label>
						<input type="date" class="form-control" id="data_scheda_s" name="data_scheda_s"  required>
					</div>
				</div>
			</div>
		`;
		
		$("#bodyvalue").html(html)
		html=`
			<button type="submit" class="btn btn-success"   id='btn_sign' name='btn_sign' value='sign_scheda_s'>Firma</button>
		`;
		$("#div_save").html(html)		
		$('#modalvalue').modal('show')		
	}	
	
	if (from=="4") {
		html=`
			<input type='hidden' name='id_pns_cert' value='`+id_pns+`'>
			<div class='form-group '>
				<div class="row mt-2">
					<div class="col-md-8">
						<label for="url_cert">URL certificato*</label>
						<input type='text' class="form-control" id="url_cert"  name="url_cert" placeholder='Example: https://www.liofilchemstore.it/...' required>
					</div>

			
					<div class="col-md-4">
						<label for="data_cert">Data Certificato</label>
						<input type="date" class="form-control" id="data_cert" name="data_cert"  required>
					</div>
				</div>
			</div>
		`;
		
		$("#bodyvalue").html(html)
		html=`
			<button type="submit" class="btn btn-success"   id='btn_sign' name='btn_sign' value='sign_cert'>Firma</button>
		`;
		$("#div_save").html(html)		
		$('#modalvalue').modal('show')		
	}	

	
}

function remove_sign_etic() {
	motivazione_elimina_etic=$("#motivazione_elimina_etic").val()
	if (motivazione_elimina_etic.length==0) {
		event.preventDefault()
		alert("Definire correttamente una motivazione!")
	}
}

function remove_sign_scheda_t() {
	motivazione_elimina_sceda_t=$("#motivazione_elimina_sceda_t").val()
	if (motivazione_elimina_sceda_t.length==0) {
		event.preventDefault()
		alert("Definire correttamente una motivazione!")
	}
}

function remove_sign_scheda_s() {
	motivazione_elimina_sceda_s=$("#motivazione_elimina_sceda_s").val()
	if (motivazione_elimina_sceda_s.length==0) {
		event.preventDefault()
		alert("Definire correttamente una motivazione!")
	}
}

function remove_sign_cert() {
	motivazione_elimina_cert=$("#motivazione_elimina_cert").val()
	if (motivazione_elimina_cert.length==0) {
		event.preventDefault()
		alert("Definire correttamente una motivazione!")
	}
}

function log_event(value) {
	$("#log_id").val(value)
}

function sign_etic() {
	//impostato da elenco_pns.js o demo-config.js
	if( typeof sign_etic.id_pns == 'undefined' ) {
		event.preventDefault()
		return false
	}
	data_etic=$("#data_etic").val()
	if (data_etic.length==0) {
		event.preventDefault()
		alert("Per apporre la firma è necessario specificare una data!")
		return false
	}

	$("#id_pns_etic").val(sign_etic.id_pns)
	$("#filename_etic").val(sign_etic.filename)
	
}


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