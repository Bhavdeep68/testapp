<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class LogoutController extends BaseController {

	public function logout(){
		Auth::guard('user')->logout();
		return redirect('/');
	}

}

