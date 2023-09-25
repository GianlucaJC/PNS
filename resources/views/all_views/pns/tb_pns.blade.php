@foreach($elenco_pns as $pns)
	<?php
		$colo_status="danger";$status_text="NoRev";
		$sign_ready=0;
		if ($pns->sign_etichetta!=null) {$colo_status="warning";$sign_ready++;}
		if ($pns->sign_scheda_t!=null) {$colo_status="warning";$sign_ready++;}
		if ($pns->sign_scheda_s!=null) {$colo_status="warning";$sign_ready++;}
		if ($pns->sign_cert!=null) {$colo_status="warning";$sign_ready++;}
		

		if ($colo_status=="warning") $status_text="InRev";
		if ($sign_ready==4) {
			$status_text="ReadySign";
			$colo_status="warning";
		}
		
		if ($pns->sign_qa!=null) {
			$colo_status="success";$status_text="Close";
		}		
	?>
	<tr>
		<td style='width:70px;text-align:center'>
			@if ($pns->dele=="0")
				@if ($pns->sign_recensione==null)
				<a href="{{route('recensione',['id'=>$pns->id])}}" >
					<button type="button" class="btn btn-primary" alt='Completa scheda'><i class="fas fa-edit fa-xs" title="Completa scheda"></i></button>
				</a>									
				@elseif($pns->sign_qa==null && $sign_ready!=4)
				<a href="{{route('recensione',['id'=>$pns->id])}}" >
					<button type="button" class="btn btn-info" alt='Visualizza scheda'><i class="fas fa-info-circle fa-xs" title="Visualizza scheda"></i></button>
				</a>									
				@elseif($sign_ready==4 && $pns->sign_qa==null)
					<a href="{{route('recensione',['id'=>$pns->id])}}" >
						<button type="button" class="btn btn-primary" alt='SignQA'><i class="fas fa-signature fa-xs" title="SignQA"></i></button>
					</a>
				@else
					<a href="{{route('recensione',['id'=>$pns->id])}}" >
						<button type="button" class="btn btn-info" alt='Visualizza scheda'><i class="fas fa-info-circle fa-xs" title="Visualizza scheda"></i></button>
					</a>					
				@endif
			@endif

		</td>

		<td style='text-align:center;width:40px'>
			<button style='width:80px' type="button" class="btn btn-{{$colo_status}} btn-sm">{{$status_text}}</button>
			<span class='firme' style='display:none'>
				<?php
					if ($pns->sign_qa!=null && isset($arr_utenti[$pns->sign_qa]['ref'])) {
						$op=$arr_utenti[$pns->sign_qa]['operatore'];
						echo "<small>";
							echo "<a href='javascript:void(0)' 
									onclick=\"alert('$op')\">";
									echo $arr_utenti[$pns->sign_qa]['ref'];
							echo "</a>";		
						echo "</small>";
					}
				?>
			</span>
		</td>
		
		<td style='width:40px'>
		 @if ($pns->dele=="1") 
			<font color='red'><del> 
		 @endif
			<span id='id_descr{{$pns->id}}' data-codice='{{ $pns->codice }}'>
				{{ $pns->codice }}
			</span>	
		 @if ($pns->dele=="1") 
			 </del></font>
				<hr>
				<small><i>{{ $pns->motivazione_dele }}</i></small>								 
		 @endif	
		</td>

		<td>
			<i>{{ $pns->descrizione }}</i>
		</td>

		<td style='width:120px'>
			{{ $pns->updated_at }}

		</td>

		<td style='width:70px'>
			{{ $pns->ivd }}

		</td>


		
		<td>
		<?php
			$stato_sign="disabled";
			if ($pns->sign_recensione!=null) $stato_sign="";

			$colo_stato_etic="danger";
			$colo_stato_tec="danger";
			$colo_stato_sic="danger";
			$colo_stato_cert="danger";

			$etic_status=0;
			if ($pns->sign_etichetta!=null) {
				$etic_status=1;
				$colo_stato_etic="success";
			}	
			$scheda_t_status=0;
			if ($pns->sign_scheda_t!=null) {
				$scheda_t_status=1;
				$colo_stato_tec="success";
			}	
			$scheda_s_status=0;
			if ($pns->sign_scheda_s!=null) {
				$scheda_s_status=1;
				$colo_stato_sic="success";
			}	
			$cert_status=0;
			if ($pns->sign_cert!=null) {
				$cert_status=1;
				$colo_stato_cert="success";
			}				

			
			
			$view_doc="display:none";
		?>	
			@if ($pns->dele=="0") 
				<?php
					if ($etic_status==0) $proc="ins_doc";
					else $proc="view_doc";
					$js="";					
					$js.="$proc.from=1;";
					$js.="$proc.id_pns=".$pns->id.";";
					$js.="$proc.sign_qa='".$pns->sign_qa."';";
					$js.="$proc.resource_file='".$pns->file_etic."';";
					if ($etic_status==0) $js.="ins_doc();";
					else $js.="view_doc();";
				?>
				<a href="javascript:void(0)" >
					<button type="button" class="btn btn-{{$colo_stato_etic}}" onclick="{{$js}}" {{$stato_sign}}><i class="fas fa-tag fa-xs" title="Etichetta"></i></button>
				</a>
				<span class='firme' style='display:none'>
				<?php
					$view_sign=view_sign($arr_utenti,$pns,"sign_etichetta");
					echo $view_sign;
				?>
				</span>	

				<?php
					if ($scheda_t_status==0) $proc="ins_doc";
					else $proc="view_doc";
					$js="";					
					$js.="$proc.from=2;";
					$js.="$proc.id_pns=".$pns->id.";";
					$js.="$proc.sign_qa='".$pns->sign_qa."';";
					$js.="$proc.resource_file='".$pns->url_scheda_t."';";
					if ($scheda_t_status==0) $js.="ins_doc();";
					else $js.="view_doc();";
				?>
				<a href="javasript:void(0)" >
					<button type="button" class="btn btn-{{$colo_stato_tec}}" onclick="{{$js}}" {{$stato_sign}}><i class="fas fa-file-invoice fa-xs" title="Scheda tecnica"></i></button>
				</a>
				<span class='firme' style='display:none'>
				<?php
					$view_sign=view_sign($arr_utenti,$pns,"sign_scheda_t");
					echo $view_sign;
				?>			
				</span>
				
				<?php
					if ($scheda_s_status==0) $proc="ins_doc";
					else $proc="view_doc";
					$js="";					
					$js.="$proc.from=3;";
					$js.="$proc.id_pns=".$pns->id.";";
					$js.="$proc.sign_qa='".$pns->sign_qa."';";
					$js.="$proc.resource_file='".$pns->url_scheda_s."';";
					if ($scheda_s_status==0) $js.="ins_doc();";
					else $js.="view_doc();";
				?>				
				<a href="javasript:void(0)" >
					<button type="button" class="btn btn-{{$colo_stato_sic}}" onclick="{{$js}}" {{$stato_sign}}>
					<i class="fas fa-shield-alt fa-xs" title="Scheda sicurezza"></i></button>
				</a>
				<span class='firme' style='display:none'>
				<?php
					$view_sign=view_sign($arr_utenti,$pns,"sign_scheda_s");
					echo $view_sign;
				?>			
				</span>
				<?php
					if ($cert_status==0) $proc="ins_doc";
					else $proc="view_doc";
					$js="";					
					$js.="$proc.from=4;";
					$js.="$proc.id_pns=".$pns->id.";";
					$js.="$proc.sign_qa='".$pns->sign_qa."';";
					$js.="$proc.resource_file='".$pns->url_cert."';";
					if ($cert_status==0) $js.="ins_doc();";
					else $js.="view_doc();";
				?>		
				<a href="javasript:void(0)" >
					<button type="button" class="btn btn-{{$colo_stato_cert}}"  onclick="{{$js}}" {{$stato_sign}}>
					<i class="fas fa-check-square fa-xs" title="Certificato"></i></button>
				</a>										
				<span class='firme' style='display:none'>
				<?php
					$view_sign=view_sign($arr_utenti,$pns,"sign_cert");
					echo $view_sign;
				?>				
				</span>				
				
				<a href="" style='{{$view_doc}}' >
					<button type="button" class="btn btn-success" alt='Fattibilità tecnica' {{$stato_sign}} title="Documenti"><i class="fas fa-file-alt fa-xs"></i></button>
				</a>
				
				<a href='#' onclick="log_event({{$pns->id}})">
					<button type="submit" class="btn btn-secondary" title="Log eventi">
					<i class="fas fa-search fa-xs"></i></button>	
				</a>				

				<a href='#' onclick="dele_element({{$pns->id}})">
					<button type="button" name='dele_ele' class="btn btn-secondary" title="Elimina PNS">
					<i class="fas fa-trash fa-xs"></i></button>	
				</a>
			@endif
			@if ($pns->dele=="1") 
				<a href='#'onclick="restore_element({{$pns->id}})" >
					<button type="button" class="btn btn-secondary" alt='Restore' title="Elimina PNS"><i class="fas fa-trash-restore"></i></button>
				</a>
			@endif
			
			
		</td>	
	</tr>
@endforeach

<?php
	function view_sign($arr_utenti,$pns,$sign) {
		$view=null;
		if ($pns->$sign!=null && isset($arr_utenti[$pns->$sign]['ref'])) {
			$view.= "<small>";
				$op=$arr_utenti[$pns->$sign]['operatore'];
				$view.= "<a href='javascript:void(0)' 
						onclick=\"alert('$op')\">";
						$view.= $arr_utenti[$pns->$sign]['ref'];
				$view.= "</a>";		
			$view.= "</small>";
		}
		return $view;
	}
?>	