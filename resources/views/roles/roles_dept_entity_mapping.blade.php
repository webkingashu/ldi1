@extends('layouts.app')
@section('title', 'Role')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
          <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Add Role Department Entity Mapping</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/role-dept-entity') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>                   
                <div class="ibox-content" style="margin-bottom: 50px;">
                    <form method="POST" action="{{ url('/store/role-dept-entity') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group clone-component form-filled">
                            <div class="col-lg-3 col-sm-3">
                                <label for="department_id" class="control-label ">{{ 'Role & Department' }}<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('role_dept_mapper_id') ? 'has-error' : ''}}">
                                    <select name="role[0][role_dept_mapper_id]" class="form-control select2 role_dept_mapper_id" required>
                                       <option disabled="disabled" selected>Select Department</option>
                                       @if(isset($role_mapper) && !empty($role_mapper))
                                        @foreach($role_mapper as $value)
                                        <option value="{{$value['id']}}">{{$value['role_name']}} ({{$value['departments_name']}})</option>
                                        @endforeach
                                        @endif
                                    </select>{!! $errors->first('role_dept_mapper_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label class=" control-label">Entity<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('entity_id') ? ' has-error' : '' }}">
                                    <select  class="select2 entity_id" id ="entity_id" name="role[0][entity_id]" data-placeholder="Select Entity" required>
                                   <?php  if (isset($entity_list) && count($entity_list) > 0) { ?>
                                    <option disabled="disabled" selected="selected">Select Entity</option>
                                     <?php foreach ($entity_list as  $value) : ?>
                                  <option  <?php if(isset($entity_data) && in_array($value->id,$entity_data)) { ?> selected="selected"  <?php } ?>  value="{{$value->id}}" >{{$value->type_name}}</option>   
                                    <?php endforeach; } ?> 
                                </select>
                                {!! $errors->first('entity_id', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-lg-5">   
                                <label class=" control-label">Permission<span class="e-color">*</span></label>
                                <div class="{{ $errors->has('permissions') ? 'has-error' : ''}}">
                                   <?php  if (isset($permission_list) && count($permission_list) > 0) : ?>
                                       <?php foreach ($permission_list as  $value) : ?>
                                        <label class="checkbox-inline">
                                        <input  type="checkbox" class="permissions" name="role[0][permissions][]" <?php if(isset($assigned_permissions_array) && in_array($value->id,$assigned_permissions_array)) { ?> checked="checked" <?php } ?> value="{{$value->id}}">{{$value->display_name}}</label>
                                    <?php endforeach; endif; ?>
                                        {!! $errors->first('permissions', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2">
                                 <div class="input-group-btn add-button">
                                    <button class="btn btn-success add-more-department" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                                </div>
                                <div class="remove-button">
                                </div>
                                
                            </div>
                        </div>
                        <div class="after-add-more-document"></div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                             <div class="col-sm-4 col-sm-offset-5">
                                <input class="btn btn-primary" type="submit" value="Create">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
@endsection 
 <?php //echo '<pre>';print_r($entity_data);exit; ?>

@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
   // $('.chosen-select').chosen({width: "100%"});
    $(".select2").select2({
        placeholder: "Select Name",
        allowClear: true
    });
    var incr = 1;
    $(".add-more-department").click(function(){
        var $self = $('.after-add-more-document');
       $('.select2').select2('destroy');
       //$('.select2').find("span").remove();
        var cloned_data = $('.clone-component').clone().removeClass("clone-component");
        // var last = $('.form-filled:last');
        cloned_data.find('.role_dept_mapper_id').attr("name", "role[" + incr + "][role_dept_mapper_id]");
        cloned_data.find('.entity_id').attr("name", "role[" + incr + "][entity_id]");
        cloned_data.find('.permissions').attr("name", "role[" + incr + "][permissions][]");
        cloned_data.find('.add-button').hide();
        cloned_data.find("span.select2 ").remove();
        //cloned_data.find('.chosen-select').remove();
        cloned_data.find('.remove-button').html('<a href="#" class="btn btn-danger">Remove</a>');
        var append_div = $self.after(cloned_data);
        $(".select2").select2({
            width: '100%'
        })   
        incr++;
    });
    $(document).on('click', '.remove-button', function () {
    $(this).parent().parent().remove();
});


</script>     
@endsection 