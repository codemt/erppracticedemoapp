<thead>
    <tr>
        <th align="center">SO No</th>
        <th align="center">SO Date</th>
        <th align="center">Customer Name</th>
        <th align="center">Project Name</th>
        <th align="center">Total Qty</th>
        <th align="center">Total Value</th>
        <th align="center">Sales Person Name</th>
        <th align="center">Status</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($salesorder_data))
        @foreach($salesorder_data as $key=>$value)
        <tr>
            <td align="center">{{$value['so_no']}}</td>
            <td align="center">{{$value['created_at']}}</td>
            <td align="center">{{$value['customer_name']}}</td>
            <td align="center">{{$value['project_name']}}</td>
            <td align="center">{{$value['total_qty']}}</td>
            <td align="center">{{$value['total_value']}}</td>
            <td align="center">{{$value['sales_person_name']}}</td>
            <td align="center">{{$value['status']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>