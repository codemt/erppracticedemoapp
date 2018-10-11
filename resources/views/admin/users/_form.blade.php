<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                <h4 class="title">User Details</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('firstname')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">First Name<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                            <?=Form::text('firstname', null, ['class' => 'form-control', 'placeholder' => 'First Name']);?>
                            <span id="firstname_error" class="help-inline text-danger"><?=$errors->first('firstname')?></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('lastname')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Last Name<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                            <?=Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => 'Last Name']);?>
                            <span id="firstname_error" class="help-inline text-danger"><?=$errors->first('lastname')?></span>
                        </div>
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('email')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Email<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                            <?=Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']);?>
                            <span id="email_error" class="help-inline text-danger"><?=$errors->first('email')?></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Address<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <?=Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']);?>
                                <span id="address_error" class="help-inline text-danger"><?=$errors->first('address')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if($errors->has('address')) {{ 'has-error' }} @endif">
                            <div class="control-label col-md-4">Status<sup class="text-danger">*</sup></div>
                            <div class="col-md-8">
                                <div class="animated-radio-button pull-left mr-10">
                                    <label for="is_active_true">
                                        <?=Form::radio('is_active', 1, true, ['id' => 'is_active_true'])?>
                                        <span class="label-text"></span> Active
                                    </label>
                                </div>
                                <div class="animated-radio-button pull-left">
                                    <label for="is_active_false">
                                        <?=Form::radio('is_active', 0, false, ['id' => 'is_active_false'])?>
                                        <span class="label-text"></span> Deactive
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>