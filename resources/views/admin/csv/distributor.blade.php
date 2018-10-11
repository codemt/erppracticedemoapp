<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Distributor Name</th>
        <th align="center">SPOC Name</th>
        <th align="center">SPOC Email</th>
        <th align="center">SPOC Phone</th>
        <th align="center">Bank Name</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($distributor_data))
        @foreach($distributor_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['distributor_name']}}</td>
            <td align="center">{{$value['spoc_name']}}</td>
            <td align="center">{{$value['spoc_email']}}</td>
            <td align="center">{{$value['spoc_phone']}}</td>
            <td align="center">{{$value['bankname']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>