@extends('layouts.app')
@section('title', 'Vendor')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/iCheck/custom.css') !!}" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title col-md-12 display-flex justify-content-between">
                <h3 class="col-md-10"><strong> Vendor</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/vendor') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
            <!-- <div class="ibox-title">
               <h3><strong> </strong></h3> 
               <a href="{{ url('/vendor') }}" title="Back"><button class="btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div> --> 
           <?php if(isset($result->id) && !empty($result->id)) { 
            $id = $result->id; }
              if(isset($is_update_url) && $is_update_url==1)
                    {
                        $url = '/vendor/'.$id;
                        $action = 'post';
                    }
                    else
                    {
                        $url = '/vendor';
                        $action = 'POST';
                    }?>           
            <div class="ibox-content" style="margin-bottom: 50px;">
                <form method="<?php echo $action;?>" class="form-horizontal" action="{{url($url)}}">
                {{csrf_field()}}
                  @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                 <input type="hidden" name="edit_id" value="{{isset($result->id) && !empty($result->id) ?$result->id:''}}">
                    <div class="form-group">
                        <div class="col-md-3 col-sm-6 col-sm-6">
                            <label class="control-label">Name<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('vendor_name') ? ' has-error' : '' }}">
                                <input type="text textonly"  placeholder="Name" class="form-control textnumberonly" name="vendor_name"<?php if(isset($result->vendor_name) && !empty($result->vendor_name)){?> value="{{ $result->vendor_name}}" <?php } else{ ?> value="{{old('vendor_name')}}" <?php } ?> required>
                                @if ($errors->has('vendor_name'))
                                <span class="help-block"><strong>{{ $errors->first('vendor_name') }}</strong></span>
                                 @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Email<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email"  placeholder="Email" class="form-control" name="email" <?php if(isset($result->email) && !empty($result->email)){?> value="{{ $result->email}}" <?php } else{ ?> value="{{old('email')}}" <?php } ?> required>
                                 @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                 @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Mobile Number<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                                <input type="text"  placeholder="Mobile Number" class="form-control numberonly" pattern="[0-9]{10}" name="mobile_no" maxlength="10" minlength="10"<?php if(isset($result->mobile_no) && !empty($result->mobile_no)){?> value="{{ $result->mobile_no}}" <?php } else{ ?> value="{{old('mobile_no')}}" <?php } ?> required>
                                @if ($errors->has('mobile_no'))
                                <span class="help-block"><strong>{{ $errors->first('mobile_no') }}</strong></span>
                                 @endif
                            </div>
                        </div>
                         <div class="col-md-3 col-sm-6">
                            <label class="control-label">Contact Number</label>
                            <div class="{{ $errors->has('contact_no') ? ' has-error' : '' }}">
                                <input type="text"  placeholder="Contact Number" class="form-control numberonly" name="contact_no" pattern="[0-9]{12}" maxlength="12" minlength="12" <?php if(isset($result->contact_no) && !empty($result->contact_no)){?> value="{{ $result->contact_no}}" <?php } else{ ?> value="{{old('contact_no')}}" <?php } ?> >
                                @if ($errors->has('contact_no'))
                                <span class="help-block"><strong>{{ $errors->first('contact_no') }}</strong></span>
                                 @endif
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Address</label>
                            <div class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                <textarea class="form-control message-input" name="address" maxlength="255" placeholder="Address"><?php if(isset($result->address) && !empty($result->address)){?>{{ trim($result->address)}} <?php } else { ?>{{old('address')}}<?php } ?></textarea>
                                @if ($errors->has('address'))
                                <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Bank Account Number<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('bank_acc_no') ? ' has-error' : '' }}">
                                <input required type="text"  placeholder="Bank Account Number" class="form-control textnumberonly" name="bank_acc_no" <?php if(isset($result->bank_acc_no) && !empty($result->bank_acc_no)){?> value="{{ $result->bank_acc_no}}" <?php } else{ ?> value="{{old('bank_acc_no')}}" <?php } ?> >
                                 @if ($errors->has('bank_acc_no'))
                                    <span class="help-block"><strong>{{ $errors->first('bank_acc_no') }}</strong></span>
                                @endif
                            </div>
                        </div>
                         <div class="col-md-3 col-sm-6">
                            <label class="control-label">GSTIN <span class="e-color">*</span></label>
                            <div class="{{ $errors->has('gstin') ? ' has-error' : '' }}">
                                <input required type="text" placeholder="GSTIN" class="form-control" name="gstin" <?php if(isset($result->gstin) && !empty($result->gstin)){?> value="{{ $result->gstin}}" <?php } else{ ?> value="{{old('gstin')}}" <?php } ?> >
                                    @if ($errors->has('gstin'))
                                        <span class="help-block"><strong>{{ $errors->first('gstin') }}</strong></span>
                                    @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Select Account Type</label>
                            <div class="{{ $errors->has('account_type') ? ' has-error' : '' }}">
                                <select data-placeholder="Select Account Type" class="form-control chosen-select"  name="account_type">
                                    <option disabled="disabled" selected>Select Account Type</option>
                                    <option @if(isset($result->account_type) && !empty($result->account_type) && $result->account_type == 'current') selected @endif value="current">Current</option>
                                    <option @if(isset($result->account_type) && !empty($result->account_type) && $result->account_type == 'saving') selected @endif value="saving">Saving</option>
                                 </select>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    
                    <div class="form-group">
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">IFSC Code<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('ifsc_code') ? ' has-error' : '' }}">
                                <input required type="text"  placeholder="IFSC Code" class="form-control textnumberonly" name="ifsc_code" <?php if(isset($result->ifsc_code) && !empty($result->ifsc_code)){?> value="{{ $result->ifsc_code}}" <?php } else{ ?> value="{{old('ifsc_code')}}" <?php } ?> >
                                    @if ($errors->has('ifsc_code'))
                                        <span class="help-block"><strong>{{ $errors->first('ifsc_code') }}</strong></span>
                                    @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Bank Name<span class="e-color">*</span></label>
                            <div class="{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                <input type="text" required placeholder="Bank Name" class="form-control textonly" name="bank_name" <?php if(isset($result->bank_name) && !empty($result->bank_name)){?> value="{{ $result->bank_name}}" <?php } else{ ?> value="{{old('bank_name')}}" <?php } ?>>
                                @if ($errors->has('bank_name'))
                                    <span class="help-block"><strong>{{ $errors->first('bank_name') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="control-label">Bank Branch</label>
                            <div class="{{ $errors->has('bank_branch') ? ' has-error' : '' }}">
                                <input type="text"  placeholder="Bank Branch" class="form-control textonly" name="bank_branch" <?php if(isset($result->bank_branch) && !empty($result->bank_branch)){?> value="{{ $result->bank_branch}}" <?php } else { ?> value="{{old('bank_branch')}}" <?php } ?>>
                                @if ($errors->has('bank_branch'))
                                    <span class="help-block"><strong>{{ $errors->first('bank_branch') }}</strong></span>
                                @endif
                            </div>
                        </div>
                          <div class="col-md-3 col-sm-6">
                            <label class="control-label">Bank Code</label>
                            <div class="{{ $errors->has('bank_code') ? ' has-error' : '' }}">
                                <input type="text"  placeholder="Bank Code" class="form-control textnumberonly" name="bank_code" <?php if(isset($result->bank_code) && !empty($result->bank_code)){?> value="{{ $result->bank_code}}" <?php } else{ ?> value="{{old('bank_code')}}" <?php } ?>>
                                @if ($errors->has('bank_code'))
                                    <span class="help-block"><strong>{{ $errors->first('bank_code') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
                   
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-5">
                          <!--  <button class="btn btn-primary" type="submit">Print</button> -->
                            <button class="btn btn-primary" type="submit">Submit</button>
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
<script src="{!! asset('js/plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/inspinia.js') !!}" type="text/javascript"></script>    
@endsection            