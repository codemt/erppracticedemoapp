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
            <td align="center">{{$value['designation_name']}}</td>
            <td align="center">{{$value['name']}}</td>
            @if($value['status'] == "1")
                <td align="center">{{"Yes"}}</td>
            @else
                <td align="center">{{"No"}}</td>
            @endif
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>