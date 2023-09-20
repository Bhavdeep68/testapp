<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;


class CourseController extends BaseController {


    public function index() {
		$data = [];
		$data['menu_course'] = true;

		return view('courses.index', $data);
	}

    /* Load courses data */
    public function load(Request $request) {
        // if ajax request
        if ($request->ajax()) {
            $courses = Course::paginate(10);
            $data['courses'] = $courses;
            return view('courses.ajax.index', $data);
        }
        // if non ajax request
        else {
            return 'No direct access allowed!';
        }
    }

    /* course add page */
    public function add() {
        $data = ['menu_course' => true];

        $course = new Course();
        $data['course'] = $course;

        return view('courses.course', $data);
    }

    /* course edit code */
    public function edit(Course $course) {
        if(!empty($course)) {
            $data = ['menu_course' => true];

            $data['course'] = $course;
            return view('courses.course', $data);
        }
        else {
            abort(404);
        }
    }

    /* course add / update code */
    public function store(Request $request) {
        // if ajax request
        if ($request->ajax()) {
            // dd($request);
            $data = [];
            $course_id = intval($request->course_id);

            // make validation rules for received data
            $rules = [
                'name'      => 'required',
                'fees'      => 'required',
                'duration'  => 'required',
            ];

            $course = ($course_id == 0) ? new Course() : Course::find($course_id);

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
                $user = Auth::guard('user')->user();
                $user_id = $user->user_id;

                $course->user_id        = $user_id;
                $course->name           = $request->name;
                $course->fees           = $request->fees;
                $course->duration       = $request->duration;
                $course->description    = $request->description;

                // add
                if($course_id == 0) {
                    $result = $course->save();
                    $captionsuccess = 'Course added successfully.';
                    $captionerror = 'Unable add course. Please try again.';
                }
                // edit
                else {
                    $result = $course->update();
                    $captionsuccess = 'Course updated successfully.';
                    $captionerror = 'Unable update course. Please try again.';
                }

                // database insert/update success
                if($result) {
                    $data["type"] = "success";
                    $data['caption'] = $captionsuccess;
                    $data['redirectUrl'] = url('/courses');

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

    //course delete code
    public function destroy(Request $request) {

        // if ajax request
        if ($request->ajax()) {

            $data = [];
            $course_id = intval($request->course_id);
            $course = Course::find($course_id);

            if(!empty($course)) {
                if($course->delete()) {
                    $data['type'] = 'success';
                    $data['caption'] = 'Course deleted successfully.';
                }
                else {
                    $data['type'] = 'error';
                    $data['caption'] = 'Unable to delete course.';
                }
            }
            else {
                $data['type'] = 'error';
                $data['caption'] = 'Invalid course.';
            }
            return response()->json($data);
        }
        // if non ajax request
        else {
            return 'No direct access allowed!';
        }
    }

    
}
