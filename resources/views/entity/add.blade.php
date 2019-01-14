@extends('layouts.app')
@section('title', 'Entity')
@section('css')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
           <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Entity</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/entity') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
            <?php if(isset($data->id) && !empty($data->id)) { 
                $id = $data->id; }
            if(isset($is_update_url) && $is_update_url==1 && isset($id)) {
            $url = '/entity/'.$id;
            $action = 'POST';

            } else {
              $url = '/entity/';
              $action = 'POST';
            }       ?>    
         <div class="ibox-content">
            <form method="<?php echo $action; ?>" class="form-horizontal" action="{{url($url)}}">
                {{csrf_field()}}
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label">Entity Name<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('type_name') ? ' has-error' : '' }}">
                            <input type="text" name="type_name" class="form-control" placeholder="Entity Name" <?php if(isset($data->type_name ) && !empty($data->type_name)){?> value="{{ $data->type_name}}"
                            <?php } else { ?> value="{{old('type_name')}}" <?php } ?> >

                            @if ($errors->has('type_name'))
                            <span class="help-block"><strong>{{ $errors->first('type_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class=" control-label">Workflow<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('workflow_id') ? ' has-error' : '' }}">
                            <select class="select2 form-control" name="workflow_id">
                                <option disabled="disabled" selected="selected">Select Workflow</option><option value="1">1</option>
                            </select>

                            @if ($errors->has('workflow_id'))
                            <span class="help-block"><strong>{{ $errors->first('workflow_id') }}</strong>
                            </span>
                            @endif
                        </div>
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
