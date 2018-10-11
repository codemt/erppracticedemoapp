<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin</title>
        <?= Html::style('backend/css/main.css') ?>
    </head>
    <body>
<body>
        <section class="material-half-bg">
        </section>
        <section class="login-content">
            <div class="login-box">
                <div class="login_logo">    
                    <p class="m-0">ADMIN</p>
                </div>

                    <form class="form-horizontal" role="form" method="POST" action="">
                        {{ csrf_field() }}
                        <div class="login-head">
                            <h3 class="m-0 text-center">Forgot Password</h3>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">EMAIL</label>

                            <!-- <div class="col-md-6"> -->
                                <input id="email" type="email" class="form-control mb-0" name="email" placeholder="Email" value="{{ old('email') }}" >

                                @if ($errors->has('email'))
                                    <span class="color-w">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            <!-- </div> -->
                        </div>

                       <!--  <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div> -->
                        <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block form-control" type="submit" id="send_data">SEND <i class="fa fa-sign-in fa-lg"></i></button>
                    </div>
                    <div class="form-group mt-20">
                        <p class="semibold-text mb-0"><a href=""><i class="fa fa-angle-left fa-fw color-w"></i><span class="color-w">Back to Login</span></a></p>
                    </div>
                    </form>
                </div>
        </section>
</body>
</html>

