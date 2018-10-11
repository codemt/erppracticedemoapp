<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/multi-select.css')}}">
    @foreach($user_permission as $keys => $single_user_perms)
        <div class="col-md-4 p-10">
            <div class="card">
                <h5 class="subtitle" style="min-height: 30px;font-weight: bold;">
                    <?=  ucwords(str_replace('_',' > ', $keys)) ?>
                </h5>
                <hr>
                <div class="animated-checkbox">
                    <label>
                        <input type="checkbox" class ="sub_selection" value="<?= $keys ?>"><span class="label-text">Select All</span>
                    </label>
                </div>
                <?= Form::select('permission[]',$single_user_perms,old('permission',$designation_current_permissions), array('multiple'=>true,'class' => 'multi-select user_permission_multi '.$keys)) ?>
            </div>
        </div>
    @endforeach
    <script type="text/javascript" src="{{ asset('backend/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript">
    
  $('.user_permission_multi').multiSelect();

        $('form').submit(function(){
            $('.overlay').show();
        });

        $("#selectall").click(function(){
            var is_checked = $(this).is(':checked');
            $(".sub_selection").prop('checked',is_checked);
            if (is_checked == true) {
                $('.user_permission_multi').multiSelect('select_all');
            }else{
                $('.user_permission_multi').multiSelect('deselect_all');
            }
        });

        $('.sub_selection').click(function(){
            var is_checked = $(this).is(':checked');
            var sub_selection_value = $(this).val();

            if (is_checked == true) {
                $('.'+sub_selection_value).multiSelect('select_all');
            }else{
                $('.'+sub_selection_value).multiSelect('deselect_all');
            }
        })

        $("input[type='checkbox'][class*='select_']").click(function(){
            var getClass = $(this).attr('class');
            var getSection = getClass.split(' ');
            var getSection = getSection[1].slice(7);
            var is_checked = $(this).is(':checked');
            $("."+getSection).prop('checked',is_checked);
        });

</script>