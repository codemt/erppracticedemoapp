@if(Session::has('message'))
	@if(Session::get('message_type'))
		<script>
	        $(document).ready(function(){
		        toastr.{{ Session::get('message_type') }}
		        ('{{ Session::get('message') }}');
	        });
	    </script>
    @endif
@endif
