@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox-tools">
            <a href="{{ url('/revision/'.$eas_id->id .'/'. $entity_slug) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
       </div> 
         <div class="col-lg-6">
            <div class="ibox">
              <h3><strong>New</strong></h3>
              <div class="table-responsive">
                        <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                            <tbody>
                              
                              @foreach($eas_latest_data as $key => $value)

                              <?php
                               $info = explode('_', $key);
                               $data = implode(' ', $info); 
                               $eas_data = ucwords($data);
                               ?>
                              <tr>
                                <th>{{ $eas_data }}</th>
                                @if(in_array($value, $differ))
                                <td><font color="#16987e">{{ $value }}</td>
                                @else
                                <td>{{ $value }}</td>
                                @endif
                              </tr>
                           
                              @endforeach
                               
                             </tbody>
                        </table>    
                      </div>
                </div>
         </div>

<div class="col-lg-6">
      <div class="ibox">
           <h3><strong>Old</strong></h3>
           <div class="table-responsive">
              <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                            <tbody>
                            <tr>
                                      <th>Sanction Title</th>
                                      <td>{{$old_data->sanction_title}}</td>
                                    </tr>
                                    <tr>
                                        <th>Sanction Purpose</th>
                                        <td>{{$old_data->sanction_purpose}}</td>
                                    </tr>
                                    <tr>
                                        <th>Competent Authority</th>
                                        <td>{{$old_data->competent_authority}}</td>
                                    </tr>
                                    <tr>
                                        <th>Serial No of Sanction</th>
                                        <td>{{$old_data->serial_no_of_sanction}}</td>
                                    </tr>
                                    <tr>
                                        <th>File Number</th>
                                        <td>{{$old_data->file_number}}</td>
                                    </tr>
                                    <tr>
                                        <th>Sanction Total</th>
                                        <td>{{$old_data->sanction_total}}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Issue</th>
                                        <td>{{$old_data->date_issue}}</td>
                                    </tr>
                                    <tr>
                                        <th>Budget Code</th>
                                        <td>{{$old_data->budget_code}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Validity Sanction Period</th>
                                        <td>{{$old_data->validity_sanction_period}}</td>
                                    </tr>
                                    <tr>
                                      <th>Vendor Name </th>
                                       <td> {{$eas_latest_data['vendor_name']}} </td>
                                    </tr>
                                     <tr>
                                      <th> Email </th>
                                       <td>{{$eas_latest_data['email']}} </td>
                                    </tr>
                                      <tr>
                                      <th>Mobile No </th>
                                       <td>{{$eas_latest_data['mobile_no']}} </td>
                                    </tr>
                                      <tr>
                                      <th> Bank Name</th>
                                       <td> {{$eas_latest_data['bank_name']}} </td>
                                    </tr>
                                     <tr>
                                      <th> Bank branch </th>
                                       <td> {{$eas_latest_data['bank_branch']}}  </td>
                                    </tr>
                                     <tr>
                                      <th> Bank Acc No  </th>
                                       <td> {{$eas_latest_data['bank_acc_no']}} </td>
                                    </tr>
                                     <tr>
                                      <th>  IFSC code </th>
                                       <td> {{$eas_latest_data['ifsc_code']}} </td>
                                    </tr>
                                     <tr>
                                      <th> Bank code </th>
                                       <td>{{$eas_latest_data['bank_code']}} </td>
                                    </tr>
                                   
                                    <tr>
                                        <th>Cfa Note Number</th>
                                        <td>{{$old_data->cfa_note_number}}</td>
                                    </tr>
                                    <tr>
                                        <th>Cfa Designation</th>
                                        <td>{{$old_data->cfa_designation}}</td>
                                    </tr>
                                    <tr>
                                        <th>Cfa Dated</th>
                                        <td>{{$old_data->cfa_dated}}</td>
                                    </tr>
                                    @if(isset($old_data->fa_number) && !empty($old_data->fa_number))
                                    <tr>
                                        <th>Fa Number</th>
                                        <td> {{ $old_data->fa_number }} </td>
                                    </tr>
                                    @endif
                                    @if(isset($old_data->fa_dated) && !empty($old_data->fa_dated))
                                    <tr>
                                        <th>Fa Dated </th>
                                        <td> {{ $old_data->fa_dated }} </td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($old_data->whether_being_issued_under) && !empty($old_data->whether_being_issued_under))
                                    <tr>
                                        <th>Whether Being Issued Under</th>
                                        <td> {{ $old_data->whether_being_issued_under }} </td> 
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $eas_latest_data['Status']}}
                                        
                                       </td>
                                     </tr>
                                  
                              </tbody>
                        </table>
                 </div>
              </div>
         </div>
    </div>
</div>
@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript">
</script>
<!-- <script type="text/javascript">
$(document).ready(function(){
$('table tr td').each(function(){
      var texto = $(this).text();
     
});
});

</script> -->
@endsection 