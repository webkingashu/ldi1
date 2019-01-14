
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover dataTables-example">
            <thead>
                <tr>
                    <th>Sr. No</th><th>Budget Code</th><th>Budget Head</th><th>Functional Wing</th><th>Amount</th><th>Year</th>
                </tr>
            </thead>
            <tbody>
                              
             @foreach($budget as $item)
                 <tr>
                     <td>{{ $loop->iteration or $item->id }}</td>
                     <td>{{ $item->budget_code }}</td>
                     <td>{{ $item->budget_head }}</td>
                     <td>{{ $item->name }}</td>
                     <td> <input class="form-control" name="amount[]" type="text" id="amount" value="{{ $item->amount or ''}}" required></td>
                     <td>{{ $item->year }}</td>
                     <input class="form-control" name="budget_id[]" type="hidden" id="amount" value="{{ $item->id }}" >
                 </tr>
              @endforeach
             </tbody>
         </table>
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
