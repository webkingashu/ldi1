<?php //dd($data['gar']);?>
        <table class="table table-hover no-margins dataTables-example">
            <thead>
             <tr>
                <th>Sr. No.</th>
                <th>EAS</th>
                <th>Release Order amount</th>
                <th>EAS File Number</th>
                <th>Amount to be Paid</th>
                <th>Actual Payment Amount</th>
                <th>Vendor Name</th>

                <!-- <th>Date of Issue</th> -->
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; 
            ?>
            @if(isset($data['gar']) && count($data['gar']) >0)
            @foreach($data['gar'] as $item)
            <?php ?>
            <tr>
                <td>{{ $loop->iteration or $item->id }}</td>
                <td>{{ $item->sanction_title }}</td>
                <td>{{ $item->release_order_amount }}</td>
                <td>{{ $item->file_number }}</td>
                <td>{{ $item->amount_paid }}</td>
                <td>{{ $item->actual_payment_amount }}</td>
                <td>{{ $item->vendor_name }}</td>
                <td>@if($item->status_name == "Approved" || $item->status_name == "DDO Approved" || $item->status_name == "PAO Approved" || $item->status_name == "Forwarding Letter Generated")
                 <span class="label label-primary"> {{ $item->status_name }} </span>
                 @elseif ($item->status_name == "Pending Approval")
                 <span class="label label-warning">  {{ $item->status_name}}</span>
                 @elseif ($item->status_name == "Draft" || $item->status_name == "Dispatch And Tally Entry Done" || $item->status_name == "Cheque Uploaded" )
                 <span class="label label-info">{{ $item->status_name}}</span>
                 @elseif ($item->status_name == "Return" || $item->status_name == "DDO Return" || $item->status_name == "PAO Return")
                 <span class="label label-danger">{{ $item->status_name}}</span>
                 @else
                 <span class="label">{{ $item->status_name }}</span>
                 @endif
             </td>
             <td>

                @if(isset($item->status_id) &&  $item->status_id != $data['entity_details']->final_status)
                @permission('can_update')
                <a href="{{ url('/gar/' . $item->id . '/edit') }}" title="Edit GAR"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                @endpermission
                @endif 
                                       <!--  @permission('can_delete')
                                        <form method="POST" action="{{ url('/gar' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete GAR" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </form>
                                        @endpermission -->
                                        @if(isset($item->status_id) &&  $item->status_id == $data['entity_details']->final_status)
                                        @permission('can_view')
                                        <a href="{{ url('/gar/' . $item->id) }}" title="View GAR"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                                        @endpermission
                                        @endif 

                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
        