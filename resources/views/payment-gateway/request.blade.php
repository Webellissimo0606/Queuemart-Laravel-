
<FORM method="post" name="ePayment" action="{{$requestUrl}}" >
            <INPUT type="hidden" name="MerchantCode" value="{{$merchantCode}}">
            <INPUT type="hidden" name="PaymentId" value="{{$paymentId}}">
            <INPUT type="hidden" name="RefNo" value="{{$refNo}}">
            <INPUT type="hidden" name="Amount" value="{{$amount}}">
            <INPUT type="hidden" name="Currency" value="{{$currency}}">
            <INPUT type="hidden" name="ProdDesc" value="{{$desc}}">
            <INPUT type="hidden" name="UserName" value="{{$name}}">
            <INPUT type="hidden" name="UserEmail" value="{{$email}}">
            <INPUT type="hidden" name="UserContact" value="{{$contact}}">
            <INPUT type="hidden" name="SignatureType" value="{{$signatureType}}">
            <INPUT type="hidden" name="Signature" value="{{$signature}}">
            <INPUT type="hidden" name="ResponseURL"
            value="{{$responseUrl}}">
            <INPUT type="hidden" name="BackendURL" value="{{$backendUrl}}">
            <INPUT type="submit" value="Proceed" name="Submit" hidden id="payment_submit">
</FORM>


<div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
  <h2 style="padding: 10px 0 10px 0;">Choose Payment</h2>
</div>
<div class="service-popup__content">
  <div class="book__service-item">
      <label for="method1">
          <p class="paragraph--big u-weight-smb" style="margin: auto; color: #45af03!important; font-size: 30px!important;" >{{$currency}} {{$amount}}</p>
      </label>
  </div>
  <div class="book__service-item">
      <input type="radio" id="method1" name="service-type">
      <label for="method1">
          <div class="book__service-item-icon"></div>
          <div class="book__service-item-copy">
              <a onclick="submit();">
                  <div>
                      <p class="paragraph--big u-weight-smb">Online Payment</p>
                      <p class="paragraph--med u-color-black u-opacity-6">Credit Card or Online Back Transfer via IPay88</p>
                  </div>
              </a>
          </div>
      </label>
  </div> 
  <div class="book__service-item">
      <input type="radio" id="method2" name="service-type">
      <label for="method2">
          <div class="book__service-item-icon"></div>
          <div class="book__service-item-copy">
              <div>
                  <p class="paragraph--big u-weight-smb">Manual Payment</p>
                  <p class="paragraph--med u-color-black u-opacity-6">Manual Bank Transfer or cash payment</p>
              </div>
          </div>
      </label>
  </div>                                                               
</div>

<script type="text/javascript">
      function submit(){
            $('#payment_submit').trigger('click');
      }
</script>