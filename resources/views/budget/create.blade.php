@extends('layouts.app')
@section('title', 'Budget')
@section('css')
<link rel="stylesheet" href="{!! asset('css/sweetalert.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
          <div class="ibox-title col-md-12 display-flex">
            <h3 class="col-md-10"><strong> Add Budget</strong></h3>
            <div class="ibox-tools col-md-2">
                <a href="{{ url('budget') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
        </div>
        <div class="ibox-content" style="margin-bottom: 80px;">
            <form method="POST" action="{{ url('/budget') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group ">
                    <div class="col-sm-4">
                        <div class="{{ $errors->has('functional_wing') ? 'has-error' : ''}}">
                            <label for="functional_wing" class="control-label">{{ 'Functional Wing' }}</label>
                            <select class="form-control chosen-select" style="width:350px;" name="functional_wing" id="functional_wing">
                                <option selected="selected">Select Functional Wing</option>
                                @if(isset($departments) && !empty($departments)) 
                                @foreach ($departments as $name)
                                <?php //dd($departments);exit;?>
                                <option value="{{ $name->id }}">{{ $name->name }}</option>
                                @endforeach 
                                @endif   
                                <i class="fa fa-plus" aria-hidden="true"></i></select>
                                {!! $errors->first('functional_wing', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class=" {{ $errors->has('budget_code') ? 'has-error' : ''}}">
                                <label for="budget_code" class="control-label">{{ 'Budget Code' }}</label>
                                <input class="form-control" style="width:350px;" name="budget_code" type="text" id="budget_code" value="{{ $budget->budget_code or ''}}" required>
                                {!! $errors->first('budget_code', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="{{ $errors->has('budget_head') ? 'has-error' : ''}}">
                                <label for="budget_head" class="control-label">{{ 'Budget Head' }}</label>
                                <input class="form-control" style="width:350px;" name="budget_head" type="text" id="budget_head" value="{{ $budget->budget_head or ''}}" required>
                                {!! $errors->first('budget_head', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-sm-4">
                            <div class="{{ $errors->has('amount') ? 'has-error' : ''}}">
                                <label for="amount" class="control-label">{{ 'Amount' }}</label>
                                <input class="form-control" style="width:350px;" name="amount" type="text" id="amount" value="{{ $budget->amount or ''}}" required>
                                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="{{ $errors->has('year') ? 'has-error' : ''}}">
                                <label for="year" class="control-label">{{ 'Year' }}</label>
                                <input class="form-control" style="width:350px;" name="year" type="text" id="year" value="{{ $budget->year or ''}}" required>
                                {!! $errors->first('year', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-4 col-sm-offset-5"> 
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/sweetalert/sweetalert.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script type="text/javascript">
$('.chosen-select').chosen({width: "100%"});
</script>
@endsection
