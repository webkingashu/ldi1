
@if(isset($is_create) && !empty($is_create) && $is_create == 1)
<div class="form-group">
   <div class="col-sm-4 col-sm-offset-5"> 
      <input class="btn btn-primary" type="submit"  value="Create"> 
      <input onclick="backToPrev()" class="btn btn-primary" type="button"  value="Cancel"> 
   </div>
</div>
@endif            
  @if(isset($is_show) && !empty($is_show) && $is_show == 1)
  <div class="form-group">
   <div class="col-sm-4 col-sm-offset-5">
      @if(isset($transaction_data) && !empty($transaction_data))
      <?php $count = 0; ?>
      @foreach($transaction_data as $value) 
        @if(isset($trans_permission) && !empty($trans_permission))
          @foreach($trans_permission as $permissions_key => $permissions)
          <?php //dd($trans_permission);?>
            @if(isset($permissions_key) && !empty($permissions_key) && $permissions_key == $value['transaction_name'] && $permissions['code'] == 200)
            <input class="btn btn-primary start" type="button" data-privious_status="{{$value['privious_status']}}" id="{{$value['transaction_id']}}" value="<?php echo $value['transaction_name'];?>">
            @endif
          @endforeach
        @endif
      @endforeach
      <?php  $count++;?>
      @endif
   </div>
</div>
 @endif
<div class="form-group d-flex flex-wrap">
  @if(isset($added_comment) && !empty($added_comment)) 
      <div class="col-lg-6 table-responsive">
        <h4 class="text-center padding-bottom-20">Added Comments</h4>
         <table class="table table-striped table-bordered table-hover">
              <thead>
                  <tr>
                      <th>Sr.No</th>
                      <th>Added Comments</th> 
                      <th>Commented By</th> 
                      <th>Commented At</th> 
                  </tr>
              </thead>
              <tbody>
              <?php $count = 1;?>
              @foreach($added_comment as $value)    
              <?php //print_r($value['comment']);exit;?>
              <tr>
                  <td>{{ $count++}}</td>
                  <td>{{$value['comment']}}</td> 
                  <td>{{$value['name']}}</td>                            
                  <td>{{$value['created_at']}}</td>
              </tr>
              @endforeach
              </tbody>
          </table>
     </div>
  @endif
@if(isset($documents_details) && !empty($documents_details))               
    <div class="col-lg-6 table-responsive">
      <h4 class="text-center padding-bottom-20">Uploaded Documents</h4> 
       <table class="table table-striped table-bordered table-hover dataTables-example">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Document Type</th> 
                    <th>Download</th>
                  @if(isset($is_update_url) && $is_update_url == 1)
                    <th>Action</th> 
                  @endif      
                </tr>
            </thead>
            <tbody>
            <?php $count = 1;?>
          @foreach($documents_details as $value)
           <?php //print_r($value['id']);exit;?>
            <tr>
                <td>{{ $count++}}</td>
                <td>{{$value['document_name']}}</td>                    
                <td><a href="{{url('/download/' .$value['entity_id'].'/'. $value['master_id'])}}"><img src="{{url('/')}}/img/pdf-icon.png" width="40px" height="40px"></a></td> 
                @if(isset($is_update_url) && $is_update_url == 1)
                 <td>
                   <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="removefile('<?php echo $value['id']; ?>')"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                </td>
               @endif   
            </tr>
            @endforeach
            </tbody>
        </table>
   </div>
@endif
</div>  
@if(isset($is_show) && !empty($is_show) && $is_show == 1)
@if(isset($copy_to_details) && !empty($copy_to_details))
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Copy To Details</h5>
         
        </div>
        <div class="ibox-content">

          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Department and Location</th> 
                <th>User</th>
             
              </tr>
            </thead>
            <tbody>

              <?php $count = 1;?>
              @foreach($copy_to_details as $key =>$val)
              <tr class="field_group">
                <td class="sr_no">{{$count++}}</td>
                <td>{{isset($val['department_name'])? $val['department_name'] :''}}</td>
                <td>{{isset($val['user_name'])?$val['user_name']:''}}</td>
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
@endif

