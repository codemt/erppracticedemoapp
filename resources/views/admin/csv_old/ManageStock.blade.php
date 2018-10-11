<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Name</th>
        <th align="center">SKU</th>
        <th align="center">Toatl Qty</th>
        <th align="center">Total Physical Qty</th>
        <th align="center">Total Blocked Qty</th>
        <th align="center">Company</th>
        <th align="center">Principle</th>
        <th align="center">Weight</th>
        <th align="center">Current Market Price</th>
        <th align="center">Open PO Qty</th>
        <th align="center">Open SO Qty</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($manage_stock_data))
        @foreach($manage_stock_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['name_description']}}</td>
            <td align="center">{{$value['model_no']}}</td>
            <td align="center">{{$value['total_qty']}}</td>
            <td align="center">{{$value['total_physical_qty']}}</td>
            <td align="center">{{$value['total_blocked_qty']}}</td>
            <td align="center">{{$value['company_name']}}</td>
            <td align="center">{{$value['supplier_name']}}</td>
            <td align="center">{{$value['weight']}}</td>
            <td align="center">{{$value['current_market_price']}}</td>
            <td align="center">{{$value['open_po_qty']}}</td>
            <td align="center">{{$value['open_so_qty']}}</td>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>