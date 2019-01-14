@extends('layouts.app')
@section('title', 'Diary Register')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <h3><strong>Diary Register Entry</strong></h3>
            </div>                   
            <div class="ibox-content" style="margin-bottom: 50px;">
                <form method="get" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Diary Register No.</label>
                        <div class="col-sm-4">
                            <input type="text" placeholder=" Enter Diary Register No." class="form-control">
                        </div>
                        
                        <label class="col-lg-2 control-label">EAS / File No. </label>
                        <div class="col-lg-4">
                         
                            <input type="text" placeholder=" Enter EAS / File No." class="form-control">
                            
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Vendor Name</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="Enter Vendor Name" class="form-control">
                        </div>
                        
                        <label class="col-lg-2 control-label">Amount to be paid</label>
                        <div class="col-lg-4">
                           <input type="text"  placeholder="Enter Amount to be paid" class="form-control">
                       </div>
                   </div>
                   <div class="hr-line-dashed"></div>
                   <div class="form-group">
                    <label class="col-lg-2 control-label">Date of Order Receiving </label>
                    <div class="col-lg-4">
                        <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input  type="text" class="form-control date_added" placeholder="Date of Order Receiving ">
                    </div>
                    </div>
                    <label class="col-lg-2 control-label">Date of Diary Register Entry</label>
                    <div class="col-lg-4">
                        <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input  type="text" class="form-control date_added" placeholder="Date of Diary Register Entry">
                    </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                
                
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-5">
                      
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>    
            </form>
        </div></div></div>    
    </div>
    @endsection
@section('scripts')
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.date_added').datepicker({
        dateFormat: 'dd-mm-yyyy',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });
 });
</script>             
@endsection            