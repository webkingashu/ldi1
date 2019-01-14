<div class="form-group ">
    <label for="city_name" class="control-label col-sm-3">{{ 'Name' }}<span class="e-color">*</span></label>
    <div class="col-lg-6 {{ $errors->has('city_name') ? 'has-error' : ''}}"><input class="form-control" name="city_name" type="text" placeholder="Name" id="name" <?php if(isset($city->city_name ) && !empty($city->city_name)){?> value="{{ $city->city_name}}" <?php } else{ ?> value="{{old('city_name')}}" <?php } ?>>
    {!! $errors->first('city_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
	<div class="col-sm-4 col-sm-offset-5">
    	<input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
    </div>
</div>
