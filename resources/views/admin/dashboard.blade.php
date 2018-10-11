@extends('admin.layout.layout')
@section('style')
	<?= Html::style('backend/css/dataranger.css',[],IS_SECURE) ?>
@stop
@section('top_fixed_content')
<nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
    <div class="title">
        <h4><i class="fa fa-dashboard"></i> Dashboard</h4>
    </div>
<!--     <div style="width: 21%;min-width: 200px;">
        <form id="frm_filter" name ="frm_filter">
            <div class="form-group" style="margin-bottom: 0">
                <div class="input-group date_range">
                    <div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>
                    <input type="text" name="yoy[]" class="form-control pull-right" id="date_range_1">
                </div>
            </div>
        </form>
    </div> -->
</nav>
@stop
@section('script')
	<?= Html::script('backend/js/moment.min.js',[],IS_SECURE) ?>
	<?= Html::script('backend/js/dateranger.js',[],IS_SECURE) ?>
<script type="text/javascript">
$(function(){

			$('#date_range_1').daterangepicker({
	         	ranges: {
	            'Today': [moment(), moment()],
	            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	            'This Month': [moment().startOf('month'), moment().endOf('month')],
	            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
	            'This Year': [moment().startOf('year').startOf('days'),moment()],
	            'Last Year': [moment().subtract('month',12).startOf('days'), moment().subtract(moment(),12).endOf('days')]
	          },
	          opens : "right",
	          startDate: moment(),
	          endDate: moment()
	        });

			// graph_generator($('#date_range_1').val());

			$('#date_range_1').on('apply.daterangepicker', function(ev, picker) {
		        graph_generator($('#date_range_1').val());
		    });
	    });
</script>
@include('admin.layout.alert')
@stop