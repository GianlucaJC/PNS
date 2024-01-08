<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\art_ana;
use App\Models\prodotti;
use App\Models\utenti;
use App\Models\gspr;
use App\Models\risk;

use DB;
use Mail;



use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ControllerArchivi extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
	}	
	
	public function gspr(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['voce']=$descr_contr;
			DB::table("gspr")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			gspr::where('id', $edit_elem)
			  ->update(['voce' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			gspr::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			gspr::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$gspr=DB::table('gspr')
		->when($view_dele=="0", function ($gspr) {
			return $gspr->where('dele', "=","0");
		})
		->orderBy('voce')->get();

		return view('all_views/gestione/gspr')->with('gspr', $gspr)->with("view_dele",$view_dele);
		
	}	


	public function risk(Request $request){
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$view_dele=$request->input("view_dele");
		$descr_contr=$request->input("descr_contr");
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");


		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			$descr_contr=strtoupper($descr_contr);
			$arr=array();
			$arr['dele']=0;
			$arr['voce']=$descr_contr;
			DB::table("risk")->insert($arr);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			$descr_contr=strtoupper($descr_contr);
			risk::where('id', $edit_elem)
			  ->update(['voce' => $descr_contr]);
		}
		if (strlen($dele_contr)!=0) {
			risk::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			risk::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		
		$risk=DB::table('risk')
		->when($view_dele=="0", function ($risk) {
			return $risk->where('dele', "=","0");
		})
		->orderBy('voce')->get();

		return view('all_views/gestione/risk')->with('risk', $risk)->with("view_dele",$view_dele);
		
	}

	
}
