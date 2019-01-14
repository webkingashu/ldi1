@extends('layouts.app')
@section('title', 'Revisions')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="wrapper wrapper-content animated fadeInRight">
<div class="row">
<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title col-md-12 display-flex">
                            <h3 class="col-md-10"><strong>Revision History</strong></h3>
                            <div id="backUrl" class="ibox-tools col-md-2">
                                <a href="{{ url('eas/' . $id) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                                <!-- <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#">Config option 1</a>
                                    </li>
                                    <li><a href="#">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a> -->
                            </div>
                        </div>
                        <div class="ibox-title col-md-12 display-flex">
                            <ul class="breadcrumb">
                                <li><a class="breadcrumb-item" href="{{ url('/eas') }}">EAS</a></li>
                              <!--   <li><a class="breadcrumb-item" href="">View EAS</a></li> -->
                                <li><a class="breadcrumb-item">Revisions</a></li>
                            </ul>
                        </div>

                        <div class="ibox-content form-horizontal">
                           <div id="content">
                           <div class="timeline-item">
                                <div class="row">
                                    @foreach($revisions as $data)
                                   
                        <div onclick="add({{$data->id}},{{$data->entity_id}},'{{$entity_slug}}');" class="col-xs-3 content no-top-border">
                                        <img src='{{url("/")}}/img/history.png' style="border-radius:4px; margin-bottom:10px;">
                                        <p class="m-b-xs"><strong> {{ $data->created_at->format ( 'M j, Y H:i:s' )}}</strong></p>

                                        <p><strong>By {{ $data->name}}</strong></p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            </div>
                           <div id="div1"></div>
                       </div>
                    </div>
                </div>
            </div>
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script>
        $(function() {

        $("#div1").hide();
        });

        function add(id,entityId,entity_slug)
        {
            $("#content").hide();
            $("#backUrl").hide();
            
            $.ajax({
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
              type: 'POST',
              url: '<?php echo route('revision-view'); ?>',
              beforeSend: function(){
                          $('.div-loader').css("display", "block");
               },
              data: {'id':id,'entityId':entityId,'entity_slug':entity_slug},
              success: function(data) {
                 //console.log(data);

                          $("#div1").append(data);
                          return $("#div1").show();
               },
               error: function(jqXHR, textStatus, errorThrown) {
                     console.log(JSON.stringify(jqXHR));
                     console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
               },
              complete: function(){
                          $('.div-loader').css("display", "none");
                        }
              });
        }
</script>
@endsection 