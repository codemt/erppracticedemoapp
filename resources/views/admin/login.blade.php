<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Admin</title>
        <?= Html::style('backend/css/main.css') ?>
		<?=Html::style('backend/plugins/toastr-master/toastr.min.css')?>
		<?=Html::style('backend/css/custom.css')?>

	</head>
	<body>
		<div id="loader" style="display: none;">
		  <div class="loader"><img src="<?= IMAGE_PATH.'backend/images/loader.gif'?>"></div>
		</div>
		<section class="material-half-bg" style="background:url('<?=LOGIN_BG?>') no-repeat !important"></section>
		<section class="login-content">
	        <div class="login-box">
	            <div class="login_logo">
	                <p class="m-0"><b>ERP ADMIN</b></p>
	            </div>
	            <?= Form::open(['url' => route('admin.login'),'id'=>'login_form','class'=>'login-form', 'method' => 'POST']) ?>
				            <center style="margin-bottom: 10px;">
				            	<!-- <span style="color:yellow;">These credentials do not match our records.</span> -->
							</center>				       
						<div class="form-group @if($errors->first('email')) has-error-white @endif">
							<label class="control-label">USERNAME</label>
							<?= Form::email('email',null,['class'=>'form-control mb-0','autofocus','placeholder'=>'Enter your email']) ?>
							@if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
							<!-- <span style="color:yellow;">The email field is required.</span> -->
						</div>
						<div class="form-group @if($errors->first('password')) has-error-white @endif">
							<label class="control-label">PASSWORD</label>
							<?= Form::password('password',['class'=>'form-control mb-0','placeholder'=>'Enter your password']) ?>
							<!-- <span style="color:yellow;">The password field is required.</span> -->
							 @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
						</div>
	                <div class="form-group">
	                    <div class="utility">
	                        <div class="animated-checkbox">
	                            <label class="semibold-text">
	                              <?=Form::checkbox('name', 'remember');?><span class="label-text">Stay Signed in</span>
	                            </label>
	                        </div>
	                        <p class="semibold-text mb-0"><a id="toFlip" href="#" class="forget_link">Forgot Password ?</a></p>
	                    </div>
	                </div>
	                <div class="form-group btn-container">
	                  <button class="btn btn-primary btn-block form-control">SIGN IN <i class="fa fa-sign-in fa-lg"></i></button>
	                </div>
	            <?=Form::close()?>
	            <form id="forget-form" class="forget-form">
	                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
	                <div class="form-group @if($errors->first('email')) has-error-white @endif">
	                    <label class="control-label"></label>
	                    <?=Form::label('email', 'EMAIL', ['class' => 'control-label']);?>
	                    <?=Form::email('email', null, ['class' => 'form-control', 'autofocus', 'placeholder' => 'Enter your email'])?>
	                    <span id="email_error" class="text-danger help-block"></span>
	                </div>
	                <div class="form-group btn-container">
	                    <button class="btn btn-primary btn-block" type="submit" id="send_mail">SEND <i class="fa fa-unlock fa-lg"></i></button>
	                </div>
	                <div class="form-group mt-20">
	                    <p class="semibold-text mb-0"><a id="noFlip" href="#"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
	                </div>
            	</form>
	        </div>
    	</section>
	</body>
<!-- js placed at the end of the document so the pages load faster -->
<?= Html::script('backend/js/jquery-2.1.4.min.js')?>
<?= Html::script('backend/js/bootstrap.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/essential-plugins.js')?>
<?= Html::script('backend/js/jquery.form.min.js')?>
<?= Html::script('backend/js/jquery.slimscroll.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/plugins/toastr-master/toastr.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/main.js',[],IS_SECURE) ?>

<script type="text/javascript">

    var token = "<?=csrf_token()?>";
    $('form').submit(function(){
        $('.overlay').show();
    });
   $(document).ready(function(){
   		$('#loader').hide();

        $('#send_mail').click(function(e,ele) {
            $('#forget-form').ajaxSubmit({
                url: "<?=URL::route('password.email.admin')?>",
                type: 'post',
                data: { "_token" : token },
                dataType: 'json',
                beforeSubmit : function()
                {
                	$('#loader').show();

                    $("[id$='_error']").empty();
                },
                success : function(resp)
                {
                	$('#loader').hide();

                	if(resp.message == 'disapprove'){
                		toastr.error('Your account is not activated.');
                	}
                	if(resp.message == 'email'){
                		toastr.error("We can't find a user with that e-mail address.");
                	}
                	if(resp.message == 'empty'){
                		toastr.error("The Email field is required.");
                	}
                    if (resp.success == false) {
                        $('#email_error').text(resp.message);
                    }
                    if (resp.success == true) {
                        $('#forget-form')[0].reset();
                        toastr.success(resp.message);
                    }
                },
                errors : function(respObj){
                	$('#loader').hide();

                    $.each(respObj.responseJSON.error, function(k,v){
                        $('#'+k+'_error').text(v);
                    });
                }
            });
            return false;
        });
    });
</script>
</html>
