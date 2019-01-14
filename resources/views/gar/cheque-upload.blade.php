@extends('layouts.app')
@section('title', 'Generate Cheque')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/sweetalert.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong> Generate Cheque</strong></h3>
                    
                    <div class="ibox-tools col-md-2">
                        <a title="Generate Cheque"><button class="btn btn-primary dim" type="button" id="add-cheque"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                        <a href="{{ url('list-cheque') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                    </div>   
                </div>
                <div class="ibox-content" id="cheque-form" style="display: none;">
                    <form method="POST" action="{{ url('/create-cheque') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <div class="col-md-3">
                                <label for="cheque_name" class="control-label">{{ 'Name on Cheque' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('name_on_cheque') ? 'has-error' : ''}}">
                                    <input class="form-control textnumberonly" required name="cheque_name" type="text" placeholder="Name on Cheque" id="name_on_cheque" <?php if(isset($eas_master->cheque_name ) && !empty($eas_master->cheque_name)){?> value="{{ $eas_master->name_on_cheque}}" <?php } else{ ?> value="{{old('cheque_name')}}" <?php } ?> />
                               
                                   {!! $errors->first('cheque_name', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="cheque_number" class="control-label">{{ 'Cheque Number' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('cheque_number') ? 'has-error' : ''}}">
                                    <input class="form-control textnumberonly" required name="cheque_number" type="text" placeholder="Cheque Number" id="cheque_number" <?php if(isset($eas_master->cheque_number ) && !empty($eas_master->cheque_number)){?> value="{{ $eas_master->cheque_number}}" <?php } else{ ?> value="{{old('cheque_number')}}" <?php } ?> />
                               
                                   {!! $errors->first('cheque_number', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-md-3">

                                <label for="cheque_date" class="control-label">{{ 'Date of Issue' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('cheque_date') ? 'has-error' : ''}}">
                                     <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control date_added" required name="cheque_date" type="text" id="cheque_date" placeholder="Date of Issue"  
                                    <?php if(isset($eas_master->cheque_date ) && !empty($eas_master->cheque_date)){?> value="{{ $eas_master->date_issue}}" <?php } else{ ?> value="{{old('cheque_date')}}" <?php } ?> >            
                                    </div> 

                                    
                                    {!! $errors->first('date_issue', '<p class="help-block">:message</p>') !!}
                                </div>

                            </div>

                            <div class="col-md-3">
                                <label for="cheque_amount" class="control-label">{{ 'Amount' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('cheque_amount') ? 'has-error' : ''}}">
                                    <input class="form-control numberonly" required name="cheque_amount" type="text" placeholder="Amount" id="cheque_amount" <?php if(isset($eas_master->cheque_amount ) && !empty($eas_master->cheque_amount)){?> value="{{ $eas_master->cheque_amount}}" <?php } else{ ?> value="{{old('cheque_amount')}}" <?php } ?> readonly/>
                               
                                   {!! $errors->first('cheque_amount', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <label for="documents"  id="user_img" id="cheque_upload">Upload Cheque<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('cheque_amount') ? 'has-error' : ''}}">
                                    <div class="fileinput fileinput-new input-group control-group after-add-doc error-doc-msg" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                           <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                           <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file"  id="file_uploads" name="file_upload" onchange="show(this)" required/>

                                        </span>                                    
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                    <span style="color: red;">Note: Only images/pdf/doc file is allowed to be uploaded.</span><br>
                                    <span class="errorshow" style="display:none;color:#F22613;"></span>
                                </div>
                                {!! $errors->first('file_upload', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-5">
                                <input type="hidden" aria-hidden="true" name="selected_gar_id" id="selected_gar_id">
                                <input class="btn btn-primary" type="submit"  value="Submit">
                                <input class="btn btn-primary" type="reset"  value="Cancel" onclick="return location.reload();">
                            </div>
                        </div>
                   </form>
                </div>
                <div class="ibox-content scroll-hide">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Title</th>
                                    <th>Amount to be Paid</th>
                                    <th>Actual Payment Amount</th>
                                    <th>Vendor Name</th>
                                    <th>Status</th>
                                    <th >Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                ?>
                                @if(isset($gar_list) && count($gar_list) >0)
                                @foreach($gar_list as $item)
                                <?php ?>
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                    <td>{{ $item->ro_title }}</td>
                                    <td>{{ $item->amount_paid }}</td>
                                    <td class="amount-{{$item->gar_id}}">{{ $item->actual_payment_amount }}</td>
                                    <td>{{ $item->vendor_name }}</td>
                                    <!-- <td>{{ $item->status_name }}</td> -->
                                    <td>@if($item->status_name == "Approved" || $item->status_name == "DDO Approved" || $item->status_name == "PAO Approved" || $item->status_name == "Forwarding Letter Generated")
                                       <span class="label label-primary"> {{ $item->status_name }} </span>
                                       @elseif ($item->status_name == "Pending Review")
                                       <span class="label label-warning">  {{ $item->status_name}}</span>
                                       @elseif ($item->status_name == "Draft" || $item->status_name == "Dispatch And Tally Entry Done" || $item->status_name == "Cheque Uploaded" )
                                       <span class="label label-info">{{ $item->status_name}}</span>
                                       @elseif ($item->status_name == "Return" || $item->status_name == "DDO Return" || $item->status_name == "PAO Return")
                                       <span class="label label-danger">{{ $item->status_name}}</span>
                                       @else
                                       <span class="label">{{ $item->status_name }}</span>
                                       @endif
                                    </td>
                                    <td >
                                        <input class="checkbox-action" type="checkbox" name="gar_id" value="{{$item->gar_id}}">
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/sweetalert/sweetalert.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script>
    var url = '{{url("/")}}/get-gar-details';
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                        ]
        });
        $('#cheque_date').datepicker({
            format: 'dd/mm/yyyy',
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true
        }).attr('readonly', 'readonly')
        .datepicker("setDate", new Date());
        var gar_id = [];
        var total_amount = 0;
        $('#add-cheque').click(function() {
            $(".checkbox-action").prop("disabled", true);
            $("#add-cheque").prop("disabled", true);
            $('input[name="gar_id"]:checked').each(function() {
               gar_id.push(this.value);
               var amount = $('.amount-'+this.value).html();
               total_amount = parseInt(total_amount) + parseInt(amount);
               // console.log(total_amount);   
            });
          if (gar_id.length > 0) {
                document.getElementById('cheque-form').style.display = 'block'; 
                $('#cheque_amount').val(total_amount);
                $('#selected_gar_id').val(gar_id);
            } else {
                alert("Please Select GARs.");
                $(".checkbox-action").prop("disabled", false);
                $("#add-cheque").prop("disabled", false);
            }
        });
    });

</script>     
@endsection 
