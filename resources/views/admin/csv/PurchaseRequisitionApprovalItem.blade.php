<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Model No</th>
        <th align="center">Product Name</th>
        <th align="center">QTY</th>
        <th align="center">Unit Price</th>
        <th align="center">Total Price</th>
        <th align="center">Last Po</th>
        <th align="center">Last Po2</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($purchase_requisition_approval_data))
        @foreach($purchase_requisition_approval_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['model_no']}}</td>
            <td align="center">{{$value['product_name']}}</td>
            <td align="center">{{$value['qty']}}</td>
            @if($cur_status['dollar_price'] == '0.00')
                <td align="center">{{$value['unit_price']}}</td>
                <td align="center">{{$value['total_price']}}</td>
            @else
                <td align="center">{{$value['dollar_price']}}</td>
                <td align="center">{{$value['dollar_price'] * $value['qty']}}</td>
            @endif
            <td align="center">{{$value['last_po']}}</td>
            <td align="center">{{$value['last_po2']}}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total Price : </td>
            <td>{{$total_calculated_price}}</td>
        </tr>
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>