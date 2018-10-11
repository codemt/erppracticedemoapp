@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>
    <meta name="_token" content="{{csrf_token()}}" />
  
@stop
@section('start_form')
    <?=Form::open(['method' => 'GET', 'url'=>'/admin/input/form', 'class' => 'm-0 form-horizontal'])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Send and Send New">Send & New </button>
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Send and Exit">Send & exit </button>
        <a href="/admin/input" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Send Emails </h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('text_box')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">To <sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::text('text_box', old('text_box'), ['class' => 'form-control', 'placeholder' => 'to','id'=>'from_email']);?>
                                <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('text_box')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                                <div class="control-label col-md-4">CC<sup class="text-danger">*</sup></div>
                                <div class="col-md-8">
                                    <?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'cc']);?>
                                    <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-md-12">
                    
				
					<div class="col-md-6">
							<div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
								<div class="control-label col-md-4">BCC<sup class="text-danger">*</sup></div>
								<div class="col-md-8">
									<?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'bcc']);?>
									<span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
								</div>
							</div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                                    <div class="control-label col-md-4">Subject<sup class="text-danger">*</sup></div>
                                    <div class="col-md-8">
                                        <?=Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'bcc']);?>
                                        <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="col-md-6">
                                            <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                                                <div class="control-label col-md-4">Template<sup class="text-danger">*</sup></div>
                                                <div class="col-md-8">
                                                        <select class="form-control" id="options">
                                                                <option>NA</option>
                                                              </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                                    <div class="col-md-8" style="padding-top:1em">
                                                            <a type="submit" id="generate" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Send and Send New"> Generate Template </a>
                                                    </div>
                                                </div>
                                            </div>
                                        
                            </div>
					<div class="col-md-12">	
                    
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Email Body<sup class="text-danger">*</sup></div>
                            <div class="col-md-12">
                                    <textarea name="summernoteInput"  id="textarea" class="summernote"></textarea>
                                <span id="address_error" class="help-inline text-danger"><?=$errors->first('address')?></span>
                            </div>
                        </div>
                
                </div>
                             
                
                <div class="col-md-12">
					<div class="col-md-12">
                </div>
                <div class="col-md-12">
					<div class="col-md-12">
                </div>
                </div>

            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save & New </button>
        
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="/admin/input" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>
  <!-- include summernote css/js-->
  <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>   

    <script type="text/javascript">
        $('.select2').select2({
            placeholder : "status",
        });
        $('.select_2').select2({
            placeholder : "status",
        });
          
        $(document).ready(function(){

             
                $('.summernote').summernote();
            



                $("#options").append( '<option value="new_user_mail"> New User Mail </option>');
                $("#options").append( '<option value="purchase_order_approval"> Purchase Order Approval </option>');
                $("#options").append( '<option value="sales_order_approval"> Sales Order Approval </option>');
                $("#options").append( '<option value="sales_order_create"> Sales Order Create </option>');
                $("#options").append( '<option value="sales_order_customer_approval"> Sales Order Customer Approval </option>');
                $("#options").append( '<option value="sales_order_accountant_approval"> Sales Order Accountant Approval </option>');
                $("#options").append( '<option value="happy_birthday"> Happy Birthday </option>');


                    $("#generate").click(function(e){


                            let optionvalue = $('#options').val();
                            let from_email = $('#from_email').val();
                            console.log(optionvalue);

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                }
                            });
                            if(optionvalue == 'new_user_mail')
                            {

                                         $.ajax({
                                                    url: "{{ route('user.mail.template') }}",
                                                    method: 'post',
                                                    data: {
                                                        from_email : $('#from_email').val(),
                                                    },
                                                    success: function(result){
                                                        //alert(result);
                                                        $('#textarea').summernote('code','<!DOCTYPE html><html> <head></head> <body> <div style="text-align: center;width: 100%;font-family: open sans;max-width: 580px;float: none;margin: 0 auto;border: 1px solid #686868 ;padding-top: 30px;display: inline-block;"> <div style="padding: 0px;text-align: center;display: inline-block;float: left;width:calc(100% - 40px);padding: 0 20px;border-box: box-sizing;"> <!-- <div style="margin-bottom: 20px;"><img src="http://wowffers.com/backend/images/wowffersLogo.png" alt="logo"></div> --> <div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Dear '+ result +'</b></h2> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 20px;margin-bottom: 10px;">Your registration for ERP access is successful!</div> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 16px;margin-bottom: 10px;">Your login credentials are. </div> </div> </div> <div style="background-color: #0d4a83;color: #ffffff;font-size: 18px;padding: 20px;width: 100%;display: inline-block;box-sizing: border-box;"> <div style="margin-bottom: 4px;"><strong>Name: </strong></div> <div style="margin-bottom: 4px;"><strong>Email: </strong></div> <div style="margin-bottom: 4px;"><strong>Password: </strong></div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;">There is link to login: <a href="">Click here</a></div> </div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;"> Please write to us on email <a href="mailto:marketing@wowffers.com" style="text-decoration:none;color: #e52534;">admin@gmail.com</a><br/>or call us on <a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890 / </a><a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890</a>for your login issues.</div> </div> </div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Regards </b><br><br> Triton Process Automation Pvt Limited<br> Swastik Disha Corporate Park, 613-615, 6th Floor<br> L.B.S. Marg, Opposite shreyas Cinema<br> Ghatkopar(W), Mumbai – 400086<br> Tel. : 022 2500 1900<br> nirav@tritonprocess.com <br> www.tritonprocess.com</h2> </div> </div> </div> </div> </div> </body></html>');
                    
                                                        
                                                    },
                                                    error: function (data) {
                                                        console.log('Error:', data);
                                                        
                                                        }

                                                });
                        
                                // $('#textarea').val('Hello User , Welcome to iTransparity').summernote('code');
                               

                            }
                            if(optionvalue == 'happy_birthday')
                            {

                                         $.ajax({
                                                    url: "{{ route('user.mail.template') }}",
                                                    method: 'post',
                                                    data: {
                                                        from_email : $('#from_email').val(),
                                                    },
                                                    success: function(result){
                                                        
                                                        $('#textarea').summernote('code',' <h1> Dear  ' + result + ' </h1> <br><h3> Happy Birthday  </h3> <br> <p> Wish you a Great Year Ahead </p> ');
                                                    },
                                                    error: function (data) {
                                                        console.log('Error:', data);
                                                        
                                                        }

                                                });
                        
                                // $('#textarea').val('Hello User , Welcome to iTransparity').summernote('code');
                               

                            }




                    });

                // $("#options").change(function(e){


                //     let optionvalue = $('#options').val();
                //     if(optionvalue == 'new_user_mail')
                //     {
                        
                //         // $('#textarea').val('Hello User , Welcome to iTransparity').summernote('code');
                //         $('#textarea').summernote('code','<!DOCTYPE html><html> <head></head> <body> <div style="text-align: center;width: 100%;font-family: open sans;max-width: 580px;float: none;margin: 0 auto;border: 1px solid #686868 ;padding-top: 30px;display: inline-block;"> <div style="padding: 0px;text-align: center;display: inline-block;float: left;width:calc(100% - 40px);padding: 0 20px;border-box: box-sizing;"> <!-- <div style="margin-bottom: 20px;"><img src="http://wowffers.com/backend/images/wowffersLogo.png" alt="logo"></div> --> <div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Dear </b></h2> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 20px;margin-bottom: 10px;">Your registration for ERP access is successful!</div> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 16px;margin-bottom: 10px;">Your login credentials are. </div> </div> </div> <div style="background-color: #0d4a83;color: #ffffff;font-size: 18px;padding: 20px;width: 100%;display: inline-block;box-sizing: border-box;"> <div style="margin-bottom: 4px;"><strong>Name: </strong></div> <div style="margin-bottom: 4px;"><strong>Email: </strong></div> <div style="margin-bottom: 4px;"><strong>Password: </strong></div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;">There is link to login: <a href="">Click here</a></div> </div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;"> Please write to us on email <a href="mailto:marketing@wowffers.com" style="text-decoration:none;color: #e52534;">admin@gmail.com</a><br/>or call us on <a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890 / </a><a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890</a>for your login issues.</div> </div> </div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Regards </b><br><br> Triton Process Automation Pvt Limited<br> Swastik Disha Corporate Park, 613-615, 6th Floor<br> L.B.S. Marg, Opposite shreyas Cinema<br> Ghatkopar(W), Mumbai – 400086<br> Tel. : 022 2500 1900<br> nirav@tritonprocess.com <br> www.tritonprocess.com</h2> </div> </div> </div> </div> </div> </body></html>');
                    

                //     }
                //     if(optionvalue == 'purchase_order_approval')
                //     {
                            
                           
                           

                //                $('#textarea').summernote('code','<h1><html><head><title></title><style type="text/css">p{font-size: 16px;font-weight: normal;margin-bottom: 0}</style></head><body><div style="margin-bottom: 8px;text-align: center;width: 420px;float: none;margin: 0 auto;background: #f8f8f8;border: 1px solid #d8d8d8;padding: 10px 20px"><div style="text-align: left;"><p style="font-size: 14px !important">Dear </p><div style="padding: 20px;display: inline-block;width: calc(100% - 40px);"><p>Please find attached herewith PO No: </p><p>Please deliver the material as per manufacturing clearance given by planning team .Material should be delivered on site between 10.30 am to 6 pm also material should not reach on Sunday or any government holiday on site.</p><p> Kindly confirm the acceptance of the P.O within 2 working days. Failing to receive any confirmation / comment from your end we would presume the acceptance of the P.O to you in its entirety.Vendor has to inform about delivery of material to our team whose no is mentioned below – Mr alpesh Parmar(+91-9167950287)</p></div><p style="margin-bottom: 10px">Regards,<br><strong></strong><br><br>Tel. : <br>nirav@tritonprocess.com <br>www.tritonprocess.com</p></div></div></body></html></h1>');

                           
                           
                        
                //             // $('#textarea').val('Hello User , Your Purchase Order is this ABP223-E');
                //             //$('#textarea').summernote('pasteHTML','<!DOCTYPE html><html> <head></head> <body> <div style="text-align: center;width: 100%;font-family: open sans;max-width: 580px;float: none;margin: 0 auto;border: 1px solid #686868 ;padding-top: 30px;display: inline-block;"> <div style="padding: 0px;text-align: center;display: inline-block;float: left;width:calc(100% - 40px);padding: 0 20px;border-box: box-sizing;"> <!-- <div style="margin-bottom: 20px;"><img src="http://wowffers.com/backend/images/wowffersLogo.png" alt="logo"></div> --> <div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Dear </b></h2> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 20px;margin-bottom: 10px;">Your registration for ERP access is successful!</div> </div> </div> <div style="padding: 0 20px;"> <div> <div style="font-size: 16px;margin-bottom: 10px;">Your login credentials are. </div> </div> </div> <div style="background-color: #0d4a83;color: #ffffff;font-size: 18px;padding: 20px;width: 100%;display: inline-block;box-sizing: border-box;"> <div style="margin-bottom: 4px;"><strong>Name: </strong></div> <div style="margin-bottom: 4px;"><strong>Email: </strong></div> <div style="margin-bottom: 4px;"><strong>Password: </strong></div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;">There is link to login: <a href="">Click here</a></div> </div> </div> <div style="padding: 0 20px 20px;"> <div> <div style="font-size: 18px;margin: 25px auto 0 ;line-height: 26px;"> Please write to us on email <a href="mailto:marketing@wowffers.com" style="text-decoration:none;color: #e52534;">admin@gmail.com</a><br/>or call us on <a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890 / </a><a href="tel:1234567890" style="text-decoration:none;color: #e52534;">1234567890</a>for your login issues.</div> </div> </div> <div style="padding: 0 20px;margin-bottom: 20px;"> <div> <h2 style="margin: 0 auto;font-size: 18px;margin-bottom: 10px;font-weight: normal;text-align: left;"><b>Regards </b><br><br> Triton Process Automation Pvt Limited<br> Swastik Disha Corporate Park, 613-615, 6th Floor<br> L.B.S. Marg, Opposite shreyas Cinema<br> Ghatkopar(W), Mumbai – 400086<br> Tel. : 022 2500 1900<br> nirav@tritonprocess.com <br> www.tritonprocess.com</h2> </div> </div> </div> </div> </div> </body></html>');
                //     }



                //     });



                });

        
    </script>
@stop