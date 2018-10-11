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
                <?= Form::open(['url'=>route('password.reset.post'),'class'=>'form-signin','method'=>'POST']) ?>
                    <div class="login-head">
                        <h3 class="m-0">Reset Your Admin Account</h3>
                    </div>
                    <div class="login-wrap">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group @if($errors->first('email')) has-error @endif">
                            <label class="control-label">USERNAME</label>
                            <?=Form::email('email', null, ['class' => 'form-control mb-0', 'autofocus', 'placeholder' => 'Enter your email'])?>
                            <span id="email_error" class="color-w"><?=$errors->first('email')?></span>
                        </div>
                        <div class="form-group @if($errors->first('password')) has-error @endif">
                            <label class="control-label">PASSWORD</label>
                            <?=Form::password('password', ['class' => 'form-control mb-0', 'placeholder' => 'Enter your password'])?>
                            <span id="password_error" class="color-w"><?=$errors->first('password')?></span>
                        </div>

                        <div class="form-group @if($errors->first('password_confirmation')) has-error @endif">
                            <label class="control-label">CONFIRM PASSWORD</label>
                            <?=Form::password('password_confirmation', ['class' => 'form-control mb-0', 'placeholder' => 'Enter password again'])?>
                        <span id="password_confirmation_error" class="color-w"><?=$errors->first('password_confirmation')?></span>
                        </div>
                        <div class="form-group btn-container">
                          <button class="btn btn-primary btn-block form-control">SUBMIT<i class=""></i></button>
                        </div>
                    </div>
                <?=Form::close()?>
                @include('admin.layout.overlay')
                <?=Html::script('backend/js/jquery.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/bootstrap.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/jquery.slimscroll.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/plugins/toastr-master/toastr.min.js', [], IS_SECURE)?>
                <?=Html::script('backend/js/main.js', [], IS_SECURE)?>
            </div>
        </section>
    </body>
</html>
