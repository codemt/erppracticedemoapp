<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body{
			font-family: Arial, Helvetica, sans-serif;
			color: #333333
		}
		table,td,th{
			border: 1px solid #333;
			border-collapse: collapse;
			vertical-align: top
		}
		.border-bottom td{
			border-bottom: medium none;
			border-top: medium none;
		}
		.border-none td{
			border: medium none;
		}
		body{
			font-size: 14px
		}
		.vertical-align{
			vertical-align: middle;
		}
		td,th{
			padding: 3px 5px
		}
	</style>
</head>
<body>
	<div style="display: inline-block;width: 100%;margin-bottom: 30px;">
		<table style="width: 100%;border: medium none;">
			<caption style="font-size: 20px;margin-bottom: 20px;font-weight: bold">Purchase Order</caption>
			<tr>
				<td style="border: medium none;width: 48%;padding: 0;display: inline-block;">
					<table style="border: medium none;width: 100%;">
						<tr>
							<td style="border: medium none;width: 30%;padding-left:0 "><strong>PO No:</strong></td>
							<td style="width: 70%">{{$purchase_data['po_no']}}</td>
						</tr>
						<tr>
							<td style="border: medium none;width: 30%;padding-left:0 "><strong>Date:</strong></td>
							<td style="width: 70%">{{$purchase_data['purchase_approval_date']}}</td>
						</tr>
					</table>
				</td>
				<td style="width: 4%;border: medium none;display: inline-block;">
					&nbsp;
				</td>
				<td style="width: 52%;padding: 0;display: inline-block;">
					<table style="width: 100%;">
						<tr>
							<td colspan="2" style="border: medium none;"><strong style="font-size:16px">{{$supplier_details['supplier_name']}}</strong></td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$supplier_billing_add['address']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;padding: 0">
								<table style="width: 100%;border: medium none;">
									<tr>
										<td style="border: medium none;">City: {{$supplier_city_name['title']}} </td>
										<td style="border: medium none;">Pincode: {{$supplier_billing_add['pincode']}}</td>
									</tr>
									<tr>
										<td style="border: medium none;">State: {{$supplier_state_name['title']}}</td>
										<td style="border: medium none;">Country: {{$supplier_country_name['title']}}</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="30%" style="border: medium none;"  valign="top">
								Contact Details :
							</td>
							<td width="70%" style="border: medium none;overflow-wrap: break-word;word-wrap: break-word;hyphens: auto;">
								@if(!isset($purchase_data['distributor_id']))
									{{$supplier_details['spoc_name']}} / {{$supplier_details['spoc_email']}} / {{$supplier_details['spoc_phone']}}
								@else
									{{$distributor_details['spoc_name']}} / {{$distributor_details['spoc_email']}} / {{$distributor_details['spoc_phone']}}
								@endif
							</td>
						</tr>
					</table>
				</td>
			<tr>
		</table>
	</div>
	<div style="display: inline-block;width: 100%;margin-bottom: 15px">
		<table style="width: 100%;border: medium none;">
			<tr>
				<td style="width: 48%;float: left;">
					<table style="width: 100%;border: medium none;">
						<tr>
							<td colspan="2" style="border: medium none;background-color: #00AEEF;color:#ffffff"><strong style="font-size:16px">Bill To</strong></td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$invoice_company_details['company_name']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$invoice_company_details['billing_address']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;padding: 0">
								<table style="width: 100%;border: medium none;">
									<tr>
										<td style="border: medium none;">City: {{$company_billing_city['title']}}</td>
										<td style="border: medium none;">Pincode: {{$invoice_company_details['billing_pincode']}}</td>
									</tr>
									<tr>
										<td style="border: medium none;">State: {{$company_billing_state['title']}}</td>
										<td style="border: medium none;">Country: {{"India"}}</td>
									</tr>
								</table>
							</td>
						</tr>	
						<tr>
							<td width="35%" style="border: medium none;"  valign="top">Contact Details :</td>
							<td width="65%" style="border: medium none;overflow-wrap: break-word;word-wrap: break-word;hyphens: auto;">
								{{$invoice_company_details['spoc_name']}} / {{$invoice_company_details['spoc_email']}} / {{$invoice_company_details['spoc_phone']}}
							</td>
						</tr>
					</table>
				</td>
				<td style="width: 4%;border: medium none;float: left"></td>
				<td style="width: 48%;float: right">
					<table style="width: 100%;border: medium none;">
						<tr>
							<td colspan="2" style="border: medium none;background-color: #00AEEF;color:#ffffff"><strong style="font-size:16px">Ship To</strong></td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$invoice_company_details['company_name']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$company_shipping_details['address']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;padding: 0">
								<table style="width: 100%;border: medium none;">
									<tr>
										<td style="border: medium none;">City: {{$invoice_city_name['title']}}</td>
										<td style="border: medium none;">Pincode: {{$company_shipping_details['pincode']}}</td>
									</tr>
									<tr>
										<td style="border: medium none;">State: {{$invoice_state_name['title']}}</td>
										<td style="border: medium none;">Country: {{$invoice_country_name['title']}}</td>
									</tr>
								</table>
							</td>
						</tr>			
						<tr>
							<td width="35%" style="border: medium none;"  valign="top">Contact Details :</td>
							<td width="65%" style="border: medium none;overflow-wrap: break-word;word-wrap: break-word;hyphens: auto;">
								{{$invoice_company_details['shipping_name']}} / {{$invoice_company_details['shipping_email']}} / {{$invoice_company_details['shipping_phone']}}
							</td>

						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;margin-bottom: 15px">
		<table style="width: 100%;">
			<tr>
				<th style="background-color: gray">OA Reference</th>
				<th style="background-color: gray">Payment Terms</th>
				<th style="background-color: gray">Delivery Terms</th>
				<th style="background-color: gray">Destination</th>
				<th style="background-color: gray">Other Terms</th>
			</tr>
			<tr>
				<td>{{$purchase_data['other ref']}}</td>
				<td>{{$purchase_data['payment_terms']}}</td>
				<td>{{$purchase_data['delivery_terms']}}</td>
				<td>{{$invoice_city_name['title']}}</td>
				@if($purchase_data['other ref'] != null)
					<td>{{$purchase_data['other ref']}}</td>
				@else
					<td>{{"N\A"}}</td>
				@endif
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;margin-bottom: 15px">
		<table style="width: 100%;" class="border-bottom">
			<tr style="background-color: #00AEEF;color:#ffffff">
				<th width="20%">Description of Goods</th>
				<th width="10%">Part Code</th>
				@if(isset($purchase_data['distributor_id']))
				<th width="10%">Make</th>
				@endif
				<th width="10%" align="center">GST</th>
				<th width="10%" align="center">Quantity</th>
				<th width="10%" align="center">Rate</th>
				<th width="5%" align="center">Per</th>
				<th width="15%" align="center">Amount</th>
			</tr>
			@foreach($get_model_details as $item_key=>$item_value)
				<tr>
					<td><strong>{{$item_value[0]['name_description']}}</strong></td>
					<td>{{$item_value[0]['model_no']}}</td>
					@if(isset($purchase_data['distributor_id']))
					<td>{{$make_name[0]}}</td>
					@endif
					<td align="center">{{$item_value[0]['tax']}}%</td>
					<td align="right"><strong>{{$item_value[0]['qty']}} Nos</strong></td>
					@if($purchase_data['currency_status'] == 'rupee')
						<td align="center">{{$item_value[0]['unit_price']}}</td>
						<td align="right"> Nos</td>
						<td align="right"><strong>{{$item_value[0]['total_price']}}</strong></td>
					@else
						<td align="center">{{$item_value[0]['dollar_price']}}</td>
						<td align="right"> Nos</td>
						<td align="right"><strong>{{str_replace(',','',$item_value[0]['dollar_price']) * (parseInt($item_value[0]['qty']))}}</strong></td>
					@endif
				</tr>
			@endforeach
			<tr>
				<td><br></td>
				<td><br></td>
				@if(isset($purchase_data['distributor_id']))
				<td><br></td>
				@endif
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td style="border-top:1px solid #333;" align="right">@if($purchase_data['currency_status'] == 'rupee') {{$purchase_data['total_price']}} @else {{$purchase_data['dollar_total_price']}} @endif</td>
			</tr>
			@if($invoice_state_name['title'] == $supplier_state_name['title'])
				<tr>
					<td><br></td>
					<td><strong>Central Tax (CGST)</strong></td>
					@if(isset($purchase_data['distributor_id']))
					<td><br></td>
					@endif
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>
					{{$total_tax_amount}}</strong></td>
				</tr>
				<tr>
					<td><br></td>
					<td><strong>State Tax (SGST)</strong></td>
					@if(isset($purchase_data['distributor_id']))
					<td><br></td>
					@endif
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>{{$total_tax_amount}}</strong></td>
				</tr>
			@else	
				<tr>
					<td><br></td>
					<td><strong>Integrated Tax (IGST)</strong></td>
					@if(isset($purchase_data['distributor_id']))
					<td><br></td>
					@endif
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>{{$total_tax}}</strong></td>
				</tr>
			@endif
			<tr>
				<td><br></td>
				<td>Round Off</td>
				@if(isset($purchase_data['distributor_id']))
				<td><br></td>
				@endif
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td align="right"><strong>{{$round_off_value}}</strong></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #333;"><br></td>
				<td style="border-top: 1px solid #333;"><strong>Total</strong></td>
				@if(isset($purchase_data['distributor_id']))
				<td style="border-top: 1px solid #333;"><br></td>
				@endif
				<td style="border-top: 1px solid #333;"><br></td>
				<td style="border-top: 1px solid #333;"><strong>{{$total_qty}} Nos</strong></td>
				<td style="border-top: 1px solid #333;"><br></td>
				<td style="border-top: 1px solid #333;"><br></td>
				<td style="border-top: 1px solid #333;" align="right"><strong><span style="font-family: DejaVu Sans; sans-serif;">@if($purchase_data['currency_status'] == 'rupee') ₹ @else $ @endif</span>  {{$total_price_tax}}</strong></td>
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;">
		<table class="border-none" style="width: 100%">
			<tr>
				<td>Amount Chargeable (in words)</td>
				<td style="text-align: right;">E. & O.E</td>
			</tr>
			<tr>
				<td colspan="2">
					<strong>@if($purchase_data['currency_status'] == 'rupee') INR {{$ntw->numToWord($total_price_tax_int[0])}} Rupees And @if($decimal_value != 0.00){{$ntw->numToWord($total_price_tax_int[1])}} @else {{"Zero"}} @endif Paise @else USD {{$ntw->numToWord($total_price_tax_int[0])}} And @if($decimal_value != 0.00) {{$ntw->numToWord($total_price_tax_int[1])}} @else {{"Zero"}} @endif Dollar @endif </strong>
				</td>
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;">
		<table style="width: 100%">
			<tr>
				<td width="40%">
					Company's PAN: <strong>{{$invoice_company_details['pan_no']}}</strong>
					<br>
					GST No:<strong>{{$invoice_company_details['gst_no']}}</strong>
				</td>
				<td  align="right" width="60%">
					For {{$invoice_company_details['company_name']}}
					<br>
					<br>
					<br>
					Authorised Signatory
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Remark:
					<br>
					@if($purchase_data['remark'] == null)
						{{"N/A"}}
					@else
						{{$purchase_data['remark']}}
					@endif
				</td>
			</tr>
		</table>
	</div>
	<p style="text-align: center;">This is a Computer Generated Document</p>
</body>
</html>
