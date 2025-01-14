<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Braintree-Demo</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
 
  <div class="container">
     <div class="row">
       <div class="col-md-8 col-md-offset-2">
         <div id="dropin-container"></div>
         <button id="submit-button">Request payment method</button>
       </div>
     </div>
  </div>
  <script>
    var button = document.querySelector('#submit-button');

    braintree.dropin.create({
      authorization: "{{ Braintree\ClientToken::generate() }}",
      container: '#dropin-container'
    }, function (createErr, instance) {
      let carrellino = JSON.parse(localStorage.getItem('carrello'));
            let tot = 0;
            if(localStorage.carrello){
              carrellino.forEach(item=>{
                let subtotal = item.qty * item.price;
                tot += subtotal;
            } )
            }
            
      button.addEventListener('click', function () {
        instance.requestPaymentMethod(function (err, payload) {
          payload.tot = tot;
          $.get('{{ route('payment.process') }}', {payload}, function (response) {
              console.log(response);
            if (response.success) {
              alert('Payment successfull!');
            } else {
              alert('Payment failed');
            }
          }, 'json');
        });
      });
    });
  </script>
</body>
</html>