<!-- ALLEGATI -->
<?php
	$operazione=$_POST['operazione'];
	$sign_qa=null;
	if (isset($_POST['sign_qa'])) $sign_qa=$_POST['sign_qa'];
?>
<!-- ref https://github.com/danielm/uploader -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
	<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>	

<?php
$disp="";
if (isset($_POST['file_tec']) && $_POST['file_tec']=="1") {
 $disp="display:none";
 echo "<div id='div_file_doc'>";
	$js="";
	$js.="$('#div_send_allegati').show(120);$('#div_file_doc').hide();";
	echo "<a href=".$_POST['url_file']." target='_blank'>";
		echo "Dichiarazione di conformità UE";
	echo "</a>";
	echo "<span class='ml-3' id='span_lnk_doc'>";
		echo "<a href='javascript:void(0)' onclick=\"$js\">";
			echo "<i class='fas fa-trash-alt' style='color: #ff0000;'></i> Sostituisci file";
		echo "</a>";
	echo "</span>";	
	
 echo "</div>";
}
?>

<div id="sez_allegati" style="" class="mt-2">
	
	<div id="div_send_allegati" style="<?php echo $disp; ?>">
		<?php if ($operazione=="tecnica") 
			 echo "<h4>Allegare dichiarazione di conformità UE (pdf)</h4>";

		?> 
		
		
		
		<div class="row mt-2">
			<div class="col-md-6 col-sm-12">
			  
			  <!-- Our markup, the important part here! -->
			  <div id="drag-and-drop-zone" class="dm-uploader p-5">
				<h3 class="mb-5 mt-5 text-muted">Trascina il file quì</h3>

				<div class="btn btn-primary btn-block mb-5">
					<span>...altrimenti sfoglia</span>
					<input type="file" title="Click to add Files" />
				</div>
			  </div><!-- /uploader -->

			</div>
			<div class="col-md-6 col-sm-12">
			  <div class="card h-100">
				<div class="card-header">
				  File Inviati
				</div>

				<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
				  <li class="text-muted text-center empty">Nessun File inviato.</li>
				</ul>
			  </div>
			</div>
		</div><!-- /file list -->				  



		<div class="row" style="display:none">
			<div class="col-12">
			   <div class="card h-100">
				<div class="card-header">
				  Messaggi di debug
				</div>

				<ul class="list-group list-group-flush" id="debug">
				  <li class="list-group-item text-muted empty">Loading plugin....</li>
				</ul>
			  </div>
			</div>
		</div> <!-- /debug -->
	</div>
	<hr>

	<?php


	if ($operazione=="etic") {?>
	 <div class="form-group">
		<div class="row mt-2">
			<div class="col-md-4">
				<input type='hidden' name='id_pns_etic' id='id_pns_etic'>
				<input type='hidden' name='filename_etic' id='filename_etic'>
			
				<label for="data_etic">Data etichetta</label>
				<input type="date" class="form-control" id="data_etic" name="data_etic" aria-describedby="Data etichetta">
			</div>
		</div>

	 </div>
	<?php } 

	if ($operazione=="tecnica") {?>
	 <div class="form-group">
		<div class="row mt-2">

			
			<div class="col-md-8">
				<label for="tecnica_file_note">Technical File (note)</label>
				<textarea class="form-control" id="tecnica_file_note" name="tecnica_file_note" rows="1"></textarea>
						
			</div>


			<div class="col-md-4">
				<label for="tecnica_file_data">Data</label>
				<input type="date" class="form-control" id="tecnica_file_data" name="tecnica_file_data" aria-describedby="Data etichetta">
			</div>
		</div>


		<div class="row mt-2">

			
			<div class="col-md-8">
				<label for="tecnica_repertorio">Registrazione sul sito ministero (numero repertorio)</label>
				<input type='text' class="form-control" id="tecnica_repertorio"  name="tecnica_repertorio" placeholder='Numero repertorio'>
			</div>
					
					
			<div class="col-md-4">
				<label for="tecnica_ministero_data">Data</label>
				<input type="date" class="form-control" id="tecnica_ministero_data" name="tecnica_ministero_data">
			</div>
		</div>
		
		<div class="row mt-2">

			
			<div class="col-md-12">
				<label for="tecnica_basic_udi">Basic UDI-DI</label>
				<input type='text' class="form-control" id="tecnica_basic_udi"  name="tecnica_basic_udi" placeholder='Basic UDI-DI'>
			</div>

		</div>	


		<div class="row mt-2">

			<!--per ora disattivato!-->
			<div class="col-md-8" style='display:none'>
				<label for="tecnica_eudamed_note">Registrazione EUDAMED </label>
				<input type='text' class="form-control" id="tecnica_eudamed_note"  name="tecnica_eudamed_note" placeholder='Numero repertorio'>
			</div>
					
			<div class="col-md-8">
				<div class="form-floating">
				<select class='form-select' id='tecnica_eudamed_sn' aria-label='Loc' name='tecnica_eudamed_sn' onchange=" $('#div_data_edudamed').hide();if (this.value=='1') $('#div_data_edudamed').show(200);" >
					
						<option value="0"
						>Non Applicabile</option>
						<option value="1"
						>Applicabile</option>
				</select>
					
				<label for="tecnica_eudamed_sn">Eudamed SI/NO</label>
				</div>
			</div>					
					
					

			<div class="col-md-8" style='display:none'>
				<label for="tecnica_eudamed_note">Registrazione EUDAMED </label>
				<input type='text' class="form-control" id="tecnica_eudamed_note"  name="tecnica_eudamed_note" placeholder='Numero repertorio'>
			</div>

					
			<div class="col-md-4" style='display:none' id='div_data_edudamed'>
				<input type="date" class="form-control" id="tecnica_eudamed_data" name="tecnica_eudamed_data">
				<label for="tecnica_eudamed_data">Data</label>
			</div>
		</div>		
		

	 </div>
	<?php } ?>

	
	
</div>


<?php
if (isset($_POST['sign_tecnica']) && strlen($_POST['sign_tecnica'])!=0 && $sign_qa==null) {
	$js="";
	$js.="if ($('#motivazione_elimina_tecnica').val().length==0) ";
	$js.="   {alert('Definire la motivazione');event.preventDefault();}";
?>	

	<button onclick="$('#div_remove_tecnica').toggle(120)" type="button" class="btn btn-outline-primary ml-2" >Rimuovi firma sezione tecnica</button>
		
		<div id='div_remove_tecnica' class='form-group mt-3'  style='display:none'>
			<label for="motivazione_elimina_tecnica">Motivazione elimina firma*</label>
			<textarea class="form-control" id="motivazione_elimina_tecnica"  name="motivazione_elimina_tecnica" rows="3"></textarea>
			
			<input type='hidden' name='id_remove_tecnica' id='id_remove_tecnica' value="<?php echo $_POST['id_pns']?>">
			
			<button type="submit" class="btn btn-primary mt-2" name='btn_remove_tecnica' onclick="<?php echo $js;?>" value='remove'>Conferma operazione di rimozione firma</button>					
		</div>
<?php } ?>



<!-- File item template -->
<script type="text/html" id="files-template">
  <li class="media">
	<div class="media-body mb-1">
	  <p class="mb-2">
		<strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
	  </p>
	  <div class="progress mb-2">
		<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
		  role="progressbar"
		  style="width: 0%" 
		  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
		</div>
	  </div>
	  <hr class="mt-1 mb-1" />
	</div>
  </li>
</script>

<!-- Debug item template -->
<script type="text/html" id="debug-template">
  <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
</script>