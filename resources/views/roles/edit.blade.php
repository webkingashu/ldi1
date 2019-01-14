@extends('layouts.app')
@section('title', 'edit Role')
@section('css')
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Edit Role</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/roles') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div> 
<!-- 
               @if ($errors->any())
               <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif -->
            <div class="ibox-content" style="margin-bottom: 50px;">
                        <form method="POST" action="{{ url('/roles/' . $role->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('roles.form', ['formMode' => 'edit'])
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script type="text/javascript">
   $('.chosen-select').chosen({width: "100%"});
</script>
@endsection 
