<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Exports\ExportParco;

use App\Models\art_ana;
use App\Models\prodotti;
use App\Models\last_ts_target;
use App\Models\log_event;
use App\Models\utenti;

use DB;
use Mail;



use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class mainController extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}	
	

    public function exportParco(Request $request){
		//la classe è in app/exports/
        return Excel::download(new ExportParco, 'parco.xlsx');
    }

    public function exportUsers(Request $request){
		//la classe è in app/exports/
        return Excel::download(new ExportUser, 'users.xlsx');
    }
	
	
	public function import_code($data_import) {
		$cond="cast(concat(substr(aa.DATA_INSERIMENTO,1,10),' ',substr(aa.ORA_INS,12)) as datetime)>'$data_import'";
		$all_data=art_ana::from('ART_ANA as aa')
		->select("aa.DATA_INSERIMENTO","aa.COD_ART","aa.DES_ART","aa.COD_CAT","au.TEMPERATURA","au.GGSCAD","au.MINORDCLI")
		->leftjoin('ART_USER as au','aa.COD_ART','au.COD_ART')
		->whereRaw($cond)		
		->orderBy('aa.DATA_INSERIMENTO')
		->get();
		print_r($all_data);
		$data_up=date("Y-m-d H:i:s");
		foreach($all_data as $data) {
			//$data_up=$data->DATA_INSERIMENTO;
			$codice=$data->COD_ART;
			$pre=substr($codice,0,1);
			$procedi=false;
			if ($pre=="0" || $pre=="1" || $pre=="2" || $pre=="3" || $pre=="4" || $pre=="5" || $pre=="6" || $pre=="7" || $pre=="8" || $pre=="9") $procedi=true;
			
			if (substr($codice,0,3)=="655") $procedi=false;
				
			if ($procedi==false) continue;
			$descrizione=$data->DES_ART;
			$temperatura_conservazione=$data->TEMPERATURA;
			$gg_validita=$data->GGSCAD;
			$minimo_ordine=$data->MINORDCLI;
			
			$cod_cat=$data->COD_CAT;
			$ivd="";
			if (strlen($cod_cat)>3) {
				$pre_cod=substr($cod_cat,0,3);
				if ($pre_cod=="006") $ivd="NOIVD";
				if ($pre_cod=="007") $ivd="IVD";
				if ($pre_cod=="008") $ivd="RIVIVD";
				if ($pre_cod=="012") $ivd="RIVNOIVD";
			}

			$info_prod=DB::table('prodotti')->select('id')->where('codice','=',$codice);
			$count=$info_prod->count();
			if ($count==0)
				$prodotto=new prodotti;
			else {
				$ref=$info_prod->get();
				$prodotto = prodotti::find($ref[0]->id);
			}	
			
			$t_c="";
	
			
			if ($temperatura_conservazione=="001") $t_c="AMBIENTE";
			if ($temperatura_conservazione=="002") $t_c="CELLA FRIGO";
			if ($temperatura_conservazione=="003") $t_c="CONGELATORE";
		
			$prodotto->codice=$codice;
			$prodotto->descrizione=$descrizione;
			$prodotto->temperatura_conservazione=$t_c;
			$prodotto->gg_validita=$gg_validita;
			$prodotto->minimo_ordine=$minimo_ordine;
			$prodotto->ivd=$ivd;
			$prodotto->save();
		}

		$last_ts_target = last_ts_target::find(1);
		$last_ts_target->last_ts=$data_up;
		$last_ts_target->save();
		
	}
	
	
	public function dashboard(Request $request) {
		$last_ts_target=last_ts_target::where('id','=',1)->get();
		//in caso di prima importazione decidere data fittizia di inizio import
		$data_import="";
		if (isset($last_ts_target[0])) {
			$data_import=$last_ts_target[0]->last_ts;
		}	
		
		if (strlen($data_import)>0) $this->import_code($data_import);
		
		return view('all_views/dashboard');
	}
	

	public function recensione($id=0) {
		$request=request();
		$utenti=utenti::select('id','operatore')->get();
		$arr_utenti=array();
		foreach($utenti as $u) {
			$op=$u->operatore;
			$arr=explode(" ",$op);
			if (count($arr)>1) {
				$ref=substr($arr[0],0,1).substr($arr[1],0,1);
			} else $ref=substr($op,0,2)."..";
			$arr_utenti[$u->id]['ref']=$ref;
			$arr_utenti[$u->id]['operatore']=$u->operatore;
		}		
		$btn_sign_recensione=$request->input("btn_sign_recensione");
		$btn_save_recensione=$request->input("btn_save_recensione");
		if ($btn_save_recensione=="save" || $btn_sign_recensione=="sign") {
			$db = prodotti::find($id);
			$db->cliente=$request->input("cliente");
			$db->codice_sl=$request->input("codice_sl");
			$db->tot_distinta_base_sl=$request->input("tot_distinta_base_sl");
			$db->tot_distinta_base_pf=$request->input("tot_distinta_base_pf");
			$db->temperatura_conservazione=$request->input("temperatura_conservazione");
			$db->gg_validita=$request->input("gg_validita");
			$db->minimo_ordine=$request->input("minimo_ordine");
			if ($request->has("gspr_applicabili"))
				$db->gspr_applicabili=$request->input("gspr_applicabili");
			if ($request->has("risk_management"))
				$db->risk_management=$request->input("risk_management");
			
			$db->progetto_rd_sn=$request->input("progetto_rd_sn");
			if ($request->has("progetto_rd"))
				$db->progetto_rd=$request->input("progetto_rd");
			if ($request->has("progetto_rd_motivazione_no"))
				$db->progetto_rd_motivazione_no=$request->input("progetto_rd_motivazione_no");

			$db->save();
			
			
			$log = print_r($_POST, true);
			$id_user=Auth::user()->id;
			$log_event=new log_event;
			$log_event->id_pns=$id;
			$log_event->user=$id_user;
			$log_event->operazione="UPDATE";
			$log_event->modulo="recensione_pns";
			$log_event->dettaglio=$log;
			$log_event->save();			
		}
		if ($btn_sign_recensione=="sign") {
			$dx=date("Y-m-d");
			$id_user=Auth::user()->id;
			
			$db = prodotti::find($id);
			$db->sign_recensione=$id_user;
			$db->data_recensione=$dx;
			$db->save();
			
		
			$log_event=new log_event;
			$log_event->id_pns=$id;
			$log_event->user=$id_user;
			$log_event->operazione="INSERT";
			$log_event->modulo="recensione_pns";
			$log_event->dettaglio="SIGN Recensione";
			$log_event->save();				
		}
		$btn_sign_qa=$request->input("btn_sign_qa");
		if ($btn_sign_qa=="sign") {
			$dx=date("Y-m-d");
			$id_user=Auth::user()->id;
			
			$db = prodotti::find($id);
			$db->sign_qa=$id_user;
			$db->data_qa=$dx;
			$db->save();
			
			$log_event=new log_event;
			$log_event->id_pns=$id;
			$log_event->user=$id_user;
			$log_event->operazione="INSERT";
			$log_event->modulo="recensione_pns";
			$log_event->dettaglio="SIGN QA";
			$log_event->save();				
		}
	
		$recensione=prodotti::where('id', $id)->get();
		$sign_recensione=null;$sign_qa=null;$sign_ready=false;

		if (isset($recensione[0])) {
			$sign_recensione=$recensione[0]->sign_recensione;
			$sign_qa=$recensione[0]->sign_qa;
			$signr=0;
			if ($recensione[0]->sign_etichetta!=null) $signr++;
			if ($recensione[0]->sign_scheda_t!=null) $signr++;
			if ($recensione[0]->sign_scheda_s!=null) $signr++;
			if ($recensione[0]->sign_cert!=null) $signr++;
			if ($recensione[0]->sign_udi!=null) $signr++;
			if ($recensione[0]->sign_altro!=null) $signr++;
			if ($recensione[0]->sign_tecnica!=null) $signr++;

			$check_ready_sign=6;
			if ($recensione[0]->ivd=="IVD" || $recensione[0]->ivd=="RIVIVD") $check_ready_sign=7;
			
			if ($signr==$check_ready_sign  || ($recensione[0]->ivd=="IVD" && $recensione[0]->progetto_rd_sn=="S" && $recensione[0]->sign_recensione==1)) $sign_ready=true;
		}




		return view('all_views/pns/recensione')->with('recensione',$recensione)->with('id',$id)->with('sign_recensione',$sign_recensione)->with('arr_utenti',$arr_utenti)->with('sign_qa',$sign_qa)->with('sign_ready',$sign_ready);
	}
	
	
	public function elenco_pns(Request $request){
		$utenti=utenti::select('id','operatore')->get();
		$arr_utenti=array();
		foreach($utenti as $u) {
			$op=$u->operatore;
			$arr=explode(" ",$op);
			if (count($arr)>1) {
				$ref=substr($arr[0],0,1).substr($arr[1],0,1);
			} else $ref=substr($op,0,2)."..";
			$arr_utenti[$u->id]['ref']=$ref;
			$arr_utenti[$u->id]['operatore']=$u->operatore;
		}
	
		$view_dele=$request->input("view_dele");
		$cur_page=$request->input("cur_page");

		$log_id=$request->input("log_id");
		$view_log=array();
		if (strlen($log_id)!=0) {
			$view_log=log_event::where('id_pns', "=",$log_id)->get();
		}

		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		$id_user=Auth::user()->id;
		
		if (strlen($dele_contr)!=0) {
			prodotti::where('id', $dele_contr)
			  ->update(['dele' => 1,'motivazione_dele'=>$request->input("motivazione_dele")]);
			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$dele_contr;
			 $log_event->operazione="DELE";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Cancellazione PNS - Motivazione: ".$request->input("motivazione_dele");
			 $log_event->save();
		}
		
		if (strlen($restore_contr)!=0) {
			prodotti::where('id', $restore_contr)
			  ->update(['dele' => 0,'motivazione_ripristino'=>$request->input("motivazione_ripristino")]);
			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$restore_contr;
			 $log_event->operazione="RIPR";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Ripristino PNS - Motivazione: ".$request->input("motivazione_ripristino");
			 $log_event->save();			  
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$btn_save_tec=$request->input("btn_save_tec");
		if ($btn_save_tec=="save") {
			
			$id_pns_tecnica=$request->input("id_pns_tecnica");

			prodotti::where('id', $id_pns_tecnica)
			  ->update(['tecnica_file_note' => $request->input("tecnica_file_note"),'tecnica_file_data'=>$request->input("tecnica_file_data"),'tecnica_repertorio'=>$request->input("tecnica_repertorio"),'tecnica_ministero_data'=>$request->input("tecnica_ministero_data"),'tecnica_basic_udi'=>$request->input("tecnica_basic_udi"),'tecnica_eudamed_note'=>$request->input("tecnica_eudamed_note"),'tecnica_eudamed_sn'=>$request->input("tecnica_eudamed_sn"),'tecnica_eudamed_data'=>$request->input("tecnica_eudamed_data")]);
			
		}
		
		$btn_sign=$request->input("btn_sign");
		if ($btn_sign=="sign_etic") {
			$id_pns_etic=$request->input("id_pns_etic");
			$filename_etic=$request->input("filename_etic");

			prodotti::where('id', $id_pns_etic)
			  ->update(['sign_etichetta' => $id_user,'data_etichetta'=>$request->input("data_etic"),'file_etic'=>$filename_etic]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_etic;
			 $log_event->operazione="Sign Etichetta";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Data etichetta: ".$request->input("data_etic");
			 $log_event->save();				  
		}
		if ($btn_sign=="sign_scheda_t") {
			$url_scheda_t=$request->input("url_scheda_t");
			if (!preg_match("~^(?:f|ht)tps?://~i", $url_scheda_t)) {
				$url_scheda_t = "http://" . $url_scheda_t;
			}
			
			$data_scheda_t=$request->input("data_scheda_t");
			$id_pns_scheda_t=$request->input("id_pns_scheda_t");

			prodotti::where('id', $id_pns_scheda_t)
			  ->update(['sign_scheda_t' => $id_user,'url_scheda_t'=>$url_scheda_t,'data_scheda_t'=>$data_scheda_t]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_scheda_t;
			 $log_event->operazione="Sign Scheda Tecnica";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Data scheda tecnica: ".$data_scheda_t;
			 $log_event->save();			
		}
		
		if ($btn_sign=="sign_scheda_s") {
			$url_scheda_s=$request->input("url_scheda_s");
			if (!preg_match("~^(?:f|ht)tps?://~i", $url_scheda_s)) {
				$url_scheda_s = "http://" . $url_scheda_s;
			}
			
			$data_scheda_s=$request->input("data_scheda_s");
			$id_pns_scheda_s=$request->input("id_pns_scheda_s");

			prodotti::where('id', $id_pns_scheda_s)
			  ->update(['sign_scheda_s' => $id_user,'url_scheda_s'=>$url_scheda_s,'data_scheda_s'=>$data_scheda_s]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_scheda_s;
			 $log_event->operazione="Sign Scheda Sicurezza";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Data scheda sicurezza: ".$data_scheda_s;
			 $log_event->save();			
		}		

		if ($btn_sign=="sign_cert") {
			$url_cert=$request->input("url_cert");
			if (!preg_match("~^(?:f|ht)tps?://~i", $url_cert)) {
				$url_cert = "http://" . $url_cert;
			}
			
			$data_cert=$request->input("data_cert");
			$id_pns_cert=$request->input("id_pns_cert");

			prodotti::where('id', $id_pns_cert)
			  ->update(['sign_cert' => $id_user,'url_cert'=>$url_cert,'data_cert'=>$data_cert]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_cert;
			 $log_event->operazione="Sign Certificato";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Data certificato: ".$data_cert;
			 $log_event->save();			
		}		
		
		if ($btn_sign=="sign_udi") {
			$udi_di=$request->input("udi_di");
			$id_pns_udi=$request->input("id_pns_udi");

			prodotti::where('id', $id_pns_udi)
			  ->update(['sign_udi' => $id_user,'udi_di'=>$udi_di]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_udi;
			 $log_event->operazione="Sign UDI-DI";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="--";
			 $log_event->save();			
		}		


		if ($btn_sign=="sign_altridoc") {
			$altri_doc=$request->input("altri_doc");
			$id_pns_altridoc=$request->input("id_pns_altridoc");

			prodotti::where('id', $id_pns_altridoc)
			  ->update(['sign_altro' => $id_user,'altri_doc'=>$altri_doc]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_altridoc;
			 $log_event->operazione="Sign Altri documenti";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="--";
			 $log_event->save();			
		}

		if ($btn_sign=="sign_tecnica") {
			$id_pns_tecnica=$request->input("id_pns_tecnica");
			$filename_etic=$request->input("filename_etic");
			$ddd=date("Y-m-d");
			prodotti::where('id', $id_pns_tecnica)
			  ->update(['sign_tecnica' => $id_user,'tecnica_sign_date'=>$ddd]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_pns_tecnica;
			 $log_event->operazione="Sign Documentazione Tecnica";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="--";
			 $log_event->save();				  
		}		
				

		
		$btn_remove_etic=$request->input("btn_remove_etic");
		if ($btn_remove_etic=="remove") {
			$id_remove_etic=$request->input("id_remove_etic");
			prodotti::where('id', $id_remove_etic)
			  ->update(['sign_etichetta' =>null,'data_etichetta'=>null,'file_etic'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_etic;
			 $log_event->operazione="Remove Sign Etichetta";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_etic");
			 $log_event->save();				  
		}	

		$btn_remove_scheda_t=$request->input("btn_remove_scheda_t");
		if ($btn_remove_scheda_t=="remove") {
			$id_remove_scheda_t=$request->input("id_remove_scheda_t");
			prodotti::where('id', $id_remove_scheda_t)
			  ->update(['sign_scheda_t' =>null,'data_scheda_t'=>null,'url_scheda_t'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_scheda_t;
			 $log_event->operazione="Remove Sign Scheda tecnica";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_sceda_t");
			 $log_event->save();				  
		}

		$btn_remove_scheda_s=$request->input("btn_remove_scheda_s");
		if ($btn_remove_scheda_s=="remove") {
			$id_remove_scheda_s=$request->input("id_remove_scheda_s");
			prodotti::where('id', $id_remove_scheda_s)
			  ->update(['sign_scheda_s' =>null,'data_scheda_s'=>null,'url_scheda_s'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_scheda_s;
			 $log_event->operazione="Remove Sign Scheda sicurezza";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_sceda_s");
			 $log_event->save();				  
		}
		
		$btn_remove_cert=$request->input("btn_remove_cert");
		if ($btn_remove_cert=="remove") {
			$id_remove_cert=$request->input("id_remove_cert");
			prodotti::where('id', $id_remove_cert)
			  ->update(['sign_cert' =>null,'data_cert'=>null,'url_cert'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_cert;
			 $log_event->operazione="Remove Sign Certificato";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_cert");
			 $log_event->save();				  
		}		
		
		$btn_remove_udi=$request->input("btn_remove_udi");
		if ($btn_remove_udi=="remove") {
			$id_remove_udi=$request->input("id_remove_udi");
			prodotti::where('id', $id_remove_udi)
			  ->update(['sign_udi' =>null,'udi_di'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_udi;
			 $log_event->operazione="Remove Sign UDI-DI";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_udi");
			 $log_event->save();				  
		}	

		$btn_remove_altridoc=$request->input("btn_remove_altridoc");
		if ($btn_remove_altridoc=="remove") {
			$id_remove_altridoc=$request->input("id_remove_altridoc");
			prodotti::where('id', $id_remove_altridoc)
			  ->update(['sign_altro' =>null,'altri_doc'=>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_altridoc;
			 $log_event->operazione="Remove Sign altri documenti";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_altridoc");
			 $log_event->save();				  
		}

		$btn_remove_tecnica=$request->input("btn_remove_tecnica");
		if ($btn_remove_tecnica=="remove") {
			$id_remove_tecnica=$request->input("id_remove_tecnica");
			prodotti::where('id', $id_remove_tecnica)
			  ->update(['sign_tecnica' =>null]);

			 $log_event=new log_event;
			 $log_event->user=$id_user;
			 $log_event->id_pns=$id_remove_tecnica;
			 $log_event->operazione="Remove Sign documentazione tecnica";
			 $log_event->modulo="elenco_pns";
			 $log_event->dettaglio="Motivazione: ".$request->input("motivazione_elimina_tecnica");
			 $log_event->save();				  
		}

		
		
		
		$elenco_pns=DB::table('prodotti')
		->when($view_dele=="0", function ($elenco_pns) {
			return $elenco_pns->where('dele', "=","0");
		})
		->orderBy('id','desc')->get();

		return view('all_views/pns/elenco_pns')->with("view_dele",$view_dele)->with("elenco_pns",$elenco_pns)->with('arr_utenti',$arr_utenti)->with('view_log',$view_log)->with('cur_page',$cur_page);

	}		
		

}
