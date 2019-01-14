
 <?php //echo '<pre>';print_r($departments);exit; ?>
<div class="form-group ">
    <div class="col-lg-4 col-sm-4">
    	 <label for="name" class="control-label">{{ 'Name' }}<span class="e-color">*</span></label>
    	 <div class="{{ $errors->has('name') ? 'has-error' : ''}}">
            <input placeholder="Role Name" class="form-control" rows="5" name="name" type="text" id="name"
            <?php if (isset($role->name) && !empty($role->name)) {?>
                    value="{{ $role->name}}"
                <?php } else {?>
                    value="{{old('name')}}"<?php }?> required>
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="col-md-6">
        <label class="control-label">Departments<span class="e-color">*</span></label>
        <div class="{{ $errors->has('department_id') ? ' has-error' : '' }}">
                <select class="form-control chosen-select disabled-select" id ="department_id" name="department_id[]" multiple data-placeholder="Select Department" >
                    <?php  if (isset($departments) && count($departments) > 0) : ?>
                    
                    <?php foreach ($departments as $value) : 
                            ?>
                       <option 
                       @if(isset($role['department']) && !empty($role['department'])) @foreach($role['department'] as $selected_department) <?php if(isset($selected_department['department_id']) && !empty($selected_department['department_id']) && $selected_department['department_id'] == $value['id']) { echo 'selected';  } ?> @endforeach @endif value="{{$value['id']}}" >{{$value['name']}}</option>                
                   <?php 
                   endforeach;
               endif; ?>
           </select>
           @if ($errors->has('department_id'))
           <span class="help-block">
            <strong>{{ $errors->first('department_id') }}</strong>
        </span>
        @endif
      </div>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
     <div class="col-sm-4 col-sm-offset-5">
        <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
    </div>
</div>
