<!DOCTYPE html>
<html lang="en" id="homepage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{$company->company_des}}">
    <link rel="icon" href="{{$company->company_image}}">
    <title>{{$company->company_name}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="{{asset('css/main.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>    

    @if($user_id != 0)
    <div class="popup <?php echo $reminder != 0 ? '' : 'popup--active'; ?>" data-popup-target="reminder_modal">
        <div class="popup__inner" data-popup-trigger="reminder_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h2 style="padding: 10px 0 10px 0;">WhatsApp Reminder</h2>
                            </div>
                            <div class="service-popup__content" style="max-height: none; padding: 20px; overflow: inherit;">
                                <div class="form__group d-flex align-items-bottom justify-content-around">
                                    <p>I would like to receive whatsapp reminder for my booking.</p>
                                </div>
                                <button onclick="reminderUser()" type="button" class="btn btn--accent u-color-white" style="margin-top: 1.25rem;">Accept Whatsapp Reminder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="popup" data-popup-target="lang_modal">
        <div class="popup__inner" data-popup-trigger="lang_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h2 style="padding: 10px 0 10px 0;">Feedback Form</h2>
                            </div>
                            <div class="service-popup__content" style="height: 80vh; max-height: none; padding: 0px; overflow: inherit;">
                                 <iframe src="{{$company->questionnaire_link}}" frameborder="0" class="phone_area-iframe" style="height: 100%; width: 100%;"></iframe>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" data-popup-target="share_modal">
        <div class="popup__inner" data-popup-trigger="share_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="service-popup__header" style="background-image: url('{{asset('images/share_img.png')}}')">&nbsp;</div>
                            <div class="service-popup__content">
                                <div class="d-flex align-items-start justify-content-between u-margin-bottom-med">
                                    <h3 class="u-text-transform-none u-weight-smb u-margin-bottom-med">Save time on booking and share the joy with your loved ones!</h3>
                                </div>
                                <p class="u-color-black-alt u-margin-bottom-med u-letter-spacing-med">Send Rm10 credit to them and get Rm10 when they complete the booking.</p>                                
                                <a href="#" class="service-popup__btn btn btn--accent u-margin-bottom-med">Copy Invitation Link</a>
                                <p class="u-color-black-alt u-letter-spacing-med">Terms & Conditions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    @foreach($services as $value)
    <div class="popup" data-popup-target="wash-cut-blow-{{$value->id}}" id="custom_h1">
        <div class="popup__inner" data-popup-trigger="wash-cut-blow-{{$value->id}}">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12">
                        <div class="service-popup">
                            <div class="service-popup__header" style="background-image: url('{{$value->service_image}}')">&nbsp;</div>
                            <div class="service-popup__content">
                                <div class="text-center u-margin-bottom-big">
                                    <!-- <div class="text-center"> -->
                                        <h1 class="sevice-title u-text-transform-none u-weight-smb u-margin-bottom-smallest">{{$value->service_name}}</h1>
                                        
                                </div>
                                <p class="u-color-black-alt u-margin-bottom-big u-letter-spacing-med">
                                    <?php echo $value->service_des; ?>
                                </p>
                                <?php
                                    if (isset($_GET['promocode']) && $_GET['promocode'] != '') {
                                        $promo_code = '/'.$company_url.'/book/'.$branch->id.'/'.$value->id.'?promocode='.$_GET['promocode'];
                                    } else {
                                        $promo_code = '/'.$company_url.'/book/'.$branch->id.'/'.$value->id;
                                    }
                                ?>
                                <a href="{{$promo_code}}" class="service-popup__btn btn btn--accent">Book Now</a>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="popup" id="branch-list-popup" data-popup-target="branch-list">
        <div class="popup__inner" data-popup-trigger="branch-list">
            <div class="container popup__content popup__content--big">
                <div class="row">
                    <p class="close_news" onclick="closeNews()"><i class="material-icons">clear</i></p>
                    <div class="col-12">
                        <div class="branch-popup">
                            @if($company != "")
                            <div class="branch-popup__intro">
                                <div class="branch-popup__logo u-margin-bottom-small">
                                    <img src="{{$company->company_image}}" alt="Logo" class="branch-popup__logo-img">
                                    <h1 class="heading-primary--big u-color-black u-weight-smb u-lh-1 branch-popup__logo-title">{{$company->company_name}}</h1>
                                </div>
                                <h3 class="u-text-transform-none u-opacity-7  text-left"><pre>{{$company->company_des}}</pre></h3>
                            </div>
                            @endif
                            <div class="branch-popup__content">
                                <div class="branch-popup__inner">
                                    <div class="branch-popup__inner-header">
                                        <h3>CHOOSE A BRANCH</h3>
                                        <img src="{{asset('images/i-search.png')}}" alt="Search" class="branch-popup__inner-search">
                                    </div>
                                    <ul class="branch-popup__list">
                                        @foreach($branches as $value)
                                        <li  data-id="{{$value->id}}" class="branch-popup__item branch_list_btn" style="background-image: url('{{$value->branch_image}}');">
                                            <div class="branch-popup__item-rating">
                                                <img src="{{asset('images/star.png')}}" alt="Star">
                                                <p class="paragraph--big">4.8</p>
                                            </div>
                                            <div class="branch-popup__item-copy">
                                                <h1 class="heading-primary--big u-weight-smb u-margin-bottom-smallest">{{$value->branch_name}}</h1>
                                                <h3 class="u-text-transform-none u-color-white u-opacity-7 u-weight-med">{{$value->branch_label}}</h3>
                                            </div>
                                        </li>
                                        @endforeach                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" id="appointments-list-popup" data-popup-target="appointments-list">
        <div class="popup__inner" data-popup-trigger="appointments-list">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12">
                        <div class="service-popup">                            
                            <div class="branch-popup__content" style="padding-top: 4.0833rem;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" data-popup-target="news">
        <div class="popup__inner popup__inner--start popup__inner--primary" data-popup-trigger="news">
            <div class="container popup__content">
                <div class="row">
                    <p class="close_news" onclick="closeNews()"><i class="material-icons">clear</i></p>
                    <div class="col-12">

                        <div class="news-popup">
                            <div class="news-popup__slider">
                                @foreach($panel_news as $value)
                                <div class="news-popup__item">
                                    <img src="{{$value->news_image}}" alt="Promotional banner image" class="news-popup__img">
                                    <div class="news-popup__copy">
                                        <p class="u-weight-smb">{{$value->news_des}}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <img src="{{asset('images/i-menu.svg')}}" alt="Menu" class="header__icon" data-popup-trigger="about_us_modal">
                        <h2 class="u-color-black u-text-transform-none">Home</h2>
                        <img src="{{asset('images/i-search.svg')}}" alt="Search" class="header__icon" data-popup-trigger="about_us_modal">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="headline" id="appointments_div" hidden>
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <div class="text-icon">
                        <img src="{{asset('images/i-calendar.svg')}}" alt="Calendar icon" class="text-icon__img">
                        <div class="text-icon__copy">
                            <h3 class="u-text-transform-none u-weight-smb u-margin-bottom-smallest u-color-white" id="appointments_title">3 Appointments</h3>
                            <p class="paragraph--med u-opacity-6 u-color-white" id="appointments_des">You have 3 pending appointments</p>
                        </div>
                    </div>
                    <p data-popup-trigger="appointments-list" class="btn">View</p>
                </div>
            </div>
        </div>
    </div> 

    <main class="main-content">
        @if($branch!="")
        <a href="/{{$company_url}}/about/{{$branch->id}}">
            <section class="banner banner--content u-margin-bottom-huge">
                
                    <img src="{{$branch->branch_image}}" alt="Banner" class="banner__img">
                
                <div class="banner__tag">
                    <div class="text-icon text-icon--center">
                        <img src="{{asset('images/i-location.svg')}}" alt="Location icon" class="text-icon__img text-icon__img--small">
                        <p class="paragraph--med u-color-white">3.4mi</p>
                    </div>
                </div>
                <div class="banner__content d-flex align-items-center justify-content-around" style="width: 90%;">
                    <div>
                        <h1 class="u-margin-bottom-smallest">{{$branch->branch_name}}</h1>
                        <h3 class="u-color-white u-opacity-7 u-text-transform-none u-weight-med">{{$branch->branch_label}}</h3>
                    </div>
                    @if($company->permission_status == 1)
                    <button class="btn branch-list-btn" data-popup-trigger="branch-list">Branches&nbsp;&nbsp;&nbsp;</button>
                    @endif
                </div>
            </section>
        </a>
        <section class="services">
            <div class="container">
                <div class="row u-margin-bottom-small">
                    <div class="col-12 d-flex align-items-center justify-content-between">
                        <h3>SERVICES</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($services as $value)
                            <div class="service-box" style="background-image: url('{{$value->service_image}}')" data-popup-trigger="wash-cut-blow-{{$value->id}}">
                                <p class="paragraph--med-2 u-color-primary u-weight-smb">{{$value->service_name}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @else
        <div>There is no branches.</div>
        @endif
    </main>

    <nav class="nav">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex align-items-center justify-content-between">                    
                    <div data-popup-trigger="about_us_modal" class="nav__item">
                        <img src="{{asset('images/i-about.svg')}}" alt="Icon" class="nav__item-icon">
                        <p class="paragraph--med u-color-primary-alt u-weight-med">About</p>
                    </div>
                    <a href="#" class="nav__item" data-popup-trigger="news">
                        <img src="{{asset('images/i-news.svg')}}" alt="Icon" class="nav__item-icon">
                        <p class="paragraph--med u-color-gray-alt-2 u-weight-med">News</p>
                    </a>
                    <div class="nav__item nav__item--placeholder"></div>
                    <a href="/{{$company_url}}/bookings" class="nav__item--accent">
                        <img src="{{asset('images/i-book.svg')}}" alt="Icon" class="nav__item-icon">
                    </a>
                    <div data-popup-trigger="lang_modal" class="nav__item">
                        <img src="{{asset('images/i-language.svg')}}" alt="Icon" class="nav__item-icon">
                        <p class="paragraph--med u-color-gray-alt-2 u-weight-med">Feedback</p>
                    </div>
                    <div data-popup-trigger="share_modal" class="nav__item">
                        <img src="{{asset('images/i-share.svg')}}" alt="Icon" class="nav__item-icon">
                        <p class="paragraph--med u-color-gray-alt-2 u-weight-med">Share</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>
    <script type="text/javascript">

        function closeNews() {
            $('.popup').removeClass('popup--active');
        }

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            var user_id = '{{$user_id}}';            

            $.get(" {{URL::to('getAppointments')}}",
            {
                id: user_id,
                company_url: '{{$company_url}}'
            },function(data) {
                if (parseInt(data.count) == 1) {
                    var messages = [];
                    messages = data.messages;

                    $('#appointments_div').removeAttr('hidden');
                    $('#appointments_title').text(data.count +' Appointment');
                    $('#appointments_des').text('You have '+ data.count +' pending appointment.');

                    if (messages[0]['booking_status'] == 5 || messages[0]['booking_status'] == 6) {
                            html = '<section class="banner message_section u-margin-bottom-med" id="'+messages[0]['id']+'">'+
                                        '<h4 class="message_title">'+messages[0]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[0]['appointment_body']+'</p>'+
                                        '<p class="delete_btn" onclick="deleteMessage('+messages[0]['id']+')">Delete</p>'+
                                    '</section>';
                        } else if (messages[0]['booking_status'] == 1) {
                            html = '<section class="banner message_section u-margin-bottom-med" id="'+messages[0]['id']+'">'+
                                        '<h4 class="message_title">'+messages[0]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[0]['appointment_body']+'</p>'+
                                        '<p class="pay_now_btn" onclick="goToBooking()">Pay Now</p>'+
                                    '</section>';
                        } else {
                            html = '<section class="banner message_section u-margin-bottom-med" id="'+messages[0]['id']+'">'+
                                        '<h4 class="message_title">'+messages[0]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[0]['appointment_body']+'</p>'+
                                    '</section>';
                            }
                    
                    $('#appointments-list-popup div.branch-popup__content').empty().append(html);
                }
                else if (parseInt(data.count) > 1) {
                    var messages = [];
                    messages = data.messages;

                    $('#appointments_div').removeAttr('hidden');
                    $('#appointments_title').text(data.count +' Appointments');
                    $('#appointments_des').text('You have '+ data.count +' pending appointments.');
                    var html = '';
                    for (var i = messages.length - 1; i >= 0; i--) {
                        if (messages[i]['booking_status'] == 5 || messages[i]['booking_status'] == 6) {
                            html += '<section class="banner message_section u-margin-bottom-med" id="'+messages[i]['id']+'">'+
                                        '<h4 class="message_title">'+messages[i]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[i]['appointment_body']+'</p>'+
                                        '<p class="delete_btn" onclick="deleteMessage('+messages[i]['id']+')">Delete</p>'+
                                    '</section>';
                        } else if (messages[i]['booking_status'] == 1) {
                            html += '<section class="banner message_section u-margin-bottom-med" id="'+messages[i]['id']+'">'+
                                        '<h4 class="message_title">'+messages[i]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[i]['appointment_body']+'</p>'+
                                        '<p class="pay_now_btn" onclick="goToBooking()">Pay Now</p>'+
                                    '</section>';
                        } else {
                            html += '<section class="banner message_section u-margin-bottom-med" id="'+messages[i]['id']+'">'+
                                        '<h4 class="message_title">'+messages[i]['appointment_title']+'</h4>'+
                                        '<p class="message_body">'+messages[i]['appointment_body']+'</p>'+
                                    '</section>';
                            }
                    }
                    $('#appointments-list-popup div.branch-popup__content').empty().append(html);

                } else {
                    $('#appointments_div').attr('hidden', '');
                }
            });
        });

        function deleteMessage($id) {
            var id = $id
            $.get(" {{URL::to('deleteAppointments')}}",
            {
                id: id
            },function(data) {
                if (data == 'success') {
                    $('section#'+id).remove();
                }
            })
        }

        function goToBooking() {
             location.href='/{{$company_url}}'+'/bookings';
        }
        
        $('body .branch_list_btn').on('click', function(e) {
            var id = $(this).data('id');

            var $_GET = <?php echo json_encode($_GET); ?>;

            if ($_GET['promocode'] != undefined) {
                location.href='/{{$company_url}}'+'/branch/'+id+'?promocode='+$_GET['promocode'];
            } else {
                location.href='/{{$company_url}}'+'/branch/'+id;
            }            
        });

        function reminderUser() {
            $.get(" {{URL::to('reminderUser')}}", function(data) {
                location.href='https://api.whatsapp.com/send?phone=85255247923&text=Accept';
            })
        }       

    </script>
</body>
</html>