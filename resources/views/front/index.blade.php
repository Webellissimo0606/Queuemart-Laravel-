<!DOCTYPE html>
<html lang="en" id="homepage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="20x20" href="{{asset('images/favicon.png')}}">
    <title>Queuemart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="{{asset('css/main.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

    <div class="popup" data-popup-target="about_us_modal">
        <div class="popup__inner" data-popup-trigger="about_us_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12">
                        <div class="service-popup">                            

                            <div class="branch-popup__content u-margin-bottom-med">
                                <div class="row">
                                    <div class="col-12 u-margin-bottom-med">
                                        <h3 class="u-margin-bottom-med text-center" style="margin-top: 15px;">Thanks for using QueueMart. We are glad to be at your service and if you have any enquiries with the use of our app or booking, we are here to help.</h3>
                                        <h4 class="u-margin-bottom-med">1. Return Policy</h4>
                                        <p class="u-margin-bottom-med">You have 14 calendar days to inform us about the dissatisfaction of the booking or service but there will be no return of service. For the return of any purchased physical item, it will take 7 days to process. Please keep the receipt as the proof of booking or purchase. A service voucher may be given as a compensation on a case-by-case basis.</p>
                                        <h4 class="u-margin-bottom-med">2. Refund Policy</h4>
                                        <p class="u-margin-bottom-med">Once we receive your dissatisfaction request, we will inspect the account and notify you that we have received your request. In the case of no-show, the deposit will be forfeited. There will be no refund of deposit once the payment has been made. You can arrange to re-schedule with the merchant on a case-by-case basis. For full service payment, actual refund amount to be based on the extend of service being provided to you. We will immediately notify you on the status of your refund after inspecting the item. If your refund is approved, we will initiate a refund to via bank account transfer and a processing fee of up to 10% may be charged.</p>
                                        <h4 class="u-margin-bottom-med">3. Delivery Methods and Timing</h4>
                                        <p class="u-margin-bottom-med">Thanks for having you to arrive at our locations of service with your proof of booking or purchase, which will be accessible by your registered phone number and password (or Facebook or google sign-in). The service would only be applicable for the range of time being mentioned in the booking detail. Please arrive 5 mins before the actual time to avoid disappointment. If you are late for more than 10 mins, we have the right to cancel or reschedule your appointment. Thanks!</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="location">
                                            <h3 class="about_us_red">ABOUT US</h3>
                                            <hr class="about_us_hr">
                                            <h4 class="u-margin-bottom-med about_us_h4">QueueMart Sdn. Bhd. (1260221 X)</h4>
                                            <p class="u-margin-bottom-med about_us_p"><i class="material-icons about_us_p">local_phone</i> +60 11-3984 5828</p>
                                            <p class="u-margin-bottom-med about_us_p"><i class="material-icons about_us_p">email</i> app@fbbooknow.com</p>
                                            <p class="u-margin-bottom-med about_us_p"><i class="material-icons about_us_p">location_on</i> H-13-3A, The Potpourri, No 2, Jalan PJU 1a/4, Ara Damansara, 47301 Petaling Jaya, Selangor.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<body>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <img src="{{asset('images/i-menu.svg')}}" alt="Menu" class="header__icon" data-popup-trigger="about_us_modal"">
                        <h2 class="u-color-black u-text-transform-none">Home</h2>
                        <img src="{{asset('images/i-search.svg')}}" alt="Search" class="header__icon" data-popup-trigger="about_us_modal">
                    </div>
                </div>
            </div>
        </div>
    </header>


    <main class="main-content">
        @foreach($companys as $value)
            <a href="{{ url('/')}}/{{$value->company_url}}">{{$value->company_name}}</a><br>
        @endforeach
    </main>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>
    
</body>
</html>