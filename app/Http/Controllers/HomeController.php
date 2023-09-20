<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\EnrollCourse;
use Stripe;


class HomeController extends BaseController
{
    public function index() {
        $data = [];
        // $data['courses'] = Course::get();
        return view('home', $data);
    }

    /* Load courses data */
    public function load(Request $request) {
        // if ajax request
        if ($request->ajax()) {
            $courses = Course::paginate(10);
            $data['courses'] = $courses;
            return view('ajax.home', $data);
        }
        // if non ajax request
        else {
            return 'No direct access allowed!';
        }
    }

    /* course enroll view code */
    public function view(Course $course) {
        if(!empty($course)) {
            $data['course'] = $course;
            return view('enroll', $data);
        }
        else {
            abort(404);
        }
    }

    /* enroll course */
    public function enroll(Request $request, Course $course) {
        // if ajax request
        if ($request->ajax()) {
            // dd($request);
            $data = [];

            // make validation rules for received data
            $rules = [
                'name'  => 'required',
                'email' => 'required',
            ];

            // validate received data
            $validator = Validator::make($request->all(), $rules);

            // if validation fails
            if ($validator->fails()) {
                $data['type'] = 'error';
                $data['caption'] = 'One or more invalid input found.';
                $data['errorfields'] = $validator->errors()->keys();
                $data['errormessage'] = $validator->errors()->all()[0];
            }
            // if validation success
            else {

                try {

                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                    $customer = $stripe->customers->create([
                        'email' => $request->email,
                        'name'  => $request->name
                    ]);

                    $charge = $stripe->paymentIntents->create([
                        'amount' => $course->fees * 100,
                        'currency' => 'inr',
                        'customer' => $customer->id,
                        'payment_method' => $request->payment_method,
                        'description' => "Payment from ".$request->name.".",
                        'confirm' => true,
                        'receipt_email' => $request->email,
                        // 'automatic_payment_methods' => [
                        //     'enabled' => true,
                        //     'allow_redirects' => 'never',
                        // ],
                        'payment_method_types' => ['card'],
                    ]);

                    dd($charge);
                    
                    $enrollcourse = new EnrollCourse();

                    $enrollcourse->course_id            = $course->course_id;
                    $enrollcourse->user_name            = $request->name;
                    $enrollcourse->user_email           = $request->email;
                    $enrollcourse->amount               = $course->fees;
                    $enrollcourse->transaction_id       = $request->description;
                    $enrollcourse->transaction_details  = $request->description;
                    
                    $result = $enrollcourse->save();
                
                }
                // SINCE IT'S A DECLINE, \Stripe\Error\Card WILL BE CAUGHT
                catch(\Stripe\Error\Card $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Card was declined.',
                        'success_object'        => null
                    ];

                }
                // TOO MANY REQUESTS MADE TO THE API TOO QUICKLY
                catch (\Stripe\Error\RateLimit $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Too many requests made to the API too quickly.',
                        'success_object'        => null
                    ];

                }
                // INVALID PARAMETERS WERE SUPPLIED TO STRIPE'S API
                catch (\Stripe\Error\InvalidRequest $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Invalid parameters were supplied to Stripe\'s API.',
                        'success_object'        => null
                    ];

                }
                // AUTHENTICATION WITH STRIPE'S API FAILED
                // (MAYBE YOU CHANGED API KEYS RECENTLY)
                catch (\Stripe\Error\Authentication $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Authentication with Stripe\'s API failed.',
                        'success_object'        => null
                    ];

                }
                // Network communication with Stripe failed
                // NETWORK PROBLEM, PERHAPS TRY AGAIN.
                catch (\Stripe\Error\ApiConnection $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Network problem.',
                        'success_object'        => null
                    ];

                }
                // DISPLAY A VERY GENERIC ERROR TO THE USER, AND MAY BE SEND YOURSELF AN EMAIL
                // SOMETHING ELSE THAT'S NOT THE CUSTOMER'S FALUT
                catch (\Stripe\Error\Base $e) {
                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Unable to process your request.',
                        'success_object'        => null
                    ];

                }
                // STRIPE'S SERVERS ARE DOWN
                catch (\Stripe\Error\Api $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Stripe\'s servers are down.',
                        'success_object'        => null
                    ];

                }
                // Invalid payload
                catch(\UnexpectedValueException $e) {
                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Invalid payload received',
                        'success_object'        => null
                    ];
                }
                // Invalid signature
                catch(\Stripe\Error\SignatureVerification $e) {
                  $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Invalid payload signature received',
                        'success_object'        => null
                    ];
                }
                // SOMETHING ELSE HAPPENED,COMPLETELY UNRELATED TO STRIPE
                catch (Exception $e) {

                    $result = [
                        'responce_code'         => 1,
                        'responce_description'  => 'Something else happened, completely unrelated to Stripe.',
                        'success_object'        => null
                    ];

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
