<thead>
    <tr>
        <th align="center">Id</th>
        <th align="center">City Name</th>
        <th align="center">State Name</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($city_data))
        @foreach($city_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['title']}}</td>
            <td align="center">{{$value['state_name']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>