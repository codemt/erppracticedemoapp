@extends('admin.layout.layout')
@section('style')
    <?=Html::style('backend/css/jquery.fileuploader.css')?>
    <?=Html::style('backend/css/jquery.fileuploader-theme-thumbnails.css')?>
    <?=Html::style('backend/css/bootstrap-fileupload.css')?>    
@stop
@section('start_form')
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
    <?=Form::model(['method' => 'POST', 'route'=>['manage_stock.release'], 'class' => 'm-0 form-horizontal','files'=>true])?>
@stop
@section('top_fixed_content')
<meta name="_token" content="{{csrf_token()}}" />
<nav class="navbar navbar-static-top">
    <div class="title">
         <h4></h4>
    </div>
    <div class="pl-10">
        <a type="submit" name="save_button" id="save_stock" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save & exit </a>
        <a href="<?= route('managestock.index')?>" class="btn btn-default btn-sm" title="Back to users Page">Cancel</a>
    </div>
</nav>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card">
            <div class="card-title-w-btn">
                    <h4 class="title">Add to Blocked Qty  </h4>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{ $id }}" id="hidden_id">
                    <div class="row">
                        {{-- <div class="col-md-4">
                            <div class="form-group @if($errors->has('company_id')) has-error @endif">
                                <div class="col-md-12"> Product ID <sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="number" class="form-control" name="product_id" value="{{ $product_id }}" id="product_id" disabled="disabled">
                                    <span id="select_2_error" class="help-inline text-danger"></span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('supplier_id')) has-error @endif">
                                <div class="col-md-12">Name and Description <sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="text" name="name_description" class="form-control" value="{{ $name_description }}"  id="name_description" disabled="disabled">
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('supplier_id')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('product_type')) has-error @endif">
                                <div class="col-md-12">Model No.<sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="text" name="model_no" class="form-control"  value="{{ $model_no }}" id="model_no" disabled="disabled">
                                    <span id="select_2_error" class="help-inline text-danger"><?=$errors->first('product_type')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('combo_product')) has-error @endif">
                                <div class="col-md-12">Total Qty <sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="number" name="total_qty" class="form-control" value="{{ $total_qty }}"  id="total_qty" disabled="disabled">
                                    <strong><small class="form-text text-muted">Please Specify Reason for Blocking Product Quantity.
                                    </small></strong><br><br>
                                    <span id="mul_select_error" class="help-inline text-danger"><?=$errors->first('combo_product')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('model_no')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Total Physical Qty <sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="text" name="total_physical" class="form-control" value="{{ $total_physical_qty }}" id="total_physical" disabled="disabled">
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('model_no')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if($errors->has('name_description')) {{ 'has-error' }} @endif">
                                <div class="col-md-12">Total Block Qty <sup class="text-danger">*</sup></div>
                                <div class="col-md-12">
                                <input type="text" class="form-control" value="{{ $total_blocked_qty }}"  name="block_qty" id="block_qty">
                                <br><br>
                                    <span id="text_box_error" class="help-inline text-danger"><?=$errors->first('name_description')?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('remark')) has-error @endif">
                            <div class="col-md-12"> Reason </div>
                            <div class="col-md-12">
                                <textarea class="form-control remark" name="reason" id="reason" cols="30" rows="5"></textarea>
                                <span id="" class="help-inline text-danger"><?=$errors->first('reason')?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
                
            </div>
            @include('admin.layout.overlay')
        </div>
    </div>
</div>
<div class="text-right">
    {{-- <button type="submit" name="save_new" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save and add new user">Save</button> --}}
    <a type="submit" name="save_button" id="save_bottom" value="save_exit" class="btn btn-primary btn-sm disabled-btn" title="Save and exit">Save and exit </a>
    {{-- <button type="submit" name="save_button" value="save_new" class="btn btn-primary btn-sm disabled-btn" title="Save & exit">Save & exit</button> --}}
    <a href="<?= route('managestock.index')?>" class="btn btn-default btn-sm" title="Back to user Page">Cancel</a>
</div>
@stop

@section('end_form')
<?=Form::close()?>
@stop
@section('script')
<?= Html::script('backend/js/select2.min.js',[],IS_SECURE) ?>
<?= Html::script('backend/js/bootstrap-fileupload.js',[],IS_SECURE) ?>

    <script type="text/javascript">

                $(document).ready(function () {
    
                 

             $('#save_stock,#save_bottom').click(function(e){

                        e.preventDefault();
                        alert("Saving..");   
                        var id = $('#hidden_id').val();
                        var product_id = $('#product_id').val();
                        var name_description = $('#name_description').val();
                        var model_no = $('#model_no').val();
                        var physical_qty = $('#total_physical').val();
                        var block_qty  = $('#block_qty').val();
                        var reason = tinyMCE.activeEditor.getContent({format : 'raw'});
                        console.log("Reason is " +reason);
                        var reason_final = $("<div>").html(reason).text();
                        console.log('final is ' +reason_final);
                        console.log(id,product_id,name_description,model_no,physical_qty,block_qty);
    
     
                    
                    $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                    });

                            $.ajax({

                            url: "{{ route('manage_stock.block') }}",
                            type: 'post',
                            datatype: 'JSON',
                            data: {
                                    'id': id,
                                    'product_id':product_id,
                                    'name_description':name_description,
                                    'model_no':model_no,
                                    'total_physical_qty':physical_qty,
                                    'total_block_qty': block_qty,
                                    'blocked_reason':reason_final

                            },
                            success: function(result){

                                    console.log(result);
                                    var final = JSON.stringify(result);
                                    console.log(final);
                                    if(result == 'Not Allowed'){

                                          //   toastr.error('Not Allowed');
                                         window.location.href = '{{ route("access.denied") }}'; 


                                    }
                                    else
                                    {

                                            toastr.success('Qty Blocked');
                                            window.location.href = '{{ route("managestock.index") }}'; 

                                    }
                                   
                    
                            },
                            error: function (data) {
                            console.log('Error:', data);
                            toastr.error('Reason field is required');    
                           // window.location.href = '{{ url("/admin/purchase-requisition-approval/index") }}';
                            
                            }

                    });

                    


        


                });

                



            });

       
    </script>
    <script type="text/javascript">
        
    </script>
<?=Html::script('backend/js/form.demo.min.js', [], IS_SECURE)?> 
@include('admin.layout.alert')
@stop