<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
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
                    // Create stripe object
                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                    // Create customer for stripe payment. its optional to pass in payment intents
                    // $customer = $stripe->customers->create([
                    //     'email' => $request->email,
                    //     'name'  => $request->name
                    // ]);

                    // create payment intents
                    $charge = $stripe->paymentIntents->create([
                        'amount' => $course->fees * 100,
                        'currency' => 'inr',
                        // 'customer' => $customer->id,
                        'payment_method' => $request->payment_method,
                        'description' => "Payment from ".$request->name.".",
                        'confirm' => true,
                        'receipt_email' => $request->email,
                        'automatic_payment_methods' => [
                            'enabled' => true,
                            'allow_redirects' => 'never',
                        ],
                    ]);

                    // store user and course data and payment intents data into database for reference
                    $enrollcourse = new EnrollCourse();
                    $enrollcourse->course_id            = $course->course_id;
                    $enrollcourse->user_name            = $request->name;
                    $enrollcourse->user_email           = $request->email;
                    $enrollcourse->amount               = $course->fees;
                    $enrollcourse->transaction_id       = $charge->id;
                    $enrollcourse->transaction_details  = $charge;
                    $enrollcourse->save();

                    
                    $to_name = $request->name;
                    $to_email = $request->email;
                    $data = array("name"=>$request->name, "course" => $course->name);
                    $mail = Mail::send("emails.mail", $data, function($message) use ($to_name, $to_email, $course) {
                        $message->to($to_email, $to_name)
                                ->subject("Payment completed");
                        $message->from('admin@admin.com','Admin');
                        $message->cc($course->coach->email, $course->coach->name);
                    });

                    $data['type'] = 'success';
                    $data['caption'] = 'Course enrolled successfully.';
                    $data['payment_status'] = "succeeded";
                    $data['client_secret'] = "";
                    $data['redirectUrl'] = url('/');

                    if ($charge->status == "requires_action") {
                        $data['payment_status'] = $charge->status;
                        $data['client_secret'] = $charge->client_secret;
                    }

                }
                // SINCE IT'S A DECLINE, \Stripe\Error\Card WILL BE CAUGHT
                catch(\Stripe\Error\Card $e) {
                    $data['type'] = 'error';
                    $data['caption'] = 'Card was declined.';
                }
                // SOMETHING ELSE HAPPENED,COMPLETELY UNRELATED TO STRIPE
                catch (Exception $e) {
                    $data['type'] = 'error';
                    $data['caption'] = 'Something else happened, completely unrelated to Stripe.';
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
