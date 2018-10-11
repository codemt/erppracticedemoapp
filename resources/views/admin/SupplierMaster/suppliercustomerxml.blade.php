<ENVELOPE>
    @foreach($all_manufacturer_data as $data)
        <DSPACCNAME>
            <DSPDISPNAME>{{$data['supplier_name']}}</DSPDISPNAME>
        </DSPACCNAME>
        <DSPACCINFO>
            <DSPCLDRAMT>
                <DSPCLDRAMTA></DSPCLDRAMTA>
            </DSPCLDRAMT>
            <DSPCLCRAMT>
                <DSPCLCRAMTA></DSPCLCRAMTA>
            </DSPCLCRAMT>
        </DSPACCINFO>
    @endforeach
</ENVELOPE>