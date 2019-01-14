

                <table class="table table-hover no-margins dataTables-example">
                    <thead>
                        <tr>
                          <th>Sr NO.</th>
                          <th>Date of Assignment</th>
                          <th>Date of Forwarding</th>
                          <th>File Number</th>
                          <th>From</th>
                          <th>Vendor Name</th>
                          <th>Proposed Payment Amount</th>
                          <th>Current Status</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if(isset($data['release_order']) && count($data['release_order']) > 0)
                    @foreach($data['release_order'] as $item)
                    <?php $date = date('d-m-Y', strtotime($item->created_at));
                    $status_approved_date = date('d-m-Y', strtotime($item->status_approved_date));
                    $location = $item->city_name;
                    $department = $item->department_name;
                    $role = $role_details['role_name'];
                    $user = $role_details['user_name'];
                    $from = $location . ' - ' . $department . ' - ' . $role . ' - ' . $user;
                    ?>   
                    <tr>
                        <td>{{ $loop->iteration or $item->id }}</td>
                        <td>{{$date}}</td>
                        <td>{{$status_approved_date}}</td>
                        <td>{{$item->file_number}}</td>
                        <td>{{$from}}</td>
                        <td>{{$item->vendor_name}}</td>
                        <td>{{$item->release_order_amount}}</td>
                        <!-- <td>{{$item->status_name}}</td> -->
                        <td> @if($item->status_name == "Approved")
                         <span class="label label-primary"> {{ $item->status_name }} </span>
                         @elseif ($item->status_name == "Pending Approval")
                         <span class="label label-warning"> {{ $item->status_name}}</span>
                         @elseif ($item->status_name == "Draft")
                         <span class="label label-info">  {{ $item->status_name}}</span>
                         @elseif ($item->status_name == "Return" || $item->status_name == "DDO Return" || $item->status_name == "PAO Return")
                         <span class="label label-danger">{{ $item->status_name}}</span>
                         @elseif ($item->status_name == "DDO Approved" || $item->status_name == "PAO Approved")
                         <span class="label label-success">{{ $item->status_name}}</span>
                         @else
                         <span class="label">{{ $item->status_name }}</span>
                         @endif
                     </td>
                     <td>
                        @if($item->status_id != $data['entity_details']->final_status)
     
                        <a href="{{ url('/ro/' . $item->id) }}" title="Edit RO"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                         
                        <form method="POST" action="{{ url('/ro' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                           {{ method_field('DELETE') }}
                           {{ csrf_field() }}
                           <button type="submit" class="btn btn-danger btn-xs" title="Delete RO" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                       </form>
                      
                       @else 
                        
                       <a href="{{ url('/ro/' . $item->id) }}" title="View RO"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>

                   </td>
                   
                   @endif

               </tr>
               @endforeach
               @endif
           </tbody>
       </table>

