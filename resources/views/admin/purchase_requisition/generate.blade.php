@extends('admin.layout.layout')
@section('start_form')
    <?=Form::open(['method' => 'POST', 'route'=>'manage_stock.postore', 'class' => 'm-0 form-horizontal','files'=>true])?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <button type="submit" name="save_button" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </button>
        <a href="<?= route('purchase-requisition.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Purchase Requisition</h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Select Company<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('company_id', $company_name['company_name'],array('class' => 'form-control select_2 select company','placeholder'=>'Select Company','id'=>'company')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('company_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Select Manufacturer<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('supplier_id', $supplier_name['supplier_name'],array('class' => 'form-control select_2 select supplier','placeholder'=>'Select Manufacturer','id' => 'supplier')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('distributor_id')) has-error @endif">
                                <div class="col-md-12">Select Distributor</div>
                                <div class="col-md-12">
                                    <?= Form::text('distributor_id','',array('class' => 'form-control select_2 select distributor','placeholder'=>'Select Distributor','id' => 'distributor')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('distributor_id')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12">Delivery Terms<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                    <?= Form::text('delivery_terms',old('delivery_terms'),array('class' => 'form-control','placeholder'=>'Delivey Terms')) ?>
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('delivery_terms')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('currency_status')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Rupee/Dollar</div>
                                <div class="col-md-12">
                                    <div class="animated-radio-button pull-left mr-10">
                                        <label class="control-label" for="is_active_true">
                                            <input type="radio" name="currency_status" value="rupee" {{old('currency_status') == 'rupee' ? 'checked' : ''}} id="is_active_true" checked="checked" class="cur_status">
                                            <span class="label-text"></span> Rupee
                                        </label>
                                    </div>
                                    <div class="animated-radio-button pull-left">
                                        <label class="control-label" for="is_active_false">
                                            <input type="radio" name="currency_status" value="dollar" {{old('currency_status') == 'dollar' ? 'checked' : ''}} id="is_active_false" class="cur_status">
                                            <span class="label-text"></span> Dollar
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table m-0 v-top vertical-align">
                                <thead>
                                    <tr class="row">
                                        <th class="col-md-3" style="border:none">Model No</th>
                                        <th class="col-md-3" style="border:none">Product Name</sup></th>
                                        <th class="col-md-3" style="border:none">QTY<sup class="text-danger">*</sup></th>
                                        <th class="col-md-3" style="border:none"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product_array_data as $key=>$value)
                                        <tr id="shipping" class="row">
                                            <td valign="top" class="col-md-3" style="border:none;padding-left: 0;">
                                                <?= Form::text('model_no[]',$value['model_no'], ['class' => 'form-control select2 model_no','id'=>'model_no','style'=>'width:247px']); ?><br>
                                                <span id="model_no_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.model_no') ?></span>
                                            </td>
                                            <td valign="top"  class="col-md-3" style="border:none;">
                                                <?= Form::text('product_name[]',$value['name_description'],['class' => 'form-control product_name','id'=>'product_name','style'=>'width:247px','readonly'=>'readonly']); ?>
                                                <span id="product_name_error" class="help-inline text-danger"><?= $errors->first('shipping.shipping.product_name') ?></span>
                                            </td>
                                            <td valign="top" class="col-md-3" style="border:none;">
                                                @if($value['po_qty'] == '')
                                                    <?= Form::number('qty[]',1, ['class' => 'form-control','id'=>'qty','style'=>'width:247px','min'=>1]); ?>
                                                @else
                                                     <?= Form::number('qty[]',$value['po_qty'], ['class' => 'form-control','id'=>'qty','style'=>'width:247px','min'=>1]); ?>
                                                <span id="qty_error" class="help-inline text-danger"><?= $errors->first('qty') ?></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    @include('admin.layout.overlay')
            </div>
        </div>
    </div>
</div>
<div class="text-right">
    <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button>
    <a href="<?= route('purchase-requisition.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/jquery.form.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/dynamicform.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>

    <script type="text/javascript">
        $('.model_no').prop('readonly',true);
        $('.company').prop('readonly',true);
        $('.supplier').prop('readonly',true);
        $('.distributor').prop('readonly',true);
    </script>
@include('admin.layout.alert')
@stop