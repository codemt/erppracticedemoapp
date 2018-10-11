<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Name</th>
        <th align="center">SKU</th>
        <th align="center">Weight</th>
        <th align="center">Total Qty</th>
        <th align="center">Enter Qty</th>
        <th align="center">Total Blocked Qty</th>
        <th align="center">Open SO Qty</th>
        <th align="center">Open PO Qty</th>
        <th align="center">Current Market Price</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($manage_stock_data))
        @foreach($manage_stock_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['name_description']}}</td>
            <td align="center">{{$value['model_no']}}</td>
            <td align="center">{{$value['weight']}}</td>
            <td align="center">{{$value['total_qty']}}</td>
            <td align="center">{{$value['po_qty']}}</td>
            <td align="center">{{$value['total_blocked_qty']}}</td>
            <td align="center">{{$value['open_so_qty']}}</td>
            <td align="center">{{$value['open_po_qty']}}</td>
            <td align="center">{{$value['current_market_price']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>