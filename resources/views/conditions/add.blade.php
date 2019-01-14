@extends('layouts.app')
@section('title', 'condition')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox ">
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong>Condition</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('conditions') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
            <?php if(isset($condition_data->id) && !empty($condition_data->id)) { 
                $id = $condition_data->id; }
              if(isset($is_update_url) && $is_update_url==1)
                {
                    $url = '/conditions/'.$id;
                    $action = 'POST';
                } 
                else 
                {
                    $url = '/conditions/';
                    $action = 'POST';
                }       
            ?>    
            <div class="ibox-content">
                <form method="<?php echo $action; ?>" class="form-horizontal" action="{{url($url)}}">
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                     {{csrf_field()}}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Condition Name<span class="e-color">*</span></label>
                        <div class="col-sm-6 {{ $errors->has('condition_name') ? ' has-error' : '' }}">
                            <input type="text" name="condition_name" class="form-control" placeholder="Condition Name" <?php if(isset($condition_data->condition_name ) && !empty($condition_data->condition_name)){?> value="{{ $condition_data->condition_name}}"
                            <?php } else { ?> value="{{old('condition_name')}}" <?php } ?> >
                            
                            @if ($errors->has('condition_name'))
                            <span class="help-block"><strong>{{ $errors->first('condition_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-5">
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
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>   
@endsection            
