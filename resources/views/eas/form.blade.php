<div class="form-group ">
    <div class="col-md-4">
        <label for="sanction_title" class="control-label">{{ 'Title of the Sanction' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('sanction_title') ? 'has-error' : ''}} input-ht">
            <input class="form-control textnumberonly" required name="sanction_title" type="text" placeholder="Sanction Title" id="sanction_title" <?php if(isset($eas_master->sanction_title ) && !empty($eas_master->sanction_title)){?> value="{{ $eas_master->sanction_title}}" <?php } else{ ?> value="{{old('sanction_title')}}" <?php } ?> />
       
           {!! $errors->first('sanction_title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-4">
        <label for="sanction_purpose" class="control-label">{{ 'Purpose of Sanction' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('sanction_purpose') ? 'has-error' : ''}} input-ht">
        <textarea class="form-control textnumberonly" maxlength="255" required rows="3" name="sanction_purpose" id="sanction_purpose" placeholder="Sanction Purpose"><?php if(isset($eas_master->sanction_purpose)&& !empty($eas_master->sanction_purpose)){?>{{trim($eas_master->sanction_purpose)}}<?php } else { ?> {{old('sanction_purpose')}} <?php } ?></textarea>
            {!! $errors->first('sanction_purpose', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-4">
        <label for="competent_authority" class="control-label">{{ 'Select the competent authority' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('competent_authority') ? 'has-error' : ''}} input-ht">
            <select class="form-control chosen-select" name="competent_authority" required>
                <option disabled="disabled" selected>Select the competent </option>
                <option  @if(old('competent_authority') == 'govt_authority_schedule') selected @endif 
                <?php if(isset($eas_master->competent_authority) && !empty($eas_master->competent_authority) && $eas_master->competent_authority == "govt_authority_schedule") { echo 'selected';}?> value="govt_authority_schedule">Govt. Authority or Schedule</option>
                <option @if(old('competent_authority') == 'sub_schedule') selected @endif   <?php if(isset($eas_master->competent_authority) && !empty($eas_master->competent_authority) && $eas_master->competent_authority == "sub_schedule") { echo 'selected';}?> value="sub_schedule">Sub-Schedule </option>
                <option @if(old('competent_authority') == 'dofp') selected @endif  <?php if(isset($eas_master->competent_authority) && !empty($eas_master->competent_authority) && $eas_master->competent_authority == "dofp") { echo 'selected';}?> value="dofp">DOFP</option>
                <!-- {{ $eas_master->competent_authority or ''}} -->
            </select>
            {!! $errors->first('competent_authority', '<p class="help-block">:message</p>') !!}
        </div> 
    </div>    
</div>
<div class="hr-line-dashed"></div>
<div class="form-group ">

    <div class="col-sm-4">
        <label for="department" class="control-label">{{ 'Select Department' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('department') ? 'has-error' : ''}} input-ht">

            <select class="form-control chosen-select" name="department" placeholder="Select Department" id="department">      
                <option disabled="disabled" selected>Select Department</option>

                @if(isset($eas_master->department_id) && !empty($eas_master->department_id))

                 <option @if(old('department') == $eas_master->department_id) selected @endif value="{{ $eas_master->department_id}}" selected slug="{{ $eas_master->department_slug}}">{{ $eas_master->department_name}}</option>

                @else
                @if(isset($list_of_departments))

                    @foreach($list_of_departments as $value) 
                    
                    <option @if(old('department') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($eas_master->department_id) && $eas_master->department_id == $value['id']) { echo 'selected';} ?>  value="{{ $value['id']}}" slug="{{ $value['slug']}}">{{ $value['name']}} - ({{$value['location_name']}})</option>
                    @endforeach
                   
                @endif
                @endif
            </select>
            {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-sm-4">
         <label for="serial_no_of_sanction" class="control-label">{{ 'Serial Number of Sanction' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('serial_no_of_sanction') ? 'has-error' : ''}} input-ht">
            <input class="form-control textnumberonly" required name="serial_no_of_sanction" placeholder="Serial Number of Sanction" type="text" id="serial_no_of_sanction" readonly
            <?php if(isset($eas_master->serial_no_of_sanction) && !empty($eas_master->serial_no_of_sanction)){?> value="{{ $eas_master->serial_no_of_sanction}}" <?php } else { ?> value="{{isset($serial_no_of_sanction)? $serial_no_of_sanction : ''}}" <?php } ?> >
            {!! $errors->first('serial_no_of_sanction', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4">
        <label for="file_number" class="control-label">{{ 'File Number' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('file_number') ? 'has-error' : ''}} input-ht">
            <input class="form-control" readonly name="file_number" type="text" placeholder="File Number" id="file_number" 
            <?php if(isset($eas_master->file_number ) && !empty($eas_master->file_number)){?> value="{{ $eas_master->file_number}}" <?php } else{ ?> value="{{old('file_number')}}" <?php } ?> >
            {!! $errors->first('file_number', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    
     <div class="col-sm-4">
        <label for="budget_code" class="control-label">{{ 'Budget Code' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('budget_code') ? 'has-error' : ''}} input-ht">

            <select class="form-control chosen-select" id="budget_code" name="budget_code" placeholder="Budget Code">      
                <option disabled="disabled" selected>Select Budget Code</option>
                 
                @if(isset($budget_code) && count($budget_code) > 0)
                    @foreach($budget_code as $value) 
                    <option @if(old('budget_code') == $value['budget_code']) selected @endif  <?php if(isset($value['id']) && isset($eas_master->budget_list_id) && $eas_master->budget_list_id == $value['id']) { echo 'selected';} ?>  budget_amount={{$value['amount']}} value="{{ $value['id']}}">{{ $value['budget_code']}} - {{ $value['budget_head_of_acc']}} - ({{ $value['oh']}})</option>
                    @endforeach
                @endif
            </select>
            {!! $errors->first('budget_code', '<p class="help-block">:message</p>') !!}
        </div>
        <span id="budget_head_amount" style="color:#000;margin-top: 5px;"></span>
    </div> 
    <div class="col-sm-4">
       <label for="sanction_total" class="control-label">{{ 'Value of Sanction Total in Rs.' }}<span class="e-color">*</span></label>
       <div class="{{ $errors->has('sanction_total') ? 'has-error' : ''}} input-ht">
           <input class="form-control" required name="sanction_total" type="number"  placeholder="Value of Sanction Total" id="sanction_total"
           <?php if(isset($eas_master->sanction_total ) && !empty($eas_master->sanction_total)){?> value="{{ $eas_master->sanction_total}}" <?php } else{ ?> value="{{old('sanction_total')}}" <?php } ?> >
           {!! $errors->first('sanction_total', '<p class="help-block">:message</p>') !!}
       </div>
   </div>
     
      <div class="col-sm-4">
        <div class="input-group-btn">
        <button class="btn btn-success" type="button" id="check_budget_head" style="margin-top: 31px;">Check Budget Amount</button>
      </div> 
</div>
</div>

<div class="hr-line-dashed"></div>
<div class="form-group ">
   
    <div class="col-sm-4">
        <label for="validity_sanction_period" class="control-label">{{ 'Validity of Sanction Period' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('validity_sanction_period') ? 'has-error' : ''}} input-ht">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <div class="date-pos">
                <input  type="text" class="form-control date_added" required placeholder="validity of Sanction Period" name="validity_sanction_period" id="validity_sanction_period" <?php if(isset($eas_master->cfa_dated ) && !empty($eas_master->cfa_dated)){?> value="{{ $eas_master->validity_sanction_period}}" <?php } else{ ?> value="{{old('validity_sanction_period')}}" <?php } ?> >  
                </div>          
            </div> 
            {!! $errors->first('validity_sanction_period', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4">
        <label for="date_issue" class="control-label">{{ 'Date of Issue' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('date_issue') ? 'has-error' : ''}} input-ht">
             <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <div class="date-pos">
                <input class="form-control date_added" required name="date_issue" type="text" id="date_issue" placeholder="Date of Issue"  
            <?php if(isset($eas_master->date_issue ) && !empty($eas_master->date_issue)){?> value="{{ $eas_master->date_issue}}" <?php } else{ ?> value="{{old('date_issue')}}" <?php } ?> >         
            </div>   
            </div> 

            
            {!! $errors->first('date_issue', '<p class="help-block">:message</p>') !!}
        </div>
    </div> 
     <div class="col-sm-4">
        <label for="vendor_name" class="control-label">{{ 'Name of Payee Agency' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('vendor_id') ? 'has-error' : ''}} input-ht">
            <select class="form-control chosen-select" name="vendor_id" id="vendor_id" required>      
                <option disabled="disabled" selected>Select Name of Payee Agency</option>
                @if(isset($vendor_name) && count($vendor_name) > 0)
            @foreach($vendor_name as $value) 
            <option @if(old('vendor_id') == $value->id ) selected @endif <?php if(isset($value->id) && isset($eas_master->vendor_id) && $eas_master->vendor_id == $value->id) { echo 'selected';} ?> value="{{$value->id}}">{{$value->vendor_name}}</option>
            @endforeach
            @endif
            </select>
            {!! $errors->first('vendor_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div id="vendor_details" style="display: none;">
<div class="hr-line-dashed"></div>
<div class="form-group">
    <h3 class="col-md-offset-5"><strong>Vendor Details</strong></h3>
    <div class="col-sm-4">
        <label for="cfa_note_number" class="control-label">{{ 'Vendor Contact Number' }}<span class="e-color">*</span></label>
            <input class="form-control" id="vendor_mobile_no" disabled>
          
    </div>
    <div class="col-sm-4">
        <label for="cfa_note_number" class="control-label">{{ 'Vendor GSTIN' }}<span class="e-color">*</span></label>
            <input class="form-control" id="vendor_gstin" disabled>
          
    </div>
    <div class="col-sm-4">
        <label for="cfa_note_number" class="control-label">{{ 'Vendor Bank Account Number' }}<span class="e-color">*</span></label>
            <input class="form-control" id="vendor_bank_acc_no" disabled>
          
    </div>
</div>
</div>

<div class="hr-line-dashed"></div>
<div class="form-group ">
    <h3 class="col-md-offset-5"><strong>CFA Approval (Approval given wide)</strong></h3>
    <div class="col-sm-4">
        <label for="cfa_note_number" class="control-label">{{ 'CFA Note Number' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('cfa_note_number') ? 'has-error' : ''}} input-ht">
            <input class="form-control textnumberonly" required name="cfa_note_number" placeholder="CFA Note Number" type="text" id="cfa_note_number" 
            <?php if(isset($eas_master->cfa_note_number ) && !empty($eas_master->cfa_note_number)){?> value="{{ $eas_master->cfa_note_number}}" <?php } else{ ?> value="{{old('cfa_note_number')}}" <?php } ?> >
            {!! $errors->first('cfa_note_number', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-sm-4">
        <label for="cfa_dated" class="control-label">{{ 'CFA Dated' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('cfa_dated') ? 'has-error' : ''}} input-ht">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <div class="date-pos">
                    <input  type="text" class="form-control date_added" required placeholder="CFA Dated" name="cfa_dated" id="cfa_dated" <?php if(isset($eas_master->cfa_dated ) && !empty($eas_master->cfa_dated)){?> value="{{ $eas_master->cfa_dated}}" <?php } else{ ?> value="{{old('cfa_dated')}}" <?php } ?> >            
                </div>
            </div>  
            {!! $errors->first('cfa_dated', '<p class="help-block">:message</p>') !!}  
        </div>
    </div>  
    <div class="col-sm-4">
        <label for="cfa_designation" class="control-label">{{ 'CFA Designation' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('cfa_designation') ? 'has-error' : ''}} input-ht">
            <input class="form-control textnumberonly" required name="cfa_designation" placeholder="CFA Designation" type="text" id="cfa_designation" <?php if(isset($eas_master->cfa_designation ) && !empty($eas_master->cfa_designation)){?> value="{{ $eas_master->cfa_designation}}" <?php } else{ ?> value="{{old('cfa_designation')}}" <?php } ?> >
            {!! $errors->first('cfa_designation', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group ">
    
    <div class="col-sm-4">
        <label class="control-label">Whether being issued under<span class="e-color">*</span></label>
        <div class="{{ $errors->has('whether_being_issued_under') ? 'has-error' : ''}} input-ht">
            <label class="checkbox-inline">
            <input type="checkbox" class="fa_concurrence" value="fa_concurrence" @if(isset($eas_master->whether_being_issued_under) && $eas_master->whether_being_issued_under == 'fa_concurrence') checked @endif 
            @if(old('whether_being_issued_under') == 'fa_concurrence') checked @endif id="fa_concurrence" name="whether_being_issued_under">With FA Concurrence</label>
            <label class="checkbox-inline">
            <input type="checkbox" class="fa_concurrence" value="delegate_powers"  @if(isset($eas_master->whether_being_issued_under) && $eas_master->whether_being_issued_under == 'delegate_powers') checked @endif @if(old('whether_being_issued_under') == 'delegate_powers' ) checked @endif id="delegate_powers" name="whether_being_issued_under">Delegated Powers</label>
            {!! $errors->first('whether_being_issued_under', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div id="is_delegated_powers">
        <div class="col-sm-4">
            <label for="fa_dated" class="control-label">{{ 'FA Dated' }}</label>
            <div class="{{ $errors->has('fa_dated') ? 'has-error' : ''}} input-ht">
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input  type="text" class="form-control date_added" placeholder="FA Dated" name="fa_dated" id="fa_dated" <?php if(isset($eas_master->fa_dated ) && !empty($eas_master->fa_dated)){?> value="{{ $eas_master->fa_dated}}" <?php } else { ?> value="{{old('fa_dated')}}" <?php } ?> >
                </div>
                {!! $errors->first('fa_dated', '<p class="help-block">:message</p>') !!}            
            </div>
        </div>
        <div class="col-sm-4">
            <label for="fa_number" class="control-label">{{ 'FA Number' }}</label>
            <div class="{{ $errors->has('fa_number') ? 'has-error' : ''}} input-ht">
                <input class="form-control"  name="fa_number" type="text" placeholder="FA Number" id="fa_number" <?php if(isset($eas_master->fa_number ) && !empty($eas_master->fa_number)){?> value="{{ $eas_master->fa_number}}" <?php } else { ?>  value="{{isset($fa_number)? $fa_number : ''}}" <?php } ?> >
                 {!! $errors->first('fa_number', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
</div>
</div>
<div class="hr-line-dashed"></div>   

<div class="form-group document-input">
   <div class="col-sm-3">
          <label for="">Document Name</label>
          <div class="input-group control-group-1 after-add-more-document " >
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
            <input type="file"  id="file_uploads" name="file_upload[]" onchange="show(this)" />

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
                        @if(old('copy_to') == '1') checked  @endif @if(isset($eas_master->copy_to) && $eas_master->copy_to) == 1) checked @endif>Copy To</label>
                        @if ($errors->has('copy_to'))
                        <span class="help-block"><strong>{{ $errors->first('copy_to') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>   
            </div>
        @endif    
            @include('copy_to')
               
          @if(isset($item_details) && !empty($item_details))           
         <div class="wrapper wrapper-content animated fadeInRight" id="edit_item">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Item Details</h5>
                            <div class="ibox-tools col-md-2 col-md-offset-10">
                          
                            <a id="edit_item_popup" class="btn btn-primary">Edit</a>
                            <a id="add_item_popup" class="btn btn-primary">Add</a>
                           
                          </div>
                        
                          </div>
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
                                  <th>Action</th>
                              </tr>
                                </thead>
                                 <tbody>
                                  
                                    <?php $count = 1;?>
                                  @foreach($item_details as $key =>$val)
                                    <tr class="field_group">
                                     <td class="sr_no">{{$count++}}</td>
                                      <td><span class="category">{{isset($val['category'])?$val['category']:''}}</span></td>
                                      <td><span class="category">{{isset($val['item'])?$val['item']:''}}</span></td>
                                      <td><span class="qty">{{isset($val['qty'])?$val['qty']:''}}</span></td>
                                      <td><span class="unit_price_tax">{{isset($val['unit_price_tax'])?$val['unit_price_tax']:''}}</span></td>
                                     <td><span class="total_unit_price_tax">{{isset($val['total_unit_price_tax'])?$val['total_unit_price_tax']:''}}</span></td>
                                    <td class="placement-for-delete"><a data-id={{$val['id']}} title="Delete" class="btn btn-danger btn-sm delete_item_details"><i class="fa fa-trash-o"></i></a></td></td>
                                  </tr>
                                  @endforeach
                                
                                  </tbody>
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
          @endif

          @if(isset($is_create) && !empty($is_create) || empty($item_details))
           <div class="wrapper wrapper-content animated fadeInRight" id="add_item">
                  <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Item Details</h5>
                            <div class="ibox-tools col-md-2 col-md-offset-10">
                            <a id="add_row" class="btn btn-primary">Add</a> 
                          </div>
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
                                  <th>Action</th>
                              </tr>
                                </thead>
                                 <tbody>
                                     <tr class="create_field_group">
                                     <td class="sr_no">1</td>
                                      <td><input class="form-control category" type="text" name="item[0][category]"></td>
                                      <td><input class="form-control item" type="text" name="item[0][item]"></td>
                                      <td><input class="form-control qty" type="number" name="item[0][qty]"></td>
                                      <td><input class="form-control unit_price_tax" type="number" name="item[0][unit_price_tax]"></td>
                                     <td><input class="form-control total_unit_price_tax" type="hidden" name="item[0][total_unit_price_tax]"><span class="total_unit_price_tax"></td>
                                     <td class="placement-for-delete"></td>   
                                  </tr>
                                </tbody> 
                                 <tfoot>
                                  <tr>
                                     <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span id="create_total_price_tax"></td>
                                </tr> 
                               
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
               </div>
          </div>
        @endif  
          
@include('transactions_view') 
