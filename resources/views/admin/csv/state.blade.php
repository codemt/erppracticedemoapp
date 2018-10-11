<thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">State</th>
    </tr>
</thead>
<tbody id="append_body">
    @if(!empty($state_data))
        @foreach($state_data as $key=>$value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td align="center">{{$value['title']}}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="6" align="center">No data available.</td>    
        </tr>
    @endif
</tbody>