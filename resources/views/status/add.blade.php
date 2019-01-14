@extends('layouts.app')
@section('title', 'Status')
@section('css')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
         <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Status</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/status') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
          </div>
         <?php if(isset($status_data->id) && !empty($status_data->id)) { 
            $id = $status_data->id; }
            if(isset($is_update_url) && $is_update_url==1 && isset($id)) {
                $url = '/status/'.$id;
                $action = 'POST';

            } else {
              $url = 'status/';
              $action = 'POST';
          }       ?>    
          <div class="ibox-content">
            <form method="<?php echo $action; ?>" class="form-horizontal" action="{{url($url)}}">
                {{csrf_field()}}
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status Name<span class="e-color">*</span></label>
                    <div class="col-sm-6 {{ $errors->has('status_name') ? ' has-error' : '' }}">
                        <input type="text" name="status_name" class="form-control" placeholder="Status Name" <?php if(isset($status_data->status_name ) && !empty($status_data->status_name)){?> value="{{ $status_data->status_name}}"
                        <?php } else { ?> value="{{old('status_name')}}" <?php } ?> >

                        @if ($errors->has('status_name'))
                        <span class="help-block"><strong>{{ $errors->first('status_name') }}</strong>
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

@endsection            
