@extends('layouts.app')
@section('title', 'Transaction')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox">

       <div class="ibox-title col-md-12 display-flex">
            <h3 class="col-md-10"><strong> Transaction</strong></h3>
            <div class="ibox-tools col-md-2">
                <a href="{{ url('/transaction') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
        </div>
       <?php if(isset($transaction->id) && !empty($transaction->id)) { 
        $id = $transaction->id; }

        if(isset($is_update_url) && $is_update_url==1 && isset($id)) {

          $url = '/transaction/'.$id;
          $action = 'POST';

        } else {
          $url = 'transaction/';
          $action = 'POST';
        }       ?>  
      </div>                   
      <div class="" style="margin-bottom: 50px;">
        <div class="ibox-content">
          <form id="frm" action="{{url($url)}}" method="<?php echo $action; ?>" class="form-horizontal form-label-left">
              {{csrf_field()}}
            @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
            <input type="hidden" name="user_id" value="{{isset($result->user_id)?$result->user_id:'1'}}">
            <input type="hidden" name="id" value="{{isset($result->id)?$result->id:'1'}}">

                <div class="form-group">
                  <div class="col-lg-4">
                    <label for="transaction_name">Transaction Name<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('transaction_name') ? ' has-error' : '' }}" >
                    <input type="text" class="form-control" name="transaction_name"  placeholder="Transaction Name" <?php if(isset($transaction->transaction_name ) && !empty($transaction->transaction_name)){?> value="{{ $transaction->transaction_name}}"
                    <?php } else { ?> value="{{old('transaction_name')}}" <?php } ?>>
                    @if ($errors->has('transaction_name'))
                    <span class="help-block"><strong>{{ $errors->first('transaction_name') }}</strong></span>
                    @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                      <label for="from_status">From<span class="e-color">*</span></label>
                      <div class="{{ $errors->has('from_status') ? ' has-error' : '' }}" >
                      <select class="form-control select2" id ="from_status" name="from_status" data-placeholder="From Status" required>
                       <?php  if (isset($get_status) && count($get_status) > 0) : ?>
                          <option value="" disabled="disabled" selected="selected">From Status </option>
                          <?php foreach ($get_status as  $value) : ?>
                          <option 
                           <?php if(isset($value->id) &&  isset($transaction->from_status) && $transaction->from_status == $value->id) { echo 'selected'; } ?>

                           <?php if(isset($value["status_name"]) ) {  ?>  <?php } ?> value="<?= $value['id'] ?>"> <?php echo $value["status_name"]; ?> 
                          </option>
                          <?php endforeach;
                          endif; ?>
                      </select>
                      @if ($errors->has('from_status'))
                      <span class="help-block"><strong>{{ $errors->first('from_status') }}</strong></span>
                      @endif
                      </div>
                  </div>
                  <div class="col-lg-4">
                    <label for="to_status">To<span class="e-color">*</span></label>
                    <div class="{{ $errors->has('to_status') ? ' has-error' : '' }}" >
                    <select class="form-control select2" id ="to_status" name="to_status" data-placeholder="To Status" required>
                     <?php if (isset($get_status) && count($get_status) > 0) : ?>
                       <option value="" disabled="disabled" selected="selected">To Status </option>
                       <?php foreach ($get_status as  $value) : ?>
                        <option <?php if(isset($value->id) &&  isset($transaction->to_status) && $transaction->to_status == $value->id) { echo 'selected'; } ?>
                         <?php if(isset($value["status_name"]) ) {  ?>  <?php } ?> value="<?= $value['id'] ?>"> <?php echo $value["status_name"]; ?> 
                        </option>
                          <?php endforeach;
                        endif; ?>
                    </select>
                    @if ($errors->has('to_status'))
                    <span class="help-block"><strong>{{ $errors->first('to_status') }}</strong></span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-4">
                  <label for="role_id">Role<span class="e-color">*</span></label>
                  <div class="{{ $errors->has('role_id') ? ' has-error' : '' }}">
                    <select class="select2 form-control" name="role_id[]" id="roleId" required multiple="true"> 
                        <!-- <option disabled="disabled" selected="selected">Select Role</option> -->
                        <?php if(isset($role) && count($role)>0) {
                         foreach ($role as $role_key) : ?>
                          <option 
                            <?php if(isset($assigned_role) && in_array($role_key->id,$assigned_role)) {  echo 'selected';  } ?>
                           <?php if(isset($role_key->name) ) {  ?>  <?php } ?> value="<?php echo $role_key->id; ?>"> <?php echo $role_key->name; ?> </option>
                          <?php endforeach; }?> 
                    </select> 
                    @if ($errors->has('role_id'))
                     <span class="help-block">
                      <strong>{{ $errors->first('role_id') }}</strong>
                    </span>
                    @endif
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
          <div class="form-buttons-w">
            <button type="submit" class="btn btn-primary" id="submit-button">Submit</button>
            &nbsp;<span class="print" id="message"></span>
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
<script>
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
  $(".select2").select2({
    placeholder: "Select Role",
    allowClear: true
  });

</script>     
@endsection 
