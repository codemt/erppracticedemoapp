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
			<caption style="font-size: 20px;margin-bottom: 20px;font-weight: bold">Sales Order Acknowledgment</caption>
			<tr>
				<td style="border: medium none;width: 40%;float: left;padding: 0">
					<table style="border: medium none;width: 100%;">
						<tr>
							<td style="font-size: 11px;border: medium none;width: 40%;padding-left:0 "><strong>SOA No:</strong></td>
							<td style="width: 60%">{{$sales_order_data['so_no']}}</td>
						</tr>
						<tr>
							<td style="font-size: 11px;border: medium none;width: 40%;padding-left:0 "><strong>Date:</strong></td>
							<td style="width: 60%">{{$sales_order_data['created_at']}}</td>
						</tr>
					</table>
				</td>
				<td style="width: 20%;border: medium none;"></td>
				<td style="border: medium none;width: 40%;float: right;padding: 0">
					<table style="border: medium none;width: 100%;">
						<tr>
							<td style="font-size: 11px;border: medium none;width: 40%;text-align: left;float: left"><strong>PO No:</strong></td>
							<td style="width: 60%;">{{$sales_order_data['po_no']}}</td>
						</tr>
						<tr>
							<td style="font-size: 11px;border: medium none;width: 40%;text-align: left;float: left"><strong>Date:</strong></td>
							<td style="width: 60%;">{{$sales_order_data['order_date']}}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div style="display: inline-block;width: 100%;margin-bottom: 15px">
		<table style="width: 100%;border: medium none;">
			<tr>
				<td style="width: 48%;">
					<table style="width: 100%;border: medium none;">
						<tr>
							<td colspan="2" style="border: medium none;background-color: #00AEEF;color: #ffffff;">Bill To</strong></td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$sales_order_data['company_name']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$sales_order_data['billing_address']}}</td>
						</tr>
						<tr>
							<td style="border: medium none;">City: {{$sales_order_data['bill_city']}}</td>
							<td style="border: medium none;">Pincode: {{$sales_order_data['bill_pincode']}}</td>
						</tr>
						<tr>
							<td style="border: medium none;">State: {{$sales_order_data['bill_state']}}</td>
							<td style="border: medium none;">Country: {{$sales_order_data['bill_country']}}</td>
						</tr>
						<tr>
							<td width="100%" style="border: medium none;" colspan="2" valign="top"><span style="width: 30%;display: inline-block;vertical-align: top">Contact Details :</span><span style="width: 70%;display: inline-block;vertical-align: top"> {{$sales_order_data['contact_name']}}/{{$sales_order_data['contact_email']}}/<br>{{$sales_order_data['contact_no']}}</span></td>
						</tr>
					</table>
				</td>
				<td style="width: 2%;border: medium none;"></td>
				<td style="width: 48%;">
					<table style="width: 100%;border: medium none;">
						<tr>
							<td colspan="2" style="border: medium none;background-color: #00AEEF;color: #ffffff;">Ship To</strong></td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$sales_order_data['company_name']}}</td>
						</tr>
						<tr>
							<td colspan="2" style="border: medium none;">{{$sales_order_data['shipping_address']}}</td>
						</tr>
						<tr>
							<td style="border: medium none;">City: {{$sales_order_data['ship_city']}}</td>
							<td style="border: medium none;">Pincode: {{$sales_order_data['pin_code']}}</td>
						</tr>
						<tr>
							<td style="border: medium none;">State: {{$sales_order_data['ship_state']}}</td>
							<td style="border: medium none;">Country: {{$sales_order_data['ship_country']}}</td>
						</tr>
						<tr>
							<td width="100%" style="border: medium none;" colspan="2" valign="top"><span style="width: 30%;display: inline-block;vertical-align: top">Contact Details :</span><span style="width: 70%;display: inline-block;vertical-align: top"> {{$sales_order_data['contact_name']}}/{{$sales_order_data['contact_email']}}/<br>{{$sales_order_data['contact_no']}}</span></td>
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
				<th style="background-color: gray">Ship Via</th>
				<th style="background-color: gray">Destination</th>
			</tr>
			<tr>
				<td>{{$sales_order_data['so_no']}}</td>
				<td>{{$sales_order_data['payment_terms']}}</td>
				<td>{{$sales_order_data['delivery']}}</td>
				<td>{{$sales_order_data['trasport']}}</td>
				<td>{{$sales_order_data['ship_city']}}</td>
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;margin-bottom: 15px">
		<table style="width: 100%;" class="border-bottom">
			<tr style="border: medium none;background-color: #00AEEF;color: #ffffff;">
				<th width="20%" style="color: #ffffff;">Description of Goods</th>
				<th width="20%" style="color: #ffffff;">Part Code</th>
				<th width="10%" style="color: #ffffff;" align="center">Make</th>
				<th width="10%" style="color: #ffffff;" align="center">HSN/SAC</th>
				<th width="10%" style="color: #ffffff;" align="center">Quantity</th>
				<th width="10%" style="color: #ffffff;" align="center">Rate</th>
				<th width="5%" style="color: #ffffff;" align="center">Per</th>
				<th width="15%" style="color: #ffffff;" align="center">Amount</th>
			</tr>
			@foreach($order_item as $item_key=>$item_value)
				@foreach($item_value as $item_data_key=>$item_data_value)
					<?php $make = explode(' ',$item_key);?>
					<tr>
						<td><strong>{{$item_data_value['name_description']}}</strong></td>
						<td>{{$item_data_value['model_no']}}</td>
						<td align="center">{{$make[0]}}</td>
						<td align="center">{{$item_data_value['hsn_code']}}</td>
						<td align="right"><strong>{{$item_data_value['qty']}} Nos</strong></td>
						<td align="center">{{$item_data_value['unit_value']}}</td>
						<td align="right"> Nos</td>
						<td align="right"><strong>{{$item_data_value['total_value']}}</strong></td>
					</tr>
				@endforeach
			@endforeach
			<tr>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td style="border-top:1px solid #333;" align="right">{{$sales_order_data['total_amount']}}</td>
			</tr>
			<tr>
				<td><br></td>
				<td><strong>Freight</strong></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td align="right">{{$sales_order_data['fright']}}</td>
			</tr>
			<tr>
				<td><br></td>
				<td><strong>Pkg & Fwd</strong></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td align="right">{{$sales_order_data['pkg_fwd']}}</td>
			</tr>
			@if($sales_order_data['igst'] == true)
				<tr>
					<td><br></td>
					<td><strong>Integrated Tax (IGST)</strong></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>{{$sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount']}}</strong></td>
				</tr>
			@else		
				<tr>
					<td><br></td>
					<td><strong>Central Tax (CGST)</strong></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>
					{{($sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount'])/2}}</strong></td>
				</tr>
				<tr>
					<td><br></td>
					<td><strong>State Tax (SGST)</strong></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td><br></td>
					<td align="right"><strong>{{($sales_order_data['tax_subtotal'] + $sales_order_data['total_tax_amount'])/2}}</strong></td>
				</tr>
			@endif
			<tr>
				<td><br></td>
				<td>Round Off</td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td><br></td>
				<td align="right"><strong>{{ $sales_order_data['round_off']}}</strong></td>
			</tr>
			<tr>
				<td><br></td>
				<td><strong>Total</strong></td>
				<td><br></td>
				<td><br></td>
				<td><strong>{{$sales_order_data['total_qty']}} Nos</strong></td>
				<td><br></td>
				<td><br></td>
				<td align="right"><strong>{{$sales_order_data['grandTotal']}}</strong></td>
				
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
					<strong>INR {{$ntw->numToWord($sales_order_data['grandTotal'])}} Rupees</strong>
				</td>
			</tr>
		</table>
	</div>
	<br style="clear: both">
	<div style="display: inline-block;width: 100%;">
		<table style="width: 100%">
			<tr>
				<td>HSN/SAC</td>
				<td>Taxable</td>
				@if($sales_order_data['igst'] == true)
					<td colspan="2">Integrated Tax</td>
				@else
					<td colspan="2">Central Tax</td>
					<td colspan="2">State Tax</td>
				@endif
				<td>Total</td>
			</tr>
			<tr>
				<td></td>
				<td>value</td>
				@if($sales_order_data['igst'] == true)
				<td>Rate</td>
				<td>Amount</td>
				@else
					<td>Rate</td>
					<td>Amount</td>
					<td>Rate</td>
					<td>Amount</td>
				@endif
				<td>Tax Amount</td>
			</tr>
			@foreach($hsn_codes as $key =>$value)
				@if($sales_order_data['igst'] == true)
					<tr>
						<td>{{$value['hsn_code']}}</td>
						<td>{{$value['total_hsn_value']}}</td>
						<td>18%</td>
						<td>{{($value['total_hsn_value'])*18/100}}</td>
						<td>{{($value['total_hsn_value'])*18/100}}</td>
					</tr>
				@else	
					<tr>
						<td>{{$value['hsn_code']}}</td>
						<td>{{$value['total_hsn_value']}}</td>
						<td>9%</td>
						<td>{{($value['total_hsn_value'])*9/100}}</td>
						<td>9%</td>
						<td>{{($value['total_hsn_value'])*9/100}}</td>
						<td>{{($value['total_hsn_value'])*18/100}}</td>
					</tr>
				@endif
			@endforeach
			@if($sales_order_data['igst'] == true)
				<tr style="border-top:medium none;">
					<td>freight code</td>
					<td>{{$sales_order_data['pkg_fwd'] + $sales_order_data['fright']}}</td>
					<td>18%</td>
					<td>{{number_format(($sales_order_data['pkg_fwd'] + $sales_order_data['fright'])*18/100,2,'.','')}}</td>
					<td>{{number_format(($sales_order_data['pkg_fwd'] + $sales_order_data['fright'])*18/100,2,'.','')}}</td>
				</tr>
			@else	
				<tr style="border-top:medium none;">
					<td>freight code</td>
					<td>{{$sales_order_data['pkg_fwd'] + $sales_order_data['fright']}}</td>
					<td>9%</td>
					<td>{{number_format(($sales_order_data['pkg_fwd'] + $sales_order_data['fright'])*9/100,2,'.','')}}</td>
					<td>9%</td>
					<td>{{number_format(($sales_order_data['pkg_fwd'] + $sales_order_data['fright'])*9/100,2,'.','')}}</td>
					<td>{{number_format(($sales_order_data['pkg_fwd'] + $sales_order_data['fright'])*18/100,2,'.','')}}</td>
				</tr>
			@endif
			@if($sales_order_data['igst'] == true)
				<tr>
					<td>Total</td>
					<td>{{$sales_order_data['total_taxable_value']}}</td>
					<td></td>
					<td>{{($sales_order_data['igst_total'])}}</td>
					<td>{{number_format((($sales_order_data['total_taxable_value']*18)/100),2,'.','')}}</td>
				</tr>
			@else	
				<tr>
					<td>Total</td>
					<td>{{$sales_order_data['total_taxable_value']}}</td>
					<td>9%</td>
					<td>{{$sales_order_data['cgst_sgst_total']}}</td>
					<td></td>
					<td>{{$sales_order_data['cgst_sgst_total']}}</td>
					<td>{{number_format((($sales_order_data['total_taxable_value']*18)/100),2,'.','')}}</td>
				</tr>
			@endif	
			<tr>
				<td height="auto">
					Company's PAN: <strong>{{$sales_order_data['pan_no']}}</strong>
					<br>
					GST No:<strong>{{$sales_order_data['gst_no']}}</strong>
				</td>
				@if($sales_order_data['igst'] == true)
				<td rowspan="3" colspan="4" align="right">
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					For Triton Process Automation Pvt Ltd
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					Authorised Signatory
				</td>
				@else
				<td rowspan="3" colspan="6" align="right">
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					For Triton Process Automation Pvt Ltd
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					Authorised Signatory
				</td>
				@endif
			</tr>
			<tr>
				<td>
					Bank Name: Kotak Mahindra Bank Limited
					<br>
					A/c No: 0411491015
					<br>
					Branch & IFS Code: Ghatkopar West & KKBK0000682
					<br>
					Pay in the favor of: Triton Process Automation Pvt Ltd.
				</td>
			</tr>
			<tr>
				<td>
					Remark:
					<br>
					1. In Case of any discrepancy in the invoice,please bring the same to our attention in 7days of receipt of invoice.
					<br>
					2.Delay in payment beyond the agreedc credit period will attract interest @18%
					<br>
					3.Government Taxes applied as Per the prevailing rates.
				</td>
			</tr>
		</table>
	</div>	
</body>
</html>
