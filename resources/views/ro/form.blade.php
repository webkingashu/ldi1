  <?php $session_eas_id = Session::get('eas_id'); //dd($session_eas_id);
  ?>
  <div class="form-group">
   <div class="col-md-3">
    <label for="ro_title" class="control-label">{{ 'Title of Release Order' }}<span class="e-color">*</span></label>
    <div class="{{ $errors->has('ro_title') ? 'has-error' : ''}}">
     <input class="form-control textnumberonly" name="ro_title" placeholder="Title of Release Order" type="text" id="ro_title" <?php if (isset($ro->ro_title) && !empty($ro->ro_title)) { ?> value="{{ $ro->ro_title}}" <?php
   } else { ?> value="{{old('ro_title')}}" <?php
 } ?> >
 {!! $errors->first('ro_title', '
 <p class="help-block">:message</p>
 ') !!}
</div>
</div>
 <?php //dd($ro);
 ?>

 @if($session_eas_id)
 <div class="col-sm-3">
  <label for="eas" class="control-label">{{ 'EAS' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('eas_id') ? 'has-error' : ''}}">
   @if(isset($session_eas_id) && !empty($session_eas_id)>0)
   <input type="hidden" name="eas_id" value="{{ $session_eas_id }}?>">
   @endif
   <select class="chosen-select"  style="width:350px;" tabindex="4"  id="eas" disabled="">
    <option  selected="selected">Select</option>
    @if(isset($session_eas_id))
    @foreach($eas as $eas_value)

    <option @if(old('eas_id') == $eas_value->id) selected @endif  value="{{ $eas_value->id }}" <?php if (isset($session_eas_id) && !empty($session_eas_id) && $session_eas_id == $eas_value->id) {
      echo 'selected'; ?>
      <?php
    } ?>
    >{{ $eas_value->sanction_title }}</option>
    @endforeach
    @endif   
  </select>
  {!! $errors->first('eas_id', '<p class="help-block">:message</p>') !!}
</div>
</div>
@else
<div class="col-sm-3">
  <label for="eas" class="control-label">{{ 'EAS' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('eas_id') ? 'has-error' : ''}}">
    <select class="chosen-select"  style="width:350px;" tabindex="4" name="eas_id" id="eas" onchange="ro_details(this)">
      <option disabled="disabled" selected="selected">Select</option>
      @if(isset($eas) && count($eas)>0)
      @foreach($eas as $eas_value)
      <option @if(old('eas_id') == $eas_value->id) selected @endif  value="{{ $eas_value->id }}" <?php if (isset($purchase_order_master->eas_id) && !empty($purchase_order_master->eas_id) && $purchase_order_master->eas_id == $eas_value->id) {
        echo 'selected'; ?>
        <?php
      } ?>
      >{{ $eas_value->sanction_title }}</option>
      @endforeach
      @endif  
       @if(isset($ro) && isset($ro->eas_id) && !empty($ro->eas_id))
       <option <?php if(isset($ro->eas_id) && $ro->eas_id) { echo 'selected';} ?>  value="{{$ro->eas_id}}">{{$ro->sanction_title}}</option>
        @endif  
    </select>
    {!! $errors->first('eas_id', '<p class="help-block">:message</p>') !!}
  </div>
</div>
@endif
<div class="col-sm-3">
  <label for="total_sanction_amount" class="control-label">{{ 'EAS Sanctioned amount' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('total_sanction_amount') ? 'has-error' : ''}}">
   <input class="form-control textnumberonly" name="total_sanction_amount" placeholder="Total Sanctioned amount" type="text" id="total_sanction_amount" <?php if (isset($ro->sanction_total) && !empty($ro->sanction_total)) { ?> value="{{ $ro->sanction_total}} "<?php
 } else { ?> value="{{ old('total_sanction_amount') }}" <?php
} ?> readonly>
{!! $errors->first('total_sanction_amount', '
<p class="help-block">:message</p>
') !!}
</div>
</div>
<div class="col-sm-3">
  <label for="release_order_amount" class="control-label">{{ 'Release Order amount' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('release_order_amount') ? 'has-error' : ''}}">
   <input  type="text" class="form-control" placeholder="Release Order Amount" name="release_order_amount" id="release_order_amount" <?php if (isset($ro->release_order_amount) && !empty($ro->release_order_amount)) { ?> value="{{ $ro->release_order_amount}} "<?php
 } else { ?> value="{{ old('release_order_amount') }}" <?php
} ?>>
{!! $errors->first('release_order_amount', '
<p class="help-block">:message</p>
') !!}
</div>
</div>
</div>

<div id="eas_total">
  <h4 class="text-center padding-bottom-20">Depending upon the EAS Number, the Amount released till date</h4>
  <div class="form-group ">
   <div class="col-lg-12">
    @if(isset($ro_table_data) && !empty($ro_table_data))
    <table class="table table-bordered">
     <thead>
      <tr>
       <th>NO.</th>
       <th>File Name</th>
       <th>Amount in Rs.</th>
     </tr>
   </thead>   
   <tbody id="release_amount">
    <?php $total = 0; ?>
    @foreach($ro_table_data as $key => $ro_value)
    <tr>
     <td>{{ $loop->iteration or $ro_value->id }}</td>
     <td>{{$ro_value['ro_title']}}</td>
     <td>{{$ro_value['release_order_amount']}}</td>
   </tr>
   <?php
   $total+= $ro_value->release_order_amount;
   ?>
   @endforeach 
   <tr>
     <td colspan="2" class="text-right ">Total</td>
     <td class="total-combat">{{$total}}</td>
   </tr>
 </tbody>
</table>
@else
<table class="table table-bordered">
 <thead>
  <tr>
   <th>NO.</th>
   <th>File Name</th>
   <th>Amount in Rs.</th>
 </tr>
</thead>
<tbody id="release_amount">
</tbody>
</table>
@endif
</div>
</div>
</div>

<h4 class="text-center padding-bottom-20">Vendor Details</h4>
<div class="form-group">
 <div class="col-md-3">
  <label for="vendor_name" class="control-label">{{ 'Vendor Name' }}<span class="e-color">*</span></label>
  <div class="">
   <input class="form-control " name="vendor_name" placeholder="Vendor Name" type="text" id="vendor_name"  <?php if (isset($ro->vendor_name) && !empty($ro->vendor_name)) { ?> value="{{ $ro->vendor_name}} "<?php
 } else { ?> value="{{ old('vendor_name') }}" <?php
} ?> readonly >
</div>
</div>
<div class="col-md-3">
  <label for="vendor_contact_number" class="control-label">{{ 'Vendor Contact Number' }}<span class="e-color">*</span></label>
  <div class="">
   <input class="form-control " name="vendor_contact_number" placeholder="+91 9869801679" type="text" id="vendor_contact_number" <?php if (isset($ro->mobile_no) && !empty($ro->mobile_no)) { ?> value="{{ $ro->mobile_no}} "<?php
 } else { ?> value="{{ old('vendor_contact_number') }}" <?php
} ?> readonly>
</div>
</div>
<div class="col-md-3">
  <label for="bank_name" class="control-label">{{ 'Bank Name & Branch' }}<span class="e-color">*</span></label>
  <div class="">
   <input type="text"  placeholder="HDFC Bank" class="form-control" id="bank_name" <?php if (isset($ro->bank_name) && !empty($ro->bank_name)) { ?> value="{{ $ro->bank_name}} "<?php
 } else { ?> value="{{ old('bank_name') }}" <?php
} ?> readonly>
</div>
</div>
<div class="col-sm-3">
  <label for="ifsc_code" class="control-label">{{ 'IFSC Code' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('ifsc_code') ? 'has-error' : ''}}">
   <input class="form-control " name="ifsc_code" placeholder="IFSC Code" type="text" id="ifsc_code" <?php if (isset($ro->ifsc_code) && !empty($ro->ifsc_code)) { ?> value="{{ $ro->ifsc_code}} "<?php
 } else { ?> value="{{ old('ifsc_code') }}" <?php
} ?> readonly>
{!! $errors->first('ifsc_code', '
<p class="help-block">:message</p>
') !!}
</div>
</div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group ">
  <div class="col-sm-2">
    <label for="budget_code" class="control-label">{{ 'Budget Code' }}<span class="e-color">*</span></label>
    <div class="{{ $errors->has('budget_code') ? 'has-error' : ''}}">
     <input class="form-control" name="budget_code" type="text" id="budget_code" placeholder="Budget Code"  <?php if (isset($ro->budget_code) && !empty($ro->budget_code)) { ?> value="{{ $ro->budget_code}} "<?php
   } else { ?> value="{{ old('budget_code') }}" <?php
 } ?> readonly>        
 {!! $errors->first('budget_code', '
 <p class="help-block">:message</p>
 ') !!}
</div>
</div>
<div class="col-sm-2">
  <label for="bank_code" class="control-label">{{ 'Bank Code' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('bank_code') ? 'has-error' : ''}}">
   <input class="form-control" name="bank_code" type="text" placeholder="Bank Code" id="bank_code" <?php if (isset($ro->bank_code) && !empty($ro->bank_code)) { ?> value="{{ $ro->bank_code}} "<?php
 } else { ?> value="{{ old('bank_code') }}" <?php
} ?> readonly>
{!! $errors->first('bank_code', '
<p class="help-block">:message</p>
') !!}
</div>
</div>
<div class="col-sm-3">
  <label for="bank_acc_no" class="control-label">{{ 'Current/ Cash Credit Account No.' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('bank_acc_no') ? 'has-error' : ''}}">
   <input class="form-control" name="bank_acc_no" type="text"  placeholder="Current/ Cash Credit Account No." id="bank_acc_no" <?php if (isset($ro->bank_acc_no) && !empty($ro->bank_acc_no)) { ?> value="{{ $ro->bank_acc_no}} "<?php
 } else { ?> value="{{ old('bank_acc_no') }}" <?php
} ?> readonly >
{!! $errors->first('bank_acc_no', '
<p class="help-block">:message</p>
') !!}
</div>
</div>
<div class="col-sm-3">
  <label for="advance_ro" class="control-label">{{ 'Release Order is advance?' }}</label>
  <div class="{{ $errors->has('advance_ro') ? 'has-error' : ''}}">
   <label class="checkbox-inline">
    <input name="advance_ro" type="checkbox"  id="advance-ro"  value="1" @if(old('advance_ro') == '1') checked  @endif 
    <?php if (isset($ro->advance_ro) && !empty($ro->advance_ro) && $ro->advance_ro == 1) {
      echo "checked"; ?> 
      <?php
    } else { ?>
    value="{{ old('advance_ro') }}" <?php
  } ?>/> 
  {!! $errors->first('advance_ro','
  <p class="help-block"> :message</p>
  ') !!}
</label>
</div>
</div>
<div id="diary-number" style="display: none;">
  <div class="col-md-2">
   <label class="control-label">FA Diary number<span class="e-color">*</span></label>
   <div class="{{ $errors->has('fa_diary_number') ? 'has-error' : ''}}">
    <input name="fa_diary_number" type="text"  id="diary-number" class="form-control" <?php if (isset($ro->fa_diary_number) && !empty($ro->fa_diary_number)) { ?> value="{{ $ro->fa_diary_number}} "<?php
  } else { ?> value="{{ old('fa_diary_number') }}" <?php
} ?>/>
{!! $errors->first('fa_diary_number','
<p class="help-block"> :message</p>
') !!}
</div>
</div>
</div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group ">
 <div class="col-sm-3">
  <label for="">Document Name</label>
  <div class="input-group control-group-1 after-add-more-document" >
    <input type="text" placeholder="Document Name" class="form-control" rows="5" name="documents_type[]" />
  </div>
</div>
<div class= "col-sm-5">
  <label for="documents" id="user_img">Upload Documents</label>
  <div class="fileinput fileinput-new input-group control-group after-add-doc" data-provides="fileinput">
    <div class="form-control" data-trigger="fileinput">
     <i class="glyphicon glyphicon-file fileinput-exists"></i>
     <span class="fileinput-filename"></span>
   </div>
   <span class="input-group-addon btn btn-default btn-file">
    <span class="fileinput-new">Select file</span>
    <span class="fileinput-exists">Change</span>
    <input type="file" id="file_uploads" name="file_upload[]" onchange="show(this)" />

  </span>
  <div class="input-group-btn">
    <button class="btn btn-success add-more-document" type="button" style="height: 36px; flex: 0.5 0;"><i class="glyphicon glyphicon-plus"></i> Add</button>
  </div>

  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
</div>
<span class="errorshow" style="display:none;color:#F22613;"></span>
</div>   <!-- 
@if(isset($ro->copy_to) && $ro->copy_to == '1' && isset($is_update_url) && $is_update_url == 1 )
            <div class="col-sm-2">
                <label class="control-label">Copy To</label>
                <div class="{{ $errors->has('copy_to') ? ' has-error' : '' }}">
                    <label class="checkbox-inline">

                        <input type="checkbox" name="copy_to" value="1" id="copy_to"
                        @if(isset($ro->copy_to) && $ro->copy_to == '1') checked disabled
                        @endif 
                        @if(old('copy_to') == '1') checked  @endif >Copy To</label>

                        @if ($errors->has('copy_to'))
                        <span class="help-block"><strong>{{ $errors->first('copy_to') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                @elseif(isset($is_update_url) && $is_update_url == 1)
                <div class="col-sm-2">
                    <label class="control-label">Copy To</label>
                    <div class="{{ $errors->has('copy_to') ? ' has-error' : '' }}">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="copy_to" value="1" id="copy_to"
                            >Copy To</label>
                        </div>
                    </div>
                    @else 
                    <div class="col-sm-1">
                        <label class="control-label">Copy To</label>
                        <div class="{{ $errors->has('copy_to') ? ' has-error' : '' }}">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="copy_to" value="1" id="copy_to"
                                @if(old('copy_to') == '1') checked  @endif >Copy To</label>

                                @if ($errors->has('copy_to'))
                                <span class="help-block"><strong>{{ $errors->first('copy_to') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif  -->
<!-- <div class="col-sm-1">
 <label for="copy_to" class="control-label">{{ 'Copy To' }}</label>
 <div class="{{ $errors->has('copy_to') ? 'has-error' : ''}}">
  <label class="checkbox-inline">
   <?php ?>
   <input name="copy_to" type="checkbox"  id="copy_to" value="1" <?php if (isset($ro->copy_to) && !empty($ro->copy_to) && $ro->copy_to == 1) {
    echo "checked"; ?> disabled="disabled"
    <?php
  } else { ?>
  @if(old('advance_ro') == '1') checked  @endif  <?php
} ?> /> 
{!! $errors->first('copy_to','
<p class="help-block"> :message</p>
') !!}
</label>
</div>
</div> -->
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
<!-- <div id="mail-users" style="display:none;">
 <div class="col-sm-4">
  <label class="control-label">Notify to : </label>
  <div class="{{ $errors->has('email_users') ? 'has-error' : ''}}">
   <select data-placeholder="Select Name" class="chosen-select" multiple style="width:350px;" tabindex="4" name="email_users[]" id="select_name" @if(isset($selected_mail_users) && !empty($selected_mail_users))  @endif>
     <option  value="" disabled="disabled"> Select Notify Email</option>
     @if(isset($users) && count($users) > 0)
     @foreach($users as $value)
     <option @if(isset($selected_mail_users) && count($selected_mail_users) >0)
       @foreach($selected_mail_users as $entity)
       @if(isset($entity) && $entity == $value->id)
       selected="selected" disabled="disabled"
       @endif
       @endforeach
       @endif
       value="{{$value->id}}"  {{ (collect(old('email_users'))->contains($value->id)) ? 'selected':'' }} >{{$value->name}}</option>
       @endforeach
       @endif
     </select>
     {!! $errors->first('email_users','
     <p class="help-block"> :message</p>
     ') !!}
   </div>
 </div>
</div> -->


<!-- <div class="hr-line-dashed"></div> -->

<div class="col-sm-4">
 <label for="copy_to" class="control-label">{{ 'Is Invoice Present?' }}</label>
 <div class="{{ $errors->has('is_invoice_details') ? 'has-error' : ''}}">
  <label class="checkbox-inline">

   <?php if (isset($ro->is_invoice_present) && !empty($ro->is_invoice_present) && $ro->is_invoice_present == 1) { ?>
   <input type="checkbox"  id="is_invoice_present" value="1" checked disabled="disabled" />
   <input name="is_invoice_present" type="hidden" value="1" />
  <?php } else { ?>
   <input name="is_invoice_present" type="checkbox"  id="is_invoice_present" value="1"  @if(old('is_invoice_present') == '1') checked  @endif />
   <?php }?>

  <!--  <input name="is_invoice_present" type="checkbox"  id="is_invoice_present" value="1" <?php if (isset($ro->is_invoice_present) && !empty($ro->is_invoice_present) && $ro->is_invoice_present == 1) {
    echo "checked"; ?> disabled="disabled"
    <?php
  } else { ?>
  @if(old('is_invoice_present') == '1') checked  @endif  <?php
} ?> />  -->

{!! $errors->first('is_invoice_present','
<p class="help-block"> :message</p>
') !!}
</label>
</div>
</div>
</div>
@include('copy_to')
<div class="hr-line-dashed"></div>
<div class="wrapper wrapper-content animated fadeInRight" id="invoice_table" style="display: none;">
  <div class="row">
<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Invoice Details</h5>
                            <div class="ibox-tools col-md-2">
                            
                          </div>
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered" id="previous_table">
                                <thead>
                                <tr>
                                  <th>Sr.No</th>
                                  <th>Invoice No.</th> 
                                  <th>Agency Name</th>
                                  <th>Qty</th> 
                                  <th>Period</th>
                                  <th>Amount sanctioned for payment (Rs)</th>
                                  <th>SLA Penalty Amount /Liquidated damages (Rs.)</th>
                                  <th>Taxes as applicable</th>
                                  <th>Withheld amount</th>
                                  <th>Net payable amount (Rs.)</th>
                                  <th>Action</th>
                              </tr>
                                </thead>
                                 <tbody>
                                @if(isset($invoice_details) && !empty($invoice_details))
                                <?php $count = 1;?>
                                  @foreach($invoice_details as $key =>$val)
                                 
                                     <tr class="field_group">
                                      <td class="sr_no">{{$count++}}</td>
                                      <td><input class="form-control invoice_no"  type="text" name="invoice[{{$key}}][invoice_no]" required value="{{isset($val['invoice_no'])?$val['invoice_no']:''}}"></td>
                                      <td><input class="form-control agency_name"  type="text" name="invoice[{{$key}}][agency_name]" required value="{{isset($val['agency_name'])?$val['agency_name']:''}}"></td>
                                      <td><input class="form-control qty"  type="text" name="invoice[{{$key}}][qty]" value="{{isset($val['qty'])?$val['qty']:''}}"></td>
                                      <td><input class="form-control period"  type="text" name="invoice[{{$key}}][period]" required value="{{isset($val['period'])?$val['period']:''}}" ></td>
                                      <td><input class="form-control amount_payment"  type="text" name="invoice[{{$key}}][amount_payment]" required value="{{isset($val['amount_payment'])?$val['amount_payment']:''}}"></td>
                                      <td><input class="form-control sla_amount"  type="text" name="invoice[{{$key}}][sla_amount]" value="{{isset($val['sla_amount'])? $val['sla_amount']:''}}"></td>
                                      <td><input class="form-control applicable_taxes"  type="text" name="invoice[{{$key}}][applicable_taxes]" required value="{{isset($val['applicable_taxes'])?$val['applicable_taxes']:''}}"></td>
                                       <td><input class="form-control withheld_amount"  type="text" name="invoice[{{$key}}][withheld_amount]" value="{{isset($val['withheld_amount'])?$val['withheld_amount']:''}}"></td>
                                       <td><input class="form-control net_payable_amount"  type="text" name="invoice[{{$key}}][net_payable_amount]" required value="{{isset($val['net_payable_amount'])?$val['net_payable_amount']:''}}"></td>
                                      <td class="placement-for-delete"></td>
                                  </tr>
                                 
                                  @endforeach
                               
                               @else 
                                <tr class="field_group">
                                    <td class="sr_no">1</td>
                                    <td><input class="form-control invoice_no"  type="text" name="invoice[0][invoice_no]" required></td>
                                    <td><input class="form-control agency_name"  type="text" name="invoice[0][agency_name]" required></td>
                                    <td><input class="form-control qty"  type="number" name="invoice[0][qty]"></td>
                                    <td><input class="form-control period"  type="text" name="invoice[0][period]" required></td>
                                    <td><input class="form-control amount_payment"  type="number" name="invoice[0][amount_payment]" required></td>
                                    <td><input class="form-control sla_amount" type="number" name="invoice[0][sla_amount]"></td>
                                    <td><input class="form-control applicable_taxes"  type="number" name="invoice[0][applicable_taxes]" required></td>
                                     <td><input class="form-control withheld_amount"  type="number" name="invoice[0][withheld_amount]"></td>
                                     <td><input class="form-control net_payable_amount"  type="number" name="invoice[0][net_payable_amount]" required></td>
                                    <td class="placement-for-delete"></td>
                                </tr>
                             @endif 
                                </tbody> 
                                  <tfoot>
                                  <tr>
                                  
                                     <td colspan="9" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span id="total_payable_amount"></td>
                                    
                                </tr> 
                               
                                </tfoot>
                            </table>
                            <div class="ibox-tools col-md-2 col-md-offset-10">
                            <a id="add_row" class="btn btn-primary dim">Add</a>  
                           </div> 

                        </div>
                    </div>
                </div>
               </div>
          </div> 
@include('transactions_view') 
