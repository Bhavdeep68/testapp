@extends('layouts.layout')

@section('pagetitle', ' - Course')

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


                            {!! Form::model($course, ['url' => url('courses/store'), 'id' => 'formcourse', 'files' => true]) !!}
                                {!! Form::hidden('course_id', null, ['id' => 'course_id']) !!}
                                

                                    <div class="">

                                        <h5 class="">Please modify the details below</h5>

                                        <div class="row">
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                                            </div>
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="fees">Fees <span class="text-danger">*</span></label>
                                                {!! Form::text('fees', null, ['class' => 'form-control', 'id' => 'fees']) !!}
                                            </div>
                                            <div class='form-group col-lg-6 col-xs-12'>
                                                <label for="duration">Duration <span class="text-danger">*</span></label>
                                                {!! Form::text('duration', null, ['class' => 'form-control', 'id' => 'duration']) !!}
                                            </div>
                                             <div class="form-group col-lg-6 col-xs-12">
                                                <label for="description">Description</label>
                                                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => '1']) !!}
                                            </div>
                                            
                                        </div>

                                        <div class="text-right mt-5">
                                            <a href="{{url('/courses')}}" class="btn btn-default">Cancel</a>
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
<script src="{{ asset('/js/course.js') }}"></script>
@endsection