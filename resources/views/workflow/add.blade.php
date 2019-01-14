@extends('layouts.app')
@section('title', 'workflow')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
    	<div class="ibox float-e-margins">
            <div class="ibox-title">
            <h5>Workflow</h5>
            </div> 
        </div>    
    </div>
</div>    
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>          
@endsection    