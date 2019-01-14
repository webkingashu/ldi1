@extends('layouts.app')
@section('title', 'Add Bank Reconsilation')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
          <div class="ibox-title col-md-12 display-flex">
            <h3 class="col-md-10"><strong>Add Bank Reconciliation</strong></h3>
            <div class="ibox-tools col-md-2">
                <!-- <a href="{{ url('purchase-order') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a> -->
            </div>
          </div>
           
           <!--   <?php //dd($workflow_mapping); ?> -->
           
           <div class="ibox-content" style="margin-bottom: 50px;">
            <form method="POST" action="{{ url('bank-reconciliation') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                {{ csrf_field() }}
              <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                <div class= "col-sm-6">
                      <label for="documents">Upload CSV<span class="e-color">*</span></label>
                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                           <i class="glyphicon glyphicon-file fileinput-exists"></i>
                           <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">Select file</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="csv_file" />
                        </span>
                     </div>
                     <span class="errorshow" style="display:none;color:#F22613;"></span>
                   </div>  
                     @if ($errors->has('csv_file'))
                        <span class="help-block">
                        <strong>{{ $errors->first('csv_file') }}</strong>
                     @endif
              </div>  
              <div class="form-group">
               <div class="col-sm-4 col-sm-offset-5"> 
                  <input class="btn btn-primary" type="submit"  value="Create"> 
                  <input class="btn btn-primary" type="reset"  value="Cancel"> 
               </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
 <script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>   
@endsection 
