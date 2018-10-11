<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
        <div class="row">
             <div class="col-md-12">
                    <div class="card-title-w-btn">
                        <h4 class="title">Dynamic form detail</h4>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group">
                            <table class="table m-0 v-top">
                                <thead>
                                    <tr>
                                        <th style="border:none">City<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Mobile No<sup class="text-danger">*</sup></th>
                                        <th style="border:none">Pincode<sup class="text-danger">*</sup></th>
                                        <th style="border:none"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="student">
                                        <td class="col-md-3" style="border:none">
                                            <?= Form::text('city',old('city'), ['class' => 'form-control','placeholder'=>'City','id'=>'city']); ?>
                                            <span id="city_error" class="help-inline text-danger"><?= $errors->first('student.student.*.city') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none">
                                            <?= Form::text('mobile',old('mobile'), ['class' => 'form-control','placeholder'=>'Mobile No','id'=>'mobile']); ?>
                                            <span id="mobile_error" class="help-inline text-danger"><?= $errors->first('student.student.*.mobile') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none">
                                            <?= Form::text('pincode',old('pincode'), ['class' => 'form-control','placeholder'=>'Pincode','id'=>'pincode']); ?>
                                            <span id="pincode_error" class="help-inline text-danger"><?= $errors->first('student.student.*.pincode') ?></span>
                                        </td>
                                        <td class="col-md-3" style="border:none;">        
                                            
                                            <a href="javascript:void(0)" id="student_remove" class="pt-10 pull-left btn-remove" style="font-size: 18px"><i class="fa fa-minus-circle fa-small pull-left"></i>&nbsp;&nbsp;</a>
                                            <a href="javascript:void(0)" id="student_add" class="pt-10 pull-left btn-add" style="font-size: 18px"><i class="fa fa-plus-circle fa-small pull-left" ></i>Add</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>      
        </div>
    </div>
    @include('admin.layout.overlay')
    </div>
</div>