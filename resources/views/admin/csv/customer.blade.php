<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Customer Name</th>
        <th align="center">Contact Person Name</th>
        <th align="center">Contact Person Email</th>
        <th align="center">Contact Person Phone</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($customer_data))
        @foreach($customer_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['name']}}</td>
            <td align="center">{{$value['person_name']}}</td>
            <td align="center">{{$value['person_email']}}</td>
            <td align="center">{{$value['person_phone']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>