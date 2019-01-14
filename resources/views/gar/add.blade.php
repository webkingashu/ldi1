@extends('layouts.app')
@section('title', 'GAR')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/iCheck/custom.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
@section('content')
<?php //dd($gar_details);
?>
 <?php
if (isset($gar_details->id) && !empty($gar_details->id)) {
    $id = $gar_details->id;
}
if (isset($is_update_url) && $is_update_url == 1 && isset($id)) {
    $url    = '/gar/' . $id;
    $action = 'POST';
    
} else {
    $url    = 'gar/';
    $action = 'POST';
}
?>  
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex">
             @if(isset($is_update_url) && !empty($is_update_url) && $is_update_url == 1) <h3 class="col-md-10"><strong> Edit GAR</strong></h3>
             <div class="ibox-tools col-md-2">
                <a href="{{ url('/gar/' .$id) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
             @else 
              <h3 class="col-md-10"><strong> Add GAR</strong></h3>
             <div class="ibox-tools col-md-2">
                <a href="{{ url('gar') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
            @endif
        </div> 
        <div class="ibox-title col-md-12 display-flex">
              <ul class="breadcrumb">
                @if(Session::get('eas_id'))
                @section('breadcrumbs')
                  <li><a class="breadcrumb-item" href="{{ url('/eas/'. Session::get('eas_id')) }}">{{ Session::get('eas_title') }}</a></li>
                @show
                @endif
                  <li><a class="breadcrumb-item" href="{{ url('/gar') }}">GAR</a></li>
                  <li><a class="breadcrumb-item">Add GAR</a></li>
              </ul>
        </div>   
                    
          <div class="ibox-content" style="margin-bottom: 50px;">
             <form method="<?php
echo $action;
?>" class="form-horizontal" action="{{url($url)}}">
                {{csrf_field()}}
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                <input type="hidden" name="entity_id" value="{{isset($entity_details->id)?$entity_details->id:''}}"/>
                <input type="hidden" name="workflow_id" value="{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}"/>
                <input type="hidden" name="entity_slug" value="{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}"/>
                <div class="form-group">
                    <div class="col-md-3 col-sm-6">
                        <label class="control-label">Select the RO<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('ro_id') ? ' has-error' : '' }}">
                            <select class="form-control chosen-select" name="ro_id" placeholder="Select the Release Order" id="ro_id" required>     
                                <option disabled="disabled" selected="selected">Select the Release Order</option>
                                @if(isset($ro) && count($ro) > 0)
                                @foreach($ro as $value) 
                                 <option <?php
                                    if (isset($value->id) && isset($gar_details->ro_id) && $gar_details->ro_id == $value->id) {
                                        echo 'selected';
                                    }
                                    ?>  @if(old('ro_id') == $value->id) selected @endif value="{{$value->id}}">{{$value->ro_title}}</option>
                                                                    @endforeach
                                                                    @endif
                                                                    @if(isset($gar_details) && isset($gar_details->ro_id) && !empty($gar_details->ro_id) && isset($gar_details->ro_title) && !empty($gar_details->ro_title))
                                                                     <option <?php
                                    if (isset($gar_details->ro_id) && $gar_details->ro_id) {
                                        echo 'selected';
                                    }
                                    ?>  value="{{$gar_details->ro_id}}">{{$gar_details->ro_title}}</option>
                                @endif 
                            </select>
                           @if ($errors->has('ro_id'))
                           <span class="help-block"><strong>{{ $errors->first('ro_id') }}</strong>
                           </span>
                           @endif
                       </div>
                   </div>
                   <div class="col-md-3 col-sm-6">  
                    <label class="control-label">Release Order amount<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('release_order_amount') ? ' has-error' : '' }}">
                        <input type="text"  name="release_order_amount" placeholder="Release Order amount" class="form-control" readonly @if(isset($gar_details->release_order_amount) && !empty($gar_details->release_order_amount)) value="{{$gar_details->release_order_amount}}" @else {{old('release_order_amount')}} @endif >
                        @if ($errors->has('release_order_amount'))
                        <span class="help-block"><strong>{{ $errors->first('release_order_amount') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">  
                        <label class="control-label">EAS Amount</label>
                        <div class="{{ $errors->has('sanction_total') ? ' has-error' : '' }}">
                            <input type="text" id="sanction_total" name="sanction_total" placeholder="EAS Amount" class="form-control" readonly @if(isset($gar_details->sanction_total) && !empty($gar_details->sanction_total)) value="{{$gar_details->sanction_total}}" @else {{old('sanction_total')}} @endif >
                            @if ($errors->has('sanction_total'))
                            <span class="help-block"><strong>{{ $errors->first('sanction_total') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div> 
                <div class="col-md-3 col-sm-6">
                    <label class="control-label">Amount Used till Date<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('amount_used_till_date') ? ' has-error' : '' }}">    
                        <input type="hidden" id="eas_total" @if(isset($eas_total) && !empty($eas_total)) value="{{$eas_total}}" @else value="0" @endif>
                        <input type="text" id="amount_used_till_date" placeholder="Amount Used till Date (including this)" class="form-control decimal" name="amount_used_till_date" readonly @if(isset($gar_details->amount_used_till_date) && !empty($gar_details->amount_used_till_date)) value="{{$gar_details->amount_used_till_date}}" @endif>
                        @if ($errors->has('amount_used_till_date'))
                        <span class="help-block"><strong>{{ $errors->first('amount_used_till_date') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @if(isset($is_update_url) && $is_update_url == 1)
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-3 col-sm-6">
                    <label for="copy_to" class="control-label">Diary Register Entry<span class="e-color">*</span></label><?php //dd($gar_details->is_diary_register);
?>
                   <div class="{{ $errors->has('is_diary_register') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            @if(isset($gar_details->is_diary_register) && $gar_details->is_diary_register == '1') 
                            <input name="is_diary_register" type="hidden" value="1">@endif
                            <input name="is_diary_register" type="checkbox" value="1"  id="is_diary_register"
                            @if(isset($gar_details->is_diary_register) && $gar_details->is_diary_register == '1') checked disabled @endif     
                            @if(old('is_diary_register') == '1') checked @endif> 
                        </label> 

                        @if ($errors->has('is_diary_register'))
                        <span class="help-block"><strong>{{ $errors->first('is_diary_register') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label for="copy_to" class="control-label">GAR Register Entry<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('gar_register_entry') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            @if(isset($gar_details->gar_register_entry) && $gar_details->gar_register_entry == '1') 
                            <input name="gar_register_entry" type="hidden" value="1">@endif
                            <input name="gar_register_entry" type="checkbox" value="1"  id="is_gar_register"
                            @if(isset($gar_details->gar_register_entry) && $gar_details->gar_register_entry == '1') checked  disabled @endif    
                            @if(old('gar_register_entry') == '1') checked @endif> 
                        </label> 

                        @if ($errors->has('gar_register_entry'))
                        <span class="help-block"><strong>{{ $errors->first('gar_register_entry') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label for="copy_to" class="control-label">ECR Entry<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('is_ecr_entry') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            @if(isset($gar_details->is_ecr_entry) && $gar_details->is_ecr_entry == '1') 
                            <input name="is_ecr_entry" type="hidden" value="1">@endif
                            <input name="is_ecr_entry" type="checkbox" value="1"
                            @if(isset($gar_details->is_ecr_entry) && $gar_details->is_ecr_entry == '1') checked disabled  @endif    
                            @if(old('is_ecr_entry') == '1') checked @endif> 
                        </label> 

                        @if ($errors->has('is_ecr_entry'))
                        <span class="help-block"><strong>{{ $errors->first('is_ecr_entry') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                @if(isset($gar_details->status_id) && ($gar_details->status_id) == 26)
                <div class="col-md-2 col-sm-6">
                    <label for="copy_to" class="control-label">Dispatch Register Entry<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('is_dispatch_register') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            <input name="is_dispatch_register" type="checkbox" value="1"  id="is_dispatch_register" @if(isset($gar_details->is_dispatch_register) && $gar_details->is_dispatch_register == '1') checked  @endif  @if(isset($is_update_url) && $is_update_url == 1 && isset($gar_details->is_dispatch_register) && $gar_details->is_dispatch_register == '1')  disabled  @endif
                            @if(old('is_dispatch_register') == '1') checked @endif> 
                        </label> 
                        @if ($errors->has('is_dispatch_register'))
                        <span class="help-block"><strong>{{ $errors->first('is_dispatch_register') }}</strong>
                        </span>
                        @endif
                    </div>

                </div>  
                <div class="col-md-2 col-sm-6">
                    <label for="copy_to" class="control-label">Tally Entry<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('tally_entry') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            <input name="tally_entry" type="checkbox" value="1"  id="is_tally_entry" @if(isset($gar_details->tally_entry) && $gar_details->tally_entry == '1') checked disabled @endif 
                            @if(isset($is_update_url) && $is_update_url == 1 && isset($gar_details->tally_entry) && $gar_details->tally_entry == '1')  disabled  @endif
                            @if(old('tally_entry') == '1') checked @endif> 
                        </label> 
                        @if ($errors->has('tally_entry'))
                        <span class="help-block"><strong>{{ $errors->first('tally_entry') }}</strong>
                        </span>
                        @endif
                    </div>
                </div> 
                @endif  
            </div>  
            @endif

        <div class="hr-line-dashed"></div>
        <h4 class="text-center padding-bottom-20">Vendor Details</h4>
        <div class="form-group">
           <input type="hidden" name="vendor_id" id="vendor_id" placeholder="Vendor ID" class="form-control vendor_id" @if(isset($gar_details->vendor_id) && !empty($gar_details->vendor_id)) value="{{$gar_details->vendor_id}}" @endif>
           <div class="col-md-3 col-sm-6">
            <label class="control-label">Vendor Name<span class="e-color">*</span></label>
            <input type="text" name="vendor_name" placeholder="Vendor Name" class="form-control vendor_name" readonly="" @if(isset($gar_details->vendor_name) && !empty($gar_details->vendor_name)) value="{{$gar_details->vendor_name}}" @endif>
        </div>
        <div class="col-md-3 col-sm-6">
            <label class="control-label">Vendor Contact Number<span class="e-color">*</span></label>
            <input type="text" id="vendor_contact" placeholder="Contact Number" class="form-control" readonly @if(isset($gar_details->mobile_no) && !empty($gar_details->mobile_no)) value="{{$gar_details->mobile_no}}" @endif>
        </div>
        <div class="col-md-3 col-sm-6">
            <label class="control-label">Bank Name & Branch<span class="e-color">*</span></label>
            <input type="text" id="bank_name"  placeholder="Bank Name - Branch" class="form-control" readonly @if(isset($gar_details->bank_name) && !empty($gar_details->bank_name) && !empty($gar_details->bank_branch)) value="{{$gar_details->bank_name}}-{{$gar_details->bank_branch}}" @endif>   
        </div>   
        <div class="col-md-3 col-sm-6">   
            <label class="control-label">IFSC Code<span class="e-color">*</span></label>
            <input type="text" id="ifsc" placeholder="IFSC Code" class="form-control" readonly @if(isset($gar_details->ifsc_code) && !empty($gar_details->ifsc_code)) value="{{$gar_details->ifsc_code}}" @endif>
        </div>   
    </div>
    <div class="form-group">
        <div class="col-md-3 col-sm-6"> 
            <label class="control-label">Bank & Branch Code<span class="e-color">*</span></label>
            <input type="text" name="bank_code" id="branch_code"  placeholder="Bank Code" class="form-control" readonly @if(isset($gar_details->bank_code) && !empty($gar_details->bank_code)) value="{{$gar_details->bank_code}}" @endif>
        </div>   
              <!--   <div class="col-sm-3"> 
                    <label class="control-label">Title of the Account<span class="e-color">*</span></label>
                    <input type="text" id="title_of_account" name="title_of_amount"  placeholder="Title of Account" class="form-control" readonly>
                </div> -->
                <div class="col-md-3 col-sm-6"> 
                    <label class="control-label">Current/ Cash Credit Account No.<span class="e-color">*</span></label>
                    <input type="text" name="bank_acc_no" id="account_no" placeholder="Acount Number" class="form-control textnumberonly" readonly @if(isset($gar_details->bank_acc_no) && !empty($gar_details->bank_acc_no)) value="{{$gar_details->bank_acc_no}}" @endif>
                </div> 
                <div class="col-md-3 col-sm-6"> 
                    <label class="control-label">Budget Code<span class="e-color">*</span> </label>
                    <input type="text" id="budget_code" placeholder="Budget Code " class="form-control" readonly @if(isset($gar_details->budget_code) && !empty($gar_details->budget_code)) value="{{$gar_details->budget_code}}" @endif>
                </div> 
                <div class="col-md-3 col-sm-6"> 
                    <label class="control-label">Budget Head Amount<span class="e-color">*</span> </label>
                    <input type="text" name="budget_head_amount" id="budget_head_amount" placeholder="Budget Head Amount" class="form-control" readonly @if(isset($gar_details->amount) && !empty($gar_details->amount)) value="{{$gar_details->amount}}" @endif>
                </div>                           
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">  
                
                <div class="col-md-3 col-sm-6">  
                    <label class="control-label">Select GAR Bill Type<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('gar_bill_type') ? ' has-error' : '' }}">
                        <select class="form-control chosen-select" name="gar_bill_type" id="gar_bill_type" required>
                            <option disabled="disabled" selected="selected">Select GAR Bill Type</option>
                            @if(isset($gar_bill_type) && count($gar_bill_type) > 0)
                            @foreach($gar_bill_type as $value) 
                            <option <?php
if (isset($value->id) && isset($gar_details->gar_bill_type) && $gar_details->gar_bill_type == $value->id) {
    echo 'selected';
}
?> @if(old('gar_bill_type') == $value->id) selected @endif value="{{$value->id}}">{{$value->gar_bill_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        @if ($errors->has('gar_bill_type'))
                        <span class="help-block"><strong>{{ $errors->first('gar_bill_type') }}</strong>
                        </span>
                        @endif
                    </div>    

                </div>
               
            </div>   
                       <div class="hr-line-dashed"></div>
                    <div class="form-group">
                    <div class="col-md-3 col-sm-6">
                        <label class="control-label">Amount to be Paid<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('amount_to_be_paid') ? ' has-error' : '' }}">    
                            <input type="text" readonly id="amount_paid" name="amount_to_be_paid" placeholder="Amount to be Paid" class="form-control" @if(isset($gar_details->amount_to_be_paid) && !empty($gar_details->amount_to_be_paid)) value="{{$gar_details->amount_to_be_paid}}" @else  value="{{old('amount_to_be_paid')}}" @endif >
                            @if ($errors->has('amount_to_be_paid'))
                            <span class="help-block"><strong>{{ $errors->first('amount_to_be_paid') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="control-label">GST Amount<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('gst_amount') ? ' has-error' : '' }}">  
                            <input type="text" name="gst_amount" id="gst_amount" placeholder="GST to be Deducted" class="form-control" @if(isset($gar_details->gst_amount) && !empty($gar_details->gst_amount)) value="{{$gar_details->gst_amount}}" @else value="{{old('gst_amount','0')}}" @endif >
                            @if ($errors->has('gst_amount'))
                            <span class="help-block"><strong>{{ $errors->first('gst_amount') }}</strong>
                            </span>
                            @endif
                        </div>  
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="control-label">Deducted GST</label>
                        <div class="{{ $errors->has('deducted_gst') ? ' has-error' : '' }}">  
                            <input type="text" name="deducted_gst" id="deducted_gst" placeholder="GST to be Deducted" class="form-control" readonly @if(isset($gar_details->deducted_gst) && !empty($gar_details->deducted_gst)) value="{{$gar_details->deducted_gst}}" @else value="{{old('deducted_gst')}}" @endif >
                            @if ($errors->has('deducted_gst'))
                            <span class="help-block"><strong>{{ $errors->first('deducted_gst') }}</strong>
                            </span>
                            @endif
                        </div> 
                    </div> 
                    <div class="col-md-3 col-sm-6">
                       <label class="control-label">TDS Amount<span class="e-color">*</span></label>
                       <div class="{{ $errors->has('tds_amount') ? ' has-error' : '' }}">   
                        <input type="text" id="tds_amount" name="tds_amount" placeholder="TDS Amount" class="form-control"  @if(isset($gar_details->tds_amount) && !empty($gar_details->tds_amount)) value="{{$gar_details->tds_amount}}" @else  value="{{old('tds_amount',0)}}" @endif >
                        @if ($errors->has('tds_amount'))
                        <span class="help-block"><strong>{{ $errors->first('tds_amount') }}</strong>
                        </span>
                        @endif
                    </div>  
                </div>
            </div>
            <div id="is_gst_present">  
               <div class="hr-line-dashed"></div>
               <div class="form-group">
                  <div class="col-md-3 col-sm-6">
                   <label class="control-label">Deducted TDS<span class="e-color">*</span></label>
                   <div class="{{ $errors->has('tds_deducted_amount') ? ' has-error' : '' }}">   
                    <input type="text" id="tds_deducted_amount" name="tds_deducted_amount" placeholder="Deducted TDS Amount" class="form-control" readonly  @if(isset($gar_details->tds_deducted_amount) && !empty($gar_details->deducted_amount)) value="{{$gar_details->tds_deducted_amount}}" @else  value="{{old('tds_deducted_amount')}}" @endif >
                    @if ($errors->has('tds_deducted_amount'))
                    <span class="help-block"><strong>{{ $errors->first('tds_deducted_amount') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="control-label">TDS On GST Amount</label>
                <div class="{{ $errors->has('gst_tds_amount') ? ' has-error' : '' }}">  
                    <input type="text" name="gst_tds_amount" id="gst_tds_amount" placeholder="GST-TDS to be Deducted" class="form-control" @if(isset($gar_details->gst_tds_amount) && !empty($gar_details->gst_amount)) value="{{$gar_details->gst_tds_amount}}" @else value="{{old('gst_tds_amount',0)}}" @endif >
                    @if ($errors->has('gst_tds_amount'))
                    <span class="help-block"><strong>{{ $errors->first('gst_tds_amount') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="control-label">LD Or Penalty Amount</label>
                <div class="{{ $errors->has('ld_amount') ? ' has-error' : '' }}">  
                    <input type="text" name="ld_amount" id="ld_amount"  placeholder="LD Or Penalty Amount" class="form-control" @if(isset($gar_details->ld_amount) && !empty($gar_details->ld_amount)) value="{{$gar_details->ld_amount}}" @else value="{{old('ld_amount',0)}}" @endif >
                    @if ($errors->has('ld_amount'))
                    <span class="help-block"><strong>{{ $errors->first('ld_amount') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="form-group">
            <div class="col-md-3 col-sm-6">
                <label class="control-label">Withheld Amount</label>
                <div class="{{ $errors->has('with_held_amount') ? ' has-error' : '' }}">  
                    <input type="text" name="with_held_amount" id="with_held_amount" placeholder="Withheld Amount" class="form-control" @if(isset($gar_details->with_held_amount) && !empty($gar_details->with_held_amount)) value="{{$gar_details->with_held_amount}}" @else value="{{old('with_held_amount',0)}}" @endif >
                    @if ($errors->has('with_held_amount'))
                    <span class="help-block"><strong>{{ $errors->first('with_held_amount') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            <div class="col-md-3 col-sm-6">
               <label class="control-label">Other Amount</label>
               <div class="{{ $errors->has('other_amount') ? ' has-error' : '' }}">   
                <input type="text" id="other_amount" name="other_amount" placeholder="Other Amount" class="form-control"  @if(isset($gar_details->other_amount) && !empty($gar_details->other_amount)) value="{{$gar_details->other_amount}}" @else  value="{{old('other_amount',0)}}" @endif >
                @if ($errors->has('other_amount'))
                <span class="help-block"><strong>{{ $errors->first('other_amount') }}</strong>
                </span>
                @endif
            </div>  
        </div>
        <div class="col-md-3 col-sm-6">
           <label class="control-label">Actual Payment Amount<span class="e-color">*</span></label>
           <div class="{{ $errors->has('actual_payment_amount') ? ' has-error' : '' }}">   
            <input type="text" id="actual_payment_amount" name="actual_payment_amount" class="form-control" placeholder="Actual Payment Amount" readonly @if(isset($gar_details->actual_payment_amount) && !empty($gar_details->actual_payment_amount)) value="{{$gar_details->actual_payment_amount}}" @else  value="0" @endif >
            @if ($errors->has('actual_payment_amount'))
            <span class="help-block"><strong>{{ $errors->first('actual_payment_amount') }}</strong>
            </span>
            @endif
        </div>  
    </div>
     @if(isset($is_create) && !empty($is_create) || empty($copy_to_details))
                <div class="col-sm-2">
                        <label class="control-label">Copy To</label>
                        <div class="{{ $errors->has('copy_to') ? ' has-error' : '' }} input-ht">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="copy_to" value="1" id="copy_to" 
                                @if(old('copy_to') == '1') checked  @endif @if(isset($purchase_order_master->copy_to) && $purchase_order_master->copy_to) == 1) checked @endif>Copy To</label>
                                @if ($errors->has('copy_to'))
                                <span class="help-block"><strong>{{ $errors->first('copy_to') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>   
                   
                @endif
</div>
</div> 
 @include('copy_to')       
                   <a data-toggle="modal" href="#ec_register"></a>
                   <div id="ec_register" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="text-center">
                                            <h3 class="m-t-none m-b">EC Register</h3>
                                        </div>
                                        <div class="form-group"> 
                                          <div class="col-sm-6">
                                            <label class="control-label">Bill No.<span class="e-color">*</span></label>
                                            <input type="text" name="bill_no" @if(isset($gar_register_bill_no) && !empty($gar_register_bill_no)) value="{{$gar_register_bill_no}}" @endif placeholder="Bill No." class="form-control" readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Gross Amount on Release Order<span class="e-color">*</span></label>
                                            <input type="text" name="release_order_amount" placeholder="Amount"  class="form-control" readonly @if(isset($gar_details->release_order_amount) && !empty($gar_details->release_order_amount)) value="{{$gar_details->release_order_amount}}" @endif>
                                        </div>
                                    </div> 
                                    <div class="form-group"> 
                                      <div class="col-sm-6">
                                        <label class="control-label">Budget Code<span class="e-color">*</span></label><?php //dd($gar_details->budget_code);
?>
                                       <input type="text" name="budget_code" placeholder="Budget Code" class="form-control" readonly @if(isset($gar_details->budget_code) && !empty($gar_details->budget_code)) value="{{$gar_details->budget_code}}" @endif>
                                    </div>
                                    <div class="col-ssm-6">
                                        <label class="control-label">Date of ECR Entry<span class="e-color">*</span></label>
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control date_added" placeholder="Date of GAR Register Entry" disabled="disabled" id="date_of_er_issue" name="date_of_er_issue">
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group"> 
                                  <div class="col-sm-6">
                                    <label class="control-label">Budget Amount<span class="e-color">*</span></label>
                                    <input type="text" name="budget_head_amount" placeholder="Budget Head Amount" class="form-control" readonly @if(isset($gar_details->amount) && !empty($gar_details->amount)) value="{{$gar_details->amount}}" @endif>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Budget Head Balance<span class="e-color">*</span></label>
                                    <input type="text" name="budget_head_balance" placeholder="Budget Code" class="form-control" readonly  id="budget_head_balance">
                                </div>
                            </div> 
                            <div class="form-group"> 
                              <div class="col-sm-6">
                                <label class="control-label">Budget Head <span class="e-color">*</span></label>
                                <input type="text" name="budget_head" placeholder="Budget Head" class="form-control" readonly @if(isset($gar_details->budget_head_of_acc) && !empty($gar_details->budget_head_of_acc)) value="{{$gar_details->budget_head_of_acc}}" @endif>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Nature Of Expense<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('nature_of_expense') ? ' has-error' : '' }}">
                                    <select class="form-control chosen-select" id="nature_of_expense" name="nature_of_expense">
                                        <option selected disabled value="">Select Nature Of Expense</option>
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>
                                @if ($errors->has('nature_of_expense'))
                                <span class="help-block"><strong>{{ $errors->first('nature_of_expense') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>  
                        <div class="form-group"> 
                            <input class="btn btn-primary col-md-offset-4" id="ec_register_entry" type="button"  value="Save">
                            <input class="btn btn-primary closeModal" type="reset"  value="Cancel">
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<a data-toggle="modal" href="#gar_register"></a>
<div id="gar_register" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <h3 class="m-t-none m-b">GAR Register</h3>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-6">
                                <label class="control-label">Bill No.<span class="e-color">*</span></label>
                                <input type="text" name="bill_no" @if(isset($gar_register_bill_no) && !empty($gar_register_bill_no)) value="{{$gar_register_bill_no}}" @endif placeholder="Bill No." value="" class="form-control" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Amount<span class="e-color">*</span></label>
                                <input type="text" name="release_order_amount" placeholder="Amount"  class="form-control" readonly @if(isset($gar_details->release_order_amount) && !empty($gar_details->release_order_amount)) value="{{$gar_details->release_order_amount}}" @endif>
                            </div>
                        </div> 
                        <div class="form-group"> 
                            <div class="col-sm-6">
                                <label class="control-label">Budget Head<span class="e-color">*</span></label>
                                <input type="text" name="budget_head" placeholder="Budget Head" class="form-control" readonly @if(isset($gar_details->budget_head_of_acc) && !empty($gar_details->budget_head_of_acc)) value="{{$gar_details->budget_head_of_acc}}" @endif>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Date of GAR Register Entry<span class="e-color">*</span></label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control date_added" placeholder="Date of GAR Register Entry" disabled="disabled" id="date_of_issue" name="date_of_issue">
                                </div>
                            </div>
                        </div>  
                        <div class="form-group"> 
                            <input class="btn btn-primary col-md-offset-4" id="gar_register_entry" type="button"  value="Save">
                             <input class="btn btn-primary closeModal" type="reset"  value="Cancel">
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<a data-toggle="modal" href="#diary_register"></a>
<div id="diary_register" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <h3 class="m-t-none m-b">Diary Register</h3>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-6">
                                <label class="control-label">Diary Register No.<span class="e-color">*</span></label>
                                @if(isset($diary_register_no_new) && !empty($diary_register_no_new))
                                <input type="text" name="diary_register_no"  placeholder="Enter Diary Register No." value="{{$diary_register_no_new}}" class="form-control" readonly>
                                @else 
                                <input type="text" id="diary_register_no" name="diary_register_no" placeholder="Enter Diary Register No." class="form-control" readonly>
                                @endif

                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">EAS / File No.<span class="e-color">*</span></label>
                                <input type="text" id="file_number" name="eas_file_no" placeholder="EAS / File No." class="form-control" readonly @if(isset($gar_details->file_number) && !empty($gar_details->file_number)) value="{{$gar_details->file_number}}" @endif>
                            </div>
                        </div> 
                        <div class="form-group"> 
                            <div class="col-sm-6">
                                <label class="control-label">Vendor Name<span class="e-color">*</span></label>
                                <input type="text" name="vendor_name" placeholder="Enter Vendor Name" class="form-control vendor_name" readonly @if(isset($gar_details->vendor_name) && !empty($gar_details->vendor_name)) value="{{$gar_details->vendor_name}}" @endif>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Amount to be Paid<span class="e-color">*</span></label>
                                <input type="text" name="amount_paid" placeholder="Amount to be Paid" class="form-control" readonly @if(isset($gar_details->amount_to_be_paid) && !empty($gar_details->amount_to_be_paid)) value="{{$gar_details->amount_to_be_paid}}" @endif>
                            </div>
                        </div> 
                        <div class="form-group"> 
                            <div class="col-sm-6">
                                <label class="control-label">Date of Order Receiving<span class="e-color">*</span></label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input  type="text" readonly class="form-control" @if(isset($gar_details->status_approved_date) && !empty($gar_details->status_approved_date)) value="<?php echo date('d-m-Y', strtotime($gar_details->status_approved_date)); ?>" @endif placeholder="Date of Order Receiving" name="date_of_receiving">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Date of Diary Register Entry<span class="e-color">*</span></label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    @if(isset($gar_details->date_of_forwarding) && !empty($gar_details->date_of_forwarding))
                                    <input type="hidden" name="date_of_forwarding" value="{{$gar_details->date_of_forwarding}}">
                                    <input type="text" class="form-control date_added" placeholder="Date of Diary Register Entry" disabled="disabled" name="date_of_forwarding" value="{{$gar_details->date_of_forwarding}}">
                                    @else 
                                    <input type="text" class="form-control date_added" placeholder="Date of Diary Register Entry" disabled="disabled" name="date_of_forwarding" >
                                    <input type="hidden" id="date_of_diary_register" name="date_of_forwarding">
                                    @endif
                                </div>
                            </div>
                        </div>  
                        <div class="form-group"> 
                            <input class="btn btn-primary col-md-offset-4" id="diary_register_entry" type="button"  value="Save">
                             <input class="btn btn-primary closeModal" type="reset"  value="Cancel">
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<a data-toggle="modal" href="#dispatch_register"></a>
<div id="dispatch_register" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center">
                            <h3 class="m-t-none m-b">Dispatch Register</h3>
                        </div>
                        <div class="form-group"> 
                          <div class="col-sm-6">
                            <label class="control-label">Dispatch Register No.<span class="e-color">*</span></label>

                            <input type="text" name="dispatch_register_no" placeholder="Enter Dispatch Register No."  @if(isset($dispatch_register_no_new) && !empty($dispatch_register_no_new)) value="{{$dispatch_register_no_new}}" @endif class="form-control" readonly>

                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">EAS / File No.<span class="e-color">*</span></label>
                            <input type="text" id="file_number" name="eas_file_no" placeholder="EAS / File No." class="form-control" readonly @if(isset($gar_details->file_number) && !empty($gar_details->file_number)) value="{{$gar_details->file_number}}" @endif>
                        </div>
                    </div> 
                    <div class="form-group"> 
                      <div class="col-sm-6">
                        <label class="control-label">Vendor Name<span class="e-color">*</span></label>
                        <input type="text" name="dispatch_vendor_name" placeholder="Enter Vendor Name" class="form-control vendor_name" readonly @if(isset($gar_details->vendor_name) && !empty($gar_details->vendor_name)) value="{{$gar_details->vendor_name}}" @endif>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Amount to be Paid<span class="e-color">*</span></label>
                        <input type="text" name="dispatch_actual_payment_amount" placeholder="Amount to be Paid" class="form-control" readonly @if(isset($gar_details->amount_to_be_paid) && !empty($gar_details->amount_to_be_paid)) value="{{$gar_details->amount_to_be_paid}}" @endif>
                    </div>
                </div> 
                <div class="form-group"> 
                    <div class="col-sm-6">
                        <label class="control-label">Date of Order Receiving<span class="e-color">*</span></label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input @if(isset($gar_details->status_approved_date) && !empty($gar_details->status_approved_date)) value="<?php echo date('d-m-Y', strtotime($gar_details->status_approved_date)); ?>" @endif type="text" class="form-control status_approved_date" placeholder="Date of Order Receiving"  name="dispatch_receiving" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Date of Dispatch Register Entry<span class="e-color">*</span></label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            @if(isset($gar_details->dispatch_forwarding) && !empty($gar_details->dispatch_forwarding))
                            <input type="hidden" name="dispatch_forwarding" value="{{$gar_details->dispatch_forwarding}}">
                            <input type="text" class="form-control date_added" placeholder="Date of Diary Register Entry" disabled="disabled" name="dispatch_forwarding" value="{{$gar_details->dispatch_forwarding}}">
                            @else 
                            <input type="text" class="form-control date_added" placeholder="Date of Dispatch Register Entry" disabled="disabled" name="dispatch_forwarding" >
                            <input type="hidden" id="dispatch_forwarding" name="dispatch_forwarding">
                            @endif
                        </div>
                    </div>
                </div> 
                <div class="form-group"> 
                    <input class="btn btn-primary col-md-offset-4" id="dispatch_register_entry" type="button"  value="Save">
                     <input class="btn btn-primary closeModal" type="reset"  value="Cancel">
                </div>
            </div> 
        </div>
    </div>
</div>
</div>
</div>
<div class="hr-line-dashed"></div>   
@include('transactions_view')
@if(isset($is_update_url) && !empty($is_update_url) && $is_update_url == 1)
<div class="form-group">
   <div class="col-sm-4 col-sm-offset-5"> 
    <input class="btn btn-primary" type="submit"  value="Update"> 
   <input onclick="backToPrev()" class="btn btn-primary" type="button"  value="Cancel"> 
</div>
</div> 
@endif

</form>
<a data-toggle="modal" href="#add_modal_copy_to"></a>
<div id="add_modal_copy_to" class="modal fade" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="text-center">
              <h3 class="m-t-none m-b">Copy To</h3>
            </div>
            <form action="{{ url('/add-copy') }}" method="POST">  
             
              {{ csrf_field() }}    
              <input type="hidden" name="entity_id" value="{{isset($entity_details->id) ? $entity_details->id:''}}"/>
              <input type="hidden" name="id" value="{{isset($gar_details->id) ? $gar_details->id:''}}"/>
              <div class="form-group"> 
                <div class="col-sm-5">
                  <select name="department_id" class="form-control select2 update_department" required>
                   <option disabled="disabled" selected>Select Department</option>
                   @if(isset($list_of_departments))
                   @foreach($list_of_departments as $value) 
                   <option @if(old('department') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($copy_to_val['department_id']) && $copy_to_val['department_id'] == $value['id']) { echo 'selected';} ?>  value="{{ $value['id']}}" slug="{{ $value['slug']}}">{{ $value['name']}} - ({{$value['location_name']}})</option>
                   @endforeach
                   @endif
                 </select>
               </div>
               <div class="col-sm-5">
                 <select  class="select2 update_user" name="user_id" data-placeholder="Select User" required>
                  <option disabled="disabled" selected="selected">Select Users</option>
                  @if(isset($users) && !empty($users))
                  @foreach($users as $value) 
                  <option @if(old('users') == $value['id']) selected @endif    value="{{ $value['id']}}" >{{ $value['name']}}</option>
                  @endforeach
                  @endif
                </select>
              </div>

                                  <!-- <div class="col-sm-2">
                                     <button class="btn btn-success add-more-copy_to" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                      <div class="remove-button">
                                   </div>
                                 </div> -->
                               </div>
                               
                               <div class="form-group" > 
                                <input class="btn btn-primary btn btn-primary col-md-offset-4" type="submit"  value="Submit" style="margin-top: 20px;">
                                <input class="btn btn-primary btn btn-primary closeModal" type="reset"  value="Cancel" style="margin-top: 20px;">
                              </div>
                            </form>   
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>  

</div>
</div>
</div>    
</div>

@endsection
@section('scripts')
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
@include('scripts.add_copy_to_js')
@include('scripts.edit_copy_to_js')
<script type="text/javascript">
$("#update_copy_to").click(function () {

  var id = '{{isset($gar_details->id)?$gar_details->id:''}}';
  var url = '{{url("/")}}/updateCopyTo';
  var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';

  var department_id = [];
  $.each($(".update_department option:selected"), function(){            
      department_id.push($(this).val());
  });
   var user_id = [];
  $.each($(".update_user option:selected"), function(){            
      user_id.push($(this).val());
  });

  if(user_id && department_id && user_id) {
   $.ajax({

            type: 'POST',
            url: url,
            data: {id:id, department_id:department_id,user_id:user_id,entity_id:entity_id,"_token": "{{ csrf_token() }}"},
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
                },
            dataType: 'json',
            success: function (data) {
             
              if(data.code == 200) {
              $('#modal_copy_to').modal('hide');
              swal({
                        title: 'Updated!', 
                        text: data.message,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
              } else {
              $('#modal_copy_to').modal('show');
              alert(data.message)
              }
              
           },
               complete: function(){
              $('.div-loader').css("display", "none");
            },
           error: function (data) {
               
           }
       });
    } else {
      alert('Please fill required field.')
    }
  //}
});

    $(document).ready(function(){
        $('.dataTables-example').DataTable({
        });

        var checkbox = $('#copy_to:checked').val(); 
        if(checkbox == 1) {
            $('#email_users').show();
        }
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        calculateGst();
    });
//     $(".start").click(function() {

//         var created_by = '{{isset($gar_details->created_by)?$gar_details->created_by:''}}';
//         var transaction_id = this.id;
//         var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';
//         var entity_slug =  '{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}';
//         var workflow_id ='{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}';
//         var url = '{{url("/")}}/updateCurrentTransaction';
//         var id = '{{isset($gar_details->id)?$gar_details->id:''}}';
//         var status_name = $(this).val();   
//         var privious_status = $(this).data('privious_status');    
//         var file_number = '{{isset($gar_details->file_number)?$gar_details->file_number:''}}';
//         var vendor_name = '{{isset($gar_details->vendor_name)?$gar_details->vendor_name:''}}';
//         var actual_payment_amount = '{{isset($gar_details->actual_payment_amount)?$gar_details->actual_payment_amount:''}}';
//         var sanction_title = '{{isset($gar_details->sanction_title)?$gar_details->sanction_title:''}}';
//         var email_users = '{{isset($gar_details->email_users)?$gar_details->email_users:''}}';
//         var gar_type = $('#gar_bill_type option:selected').text();
       
//         var final_status =  '{{isset($entity_details->final_status)?$entity_details->final_status:''}}';

//         swal({
//             title: "Are you sure want to change status?",
//             text: "Once status changed,will not retain again!!",
//             type: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#DD6B55",
//             confirmButtonText: "Yes",
//             cancelButtonText: "No",
//             closeOnConfirm: false
//         },
//         function () {

//            if(status_name == "DDO Return" || status_name == "AAO Return" || status_name == "PAO Return")  {

//             swal({
//               title: "Please Enter Comment!",
//               text: "Why this GAR Rejected ?",
//               type: "input",
//               html:true,
//               showCancelButton: true,
//               closeOnConfirm: false,
//               animation: "slide-from-top",
//               inputPlaceholder: "Enter comment"
//           },
//           function(inputValue){

//             $('input').bind('keyup blur',function(){

//               var node = $(this);
//               node.val(node.val().replace(/[^A-Za-z_\s-/0-9]/,'') ); 
//                 }   // (/[^a-z]/g,''
//                 );
//             if (inputValue === "") {
//                 swal.showInputError("Comment is Required!");
//                 return false
//             } else {
//                 if (inputValue.length >100 ) {
//                  swal.showInputError("'you have reached a limit of 100.");   
//              } else {
//                 updateTransaction(inputValue);
//             }
//         }

//     });

//         } else {
//            updateTransaction();
//        }
//        function updateTransaction(inputValue=null) {
//         if(created_by && transaction_id && entity_id && workflow_id && url ) {

//             $.ajax({
//                 url: url,
//                 dataType: "json",
//                 type:"POST",
//                 beforeSend: function(){
//                     swal.close()
//                     $('.div-loader').css("display", "block");
//                 },
//                 data: {
//                     transaction_type : transaction_id,
//                     entity_id :entity_id,comment:inputValue,email_users:email_users,
//                     workflow_id:workflow_id,created_by:created_by,entity_slug:entity_slug,id:id,status_name:status_name,privious_status:privious_status,file_number:file_number,sanction_title:sanction_title,vendor_name:vendor_name,actual_payment_amount:actual_payment_amount,gar_type:gar_type,final_status:final_status,
//                     _token: "{{csrf_token()}}"
//                 },

//                 success: function(data) {
//                     if(data.code == 204){
//                         swal({
//                             title: 'Status', 
//                             text: data.message,
//                             type: 'error',
//                             showConfirmButton: false,
//                             timer: 1500
//                         });
//                     } else {
//                  //if(data.status_id == 33) {
//                    if(data.status_id == final_status) {

//                     swal({
//                         title: "Approved",
//                         text: data.message,
//                         type: "success",
//                         showConfirmButton: false,
//                         timer: 1500
//                     });

//                     window.location.href = "{{ url('/gar') }}/"+id;
//                 } else {

//                     swal({
//                         title: 'Updated!', 
//                         text: data.message,
//                         type: 'success',
//                         showConfirmButton: false,
//                         timer: 1500
//                     });
//                     location.reload();
//                 }
//             }
//         },complete: function(){
//           $('.div-loader').css("display", "none");
//       }
//   });
//         } else {
//             swal({
//                 title: 'Status', 
//                 text: 'Data Not Found.',
//                 type: 'message',
//                 showConfirmButton: false,
//                 timer: 1500
//             });
//         }
//     } 
// });
// });
$('.chosen-select').chosen({width: "100%"});
$("#copy_to").change(function () {
  //  $('input[name="copy_to"]').not(this).prop('checked', false);
  var checkebox = $('#copy_to:checked').val(); 
  if (checkebox == 1) { 
    document.getElementById("email_users").style.display = 'block';
} else {
    document.getElementById("email_users").style.display = 'none'; 

}    
});


$('#ec_register_entry').click(function() { 
   var budget_head = $("input[name='budget_head']").val(); 
   var budget_head_amount = $("input[name='budget_head_amount']").val(); 
   var budget_head_balance = $("input[name='budget_head_balance']").val(); 
   var bill_no = $("input[name='bill_no']").val(); 
   var nature_of_expense = $('#nature_of_expense option:selected').val();
   var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';
   var date_of_issue = $("input[name='date_of_er_issue']").val();
   var is_ecr_entry = $("input[name='is_ecr_entry']").val();

   if(budget_head && budget_head_amount && budget_head_balance &&  bill_no && nature_of_expense && gar_id && date_of_issue && is_ecr_entry) {
       $.ajax({
        url:"{{url('/')}}/garEcRegisterEntry",
        dataType: "json",
        type:"POST",
        beforeSend: function(){
            swal.close()
            $('.div-loader').css("display", "block");
        },
        data: {
            budget_head:budget_head,budget_head_amount:budget_head_amount,budget_head_balance:budget_head_balance,bill_no:bill_no,nature_of_expense:nature_of_expense,gar_id:gar_id,date_of_issue:date_of_issue,is_ecr_entry:is_ecr_entry, _token: "{{csrf_token()}}"
        },                     
        success: function(data) {
            if((data) && data.code == 200) {
                $('#ec_register').modal('hide');
                swal({
                    title: 'Updated!', 
                    text: data.message,
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
                location.reload();
            } else {
               $('#ec_register').modal('hide');
               swal({
                title: 'Status', 
                text: data.message,
                type: 'error',
                showConfirmButton: false,
                timer: 1500
            });
           }
       },complete: function(){
        $('.div-loader').css("display", "none");
    }
});
   } else {
    alert('Please fill mendatory fields.')
}

});


$('#gar_register_entry').click(function() {
   var budget_head = $("input[name='budget_head']").val(); 
   var bill_no = $("input[name='bill_no']").val(); 
   var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';
   var date_of_issue = $("input[name='date_of_issue']").val();
   var gar_register_entry = $("input[name='gar_register_entry']").val();
   if(budget_head && bill_no && gar_id && date_of_issue && gar_register_entry) {
       $.ajax({
        url:"{{url('/')}}/garRegisterEntry",
        dataType: "json",
        type:"POST",
        beforeSend: function(){
            swal.close()
            $('.div-loader').css("display", "block");
        },
        data: {
         budget_head:budget_head,bill_no:bill_no,gar_id:gar_id,date_of_issue:date_of_issue,gar_register_entry:gar_register_entry, _token: "{{csrf_token()}}"
     },                     
     success: function(data) {
        if((data) && data.code == 200) {
            $('#gar_register').modal('hide');
            swal({
                title: 'Updated!', 
                text: data.message,
                type: 'success',
                showConfirmButton: false,
                timer: 1500
            });
            location.reload();
        } else {
           $('#gar_register').modal('hide');
           swal({
            title: 'Status', 
            text: data.message,
            type: 'error',
            showConfirmButton: false,
            timer: 1500
        });
       }
   },complete: function(){
    $('.div-loader').css("display", "none");
}
});
   } else {
    alert('data not found.')
}

});

$('#diary_register_entry').click(function() {
 //var vendor_name = $("input[name='vendor_name']").val(); 
 //var eas_file_no = $("input[name='eas_file_no']").val(); 
 //var amount_paid = $("input[name='amount_paid']").val(); 
 var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';
 var diary_register_no = $("input[name='diary_register_no']").val();
 var date_of_receiving = $("input[name='date_of_receiving']").val();
 var date_of_forwarding = $("input[name='date_of_forwarding']").val();

 if( gar_id && diary_register_no && date_of_receiving && date_of_forwarding) {
   $.ajax({
    url:"{{url('/')}}/diaryRegisterEntry",
    dataType: "json",
    type:"POST",
    beforeSend: function(){
        swal.close()
        $('.div-loader').css("display", "block");
    },
    data: {
        gar_id:gar_id,diary_register_no:diary_register_no,date_of_receiving:date_of_receiving,date_of_forwarding:date_of_forwarding,
        _token: "{{csrf_token()}}"
    },                     
    success: function(data) {
        if((data) && data.code == 200) {
            $('#diary_register').modal('hide');
            swal({
                title: 'Updated!', 
                text: data.message,
                type: 'success',
                showConfirmButton: false,
                timer: 1500
            });
            location.reload();
        } else {
           $('#diary_register').modal('hide');
           swal({
            title: 'Status', 
            text: data.message,
            type: 'error',
            showConfirmButton: false,
            timer: 1500
        });
       }
   },complete: function(){
    $('.div-loader').css("display", "none");
}
});
} else {
    alert('data not found.')
}

});

$('#dispatch_register_entry').click(function() {

   var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';
   var dispatch_register_no = $("input[name='dispatch_register_no']").val();
   var date_of_receiving = $("input[name='date_of_receiving']").val();
   var date_of_forwarding = $("input[name='date_of_forwarding']").val();

   if(gar_id && dispatch_register_no && date_of_receiving && date_of_forwarding) {
       $.ajax({
        url:"{{url('/')}}/dispachRegisterEntry",
        dataType: "json",
        type:"POST",
        beforeSend: function(){
            swal.close()
            $('.div-loader').css("display", "block");
        },
        data: {
            gar_id:gar_id,dispatch_register_no:dispatch_register_no,date_of_receiving:date_of_receiving,date_of_forwarding:date_of_forwarding,
            _token: "{{csrf_token()}}"
        },                     
        success: function(data) {
            if((data) && data.code == 200) {
                $('#dispatch_register').modal('hide');
                swal({
                    title: 'Updated!', 
                    text: data.message,
                    type: 'success',   
                    showConfirmButton: false,
                    timer: 1000
                });
                location.reload();

            } else {
               $('#dispatch_register').modal('hide');
               swal({
                title: 'Status', 
                text: data.message,
                type: 'error',
                showConfirmButton: false,
                timer: 1000
            });
           }
       },complete: function(){
        $('.div-loader').css("display", "none");
    }
});
   }

});
$('#is_tally_entry').click(function() {
   var tally_entry = $(this).val();
   var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';

   if(tally_entry == 1 && gar_id) {
       $.ajax({
        url:"{{url('/')}}/tallyEntry",
        dataType: "json",
        type:"POST",
        beforeSend: function(){
            swal.close()
            $('.div-loader').css("display", "block");
        },
        data: {
            tally_entry : tally_entry,gar_id:gar_id,
            _token: "{{csrf_token()}}"
        },                     
        success: function(data) {
            if((data) && data.code == 200) {

                swal({
                    title: 'Updated!', 
                    text: data.message,
                    type: 'success',
                    showConfirmButton: false,
                    timer: 1000

                });
                location.reload();
            } else {

             swal({
                title: 'Status', 
                text: data.message,
                type: 'error',
                showConfirmButton: false,
                timer: 1000
            });
         }
     },complete: function(){
        $('.div-loader').css("display", "none");
    }
});
   }

});
$('#ro_id').change(function() {
    var ro_id = $(this).val();
    if(ro_id) {
        $.ajax({
            url:"{{url('/')}}/getRoDetails",
            dataType: "json",
            type:"GET",
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
            },
            data: {
                ro_id : ro_id,
                _token: "{{csrf_token()}}"
            },                     
            success: function(data) {
                console.log(data);
                if((data) && data.code == 200) {
                    $("#eas_total").val(data.eas_total);
                    $("input[name='release_order_amount']").val(data.release_order_amount); 
                    $("#release_order_amount").val(data.release_order_amount); 
                    $(".status_approved_date").val(data.status_approved_date);
                    $("#amount_paid").val(data.release_order_amount); 
                    var amount_used_till_date = (parseInt(data.eas_total) + parseInt(data.release_order_amount));
                    $("#amount_used_till_date").val(amount_used_till_date); 
                    $("#sanction_total").val(data.sanction_total);
                    $("#budget_head_amount").val(data.amount);
                    $("input[name='vendor_name']").val(data.vendor_name); 
                    $("#vendor_id").val(data.vendor_id); 
                    $("#vendor_contact").val(data.mobile_no);
                    $("#bank_name").val(data.bank_name);
                    $("#ifsc").val(data.ifsc_code); 
                    $("#branch_code").val(data.bank_code);
                    $("#budget_code").val(data.budget_code);
                    $("#account_no").val(data.bank_acc_no);
                    $("#file_number").val(data.file_number);
                    $("#date_of_order_receiving").val(data.status_approved_date);
                    $("#diary_register_no").val(data.diary_register_no+1);
                    $("#sanction_total").val(data.sanction_total);
                      //$("input[name='release_order_amount']").val(''); 
                } else {
                    alert('RO details not found')
                }
            },
            complete: function(){
              $('.div-loader').css("display", "none");
              var budget_head_amount = parseInt($("#budget_head_amount").val());
              var release_order_amount = parseInt($("#release_order_amount").val());
              if(release_order_amount > budget_head_amount) {
                alert('Insufficient Budget');
                $("input[name='release_order_amount']").val(''); 
                $("#ro_id").val(null).trigger("change"); 
                $("#release_order_amount").val('');
                $(".status_approved_date").val('');
                $("#amount_paid").val(''); 
                $("#amount_used_till_date").val(''); 
            } 
        }
    });
    } 
  //   else {
  //     alert('RO data not found')
  // }

});  


var $amount_paid = $("input[name='amount_to_be_paid']").on("input", calculateGst),
$gst_value = $("input[name='gst_amount']").on("input", calculateGst),
$tds_amount = $("input[name='tds_amount']").on("input", calculateGst),
$gst_tds_amount = $("input[name='gst_tds_amount']").on("input", calculateGst),
$other_amount = $("input[name='other_amount']").on("input", calculateGst),
$ld_amount = $("input[name='ld_amount']").on("input", calculateGst),
$with_held_amount = $("input[name='with_held_amount']").on("input", calculateGst),
$amount_used_till_date = $("select[name='eas_id']").on("select", calculateGst);

function calculateGst() {

    var gst_value = $gst_value.val();
    var amount    = $("input[name='amount_to_be_paid']").val();
    var ld_amount = $("input[name='ld_amount']").val();
    var with_held_amount = $("input[name='with_held_amount']").val();
    var cal_deducted_gst = (amount - gst_value).toFixed(2);

    $("input[name='deducted_gst']").val(cal_deducted_gst);

    var cal_deducted_tds= (cal_deducted_gst - $tds_amount.val()).toFixed(2);
    $("input[name='tds_deducted_amount']").val(cal_deducted_tds);

    var cal_other_amount = (cal_deducted_tds - $other_amount.val()).toFixed(2);
    $("input[name='actual_payment_amount']").val(cal_other_amount);

    var adding_gst = (parseInt(cal_other_amount) + parseInt(gst_value)).toFixed(2);
    $("input[name='actual_payment_amount']").val(adding_gst);

    var cal_gst_tds_amount = (adding_gst - $gst_tds_amount.val()).toFixed(2);
    $("input[name='actual_payment_amount']").val(cal_gst_tds_amount);
    
    if(ld_amount != undefined && ld_amount !== '0'){
    var cal_ld_amount = ($("input[name='actual_payment_amount']").val() - ld_amount).toFixed(2);
    $("input[name='actual_payment_amount']").val(cal_ld_amount);
    }

    if(with_held_amount != undefined && with_held_amount !== '0'){
    var cal_with_held_amount = ($("input[name='actual_payment_amount']").val() - with_held_amount).toFixed(2);
    $("input[name='actual_payment_amount']").val(cal_with_held_amount);
    }

    // calculateAmountUsedTillDate(); 
}

// function calculateAmountUsedTillDate(){
//     var eas_total = $("#eas_total").val();
//     $('#amount_used_till_date').val('');  
//     var amount_used_till_date = (parseInt(eas_total) + parseInt($("input[name='actual_payment_amount']").val()));
//     $('#amount_used_till_date').val(amount_used_till_date);
// }
$('input[name="gar_register_entry"]').change(function() {
 if($(this).is(':checked') && $(this).val() == '1') {
    $('#gar_register').modal('show');

   // $('#budget_head_balance').val(amount_used_till_date);
  //$('#amount_used_till_date').val(amount_used_till_date);  
}
});
$('input[name="is_ecr_entry"]').change(function() {
 if($(this).is(':checked') && $(this).val() == '1') {
    var ro_amount = $("input[name='release_order_amount']").val();
    var budget_head_amount ='{{isset($gar_details->amount)?$gar_details->amount:''}}';
    var budget_head_balance ='{{isset($budget_head_balance->budget_head_balance)?$budget_head_balance->budget_head_balance:''}}';
    var total = (parseInt(budget_head_balance) + parseInt(ro_amount));
    var head_balance_amount = (budget_head_amount - total);   
    $('#budget_head_balance').val(head_balance_amount);
    $('#ec_register').modal('show');
}
});
$('input[name="is_diary_register"]').change(function() {
 if($(this).is(':checked') && $(this).val() == '1') {
    $('#diary_register').modal('show');
}
});
$('input[name="is_dispatch_register"]').change(function() {
 if($(this).is(':checked') && $(this).val() == '1') {
    $('#dispatch_register').modal('show');
}
});

$('.date_added').datepicker({
    todayBtn: "linked",
    format: 'dd/mm/yyyy',
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
}).datepicker("setDate", new Date()).attr('readonly', 'readonly');
// $('#date_of_diary_register').val($('input[name="date_of_forwarding"]').val());
// $('#dispatch_forwarding').val($('input[name="dispatch_forwarding"]').val());
// $('#date_of_issue').val($('input[name="date_of_issue"]').val());
// $('#date_of_er_issue').val($('input[name="date_of_er_issue"]').val());

// $("#cheque_upload").click(function() {
//     var gar_id = '{{isset($gar_details->id)?$gar_details->id:''}}';
//     var form = new FormData();
//     var cheque_number = $('input[name="cheque_number"]').val();
//     var payment_mode =  $('#payment_mode').val(); 
//     var cheque_payment_amount = $('input[name="cheque_payment_amount"]').val();
//     var file_upload =  $('#file_upload').prop('files')[0];
//     form.append('file_upload', file_upload);
//     form.append('payment_mode', payment_mode);
//     form.append('gar_id', gar_id);
//     form.append('cheque_number', cheque_number);
//     form.append('cheque_payment_amount', cheque_payment_amount);
//     form.append( '_token', "{{csrf_token()}}");
//     if(cheque_number && payment_mode && cheque_payment_amount) {

//         $.ajax({
//           url:"{{url('/')}}/uploadCheque",
//           data: form,
//           cache: false,
//           contentType: false,
//           processData: false,
//           beforeSend: function(){
//             swal.close()
//             $('.div-loader').css("display", "block");
//         },
//         type: "POST",

//         success:function(data){
//            // console.log(data.message['file_upload'])
//            var file_error=[];
//            if((data) && data.code == 200) {

//             swal({
//                 title: 'Updated!', 
//                 text: data.message,
//                 type: 'success',
//                 showConfirmButton: false,
//                 timer: 1000
//             });
//             location.reload();
//         } else {
//           if(data.message['file_upload'])   {
//             $.each(data.message['file_upload'], function(k, v) {      
//                 file_error.push(v);
//             });
//             error = file_error[0];
//         } else {
//          error = data.message;
//      }
//      swal({
//         title: 'Status', 
//         text: error,
//         type: 'error',
//         showConfirmButton: false,
//         timer: 1000
//     });
//  }
// },
// complete: function(){
//   $('.div-loader').css("display", "none");
// }
// });
//     } else {
//         alert('Please Fill required fields');
//     }  
// });

// $("#ro_id").change(function(){
//   var budget_head_amount = $("#budget_head_amount").val();
//   var release_order_amount = $("#release_order_amount").val();

//   if(budget_head_amount  > release_order_amount) {
//     alert('Release Order Amount not greater than Budget Head amount.');
//     //$("#release_order_amount").val('');
//   } 
// });
$(".closeModal").on("click", function () {
   $('.modal').modal('hide');
  //}
}); 
</script>       
@endsection            