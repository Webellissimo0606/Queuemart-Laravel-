<!DOCTYPE html>
<html lang="en" id="homepage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Breon Hair Salon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="{{asset('css/main.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>

    <div class="popup popup--active" id="reminder_modal" data-popup-target="reminder_modal">
        <div class="popup__inner" data-popup-trigger="reminder_modal">
            <div class="container popup__content" style="padding-top: 30%;">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: gray; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h1 style="padding: 10px 0 10px 0;">REMINDERS</h1>
                            </div>
                            <div class="service-popup__content">
                                <div class="d-flex align-items-start justify-content-between u-margin-bottom-med">
                                    <h3 class="u-text-transform-none u-weight-smb u-margin-bottom-med" id="alert_title">Alert</h3>
                                </div>
                                <p class="u-color-black-alt u-margin-bottom-med u-letter-spacing-med" id="alert_text">Sorry, You already reserved this slot!</p>                                
                                <a href="{{url('/')}}" class="service-popup__btn btn btn--accent u-margin-bottom-med">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>
    
</body>
</html>