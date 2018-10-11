<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Company Name</th>
        <th align="center">Creation Date</th>
        <th align="center">Approval Date</th>
        <th align="center">Manufacturer Name</th>
        <th align="center">Total Price in INR</th>
        <th align="center">Total Price in USD</th>
        <th align="center">Purchase Approval Status</th>
        <th align="center">Po No</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($purchase_requisition_approval_data))
        @foreach($purchase_requisition_approval_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['company_name']}}</td>
            <td align="center">{{$value['created_at']}}</td>
            <td align="center">{{$value['purchase_approval_date']}}</td>
            <td align="center">{{$value['supplier_name']}}</td>
            <td align="center">{{$value['total_price']}}</td>
            <td align="center">{{$value['dollar_total_price']}}</td>
            <td align="center">{{$value['purchase_approval_status']}}</td>
            <td align="center">{{$value['po_no']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>