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
		$all_data=art_ana::from('art_ana as aa')
		->select("aa.TCTIMESTAMP","aa.COD_ART","aa.DES_ART","aa.COD_CAT","au.TEMPERATURA","au.GGSCAD","au.MINORDCLI")
		->leftjoin('art_user as au','aa.COD_ART','au.COD_ART')
		->where('aa.TCTIMESTAMP','>',$data_import)
		->orderBy('aa.TCTIMESTAMP')
		->get();
		$data_up="?";
		foreach($all_data as $data) {
			$data_up=$data->TCTIMESTAMP;
			$codice=$data->COD_ART;
			$pre=substr($codice,0,1);
			$procedi=false;
			if ($pre=="0" || $pre=="1" || $pre=="2" || $pre=="3" || $pre=="4" || $pre=="5" || $pre=="6" || $pre=="7" || $pre=="8" || $pre=="9") $procedi=true;
			
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
		if ($data_up!="?") {
			$last_ts_target = last_ts_target::find(1);
			$last_ts_target->last_ts=$data_up;
			$last_ts_target->save();
		}
	}
	
	
	public function dashboard(Request $request) {
		$last_ts_target=last_ts_target::where('id','=',1)->get();
		//in caso di prima importazione decidere data fittizia di inizio import
		$data_import="";
		if (isset($last_ts_target[0]))
			$data_import=$last_ts_target[0]->last_ts;
		
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
			if ($request->has("progetto_rd"))
				$db->progetto_rd=$request->input("progetto_rd");
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
		}
		$btn_sign_qa=$request->input("btn_sign_qa");
		if ($btn_sign_qa=="sign") {
			$dx=date("Y-m-d");
			$id_user=Auth::user()->id;
			
			$db = prodotti::find($id);
			$db->sign_qa=$id_user;
			$db->data_qa=$dx;
			$db->save();
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
			if ($signr==4) $sign_ready=true;
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
		
		$elenco_pns=DB::table('prodotti')
		->when($view_dele=="0", function ($elenco_pns) {
			return $elenco_pns->where('dele', "=","0");
		})
		->orderBy('id','desc')->get();

		return view('all_views/pns/elenco_pns')->with("view_dele",$view_dele)->with("elenco_pns",$elenco_pns)->with('arr_utenti',$arr_utenti);

	}		
		

}
