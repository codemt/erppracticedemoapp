<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table,td,th{
			border: 1px solid #333;
			border-collapse: collapse;
			text-align: center;
		}
		td,th{
			padding: 5px
		}
		p{
			font-size: 16px;
			line-height: 24px
		}
	</style>
</head>
<body>
	<div style="margin-bottom: 8px;text-align: center;width: 500px;float: none;margin: 0 auto;background: #f8f8f8;border: 1px solid #d8d8d8;padding: 10px 20px">
		<div style="text-align: left;">
			<p style="font-size: 14px !important">Dear SuperAdmin/Accoutant,</p>
			<div style="padding: 20px;display: inline-block;width: calc(100% - 40px);">
				<table style="width: 100%">
					<tr>
						<th>SO No.</th>
						<th>Customer Name</th>
						<th>Model number</th>
						@if($data[0]['is_superadmin'] == 1)
						<th>Discount Applied</th>
						@endif
						<th>List Price</th>
						<th>Unit value</th>
					</tr>
					@foreach($data as $sales_item)
						<tr @if($sales_item['is_mail'] == '1') style="background-color: red;" @endif>
							<td>{{$sales_item['so_no']}}</td>
							<td>{{$sales_item['customer_name']}}</td>
							<td>{{$sales_item['model_no']}}</td>
							@if($data[0]['is_superadmin'] == 1)
							<td>{{$sales_item['discount_applied']}}%</td>
							@endif
							<td>{{$sales_item['list_price']}}</td>
							<td>{{$sales_item['unit_value']}}</td>
						</tr>
					@endforeach	
				</table>
				<ul style="margin-left: 0;margin-bottom: 0">
					<li style="margin-left: 0;margin-bottom: 0">Request for approving the same</li>
				</ul>
				<ul style="margin-left: 0;margin-bottom: 0">
					<li style="margin-left: 0;margin-bottom: 0">
						@if($data[0]['is_superadmin'] == 1)
							<a href="<?= URL::route('salesorder.edit',$data[0]['sales_order_id'])?>">Go Back to Sales order</a>
						@endif
					</li>
				</ul>
			</div>
			@if($data[0]['company_id'] == 1)
				<p>
					Regards,
					<br>
					<strong>Stellar Ecoenergy Solutions LLP</strong>
					<br>
					Swastik Disha Corporate Park, 613-615, 6th Floor
					<br>
					L.B.S. Marg, Opposite shreyas Cinema
					<br>
					Ghatkopar(W), Mumbai – 400086
					<br>
					Tel. : 022 2500 1900
					<br>
					nirav@tritonprocess.com 
					<br>
					www.tritonprocess.com
				</p>
			@else
				<p>
					Regards,
					<br>
					<strong>Triton Process Automation Pvt Limited</strong>
					<br>
					Swastik Disha Corporate Park, 613-615, 6th Floor
					<br>
					L.B.S. Marg, Opposite shreyas Cinema
					<br>
					Ghatkopar(W), Mumbai – 400086
					<br>
					Tel. : 022 2500 1900
					<br>
					nirav@tritonprocess.com 
					<br>
					www.tritonprocess.com
				</p>
			@endif	
		</div>
	</div>

</body>
</html>