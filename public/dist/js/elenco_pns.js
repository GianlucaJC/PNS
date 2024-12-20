$(document).ready( function () {
	$('body').addClass("sidebar-collapse");
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

function set_filtro() {
	filtro_chiusi=$("#filtro_chiusi").val()
	if (filtro_chiusi=="0") $("#filtro_chiusi").val('1')
	else $("#filtro_chiusi").val('0')
}
function view_doc() {

	id_pns=0;from=0;resource_file="";sign_qa=""
	if( typeof view_doc.from != 'undefined' ) from=view_doc.from
	if( typeof view_doc.id_pns != 'undefined' ) id_pns=view_doc.id_pns
	if( typeof view_doc.resource_file != 'undefined' ) 
		resource_file=view_doc.resource_file
	if( typeof view_doc.sign_qa != 'undefined' ) 
			sign_qa=view_doc.sign_qa	
	
	$("#btn_sign").hide();
	

	title_doc="Definizione etichetta e foto";
	if (from=="1") title_doc="Definizione Etichetta e foto";
	if (from=="2") title_doc="Definizione Scheda tecnica";
	if (from=="3") title_doc="Definizione Scheda sicurezza";
	if (from=="4") title_doc="Definizione Certificato";
	if (from=="5") title_doc="Definizione UDI-DI";
	if (from=="6") title_doc="Definizione Controllo listini e altri documenti";
	if (from=="8") title_doc="Definizione Fattibilità tecnica";
	//from==7 --il view_doc di fatto viene fatto da ins_doc
	if (from=="100") title_doc="Altro";

	$("#title_doc").html(title_doc)	
	
	html="";
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
	

	
	if (from==4 && resource_file.length>0) {
		filex="allegati/"+id_pns+"/cert/"+resource_file
		html=`
			<a href='`+filex+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri Certificato</button>
			</a>
		`	

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
	
	if (from==5) {
		udi_di=resource_file
		html=`UDI-DI: <b>`+udi_di+`</b>`

		if (sign_qa.length==0) {
			html+=`<button onclick="$('#div_remove').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma UDI-DI</button>
			
			
			<div id='div_remove' class='form-group mt-3'  style='display:none'>
				<label for="motivazione_elimina_udi">Motivazione elimina UDI-DI e firma*</label>
				<textarea class="form-control" id="motivazione_elimina_udi"  name="motivazione_elimina_udi" rows="3"></textarea>
				<input type='hidden' name='id_remove_udi' id='id_remove_udi' value='`+id_pns+`'>
				
				<button type="submit" onclick='remove_sign_udi()' class="btn btn-primary mt-2" name='btn_remove_udi' value='remove'>Conferma operazione di rimozione firma UDI-DI</button>					
			</div>
			
			`;
		}			
	}
	
	if (from==6) {
		altri_doc=resource_file
		html=`Altri DOC:<hr><b>`+altri_doc+`</b>`

		if (sign_qa.length==0) {
			html+=`<button onclick="$('#div_remove').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma Controllo listini e altri documenti</button>
			
			
			<div id='div_remove' class='form-group mt-3'  style='display:none'>
				<label for="motivazione_elimina_altridoc">Motivazione elimina Controllo listini e altri Documenti e firma*</label>
				<textarea class="form-control" id="motivazione_elimina_altridoc"  name="motivazione_elimina_altridoc" rows="3"></textarea>
				<input type='hidden' name='id_remove_altridoc' id='id_remove_altridoc' value='`+id_pns+`'>
				
				<button type="submit" onclick='remove_sign_altridoc()' class="btn btn-primary mt-2" name='btn_remove_altridoc' value='remove'>Conferma operazione di rimozione controllo listini e altri documenti</button>					
			</div>
			
			`;
		}			
	}	

	if (from==8 && resource_file.length>0) {
		filex="allegati/"+id_pns+"/ft/"+resource_file
		html=`
			<a href='`+filex+`' target='_blank'>
				<button type="button" class="btn btn-success" >Apri file Fattibilità tecnica</button>
			</a>
		`	

		if (sign_qa.length==0) {
			html+=`<button onclick="$('#div_remove_ft').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma file Fattibilità tecnica</button>
				
				
				<div id='div_remove_ft' class='form-group mt-3'  style='display:none'>
					<label for="motivazione_elimina_ft">Motivazione elimina Fattibilità tecnica e firma*</label>
					<textarea class="form-control" id="motivazione_elimina_ft"  name="motivazione_elimina_ft" rows="3"></textarea>
					<input type='hidden' name='id_remove_ft' id='id_remove_ft' value='`+id_pns+`'>
					
					<button type="submit" onclick='remove_sign_ft()' class="btn btn-primary mt-2" name='btn_remove_ft' value='remove'>Conferma operazione di rimozione firma e file Fattibilità tecnica</button>					
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
	

	title_doc="Definizione etichetta e foto";
	if (from=="1") title_doc="Definizione Etichetta e foto";
	if (from=="2") title_doc="Definizione Scheda tecnica";
	if (from=="3") title_doc="Definizione Scheda sicurezza";
	if (from=="4") title_doc="Definizione Certificato";
	if (from=="5") title_doc="Definizione UDI-DI";
	if (from=="6") title_doc="Definizione Controllo listini e altri documenti";
	if (from=="7") title_doc="Definizione Documentazione tecnica";
	if (from=="8") title_doc="Definizione Fattibilità tecnica";
	if (from=="100") title_doc="Altro";
	

	$("#title_doc").html(title_doc)
	
	$("#div_save").html('')
	
	if (from=="1") {
		html=`<button type="submit" class="btn btn-outline-success"  onclick='sign_etic()' id='btn_sign' name='btn_sign' value='sign_etic' disabled>Firma</button>`
		$("#div_save").html(html)
	}

	if (from=="4") {
		html=`<button type="submit" class="btn btn-outline-success"  onclick='sign_cert()' id='btn_sign' name='btn_sign' value='sign_cert' disabled>Firma</button>`
		$("#div_save").html(html)
	}
	

	if (from=="8") {
		html=`<button type="submit" class="btn btn-outline-success"  onclick='sign_ft()' id='btn_sign' name='btn_sign' value='sign_ft' disabled>Firma</button>`
		$("#div_save").html(html)
	}
	file_tec="";url_file="";sign_tecnica="";
	if (from=="7") {
		file_tec=$("#info_tecnica"+id_pns).data("file_tec");
		url_file=$("#info_tecnica"+id_pns).data("url_file");
		sign_tecnica=$("#info_tecnica"+id_pns).data("sign_tecnica");
	}

	if (from=="1" || from=="4" || from=="7" || from=="8") {
		operazione=""
		if (from=="1") operazione="etic";
		if (from=="4") operazione="cert";
		if (from=="7") operazione="tecnica";
		if (from=="8") operazione="ft";
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
			body: 'operazione='+operazione+'&id_pns='+id_pns+'&file_tec='+file_tec+'&url_file='+url_file+'&sign_tecnica='+sign_tecnica+'&sign_qa='+sign_qa
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
			if (from==7) {
				if ($("#info_tecnica"+id_pns).data("doc_tecnica")==1) 
					$("#sez_allegati :input").prop("disabled", true);				
				//popolamento dati da span tramite prop data
				$("#tecnica_file_note").val($("#info_tecnica"+id_pns).data("tecnica_file_note"))
				$("#tecnica_file_data").val($("#info_tecnica"+id_pns).data("tecnica_file_data"))
				$("#tecnica_repertorio").val($("#info_tecnica"+id_pns).data("tecnica_repertorio"))
				$("#tecnica_ministero_data").val($("#info_tecnica"+id_pns).data("tecnica_ministero_data"))
				$("#tecnica_basic_udi").val($("#info_tecnica"+id_pns).data("tecnica_basic_udi"))
				$("#tecnica_eudamed_note").val($("#info_tecnica"+id_pns).data("tecnica_eudamed_note"))
				
				$('#div_data_edudamed').hide()
				if ($("#info_tecnica"+id_pns).data("tecnica_eudamed_sn")=="1") $('#div_data_edudamed').show()
				
				$("#tecnica_eudamed_sn").val($("#info_tecnica"+id_pns).data("tecnica_eudamed_sn"))
				$("#tecnica_eudamed_data").val($("#info_tecnica"+id_pns).data("tecnica_eudamed_data"))				
			}
			
			
			if (sign_tecnica) {
				/*
					disabilito la sezione documentazione tecnica perchè
					è stata già apposta la firma
				*/
				
				$("#sez_allegati :input").prop("disabled", true);
				$("#span_lnk_doc").hide();
				
				
			}
			

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
	
	


	if (from=="5") {
		html=`
			<input type='hidden' name='id_pns_udi' value='`+id_pns+`'>
			<div class='form-group '>
				<div class="row mt-2">
					<div class="col-md-8">
						<label for="udi_di">UDI-DI*</label>
						<input type='text' class="form-control" id="udi_di"  name="udi_di" placeholder='UDI-DI' required>
					</div>

				</div>
			</div>
		`;
		
		$("#bodyvalue").html(html)
		html=`
			<button type="submit" class="btn btn-success"   id='btn_sign' name='btn_sign' value='sign_udi'>Firma</button>
		`;
		$("#div_save").html(html)		
		$('#modalvalue').modal('show')		
	}

	if (from=="6") {
		html=`
			<input type='hidden' name='id_pns_altridoc' value='`+id_pns+`'>
			<div class='form-group '>
				<div class="row mt-2">
					<div class="col-md-12">
						<label for="altri_doc">Altri documenti</label>
						<textarea class="form-control" id="altri_doc" name="altri_doc" rows="3"></textarea>
								
					</div>

				</div>
				<div class="row mt-2">
					<div class="form-check ml-2">
					  <input class="form-check-input" type="checkbox" value="" id="check_list" required>
					  <label class="form-check-label" for="check_list">
						Controllo listini
					  </label>
					</div>

				</div>



			</div>
		`;
		
		$("#bodyvalue").html(html)
		html=`
			<button type="submit" class="btn btn-success"   id='btn_sign' name='btn_sign' value='sign_altridoc'>Firma</button>
		`;
		$("#div_save").html(html)		
		$('#modalvalue').modal('show')		
	}
	
	if (from==7) {
		html=""
		html+=`<input type='hidden' name='id_pns_tecnica' id='id_pns_tecnica' value='`+id_pns+`'>`;
		if (!sign_tecnica) {
			
			
			if ($("#info_tecnica"+id_pns).data("doc_tecnica")==1) {
				html+=`<button type="button" id="btn_sblocca" class="btn btn-info mr-5" onclick='$("#sez_allegati :input").prop("disabled", false);$("#btn_sign").hide();$("#btn_sblocca").hide();'>Sblocca modifica</button>`
				
				html+=`<button type="submit" class="btn btn-success mr-2"  id='btn_sign' name='btn_sign' value='sign_tecnica' >Firma</button>`
			}
			html+=`<button type="submit" class="btn btn-success"   id='btn_save_tec' name='btn_save_tec' value='save'>Salva</button>`
		}

		
		$("#div_save").html(html)
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

function remove_sign_udi() {
	motivazione_elimina_udi=$("#motivazione_elimina_udi").val()
	if (motivazione_elimina_udi.length==0) {
		event.preventDefault()
		alert("Definire correttamente una motivazione!")
	}
}


function remove_sign_altridoc() {
	motivazione_elimina_altridoc=$("#motivazione_elimina_altridoc").val()
	if (motivazione_elimina_altridoc.length==0) {
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

function sign_cert() {
	//impostato da elenco_pns.js o demo-config.js
	if( typeof sign_cert.id_pns == 'undefined' ) {
		event.preventDefault()
		return false
	}
	data_cert=$("#data_cert").val()
	if (data_cert.length==0) {
		event.preventDefault()
		alert("Per apporre la firma è necessario specificare una data!")
		return false
	}

	$("#id_pns_cert").val(sign_cert.id_pns)
	$("#filename_cert").val(sign_cert.filename)
	
}

function sign_ft() {
	//impostato da elenco_pns.js o demo-config.js
	if( typeof sign_ft.id_pns == 'undefined' ) {
		event.preventDefault()
		return false
	}
	data_ft=$("#data_ft").val()
	if (data_ft.length==0) {
		event.preventDefault()
		alert("Per apporre la firma è necessario specificare una data!")
		return false
	}

	$("#id_pns_ft").val(sign_ft.id_pns)
	$("#filename_ft").val(sign_ft.filename)
	
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