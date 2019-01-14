@extends('layouts.app')
@section('title', 'Permission')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
          <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Permission</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/permission') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
          </div>
         <?php if(isset($data->id) && !empty($data->id)) { 
            $id = $data->id; }
            if(isset($is_update_url) && $is_update_url==1 && isset($id)) {
                $url = '/permission/'.$id;
                $action = 'POST';

            } else {
              $url = 'permission/';
              $action = 'POST';
          }       ?>    
          <div class="ibox-content">
            <form method="<?php echo $action; ?>" class="form-horizontal" action="{{url($url)}}">
                {{csrf_field()}}
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                <input type="hidden" name="created_by" value="{{isset($data->created_by)?$data->created_by:'0'}}"/>
                  <div class="form-group">
                    <div class="col-md-6">
                       <label for="name" class="control-label">{{ 'Display Name' }}<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('display_name') ? 'has-error' : ''}}">
                          <input placeholder="Display Name" class="form-control" rows="5" name="display_name" type="text" 
                            <?php if (isset($data->display_name) && !empty($data->display_name)) {?>
                                    value="{{ $data->display_name}}"
                                <?php } else {?>
                                    value="{{old('display_name')}}"<?php }?>>
                                {!! $errors->first('display_name', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="name" class="control-label">{{ 'Name' }}<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('name') ? 'has-error' : ''}}">
                          <input placeholder="Role Name" class="form-control" rows="5" name="name" type="text" id="name"
                          <?php if (isset($data->name) && !empty($data->name)) {?>
                                  value="{{ $data->name}}"
                              <?php } else {?>
                                  value="{{old('name')}}"<?php }?>>
                              {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div> 
                    </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-5">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>    
                </div> 
            </form>
        </div>
    </div>
</div>    
</div>

@endsection
@section('scripts')
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
    $('.chosen-select').chosen({width: "100%"});
</script>
@endsection            
