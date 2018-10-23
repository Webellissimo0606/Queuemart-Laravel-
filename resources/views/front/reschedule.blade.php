<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{$company->company_des}}">
    <link rel="icon" href="{{$company->company_image}}">
    <title>{{$company->company_name}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/main.css') }}">
</head>

<body>

    <div class="popup" id="reminder_modal" data-popup-target="reminder_modal">
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
                                    <h3 class="u-text-transform-none u-weight-smb u-margin-bottom-med" id="alert_title"></h3>
                                </div>
                                <p class="u-color-black-alt u-margin-bottom-med u-letter-spacing-med" id="alert_text"></p>
                                <p id="proceed_btn" class="service-popup__btn btn btn--accent u-margin-bottom-med">PROCEED</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <header class="header header--absolute">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="javascript:history.go(-1)">
                        <img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--back">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="banner banner--overlay-alt">
            <div class="banner__bg" style="background-image: url('{{$service->service_image}}')">&nbsp;</div>
            <h1 class="heading-primary--huge-2">{{$service->service_name}}</h1>
        </section>
        <section class="book">
            <div class="container">
                <div class="row book__service">
                    <div class="col-12 d-flex align-items-center justify-content-between u-margin-bottom-big">
                        <h3>CHOOSE PACKAGE</h3>                        
                    </div>
                    <div class="col-12" id="package_options">
                        <div class="book__service-item">
                            <input type="radio" id="{{$package->id}}" name="service-type" checked>
                            <label for="{{$package->id}}">
                                <div class="book__service-item-icon"></div>
                                <div class="book__service-item-copy">
                                    <div>
                                        <p class="paragraph--big u-weight-smb">{{$package->package_name}}</p>
                                        <p class="paragraph--med u-color-black u-opacity-6" id="disabled_text">{{$package->package_des}}</p>
                                    </div>
                                    <h3 class="u-color-secondary u-weight-med">{{$package->package_unit}}{{$package->package_price}}</h3>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                @if($service->service_type == 'carservice')
                    <div id="calendar_div"></div>
                @endif               
                <div id="client_notice_div" hidden>
                    <h3 class="u-margin-bottom-med" style="margin-top: 30px;">{{$reminder->question}}</h3>
                    <textarea placeholder="{{$reminder->answer}}" id="customer_notice" style="width: 100%;height: 100px;font-size: 20px;">{{$booking->client_notice}}</textarea>
                </div>
            </div>
        </section>
        <section class="cta"> 
            <p data-popup-trigger="reminder_modal" class="u-color-white cta__label" id="pre_proceed">PROCEED</p>
        </section>        
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>
    

    <script type="text/javascript">

        var package_max_id = $("#package_options input[type='radio']:checked").prop('id');
        $.get("{{URL::to('getCalendar')}}",
        {
            request_id: '{{$service->id}}',
            package_id: package_max_id,
            company_url: '{{$company_url}}',
            selected_month: new Date().getMonth(),
            selected_year: new Date().getFullYear()
        }, function(data) {
            $('#calendar_div').empty().html(data);
        });

        function year_month_btn(year, month){
            var package_max_id = $("#package_options input[type='radio']:checked").prop('id');
            $.get("{{URL::to('getCalendar')}}",
            {
                request_id: '{{$service->id}}',
                package_id: package_max_id,
                company_url: '{{$company_url}}',
                selected_month: month,
                selected_year: year
            }, function(data) {
                $('#calendar_div').empty().html(data);
            });
        }    

        function diff_hours(dt2, dt1) 
         {

          var diff =(dt2.getTime() - dt1.getTime()) / 1000;
          diff /= (60 * 60);
          return Math.abs(Math.round(diff));
          
         }

        function get_today_datetime () {
            now = new Date();
            year = "" + now.getFullYear();
            month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
            day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
            hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
            minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
            second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
            return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
        }

        var service_type_custom = '{{$service->service_type}}';
        $('#pre_proceed').on('click', function(){
            if (service_type_custom == 'carservice') {
                var before_popup_des = '{{$reminder->before_popup_des}}';
                var before_popup_title = '{{$reminder->before_popup_title}}';                
                var package_id = $("#package_options input[type='radio']:checked").prop('id');
                var booking_day = $(".book__date input[name='date']:checked").val();
                var booking_time = $(".book__time input[name='book_time']:checked").val();
                var booking_date_time = new Date(booking_day+' '+booking_time);
                var today_date_time = new Date(get_today_datetime());
                var counts = diff_hours(booking_date_time, today_date_time);
                if (!(package_id && booking_day && booking_time)) {
                    $('#proceed_btn').text('CLOSE');
                    $('#reminder_modal #alert_text').text('Please select package, date, time!!!');   
                    $('#reminder_modal #alert_title').text('Alert');   
                } else {
                    $('#proceed_btn').text('PROCEED');
                    $('#reminder_modal #alert_text').text(before_popup_des); 
                    $('#reminder_modal #alert_title').text(before_popup_title); 
                }
            } else {
                var before_before_des = '{{$reminder->before_popup_des}}';
                var before_before_title = '{{$reminder->before_popup_title}}';
                var package_id = $("#package_options input[type='radio']:checked").prop('id');
                if (!package_id) {
                    $('#proceed_btn').text('CLOSE');
                    $('#reminder_modal #alert_title').text('Alert');   
                    $('#reminder_modal #alert_text').text('Please select package!!!');   
                } else {
                    $('#proceed_btn').text('PROCEED');
                    $('#reminder_modal #alert_text').text(before_before_des); 
                    $('#reminder_modal #alert_title').text(before_before_title); 
                }
            }
            
        })

        function get_today_date () {
            now = new Date();
            year = "" + now.getFullYear();
            month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
            day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
            hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
            minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
            second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
            return year + "-" + month + "-" + day;
        }

        function get_today_time () {
            now = new Date();
            year = "" + now.getFullYear();
            month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
            day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
            hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
            minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
            second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
            return hour + ":" + minute + ":" + second;
        }

        $('#proceed_btn').on('click', function(){
            if (service_type_custom == 'carservice') {
                var branch_id = '{{$booking->branch_id}}';
                var service_id = '{{$service->id}}';
                var package_id = $("#package_options input[type='radio']:checked").prop('id');
                var booking_day = $(".book__date input[name='date']:checked").val();
                var booking_time = $(".book__time input[name='book_time']:checked").val();
                var client_notice = $('#customer_notice').val();
                if (!(package_id && booking_day && booking_time)) {
                    $('#reminder_modal').removeClass('popup--active');
                    return false;
                }
                
                $.get('/{{$company_url}}/reschedule_order',
                {
                    booking_id: '{{$booking->id}}',
                    booking_day: booking_day,
                    booking_time: booking_time,
                    client_notice: client_notice
                }, function(data) {                    
                    location.href='/{{$company_url}}/'+data;
                })
            } else {
                var branch_id = '{{$booking->branch_id}}';
                var service_id = '{{$service->id}}';
                var package_id = $("#package_options input[type='radio']:checked").prop('id');
                var client_notice = $('#customer_notice').val();
                if (!package_id) {
                    $('#reminder_modal').removeClass('popup--active');
                    return false;
                }

                $.get('/{{$company_url}}/reschedule_order',
                {
                    booking_id: '{{$booking->id}}',
                    booking_day: get_today_date(),
                    booking_time: get_today_time(),
                    client_notice: client_notice
                }, function(data) {                    
                    location.href='/{{$company_url}}/'+data;
                })
            }
        })
    </script>

</body>

</html>