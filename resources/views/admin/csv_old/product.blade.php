<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Company Name</th>
        <th align="center">Supplier Name</th>
        <th align="center">Product Type</th>
        <th align="center">Model No</th>
        <th align="center">Price</th>
        <th align="center">Max Discount</th>
        <th align="center">Tax</th>
        <th align="center">QTY</th>
        <th align="center">Minimum QTY</th>
        <th align="center">Product Status</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($product_data))
        @foreach($product_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['company_name']}}</td>
            <td align="center">{{$value['supplier_name']}}</td>
            <td align="center">{{$value['product_type']}}</td>
            <td align="center">{{$value['model_no']}}</td>
            <td align="center">{{$value['price']}}</td>
            <td align="center">{{$value['max_discount']}}</td>
            <td align="center">{{$value['tax']}}</td>
            <td align="center">{{$value['qty']}}</td>
            <td align="center">{{$value['min_qty']}}</td>
            <td align="center">{{$value['product_status']}}</td>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>