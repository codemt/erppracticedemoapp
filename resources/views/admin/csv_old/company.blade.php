<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Company Name</th>
        <th align="center">SPOC Name</th>
        <th align="center">SPOC Email</th>
        <th align="center">SPOC Phone</th>
        <th align="center">Bank Name</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($company_data))
        @foreach($company_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['company_name']}}</td>
            <td align="center">{{$value['spoc_name']}}</td>
            <td align="center">{{$value['spoc_email']}}</td>
            <td align="center">{{$value['spoc_phone']}}</td>
            <td align="center">{{$value['bankname']}}</td>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>