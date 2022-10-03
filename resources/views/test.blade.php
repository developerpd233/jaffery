@extends('layouts.app')

@section('content')

<button type="button" id="btn_google_pay">Google Pay</button>
<button type="button" id="btn_apple_pay">Apple Pay</button>

@endsection

@section('script')

<script>

  $(document).ready(function(){
		    
		$("body").on('click','#btn_google_pay',function(e){
	    //e.preventDefault();
	    
	    alert('dfdsf');

	    // var token   = $('meta[name="csrf-token"]').attr('content');
	    // var url = '{{ url("/comments/") }}'+ '/' + $(this).data('id');
	    // var box = $(this).parent().parent().parent().parent();

	    // $.ajax({
	    //   url: url,
	    //   type: 'Delete',
	    //   data: {
	    //     _token: token
	    //   },
	    //   success: function(response) {
	    //     if(response.code == 200) {
	    //     	console.log('delete');
	    //     	box.remove();
	    //     	$('.toast .toast-body').empty().html(response.message);    
		   //      $('.toast').toast('show');
	    //     }
	    //   },
	    //   error: function(response) {
	    //     console.log(response);
	    //   }
	    // });

		});

  });

</script>

@endsection
