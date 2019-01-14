
<a data-toggle="modal" href="#modal_copy_to"></a>
<div id="modal_copy_to" class="modal fade" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="text-center">
              <h3 class="m-t-none m-b">Copy To</h3>
            </div>
            @if(isset($copy_to_details) && !empty($copy_to_details))
            @foreach($copy_to_details as $copy_to_val)
            <div class="form-group"> 
              <div class="col-sm-6">
                <select name="department_id" class="form-control select2 update_department" required>
                 <option disabled="disabled" selected>Select Department</option>
                 @if(isset($list_of_departments))
                 @foreach($list_of_departments as $value) 
                 <option @if(old('department') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($copy_to_val['department_id']) && $copy_to_val['department_id'] == $value['id']) { echo 'selected';} ?>  value="{{ $value['id']}}" slug="{{ $value['slug']}}">{{ $value['name']}} - ({{$value['location_name']}})</option>
                 @endforeach
                 @endif
               </select>
             </div>
             <div class="col-sm-6">
               <select  class="select2 update_user" name="user_id" data-placeholder="Select User" required>
                <option disabled="disabled" selected="selected">Select Users</option>
                @if(isset($users) && !empty($users))
                @foreach($users as $value) 
                <option @if(old('users') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($copy_to_val['user_id']) && $copy_to_val['user_id'] == $value['id']) { echo 'selected';} ?>   value="{{ $value['id']}}" >{{ $value['name']}}</option>
                @endforeach
                @endif
              </select>
            </div>
          </div>
          @endforeach 
          @endif
          <div class="form-group"> 
            <input class="btn btn-primary btn btn-primary col-md-offset-5" id="update_copy_to" type="button"  value="Update">
            <input class="btn btn-primary btn btn-primary closeModal" type="reset"  value="Cancel" > 
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>
</div>
@if(isset($copy_to_details) && !empty($copy_to_details))
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Copy To Details</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary" id="show_copy_to">Add Copy To</a>
            <a class="btn btn-primary edit_copy_to">Edit</a>
          </div>
        </div>
        <div class="ibox-content">

          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Department and Location</th> 
                <th>User</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php $count = 1;?>
              @foreach($copy_to_details as $key =>$val)
              <tr class="field_group">
                <td class="sr_no">{{$count++}}</td>
                <td>{{isset($val['department_name'])? $val['department_name'] :''}}</td>
                <td>{{isset($val['user_name'])?$val['user_name']:''}}</td>
                <td>
                  <a  data-id={{$val['id']}} title="Delete" class="btn btn-danger btn-sm delete_copy_to"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                @endforeach
              </tbody> 
            </table>
          </div>
        </div>
      </div>
    </div>
  </div> 
  @endif
  @if(isset($is_create) && !empty($is_create) || empty($copy_to_details))
  <div id="copy_to_div" style="display:none;">  
    <div class="hr-line-dashed"></div>    
    <div class="form-group clone-div form-filled mr-3">
      <div class="col-lg-3 col-sm-3">
        <label for="department_id" class="control-label ">{{ 'Department And Location' }}<span class="e-color">*</span></label>
        <div class="{{ $errors->has('role_dept_mapper_id') ? 'has-error' : ''}}">
          <select name="copy[0][department_id]" class="form-control select2 department_id" required data-id="0">
           <option disabled="disabled" selected>Select Department</option>
           @if(isset($list_of_departments))
           @foreach($list_of_departments as $value) 
           <option @if(old('department') == $value['id']) selected @endif  <?php if(isset($value['id']) && isset($eas_master->department_id) && $eas_master->department_id == $value['id']) { echo 'selected';} ?>  value="{{ $value['id']}}" slug="{{ $value['slug']}}" >{{ $value['name']}} - ({{$value['location_name']}})</option>
           @endforeach
           @endif
         </select>{!! $errors->first('role_dept_mapper_id', '<p class="help-block">:message</p>') !!}
       </div>
     </div>
     <div class="col-lg-2">
      <label class=" control-label">Users<span class="e-color">*</span></label>
      <div class="{{ $errors->has('user_id') ? ' has-error' : '' }}">
        <select  class="select2 user_id"  name="copy[0][user_id]" data-placeholder="Select User" required data-id=0>

          <option disabled="disabled" selected="selected">Select Users</option>
          @if(isset($users) && !empty($users))
          @foreach($users as $value) 
          <option @if(old('users') == $value['id']) selected @endif    value="{{ $value['id']}}" >{{ $value['name']}}</option>
          @endforeach
          @endif
        </select>
        {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="col-lg-2 col-sm-2">
     <div class="input-group-btn add-button add-sm">
      <button class="btn btn-success add-more-department" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
    </div>
    <div class="remove-button remove-sm">
    </div>

  </div>
</div>
</div>
<div class="after-add-more-row"></div>
@endif
