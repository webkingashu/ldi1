
<?php //dd($data); ?>
        <table class="table table-hover dataTables-example">
            <thead>
                <tr>
                   <th>Sr. No</th>
                    <!-- <th>Dates</th> -->
                   <th>Title Of Bid</th>
                   <th>Subject</th>
                   <th>EAS Name</th>
                   <th>Vendor Name</th>
                   <th>Bid Number</th>
                   <!--   <th>Date Of Bid</th> -->
                   <th>Status</th>
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
            @if(isset($data['purchase_order']) && count($data['purchase_order'])>0)
            @foreach($data['purchase_order'] as $item)
            <tr>
                <td>{{ $loop->iteration or $item->id }}</td>
                 <!-- <td>{{  date("Y-m-d", strtotime($item->created_at)) }}</td> -->
                <td>{{ $item['title_of_bid'] }}</td>
                <td>{{ $item->subject }}</td>
                <td>{{ $item->sanction_title }}</td>
                <td>{{ $item->vendor_name }}</td>
                <td>{{ $item->bid_number }}</td>
                <!-- <td>{{ $item->date_of_bid }}</td> -->

                <td> @if($item->status_name == "Approved")
                    <span class="label label-primary"> {{ $item->status_name }} </span>
                    @elseif ($item->status_name == "Pending Approval")
                    <span class="label label-warning">  {{ $item->status_name}}</span> 
                    @elseif ($item->status_name == "Draft")
                    <span class="label label-info">  {{ $item->status_name}}</span> 
                    @elseif ($item->status_name == "Return")
                    <span class="label label-success">{{ $item->status_name}}</span> 
                    @else
                    <span class="label">{{ $item->status_name }}</span> 
                @endif</td>
                <td>
                   <div class="row btn-action">  
                    @if($item->status_id != $data['entity_details']->final_status)

                    @permission('can_update')
                    <a href="{{ url('/purchase-order/' . $item->id) }}" title="Edit Purchase Order"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                    @endpermission

                    @permission('can_delete')
                    <form method="POST" action="{{ url('purchase-order' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-xs" title="Delete Purchase Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    </form>
                    @endpermission

                    @else 
                    @permission('can_view')
                    <a href="{{ url('/purchase-order/' . $item->id) }}" title="View Purchase Order"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a> 
                    @endpermission
                    @endif
                </div>  
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
