<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin</title>
        <?=Html::style('backend/plugins/toastr-master/toastr.min.css')?>
        <?=Html::style('backend/css/main.css')?>
    </head>
    <body class="login-body">
    <section class="material-half-bg">
    </section>
        <section class="login-content">
            <div class="login-box">
                <div class="login_logo">
                  <p class="m-0">ADMIN</p>
                </div>
                <?= Form::open(['class'=>'form-signin','method'=>'POST']) ?>
                    <div class="login-head">
                        <h3 class="m-0">Reset Your Admin Account</h3>
                    </div>
                    <div class="login-wrap">
                        <div class="form-group @if($errors->first('email')) has-error @endif">
                            <label class="control-label">USERNAME</label>
                            <?=Form::email('email', null, ['class' => 'form-control mb-0','id'=>'email','autofocus', 'placeholder' => 'Enter your email'])?>
                            <span id="email_error" class="color-w"><?=$errors->first('email')?></span>
                        </div>
                        <div class="form-group @if($errors->first('password')) has-error @endif">
                            <label class="control-label">PASSWORD</label>
                            <?=Form::password('password', ['class' => 'form-control mb-0', 'placeholder' => 'Enter your password','id'=>'password'])?>
                            <span id="password_error" class="color-w"><?=$errors->first('password')?></span>
                        </div>

                        <div class="form-group @if($errors->first('password_confirmation')) has-error @endif">
                            <label class="control-label">CONFIRM PASSWORD</label>
                            <?=Form::password('password_confirmation', ['class' => 'form-control mb-0', 'placeholder' => 'Enter password again','id'=>'password_confirm'])?>
                        <span id="password_confirmation_error" class="color-w"><?=$errors->first('password_confirmation')?></span>
                        </div>
                        <div class="form-group btn-container">
                          <button class="btn btn-primary btn-block form-control" id="submit_form" type="button">SUBMIT<i class=""></i></button>
                        </div>
                    </div>
                <?=Form::close()?>
                @include('admin.layout.overlay')
                <?=Html::script('backend/js/jquery.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/bootstrap.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/jquery.slimscroll.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/plugins/toastr-master/toastr.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/main.js', [], IS_SECURE)?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#password').keyup(function() {
                            // checkStrength($('#password').val());
                            // console.log($('#password').val());
                            $('#password_error').html(checkStrength($('#password').val()));

                        });
                    });
                    function checkStrength(password) {  
                        var password_err = [];
                        if(password.length <8 || password.length>16){
                            password_err.push("Password must be Greater than 8 & Less than 16 digit.");
                        }else{
                            password_err.push("");
                        }
                        if(password.match(/([a-zA-Z])/) && password.match(/([0-9])/)){
                            password_err.push("");
                        }
                        else{
                            password_err.push("Password must be Alpha Numeric.");
                        }
                        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)){
                            password_err.push("");
                        }
                        else{
                            password_err.push("Password must be Special Character.");
                        }
                        var error = '';
                        if(password_err == ''){
                            error = '';
                        }else{
                            error = password_err[0] + "<br/>" + password_err[1]+ "<br/>" + password_err[2];
                        }
                        if(error == '<br/><br/>'){
                            error = null;
                        }
                        return error;
                    }
                    $('#submit_form').click(function(){
                        var url = "<?=route('password.change.post')?>";
                        var email = $('#email').val();
                        var password = $('#password').val();
                        var confirm_password = $('#password_confirm').val();
                        var token = "{{csrf_token()}}";
                        $.ajax({
                            type : 'POST',
                            url : url,
                            data : {
                                '_token' : token,
                                'email' : email,
                                'password' : password,
                                'confirm_password' : confirm_password
                            },
                            beforeSubmit : function()
                            {
                               $("[id$='_error']").empty();
                            },
                            success : function(data){
                                if(data.message == 'conf_password'){
                                    $('#password_confirmation_error').text(data.conf_password);
                                }
                                if(data.message == 'email'){
                                    $('#email_error').text(data.email);
                                }
                                if(data.message == 'not_match'){
                                    $("#password_confirm").val('');
                                    $('#password').val('');
                                    $('#password_error').text('The Password Confiration does not match.');
                                }
                                if(data.message == 'success'){
                                    window.location.href = 'login';
                                }
                            },
                            error : function(data){
                                $.each(data.responseJSON.errors,function(k,v){
                                    $('#'+k+'_error').text(v);
                                })
                            }
                        })
                    });
                </script>
            </div>
        </section>
    </body>
</html>
