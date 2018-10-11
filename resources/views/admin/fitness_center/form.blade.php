<div class="row">
        <div class="card col-md-12 mb-30">
            <div class="bg-white p-5 row m-0 box-shadow">
                <div class="card-title-w-btn">
                    <h5 class="title">Fitness Center Details</h5>
                </div><hr>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-2">Mobile</label>
                        <div class="col-md-10">
                            <?= Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Enter Mobile','id'=>'mobile']); ?>
                            <span id="mobile_error" class="help-inline text-danger"><?= $errors->first('mobile') ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Email</label>
                        <div class="col-md-10">
                            <?= Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email']); ?>
                            <span id="email_error" class="help-inline text-danger"><?= $errors->first('email') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Owner Name</label>
                        <div class="col-md-9">
                            <?= Form::text('owner_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Owner Name']); ?>
                            <span id="owner_name_error" class="help-inline text-danger"><?= $errors->first('owner_name') ?></span>
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Is Active</label>
                        <div class="col-md-9">
                            <div class="animated-radio-button pull-left mr-10">
                                <label for="is_active_true">
                                    <?=Form::radio('is_active', 1, true, ['id' => 'is_active_true'])?>
                                    <span class="label-text"></span> Active
                                </label>
                            </div>
                            <div class="animated-radio-button pull-left">
                                <label for="is_active_false">
                                    <?=Form::radio('is_active', 0, null, ['id' => 'is_active_false'])?>
                                    <span class="label-text"></span> Deactive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>