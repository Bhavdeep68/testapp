@extends('layouts.layout')

@section('pagetitle', ' - Courses')

@section('content')

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card main-card">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-sm-10 col-12">
                                    <h5 class="card-title">Courses</h5>
                                </div>
                                <div class="col-sm-2 col-12 text-right">
                                    <a href="{{ url('/courses/add') }}" class="btn btn-primary btn-sm">Add</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover verticle-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Coach Name</th>
                                            <th>Course Name</th>
                                            <th>Description</th>
                                            <th>Duration</th>
                                            <th>Fees</th>
                                            <th class="width100 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabledata">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right" id="pagingdata" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
@endsection
@section('page-scripts')
<script src="{{ asset('/js/courses.js') }}"></script>
@endsection