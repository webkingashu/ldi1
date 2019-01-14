<div class="form-group ">
    <label for="name" class="control-label col-sm-1">{{ 'Name' }}<span class="e-color">*</span></label>
     <div class="col-lg-4 {{ $errors->has('name') ? 'has-error' : ''}}">
        <input class="form-control" name="name" type="text" id="name" placeholder="Name" <?php if(isset($department->name ) && !empty($department->name)){?> value="{{ $department->name}}" <?php } else{ ?> value="{{old('name')}}" <?php } ?> required>
     {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
     </div>
</div>
<div class="form-group">
    <div class="col-sm-4 col-sm-offset-5">
        <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
    </div>
</div>
