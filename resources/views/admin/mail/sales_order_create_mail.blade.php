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
	<div style="margin-bottom: 8px;text-align: center;width: 420px;float: none;margin: 0 auto;background: #f8f8f8;border: 1px solid #d8d8d8;padding: 10px 20px">
		<div style="text-align: left;">
			<p style="font-size: 14px !important">Dear SuperAdmin/Accoutant,</p>
			<div style="padding: 20px;display: inline-block;width: calc(100% - 40px);">
				The SO No. {{$data['so_no']}} has been generated for the Customer {{$data['customer_name']}} BY Engineer {{$data['user_name']}}.
			</div>
			@if($data['company_id'] == 1)
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