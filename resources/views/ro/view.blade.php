@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
       <div class="ibox-tools">
            <a href="{{ url('/revision/'.$ro_id->id.'/'. $entity_slug) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
       </div> 
         <div class="col-lg-6">
            <div class="ibox">
              <h3><strong>New</strong></h3>
              <div class="table-responsive">
                        <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                            <tbody>

                               @foreach($ro_latest_data as $key => $value)

                              <?php
                               $info = explode('_', $key);
                               $data = implode(' ', $info); 
                               $ro_data = ucwords($data);
                               ?>
                              <tr>
                                <th>{{ $ro_data }}</th>
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
                                <th> Release Order Title</th>
                                <td> {{ $old_data->ro_title }} </td>
                            </tr>
                            <tr>
                                <th>EAS Name </th>
                                <td> {{ $ro_latest_data['EAS Name'] }} </td>
                            </tr>
                            <tr>
                                <th> Vendor Name </th>
                                <td> {{ $old_data->vendor_name }} </td>
                            </tr>
                            <tr>
                                <th> Mobile No </th>
                                <td> {{ $old_data->vendor_contact_number }} </td>
                            </tr>
                            <tr>
                                <th> Bank Name</th>
                                <td> {{ $ro_latest_data['bank_name'] }} </td>
                            </tr>
                            <tr>
                                <th>Bank Branch</th>
                                <td> {{ $ro_latest_data['bank_branch'] }} </td>
                            </tr>
                            
                            <tr>
                               <th> IFSC Code</th>
                               <td> {{ $old_data->ifsc_code }} </td>
                            </tr>
                            <tr>
                               <th> Budget Code</th>
                               <td> {{ $old_data->budget_code }} </td>
                            </tr>
                            <tr>
                                <th> Total Sanctioned amount</th>
                                <td> {{ $old_data->total_sanction_amount }} </td>
                            </tr>
                            <tr>
                                 <th> Bank Code</th>
                                 <td> {{ $old_data->bank_code }} </td>
                            </tr>
                             <tr>
                                <th> Current/ Cash Credit Account No</th>
                                <td> {{ $old_data->bank_acc_no }} </td> 
                              </tr>
                              <tr>
                                <th> Release Order amount</th> 
                                <td> {{ $old_data->release_order_amount }} </td>
                             </tr>
                               <tr>
                             <th> Status</th> 
                              <td> {{ $ro_latest_data['Status'] }}
                              
                          </td>
                         </tr>
                           
                            <!--  @if(isset($eas_old_data->ro_pdf) && !empty($eas_old_data->ro_pdf)) 
                             <tr>
                                 <th>Generated Release Order</th> 
                                 <td><a class="label label-success" target="_blank" href="{{url('/')}}/<?php echo $eas_old_data->ro_pdf; ?>">View</a></td>
                            </tr>
                             @endif
                          -->
                                  
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
@endsection 