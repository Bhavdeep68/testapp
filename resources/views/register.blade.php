@extends('layouts.layout')

@section('pagetitle', ' - Register')

@section('content')


    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card main-card">
                        <div class="card-body">

                            <div class="row mb-5">
                                <div class="col-sm-10 col-12">
                                    <h5 class="card-title">Register</h5>
                                </div>
                            </div>


                            {!! Form::open(['url' => url('register'), 'id' => 'formregister', 'files' => true]) !!}
                                    <div class="">

                                        <h5 class="">Please fill in the below fields to register</h5>

                                        <div class="row mt-5">
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                            </div>
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                                            </div>
                                            
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                {!! Form::password('password', ['class' => 'form-control', 'id'=>'password']) !!}
                                            </div>
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                                {!! Form::password('password_confirmation', ['class' => 'form-control', 'id'=>'password_confirmation']) !!}
                                            </div>


                                        </div>
                                        <div class="text-right">
                                            {!! Form::button('Submit', ['id'=>'btnsubmit', 'type' => 'submit', 'class' => 'btn btn-primary ml10']) !!}
                                        </div>
                                    </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('page-scripts')
<script src="{{ asset('/js/register.js') }}"></script>
@endsection