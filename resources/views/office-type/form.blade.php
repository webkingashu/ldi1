<div class="form-group ">
    <label for="office_type" class="control-label col-sm-3">{{ 'Office Type' }}<span class="e-color">*</span></label>
    <div class="col-lg-6 {{ $errors->has('office_type_name') ? 'has-error' : ''}}">
    <input class="form-control" name="office_type_name" type="text" id="office_type_name" placeholder="Office Type" <?php if(isset($officetype->office_type_name ) && !empty($officetype->office_type_name)){?> value="{{ $officetype->office_type_name}}" <?php } else{ ?> value="{{old('office_type_name')}}" <?php } ?>>
    {!! $errors->first('office_type_name', '<p class="help-block">:message</p>') !!}
</div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
	<div class="col-sm-4 col-sm-offset-5">
    	<input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
    </div>
</div>
