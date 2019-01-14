@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox-tools">
            <a href="{{ url('/revision/'.$gar_id->id.'/'. $entity_slug) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
       </div>
         <div class="col-lg-6">
            <div class="ibox">
              <h3><strong>New</strong></h3>
              <div class="table-responsive">
                        <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                            <tbody>
                            
                              @foreach($gar_latest_data as $key => $value)

                              <?php
                               $info = explode('_', $key);
                               $data = implode(' ', $info); 
                               $gar_data = ucwords($data);
                               ?>
                              <tr>
                                <th>{{ $gar_data }}</th>
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
                              @if(isset($old_data) && !empty($old_data))
                           <tr>
                                <th> EAS Title</th>
                                <td> {{ $gar_latest_data['EAS Title'] }} </td>
                            </tr>
                            <tr>
                                <th> Ro Title </th>
                                <td> {{ $gar_latest_data['Ro Title'] }} </td>
                            </tr>
                            <tr>
                                <th> Vendor Name </th>
                                <td> {{ $gar_latest_data['vendor_name'] }} </td>
                            </tr>
                            <tr>
                                <th> Vendor Contact Number </th>
                                <td> {{ $gar_latest_data['Vendor Contact Number'] }} </td>
                            </tr>
                            <tr>
                                <th> Bank Name</th>
                                <td> {{ $gar_latest_data['bank_name'] }} </td>
                            </tr>
                            <tr>
                                <th>Branch Name</th>
                                <td> {{ $gar_latest_data['bank_branch'] }} </td>
                            </tr>
                            <tr>
                               <th> IFSC Code</th>
                               <td> {{ $gar_latest_data['IFSC Code']}} </td>
                            </tr>
                            <tr>
                               <th> Budget Code</th>
                               <td> {{ $gar_latest_data['budget_code'] }} </td>
                            </tr>
                            
                            <tr>
                               <th> Bank Code</th>
                               <td> {{ $gar_latest_data['bank_code'] }} </td>
                            </tr>
                             <tr>
                               <th>GST Amount</th>
                               <td> {{ $old_data->gst_amount }} </td>
                            </tr>
                            <tr>
                               <th>TDS Amount</th>
                               <td> {{ $old_data->tds_amount }} </td>
                            </tr>

                            <tr>
                               <th>TDS On GST Amount</th>
                               <td> {{ $old_data->gst_tds_amount }} </td>
                             </tr>
                             <tr>
                               <th>LD Or Penalty Amount</th>
                               <td> {{ $old_data->ld_amount }} </td>
                            </tr>

                            <tr>
                               <th>Withheld Amount</th>
                               <td> {{ $old_data->with_held_amount }} </td>
                             </tr>
                             <tr>
                               <th>Other Amount</th>
                               <td> {{ $old_data->other_amount }} </td>
                            </tr>
                             <tr>
                               <th> Current/ Cash Credit Account No</th>
                               <td>{{ $gar_latest_data['Current/ Cash Credit Account No'] }} </td>
                             </tr>
                             <tr>
                               <th> Release Order amount</th>
                               <td> {{ $old_data->release_order_amount }} </td>
                            </tr>
                            <tr>
                               <th> Status</th>
                               <td> {{$gar_latest_data['Status']}} </td>
                               
                            </tr>
                            @endif
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