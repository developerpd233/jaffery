@extends('layouts.app')
@section('page_title','Payment')

@section('content')

<section class="paypal-payment" style="text-align: center;padding: 50px 0;clear: both;">
	
	<div id="paypal-button-container"></div>

</section>

@endsection

@section('script')

				<!-- <script>
	
				$(document).ready(function() {
				  	
				  	var pro = <?php echo json_encode($product); ?>;
				  	var req_info = <?php echo json_encode($req_info); ?>;

				  	console.log(pro,req_info);
					
				});

			</script> -->

				<!-- PayPal account: saddlerj62@gmail.com -->
				<!-- secret_key=ELLDNHHW0NIT-eMNTL3sN-3kgraUJeozzbmQ0Yr3EaxO9d6KIXgXWtj4vrdzXO2jwAjhFfmitbUZM6w7 -->
        <!-- <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD&intent=capture"></script> -->

        <script src="https://www.paypal.com/sdk/js?client-id=AQd2anA19ClRYdl-COHOUQPFKulhqehpHND5AO5SLjeKnSD2aw7PkY_2-_xcjT-GkOAz9Sxlqm8Zu1VJ&currency=USD&intent=capture&enable-funding=venmo"></script>

        <script>
        	var pro = <?php echo json_encode($product); ?>;
				  var req_info = <?php echo json_encode($req_info); ?>;

          const paypalButtonsComponent = paypal.Buttons({
              // optional styling for buttons
              // https://developer.paypal.com/docs/checkout/standard/customize/buttons-style-guide/
              style: {
                color: "gold",
                shape: "rect",
                layout: "vertical"
              },

              // set up the transaction
              createOrder: (data, actions) => {
                  // pass in any options from the v2 orders create call:
                  // https://developer.paypal.com/api/orders/v2/#orders-create-request-body
                  const createOrderPayload = {
                      purchase_units: [
                          {
                              amount: {
                                  value: pro.items[0].price,
                                  currency_code: "USD",
                              },
                              description: pro.items[0].desc,
                              name: pro.items[0].name,
                          }
                      ]
                  };

                  return actions.order.create(createOrderPayload);
              },

              // finalize the transaction
              onApprove: (data, actions) => {
                  const captureOrderHandler = (details) => {
                      const payerName = details.payer.name.given_name;
                      
                      console.log(details);
                      console.log(details.ammar);

                      if (details.status == 'COMPLETED') 
                      {

										    $.ajax({
										      url: base_url+'/paypal-success',
										      type: "GET",
										      data: {
										        order_id: details.id,
										      },
										      success: function(response) {
										        if(response.code == 200) {
										          //alert(response.message);
										          window.location.href = response.url;
										        }else{
										        	alert(response.message);
										        }
										      },
										      error: function(response) {
										        console.log(response);
										      }
										    });

                      	console.log('Transaction completed');
                      } 
                      else {
                      	alert('Transaction not completed. Please try again later!');
                      }
                  };

                  return actions.order.capture().then(captureOrderHandler);
              },

              // handle unrecoverable errors
              onError: (err) => {
                  console.error('An error prevented the buyer from checking out with PayPal');
              }
          });

          paypalButtonsComponent
              .render("#paypal-button-container")
              .catch((err) => {
                  console.error('PayPal Buttons failed to render');
              });
        </script>
      

<script>
	


	$(document).ready(function() {
	  	
	  	var amount = $('#amount').val();
			
			$('ul h3').empty().text('YOU VOTED AMOUNT $' + amount);

	  	$('#amount').on('change',function(){
	  		var amount = $('#amount').val();
				$('ul h3').empty().text('YOU VOTED AMOUNT $' + amount);
	  	});

	  	$('#loginBtn').on('click',function(){
				window.location.href = '{{ url("login") }}';
	  	});

	  	$(document).on('click','.comment .rit i',function(){
		    $(this).next().toggle();
			});
		
	});

</script>

@endsection
