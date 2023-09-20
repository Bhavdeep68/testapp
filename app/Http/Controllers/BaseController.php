<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use View;

class BaseController extends Controller
{
    protected $globaldata = [];

    public function __construct() {
    	if(Auth::guard('user')->check()) {
    		$user = Auth::guard('user')->user();
    		$this->globaldata['user'] = $user;
    		View::share('globaldata', $this->globaldata);
    	}
    }
}
