@extends('layouts.app')
@section('title', 'Add Department')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox"> 
             <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Add Department</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('departments') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
                    <div class="ibox-content" style="margin-bottom: 50px;">
                        <form method="POST" action="{{ url('/departments') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('departments.form', ['formMode' => 'create'])

                        </form>
                    </div>
                    </div>
                </div>
            </div>
      <!--   </div>
    </div>
    </div> -->
@endsection
