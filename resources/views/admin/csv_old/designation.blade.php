<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Name</th>
        <th align="center">Team</th>
        <th align="center">Action</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($designation_data))
        @foreach($designation_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['name']}}</td>
            <td align="center">{{$value['name']}}</td>
            <td align="center">{{$value['status']}}</td>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>