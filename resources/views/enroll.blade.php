@extends('layouts.layout')

@section('pagetitle', ' - Enroll Course')

@section('content')

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card main-card">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-sm-10 col-12">
                                    <h5 class="card-title">Course</h5>
                                </div>
                            </div>
                            <!-- <div class="row mb-5 justify-content-center"> -->
                            <div class="row mb-5">
                                <div class="col-sm-6 col-12">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover verticle-middle">
                                            <tbody>
                                                <tr>
                                                    <td class="width150">Coach Name : </td>
                                                    <td>{{$course->coach->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="width150">Course Name : </td>
                                                    <td>{{$course->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="width150">Description : </td>
                                                    <td>{{$course->duration}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="width150">Duration : </td>
                                                    <td>{{$course->fees}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="width150">Fees : </td>
                                                    <td>{{$course->description}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    {!! Form::open(['url' => url('/enroll/'.$course->course_id), 'class' => 'tooltip-right-bottom', 'id' => 'formenroll']) !!}
                                        {!! Form::hidden('payment_method', null, ['id' => 'payment_method']) !!}
                                        {!! Form::hidden('stripekey', env('STRIPE_KEY'), ['id' => 'stripekey']) !!}
                                        {!! Form::hidden('course_id', $course->course_id, ['id' => 'course_id']) !!}
                                        {!! Form::hidden('fees', $course->fees, ['id' => 'fees']) !!}
                                        
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Name']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            {!! Form::text('email', '', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Email']) !!}
                                        </div>
                                        <div class="form-group">
                                            <div id="card"></div>
                                        </div>
                                        <button class="btn btn-lg btn-primary btn-block text-uppercase enroll-btn" type="button">Enroll Now</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
@endsection
@section('page-scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('/js/enroll.js') }}"></script>
@endsection