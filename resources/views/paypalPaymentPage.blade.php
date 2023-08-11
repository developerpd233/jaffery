@extends('layouts.app')
@section('page_title','Payment')

@section('css')

<style>

    .container {
      width: 500px;
      margin: 25px auto;
    }

    .title {
      margin-bottom: 1rem;
    }

    form {
      padding: 50px;
      background: #1b2c57;
      color: #fff;
      border-radius: 4px;
    }
    form label,
    form input,
    form button {
      border: 0;
      margin-bottom: 3px;
      display: block;
      width: 100%;
    }
    form input {
      height: 25px;
      line-height: 25px;
      background: #fff;
      color: #000;
      padding: 20px;
      box-sizing: border-box;
      border-radius: 2px;
    }

    form button {
      height: 30px;
      line-height: 30px;
      background: #3498db;
      color: #fff;
      text-transform: uppercase;
      font-weight: bold;
      margin-top: 1.5rem;
      cursor: pointer;
      border-radius: 2px;
      padding-top: 10px;
    	padding-bottom: 35px;
    }
    form .error {
      color: #ff0000;
    }

    .article-reference {
      margin-top: 15px;
    }
    .article-reference a {
      color: #e67e22;
    }
</style>

@endsection

@section('content')

<!-- <section class="paypal-payment" style="text-align: center;padding: 50px 0;clear: both;">
	<div id="paypal-button-container"></div>
</section> -->

<section class="paypal-payment" style="padding: 50px 0;clear: both;">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-gray-500 sm:text-left">
                <div class="flex items-center">

                    <div class="container">
                      <form action="#" name="payment">
                        <h1 class="title" style="text-align: center;">Authorize.net</h1>

                        <div style="text-align:left;">
	                        <br><br>
	                        <label for="amount">Amount</label>
	                        <input type="number" name="amount"  autocomplete="on" id="amount" value="" readonly>
	                        <br>
	                        <label for="cardNumber">Card Number</label>
	                        <input type="number" name="cardNumber"  autocomplete="on" id="cardNumber" placeholder="5424000000000015">
	                        <br>
	                        <label for="expirationDate">Expiration Date</label>
	                        <input type="text" name="expirationDate"  autocomplete="on" id="expirationDate" placeholder="12/2025">
	                        <br>
	                        <label for="cardCode">CVV</label>
	                        <input type="number" name="cardCode"  autocomplete="on" id="cardCode" placeholder="999">
	                        <br>

	                        <h3 class="title" style="text-align: center;">Billing Info</h3>
	                        <br>

	                        <label for="firstName">First Name (Ellen)</label>
	                        <input type="text" name="firstName"  autocomplete="on" id="firstName" placeholder="Ellen">
	                        <br>
	                        <label for="lastName">Last Name (Johnson)</label>
	                        <input type="text" name="lastName"  autocomplete="on" id="lastName" placeholder="Johnson">
	                        <br>
	                        <label for="company">Company (Souveniropolis)</label>
	                        <input type="text" name="company"  autocomplete="on" id="company" placeholder="Souveniropolis">
	                        <br>
	                        <label for="address">Address (14 Main Street)</label>
	                        <input type="text" name="address"  autocomplete="on" id="address" placeholder="14 Main Street">
	                        <br>
	                        <label for="city">City (Pecan Springs)</label>
	                        <input type="text" name="city"  autocomplete="on" id="city" placeholder="Pecan Springs">
	                        <br>
	                        <label for="state">State (TX)</label>
	                        <input type="text" name="state"  autocomplete="on" id="state" placeholder="TX">
	                        <br>
	                        <label for="zip">ZIP Code (44628)</label>
	                        <input type="number" name="zip"  autocomplete="on" id="zip" placeholder="44628">
	                        <br>
	                        <label for="country">Country (US)</label>
	                        <input type="text" name="country"  autocomplete="on" id="country" placeholder="US">

                        </div>
                        <br>
                        <div class="g-recaptcha" data-sitekey="6LfLsZQmAAAAAFY-dvtdYnh-1Rc0-RFEtniE4hFe" data-callback="enableBtn"></div>
                        <button type="submit" id="submit-btn"  disabled="disabled">Submit</button>
                      </form>
                      <!-- <input type="button" name="btn_auth" id="btn_auth" value="Authorize"> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
    async defer>
</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

		<script>
			console.clear();
    	var pro = <?php echo json_encode($product); ?>;
		  var req_info = <?php echo json_encode($req_info); ?>;

		  var price = pro.items[0].price;
		  var email = pro.items[0].email;
		  var nameU = pro.items[0].name;

		  //price = price.indexOf(".") > -1 ? price : price+".00";

		  $("#amount").val(price);
		  console.log(pro,req_info);
    </script>

    <script>

    		// $("#submit-btn").click(function(){
      //       alert('dsfs');
      //       authorizeDotNet();
      //   });
        function enableBtn(){
            // document.getElementById("submit-btn").disabled = false;
            $("#submit-btn").attr('disabled', false);
        }
        function authorizeDotNet() {

            $("#submit-btn").attr('disabled', true);

            var expireDate = $("#expirationDate").val().split('/');
            expireDate = expireDate[1]+'-'+expireDate[0];

            var amount = $("#amount").val() + ".00"; //"5.00";
            var cardNumber = $("#cardNumber").val(); //"370000000000002";
            var expirationDate = expireDate; //"2022-12";
            var cardCode = $("#cardCode").val(); //"999";

            var firstName = $("#firstName").val();
            var lastName = $("#lastName").val();
            var company = $("#company").val();
            var address = $("#address").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var zip = $("#zip").val();
            var country = $("#country").val();

            var refId = makeid(16);

            var myData = `{
                            "createTransactionRequest": {
                                "merchantAuthentication": {
                                    "name": "5p79CKt4dr2",
                                    "transactionKey": "57Gs6N6462y5HaG3"
                                },
                                "refId": "${refId}",
                                "transactionRequest": {
                                    "transactionType": "authCaptureTransaction",
                                    "amount": "${amount}",
                                    "payment": {
                                        "creditCard": {
                                            "cardNumber": "${cardNumber}",
                                            "expirationDate": "${expirationDate}",
                                            "cardCode": "${cardCode}"
                                        }
                                    },
                                    "poNumber": "456654",
                                    "customer": {
                                        "id": "99999456654"
                                    },
                                    "billTo": {
                                        "firstName": "${firstName}",
                                        "lastName": "${lastName}",
                                        "company": "${company}",
                                        "address": "${address}",
                                        "city": "${city}",
                                        "state": "${state}",
                                        "zip": "${zip}",
                                        "country": "${country}"
                                    },
                                    "customerIP": "192.168.1.1",
                                    "userFields": {
                                        "userField": [
                                            {
                                                "name": "MerchantDefinedFieldName1",
                                                "value": "MerchantDefinedFieldValue1"
                                            },
                                            {
                                                "name": "favorite_color",
                                                "value": "blue"
                                            }
                                        ]
                                    },
                                "processingOptions": {
                                     "isSubsequentAuth": "true"
                                    },
                                 "subsequentAuthInformation": {
                                     "originalNetworkTransId": "123456789NNNH",
                                     "originalAuthAmount": "${amount}",
                                     "reason": "resubmission"
                                    },
                                    "authorizationIndicatorType": {
                                    "authorizationIndicator": "pre"
                                  }

                               }
                            }
                        }`;

            //test start

            // $.ajax({
            //   url: base_url+'/authorizeDotNet-success',
            //   type: "GET",
            //   data: {
            //     order_id: res.transactionResponse.transId,
            //     method: 'authorize.net - '+res.transactionResponse.accountType,
            //     message: res.messages.message[0].text
            //   },
            //   success: function(response) {
            //     if(response.code == 200) {
            //       //alert(response.message);
            //       window.location.href = response.url;
            //     }else{
            //         alert(response.message);
            //      $("#submit-btn").attr('disabled', false);
            //     }
            //   },
            //   error: function(response) {
            //     console.log(response);
            //     $("#submit-btn").attr('disabled', false);
            //   }
            // });
            // return;

            //test end

            $.ajax({
                type: 'POST',
                contentType: 'application/json',
                url: 'https://api.authorize.net/xml/v1/request.api',
                data: myData,
                dataType: 'json',
                success: function (res) {

                    console.log('Response: ',res,res.messages.resultCode,res.messages.message[0].text);
                    //return;
                    if (res != '') {
                        if (res.messages.resultCode != "Error") {
                            //console.log('Success: Successful-',res.transactionResponse.transId);

                            if (typeof(res.transactionResponse.errors) != "undefined" && res.transactionResponse.errors !== null)
                            {
                            	alert(res.transactionResponse.errors[0].errorText+' Kindly check your bill details are correct!');
                            	$("#submit-btn").attr('disabled', false);
                            	return;
                            }
                            else
                            {
                          		$.ajax({
													      url: base_url+'/authorizeDotNet-success',
													      type: "GET",
													      data: {
													        order_id: res.transactionResponse.transId,
													        method: 'authorize.net - '+res.transactionResponse.accountType,
													        message: res.messages.message[0].text
													      },
													      success: function(response) {
													        if(response.code == 200) {
													          //alert(response.message);
													          window.location.href = response.url;
													        }else{
													        	alert(response.message);
													         $("#submit-btn").attr('disabled', false);
													        }
													      },
													      error: function(response) {
													        console.log(response);
													        $("#submit-btn").attr('disabled', false);
													      }
													    });
                            }

                        }
                        else {
                            //console.log('Error: Handshake Unsuccessful');
                            alert(res.messages.message[0].text);
                            $("#submit-btn").attr('disabled', false);
                        }
                    }
                    else {
                        console.log('Error: Handshake Unsuccessful 1');
                        $("#submit-btn").attr('disabled', false);
                    }
                },
                error: function (error) {
                    console.log('Error: An error occurred',error);
                    $("#submit-btn").attr('disabled', false);
                },
            });

        }

          $("form[name='payment']").validate({
            rules: {
              cardNumber: {
                required: true,
                minlength: 13,
                maxlength: 16,
              },
              cardCode: {
                required: true,
                minlength: 3,
                maxlength: 3,
              },
              expirationDate: {
                required: true,
                minlength: 7,
                maxlength: 7,
                dateFormat: true,
                future: true
              },
              firstName: {
                required: true,
                minlength: 3,
                maxlength: 50,
              },
              lastName: {
                required: true,
                minlength: 3,
                maxlength: 50,
              },
              company: {
                required: true,
                minlength: 3,
                maxlength: 100,
              },
              address: {
                required: true,
                minlength: 3,
                maxlength: 255,
              },
              city: {
                required: true,
                minlength: 3,
                maxlength: 100,
              },
              state: {
                required: true,
                minlength: 1,
                maxlength: 5,
              },
              zip: {
                required: true,
                minlength: 4,
                maxlength: 7,
              },
              country: {
                required: true,
                minlength: 1,
                maxlength: 100,
              }
            },
            messages: {
              cardNumber: {
                required: "Please provide a card number",
                minlength: "Your card number must be at least 13 characters long",
                maxlength: "Your card number must be at most 16 characters long"
              },
              cardCode: {
                required: "Please provide a CVV",
                minlength: "Your CVV must be at least 3 characters long",
                maxlength: "Your CVV must be at most 3 characters long"
              },
              expirationDate: {
                required: "Please provide a expiration date",
                minlength: "Your expiration date must be at least 7 characters long",
                maxlength: "Your expiration date must be at most 7 characters long"
              },
              firstName: {
                required: "Please provide a firstName",
                minlength: "Your firstName must be at least 3 characters long",
                maxlength: "Your firstName must be at most 50 characters long"
              },
              lastName: {
                required: "Please provide a lastName",
                minlength: "Your lastName must be at least 3 characters long",
                maxlength: "Your lastName must be at most 50 characters long"
              },
              company: {
                required: "Please provide a company",
                minlength: "Your company must be at least 3 characters long",
                maxlength: "Your company must be at most 100 characters long"
              },
              address: {
                required: "Please provide a address",
                minlength: "Your address must be at least 3 characters long",
                maxlength: "Your address must be at most 255 characters long"
              },
              city: {
                required: "Please provide a city",
                minlength: "Your city must be at least 3 characters long",
                maxlength: "Your city must be at most 100 characters long"
              },
              state: {
                required: "Please provide a state",
                minlength: "Your state must be at least 1 characters long",
                maxlength: "Your state must be at most 5 characters long"
              },
              zip: {
                required: "Please provide a zip",
                minlength: "Your zip must be at least 4 characters long",
                maxlength: "Your zip must be at most 7 characters long"
              },
              country: {
                required: "Please provide a country",
                minlength: "Your country must be at least 1 characters long",
                maxlength: "Your country must be at most 100 characters long"
              }
            },
            submitHandler: function(form) {
            		// alert('dfsf');
                authorizeDotNet();
            }
          });

        $.validator.addMethod("future", function(value, element) {

            var temp = value.split('/');
            temp = temp[0]+'/30/'+temp[1];
            var curDate = new Date();
            var inputDate = new Date(temp);

            if (inputDate > curDate)
                return true;
            return false;

        }, "Please add future date!");

        $.validator.addMethod("dateFormat", function(value, element) {

            var pattern = new RegExp(/\b\d{1,2}[\/]\d{4}\b/);
            return pattern.test(value);

        }, "Please add correct format! Date format is 12/2025");

        function makeid(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

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

        <!-- <script src="https://www.paypal.com/sdk/js?client-id=AQd2anA19ClRYdl-COHOUQPFKulhqehpHND5AO5SLjeKnSD2aw7PkY_2-_xcjT-GkOAz9Sxlqm8Zu1VJ&currency=USD&intent=capture&enable-funding=venmo"></script>

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
        </script> -->

@endsection
