@extends('all_views.viewmaster.index')

@section('title', 'PNS')




@section('extra_style')  
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
  <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection


@section('content_main')



  <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">  
			<h1 class="m-0">
				<center>
					<font color='red'>
						RECENSIONE DEL PRODOTTO
					</font>
				</center>
				
			</h1>
			</div>	
		</div>	

      </div><!-- /.container-fluid -->
    </div>

    <!-- /.content-header -->
	

	<form method='post' action="{{route('recensione',['id'=>$id])}}" id='frm_recensione' name='frm_recensione' autocomplete="off" class="needs-validation" novalidate>
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
	
	<?php
		$dis="";
		if ($sign_recensione!=null && $sign_recensione>0) $dis="disabled";
		
	?>

		<!-- Main content -->
		<div class="content">
		  <div class="container-fluid">
		  
		  
			<div class="row mb-3">

				<div class="col-md-12">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="cliente" name="cliente" type="text" placeholder="Cliente" required value="{{$recensione[0]->cliente ?? ''}}" />
						<label for="cliente">Cliente*</label>
					</div>
				</div>			
				
			</div>

			<div class="row mb-3">

				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="codice_sl" name='codice_sl' type="text" required value="{{$recensione[0]->codice_sl ?? ''}}" />
						<label for="codice_sl">Codice semilavorato*</label>
					</div>
				</div>	

				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="tot_distinta_base_sl" name="tot_distinta_base_sl" type="text"  value="{{$recensione[0]->tot_distinta_base_sl ?? ''}}" />
						<label for="tot_distinta_base_sl">Tot Distinta Base economica SL</label>
					</div>
				</div>			
			
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="tot_distinta_base_sl" name="tot_distinta_base_pf" type="text"  value="{{$recensione[0]->tot_distinta_base_pf ?? ''}}" />
						<label for="tot_distinta_base_pf">Tot Distinta Base economica PF</label>
					</div>
				</div>				


			</div>	
			<div class="row mb-3">	
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="temperatura_conservazione" name='temperatura_conservazione' type="text" value="{{$recensione[0]->temperatura_conservazione ?? ''}}" />
						<label for="codice_sl">Temperatura di conservazione</label>
					</div>
				</div>	
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="gg_validita" name='gg_validita' type="text" value="{{$recensione[0]->gg_validita ?? ''}}" />
						<label for="gg_validita">Giorni di validità</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control" {{$dis}} id="minimo_ordine" name='minimo_ordine' type="text" value="{{$recensione[0]->minimo_ordine ?? ''}}" />
						<label for="minimo_ordine">Minimo d'ordine</label>
					</div>
				</div>
				
				
			</div>
			
			<!--
			<div class='row mb-3'>
		
				<div class="col-md-8">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="ditta" id="ditta"  required onchange=''>
						<option value=''>Select...</option>
							
						</select>
						<label for="ditta">Ditta*</label>
					</div>
				</div>			
			</div>		
			!-->
			<?php
				if (isset($recensione[0]) && $recensione[0]->ivd=="IVD") {
			?>
			<div class="row mb-3">
				<div class="col-md-4">
					<label for="gspr_applicabili">GSPR applicabili</label>
					<div class="form-floating mb-3">
						 <textarea class="form-control" {{$dis}} id="gspr_applicabili" name="gspr_applicabili" style="height: 80%;" rows="3">{{$recensione[0]->gspr_applicabili ?? ''}}</textarea>
					</div>
				</div>	
				<div class="col-md-4">
					<label for="risk_management">Risk Management File applicabile</label>
					<div class="form-floating mb-3">
						 <textarea class="form-control" {{$dis}} id="risk_management" name="risk_management" style="height: 80%;" rows="3">{{$recensione[0]->risk_management ?? ''}}</textarea>
					</div>
				</div>
				<div class="col-md-4">
					<label for="progetto_rd">Progetto R&D </label>
					<div class="form-floating mb-3">
						 <textarea class="form-control" {{$dis}} id="progetto_rd" name="progetto_rd" style="height: 80%;" rows="3">{{$recensione[0]->progetto_rd ?? ''}}</textarea>
					</div>
				</div>				
			</div>
			<?php } 
				if ($sign_recensione!=null && $sign_recensione>0)
					echo "Firma recensione apposta da: <b>".$arr_utenti[$sign_recensione]['operatore']."</b><hr>";
			?>
			
			<div class='container-fluid'>
			
				@if ($dis!="disabled")
					<button type="submit" name='btn_save_recensione' id='btn_save_recensione' value="save" class="btn btn-success btn-lg btn">SALVA</button>  
					
					<button type="submit" name='btn_sign_recensione' id='btn_sign_recensione' value="sign" class="btn btn-primary btn-lg btn ml-3">FIRMA sezione</button>
					<hr>
				@endif
				@if ($sign_recensione!=null && $sign_qa==null && $sign_ready==true) 
					<button type="submit" name='btn_sign_qa' id='btn_sign_qa' value="sign" class="btn btn-primary btn-lg btn-block">FIRMA QA</button>
				@endif
				<a href="{{ route('elenco_pns') }}">
					<button type="button"  id='back_appalti' class="btn btn-info btn-lg btn-block mt-3">TORNA AD ELENCO PNS</button>
				</a>
				
			</div>
			
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
		
			
			
				
			
			<!-- /.row -->
		  </div><!-- /.container-fluid -->
		</div>
	</form>	
	
    <!-- /.content -->
  </div>


  <!-- /.content-wrapper -->
  
  
  

  
  

  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.24"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.356"></script>
	<!-- fine upload -->		
	

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	<script src="{{ URL::asset('/') }}dist/js/recensione.js?ver=1.006"></script>

	
@endsection 