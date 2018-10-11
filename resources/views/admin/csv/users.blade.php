<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Name</th>
        <th align="center">Username</th>
        <th align="center">Team</th>
        <th align="center">Designation</th>
        <th align="center">Region</th>
        <th align="center">Status</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($user_data))
        @foreach($user_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['name']}}</td>
            <td align="center">{{$value['email']}}</td>
            <td align="center">{{$value['team_name']}}</td>
            <td align="center">{{$value['designation_name']}}</td>
            <td align="center">{{$value['region']}}</td>
            <th align="center">{{$value['status']}}</th>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>