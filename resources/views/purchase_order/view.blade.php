@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox-tools">
            <a href="{{ url('/revision/'.$po_id->id.'/'. $entity_slug) }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
       </div> 
         <div class="col-lg-6">
            <div class="ibox">
              <h3><strong>New</strong></h3>
              <div class="table-responsive">
                    <table id="show" class="table table-striped table-bordered table-hover dataTables-example">
                        <tbody>
                          @foreach($po_latest_data as $key => $value)

                              <?php
                               $info = explode('_', $key);
                               $data = implode(' ', $info); 
                               $po_data = ucwords($data);
                               ?>
                              <tr>
                                <th>{{ $po_data }}</th>
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
                                <th> Vendor Name </th>
                                <td> {{ $old_data->vendor_name }} </td>
                              </tr>
                              <tr>
                                <th> Vendor Address </th>
                                <td> {{ $old_data->vendor_address }} </td>
                            </tr>
                            <tr>
                                <th> Subject </th>
                                <td> {{ $old_data->subject }} </td>
                            </tr>
                            <tr>
                                <th> Bid Number </th>
                                <td> {{ $old_data->bid_number }} </td>
                            </tr>
                            <tr>
                                <th>Date Of Bid</th>
                                <td> {{ $old_data->date_of_bid }} </td>
                            </tr>
                            <tr>
                                <th>Title Of Bid</th>
                                <td> {{ $old_data->title_of_bid }} </td>
                            </tr>
                         
                            
                               
                                 <tr>
                                    <th>Status</th>
                                    <td>{{ $po_latest_data['Status'] }}
                                   
                                   </td>
                                    </tr>
                               
                           
                           <!--  @if(isset($eas_old_data->po_pdf) && !empty($eas_old_data->po_pdf)) 
                            <tr>
                                <th>Generated Purchase Order</th><td><a class="label label-success" target="_blank" href="{{url('/')}}/<?php echo $eas_old_data->po_pdf; ?>">View</a></td>
                            </tr>
                            @endif -->
                                  
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