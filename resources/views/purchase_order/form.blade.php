  <?php $session_eas_id = Session::get('eas_id'); //dd($session_eas_id); ?>

  <input type="hidden" name="vendor_email" id="vendor_email" 
  <?php if (isset($purchase_order_master->vendor_email) && !empty($purchase_order_master->vendor_email)) {?>
    value="{{ $purchase_order_master->vendor_email}} "
  <?php } else {?>
    value="{{ old('vendor_email') }}" <?php }?>>
    <div class="form-group  ">


      @if($session_eas_id)
      <div class="col-sm-4">
        <label for="eas" class="control-label">{{ 'EAS' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('eas_id') ? 'has-error' : ''}}">
 @if(isset($session_eas_id) && !empty($session_eas_id)>0)
<input type="hidden" name="eas_id" value="{{ $session_eas_id }}?>">
@endif
<select class="chosen-select"  style="width:350px;" tabindex="4"  id="eas" disabled="">
  <option  selected="selected" disabled="">Select</option>
  @if(isset($session_eas_id))
  @foreach($eas as $eas_value)

  <option @if(old('eas_id') == $eas_value->id) selected @endif  value="{{ $eas_value->id }}" <?php if (isset($session_eas_id) && !empty($session_eas_id) && $session_eas_id == $eas_value->id)  {  echo 'selected';?>
<?php } ?>
>{{ $eas_value->sanction_title }}</option>
@endforeach
@endif   
</select>
{!! $errors->first('eas_id', '<p class="help-block">:message</p>') !!}
</div>
</div>
@else
<div class="col-sm-4">
  <label for="eas" class="control-label">{{ 'EAS' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('eas_id') ? 'has-error' : ''}}">
    <select class="chosen-select"  style="width:350px;" tabindex="4" name="eas_id" id="eas">
      <option disabled="disabled" selected="selected">Select</option>
      @if(isset($eas) && count($eas)>0)
      @foreach($eas as $eas_value)

      <option @if(old('eas_id') == $eas_value->id) selected @endif  value="{{ $eas_value->id }}" <?php if (isset($purchase_order_master->eas_id) && !empty($purchase_order_master->eas_id) && $purchase_order_master->eas_id == $eas_value->id)  {  echo 'selected';?>
    <?php } ?>
    >{{ $eas_value->sanction_title }}</option>
    @endforeach
    @endif   
  </select>
  {!! $errors->first('eas_id', '<p class="help-block">:message</p>') !!}
</div>
</div>
@endif




<div class="col-sm-4">
  <label for="vendor_name" class="control-label">{{ 'Vendor Name' }}<span class="e-color">*</span></label>
  <div class="{{ $errors->has('vendor_name') ? 'has-error' : ''}}">
    <input type="text" placeholder="Enter Vendor Name" class="form-control textnumberonly"  name="vendor_name" id="vendor_name"  readonly <?php if (isset($purchase_order_master->vendor_name) && !empty($purchase_order_master->vendor_name)) {?>
      value="{{ $purchase_order_master->vendor_name}} "
    <?php } else {?>
      value="{{ old('vendor_name') }}" <?php }?> />
      {!! $errors->first('vendor_name', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
  <div class="col-sm-4">
    <label for="vendor_address" class="control-label">{{ 'Vendor Address' }}<span class="e-color">*</span></label>
    <div class="{{ $errors->has('vendor_address') ? 'has-error' : ''}}"><textarea class="form-control" placeholder="Enter Vendor Address" rows="3" name="vendor_address" id="vendor_address" type="textarea" readonly><?php if (isset($purchase_order_master->vendor_address) && !empty($purchase_order_master->vendor_address)) {?>{{ trim($purchase_order_master->vendor_address)}}<?php } else {?>{{ old('vendor_address')}} <?php }?></textarea>
      {!! $errors->first('vendor_address', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group  ">
  <div class="col-sm-4">
    <label for="subject" class="control-label">{{ 'Subject' }}<span class="e-color">*</span></label>
    <div class="{{ $errors->has('subject') ? 'has-error' : ''}}"><input type="text" placeholder="Enter Subject" class="form-control textnumberonly" rows="5" name="subject" id="subject"  <?php if (isset($purchase_order_master->subject) && !empty($purchase_order_master->subject)) {?>
      value="{{ $purchase_order_master->subject}} "
    <?php } else {?>
      value="{{ old('subject') }}" <?php }?> >{!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
  <div class="col-sm-4">
    <label for="bid_number" class="control-label">{{ 'Bid Number' }}<span class="e-color">*</span></label>
    <div class="{{ $errors->has('bid_number') ? 'has-error' : ''}}"><input type="text" placeholder="Enter Bid Number" class="form-control textnumberonly" rows="5" name="bid_number"  id="bid_number" <?php if (isset($purchase_order_master->bid_number) && !empty($purchase_order_master->bid_number)) {?>
      value="{{ $purchase_order_master->bid_number}} "
    <?php } else {?>
      value="{{ old('bid_number') }}" <?php }?> >{!! $errors->first('bid_number', '<p class="help-block">:message</p>') !!}
    </div>
  </div>
  <div class="col-sm-4">
    <label for="date_of_bid" class="control-label">{{ 'Date Of Bid' }}</label>
    <div class="{{ $errors->has('date_of_bid') ? 'has-error' : ''}}">
      <div class="input-group date">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" readonly="" placeholder="Select Date Of Bid" class="form-control" rows="5" name="date_of_bid" id="date_of_bid" 
        <?php if (isset($purchase_order_master->date_of_bid) && !empty($purchase_order_master->date_of_bid)) {?>
          value="{{ $purchase_order_master->date_of_bid}} "
        <?php } else {?>
          value="{{ old('date_of_bid') }}" <?php }?> > 
        </div>{!! $errors->first('date_of_bid', '<p class="help-block">:message</p>') !!}
      </div>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="form-group ">
    <div class="col-sm-4">
      <label for="date_of_bid" class="control-label">{{ 'FA Date' }}<span class="e-color">*</span></label>
      <div class="{{ $errors->has('fa_date') ? 'has-error' : ''}}">
        <div class="input-group date">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" readonly="" placeholder="Select Date Of FA" class="form-control date_added" rows="5" name="fa_date" id="fa_date" 
          <?php if (isset($purchase_order_master->fa_date) && !empty($purchase_order_master->fa_date)) {?>
            value="{{ $purchase_order_master->fa_date}} "
          <?php } else {?>
            value="{{ old('fa_date') }}" <?php }?> > 
          </div>{!! $errors->first('fa_date', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
      <div class="col-sm-4">
        <label for="title_of_bid" class="control-label">{{ 'Title Of Bid' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('title_of_bid') ? 'has-error' : ''}}"><input class="form-control textnumberonly" placeholder="Enter Title Of Bid" rows="5" name="title_of_bid" type="text" id="title_of_bid"  <?php if (isset($purchase_order_master->title_of_bid) && !empty($purchase_order_master->title_of_bid)) {?>
          value="{{ $purchase_order_master->title_of_bid}} "
        <?php } else {?>
          value="{{ old('title_of_bid') }}" <?php }?> />{!! $errors->first('title_of_bid', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
          
    </div>
  
    <div class="hr-line-dashed"></div>    
    <div class="form-group">
     <div class="col-sm-3">
      <label for="">Document Name</label>
      <div class="input-group control-group-1 after-add-more-document" >
        <input type="text" placeholder="Document Name" class="form-control" rows="5" name="documents_type[]" />
      </div>
    </div>
    <div class= "col-sm-6">
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
 
@include('copy_to')
<!-- <div class="hr-line-dashed"></div> -->
     <!--   @if(isset($item_details) && !empty($item_details))             
                <div class="wrapper wrapper-content animated fadeInRight" id="edit_item">
                  <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title d-flex justify-content-between">
                            <h5>Item Details</h5>
                           
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered" id="previous_table">
                                <thead>
                                <tr>
                                  <th>Sr.No</th>
                                  <th>Category</th> 
                                  <th>Item</th>
                                  <th>Qty</th> 
                                  <th>Unit Price Excl Tax</th>
                                  <th>Total Price Excl Tax</th>
                                  
                              </tr>
                                </thead>
                                 <tbody>
                               
                                <?php $count = 1;?>
                                  @foreach($item_details as $key =>$val)
                                 
                                     <tr class="field_group">
                                     <td class="sr_no">{{$count++}}</td>
                                      <td><span class="category">{{isset($val['category'])?$val['category']:''}}</span></td>
                                      <td><span class="item">{{isset($val['item'])?$val['item']:''}}</span></td>
                                      <td class="qty">{{isset($val['qty'])?$val['qty']:''}}</td>
                                      <td class="unit_price_tax">{{isset($val['unit_price_tax'])?$val['unit_price_tax']:''}}</td>
                                     <td><span class="total_unit_price_tax"></td>
                                  </tr>
                                  @endforeach
                                </tbody> 
                                 <tfoot>
                                  <tr>
                                     <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span id="total_price_tax"></td>
                                </tr> 
                               
                                </tfoot>
                            </table>
                           
                    </div>
                </div>
               </div>
          </div>
          @endif -->
<div id="invoice_details" style="display: none;">
<div class="hr-line-dashed"></div>
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Item Details</h5>
                            <div class="ibox-tools col-md-2">
                            
                          </div>
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered"  id="previous_table">
                                <thead>
                                <tr class="field_group">
                                  <th>Sr.No</th>
                                  <th>Category</th> 
                                  <th>Item</th>
                                  <th>Qty</th> 
                                  <th>Unit Price Excl Tax</th>
                                  <th>Total Price Excl Tax</th>
                              </tr>
                                </thead>
                                 <tbody id="invoice">
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
               </div>
          </div>  
        </div>
         
@include('transactions_view') 

