<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Exports\ExportParco;


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
	
	public function dashboard(Request $request) {
		//$this->test_mail();
		/*
		if ($request->has("btn_save")) {
			$mail_parco=$request->input('mail_parco');
			
			$count=DB::table('set_global')->where('id','=',1)->count();
			if ($count==0)
				$set_global = new set_global;
			else
				$set_global = set_global::find(1);
					
			$set_global->email_parco = $request->input('email_parco');
			$set_global->email_acquisti = $request->input('email_acquisti');
			$set_global->save();
		}
		*/

		
		return view('all_views/dashboard');
		
	}


}
