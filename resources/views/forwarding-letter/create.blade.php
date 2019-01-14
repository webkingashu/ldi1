@extends('layouts.app')
@section('title', 'Generate Forwarding Letter')
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
                    <h3 class="col-md-10"><strong> Generate Forwarding Letter</strong></h3>
                       <div class="ibox-tools col-md-2">
                        <a title="Generate Forwarding Letter"><button class="btn btn-primary dim" type="button" id="add-forwarding-letter"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </a>
                        <a href="{{ url('forwarding-letter') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                    </div>   
                </div>
                <div class="ibox-content" id="cheque-form" style="display: none;">
                    <form method="POST" action="{{ url('/forwarding-letter') }}" accept-charset="UTF-8" class="form-horizontal form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <div class="col-md-3">
                                <label for="forwarding_letter_date" class="control-label">{{ 'Date of Issue' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('forwarding_letter_date') ? 'has-error' : ''}}">
                                     <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control date_added" required name="forwarding_letter_date" type="text" id="forwarding_letter_date" placeholder="Date of Issue"  
                                    <?php if(isset($eas_master->forwarding_letter_date ) && !empty($eas_master->forwarding_letter_date)){?> value="{{ $eas_master->date_issue}}" <?php } else{ ?> value="{{old('forwarding_letter_date')}}" <?php } ?> >            
                                    </div> 

                                    
                                    {!! $errors->first('forwarding_letter_date', '<p class="help-block">:message</p>') !!}
                                </div>

                            </div>

                            <div class="col-md-3">
                                <label for="total_amount" class="control-label">{{ 'Amount' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('total_amount') ? 'has-error' : ''}}">
                                    <input class="form-control numberonly" required name="total_amount" type="text" placeholder="Amount" id="total_amount" <?php if(isset($eas_master->total_amount ) && !empty($eas_master->total_amount)){?> value="{{ $eas_master->total_amount}}" <?php } else{ ?> value="{{old('total_amount')}}" <?php } ?> readonly/>
                               
                                   {!! $errors->first('total_amount', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-5">
                                <input type="hidden" aria-hidden="true" name="selected_cheque_id" id="selected_cheque_id">
                                <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:'cheque'}}"/>
                                <input class="btn btn-primary" type="submit"  value="Submit">
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
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Eas Title</th>
                                    <th>Ro Title</th>
                                    <th>Cheque Date</th>
                                    <th>Cheque Number</th>
                                    <th>Name & Designation/Party</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; 
                                ?>
                                @if(isset($cheque_generated) && count($cheque_generated) >0)
                                @foreach($cheque_generated as $item)
                                <?php ?>
                                <tr>
                                    <td>{{ $loop->iteration or $item->id }}</td>
                                     <td>{{ $item->department_name }}</td>
                                    <td>{{ $item->location_name }}</td>
                                    <td>{{ $item->sanction_title }}</td>
                                    <td>{{ $item->ro_title }}</td>
                                    <td>{{ $item->cheque_date }}</td>
                                    <td>{{ $item->cheque_number }}</td>
                                    <td>{{ $item->cheque_name }}</td>
                                    <td class="amount-{{$item->cheque_id}}">{{ $item->cheque_amount }}</td>
                                    
                                    <td>
                                        <input type="checkbox" id="cheque_id" name="cheque_id" value="{{$item->cheque_id}}">
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
        $('#forwarding_letter_date').datepicker({
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
        $('#add-forwarding-letter').click(function() {
            $('input[name="cheque_id"]:checked').each(function() {
               gar_id.push(this.value);
               var amount = $('.amount-'+this.value).html();
               total_amount = parseInt(total_amount) + parseInt(amount);
               // console.log(total_amount);   
            });
            if (gar_id.length > 0) {
                document.getElementById('cheque-form').style.display = 'block'; 
                $('#total_amount').val(total_amount);
                $('#selected_cheque_id').val(gar_id);
                $("#add-forwarding-letter").prop("disabled", true);
                $("#cheque_id").prop("disabled", true);
            } else {
            alert("Please Select atleast one Cheque first.");
            $("#add-forwarding-letter").prop("disabled", false);
            $("#cheque_id").prop("disabled", false);
             }
        });
    });

</script>     
@endsection 
