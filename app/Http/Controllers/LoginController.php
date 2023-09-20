<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
	
    public function login() {
    	$data = [];
		$data['menu_login'] = true;

		return view('login', $data);
	}

	public function register() {
		$data = [];
		$data['menu_register'] = true;

		return view('register', $data);
	}

	public function submit(Request $request) {
		// if ajax request
		if($request->ajax()) {
			// dd($request);
			$rules = [
					'email'   	=> 'required|email',
					'password'  => 'required'
			];

			$validator = Validator::make($request->all(), $rules);

			if($validator->fails()) {
				$errors = $validator->errors()->all();
				$data['type'] = 'error';
				$data['caption'] = 'One or more invalid input found.';
				$data['errorfields'] = $validator->errors()->keys();
			}
			else {
				$email = trim($request->email);
				$password = $request->password;

				$remember = 0;
				if($request->remember){
					$remember = 1;
				}
				
				if(Auth::guard('user')->attempt(['email' => $email,'password' => $password], $remember)) {
					$data['type'] = 'success';
					$data['caption'] = 'Logged in successfully.';
					$data['redirectUrl'] = url('/');
				}
				else {
					$data['type'] = 'error';
					$data['caption'] = 'Invalid email address or password.';
				}

			}

			return response()->json($data);
		}
		else{
			return 'No direct access allowed!';
		}
	}

	public function submitregister(Request $request) {
		// dd($request);
        // if ajax request
        if ($request->ajax()) {
            $data = [];
            
            $email  = trim($request->email);

            // make validation rules for received data
            $rules = [
                    'name'      => 'required',
                    'email'         => 'required|email|unique:users',
                    'password'       => 'required|confirmed',
            ];


            $message = [
	            'email.unique' => 'Email address is already in use. Please try different email address.'
	        ];

            // validate received data
            $validator = Validator::make($request->all(), $rules, $message);

            // if validation fails
            if ($validator->fails()) {
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();
                $data['errormessage'] = $validator->errors()->all()[0];
            }
            // if validation success
            else {
            	
            	$user = new User();

                $user->name     = $request->name;
                $user->email    = trim($request->email);

                $password 		= $request->password;
                $user->password = Hash::make($password);

                $result = $user->save();
                
                $captionsuccess = 'Registration successful.';
                $captionerror = 'Unable register. Please try again.';

                // database insert/update success
                if($result) {
                    $data["type"] = "success";
                    $data['caption'] = $captionsuccess;
                    $data['redirectUrl'] = url('/login');

                }
                // database insert/update fail
                else {
                    $data['type'] = 'error';
                    $data['caption'] = $captionerror;
                }
            }

            return response()->json($data);

        }
        // if non ajax request
        else {
            return 'No direct access allowed!';
        }
    }
}
