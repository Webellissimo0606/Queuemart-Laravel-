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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <style type="text/css">
        #map .gmnoprint, #map .gm-control-active {
            display: none!important;
        }
        #main-direction {
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        #main-direction .app {
            width: 40%;
            line-height: 2.5;
            margin: 20px 10px 15px;
            display: inline-block;
        }

        #main-direction .app img {
            height: 90px;
            border-radius: 18%;
        }
    </style>
</head>

<body>

    <div class="popup" data-popup-target="setting_modal">
        <div class="popup__inner" data-popup-trigger="setting_modal" style="padding-top: 10vh;">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h2 style="padding: 10px 0 10px 0;">Setting</h2>
                            </div>
                            <div class="service-popup__content" style="max-height: none; padding: 20px; overflow: inherit;">
                                <div id="errorMSG"></div>
                                <div class="form__group d-flex align-items-bottom justify-content-around">
                                    <input type="password" id="current_password" placeholder="Current Password" required>
                                </div>   
                                <div class="form__group d-flex align-items-bottom justify-content-around">
                                    <input type="password" id="new_password" placeholder="New Password" required>
                                </div>  
                                <div class="form__group d-flex align-items-bottom justify-content-around">
                                    <input type="password" id="confirm_password" placeholder="Confirm Password" required>
                                </div>                                   
                                <button onclick="resetPassword()" type="button" class="btn btn--accent u-color-white" style="margin-top: 1.25rem;">Reset Password</button>
                                <a href="https://api.whatsapp.com/send?phone=85255247923&text=Accept" class="btn btn--accent u-color-white" style="margin-top: 1.25rem;">Accept Whatsapp Reminder</a>
                                <button onclick="logoutButton()" type="button" class="btn btn--accent u-color-white" style="margin-top: 1.25rem;">Logout</button>
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
                        <a href="/{{$company_url}}"><img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--left header__icon--back"></a>
                        <h2 class="u-color-black u-text-transform-none">My Bookings</h2>
                        <img src="{{asset('images/logout.png')}}" alt="Setting" class="header__icon" data-popup-trigger="setting_modal">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="popup" data-popup-target="payment_modal" id="payment_modal">
        <div class="popup__inner" data-popup-trigger="payment_modal">
            <div class="container popup__content" style="padding-top: 30%;">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup" id="payment_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" data-popup-target="cancel_modal" id="cancel_modal">
        <div class="popup__inner" data-popup-trigger="cancel_modal">
            <div class="container popup__content" style="padding-top: 30%;">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h2 style="padding: 10px 0 10px 0;">Are you sure?</h2>
                            </div>
                            <div class="service-popup__content">                                
                                <div class="book__service-item">
                                    <input type="radio" id="ok_check" name="cancel">
                                    <label for="ok_check">
                                        <div class="book__service-item-icon"></div>
                                        <div class="book__service-item-copy">
                                            <div>
                                                <p class="paragraph--big u-weight-smb">Ok</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="book__service-item">
                                    <input type="radio" id="cancel_check" name="cancel">
                                    <label for="cancel_check">
                                        <div class="book__service-item-icon"></div>
                                        <div class="book__service-item-copy">
                                            <div>
                                                <p class="paragraph--big u-weight-smb">Cancel</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>                                                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup" data-popup-target="reschedule_modal" id="reschedule_modal">
        <div class="popup__inner" data-popup-trigger="reschedule_modal">
            <div class="container popup__content" style="padding-top: 30%;">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="text-center" style="background-color: #dc97f5; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <h2 style="padding: 10px 0 10px 0;">Are you sure?</h2>
                            </div>
                            <div class="service-popup__content">                                
                                <div class="book__service-item">
                                    <input type="radio" id="ok_reschedule" name="reschedule">
                                    <label for="ok_reschedule">
                                        <div class="book__service-item-icon"></div>
                                        <div class="book__service-item-copy">
                                            <div>
                                                <p class="paragraph--big u-weight-smb">Ok</p>
                                            </div>
                                        </div>
                                    </label>
                                </div> 
                                <div class="book__service-item">
                                    <input type="radio" id="cancel_reschedule" name="reschedule">
                                    <label for="cancel_reschedule">
                                        <div class="book__service-item-icon"></div>
                                        <div class="book__service-item-copy">
                                            <div>
                                                <p class="paragraph--big u-weight-smb">Cancel</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>                                                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="banner banner--overlay banner--overlay-2">
        @if($floating_news != '')
        <div class="marquee">
            <p class="u-color-black-alt-2 u-letter-spacing-small u-color-white u-weight-lgt">{{$floating_news->float_des}}</p>
        </div>
        @endif
        <div class="banner__bg" style="background-image: url('{{$branch_first->branch_image}}');">&nbsp;</div>
        <div class="banner__profile">
            <div class="banner__profile-img u-margin-bottom-smaller">
                @if($user_info->user_image)
                    <img src="{{$user_info->user_image}}" alt="Profile picture" class="banner__profile-img-picture">
                @else
                    <img src="{{asset('images/default-user.jpeg')}}" alt="Profile picture" class="banner__profile-img-picture">
                @endif
                <img onclick="myImage()" src="{{asset('images/i-camera.png')}}" alt="Camera icon" class="banner__profile-img-btn">
            </div>
            <h1 class="heading-primary--big">{{$user->name}}</h1>            
            <div class="banner__profile-info banner__profile-info--alt">
                <a href="/{{$company_url}}/bookings" class="banner__profile-info-group banner__profile-info-group--active">
                    <h2 class="u-margin-bottom-small">{{$total_count}}</h2>
                    <p class="u-text-transform-none u-color-white u-weight-smb">Total Bookings</p>
                </a>
                <a href="/{{$company_url}}/credits" class="banner__profile-info-group">
                    <h2 class="u-margin-bottom-small" style="display: flex; justify-content: space-around; align-items: center;">
                        <span>{{$user->credits_rm}}&nbsp;RM</span>
                        <span>{{$user->credits_sgd}}&nbsp;SGD</span>
                    </h2>
                    <p class="u-text-transform-none u-color-white u-weight-smb">Total Rewards</p>
                </a>
            </div>
        </div>
    </section>

    <main class="main-content">
        @foreach($bookings as $value)
        <section class="bookings" style="margin-bottom: 30px; position: relative;">
            <h2 class="u-color-black u-weight-bld u-text-transform-none u-margin-bottom-med"><?php $day_custom = new DateTime($value->appt_datetime); echo ($day_custom->format('jS F Y')); ?></h2>
            <ul class="bookings__list">
                <div class="booking bookings__list-item">
                    <div class="booking__date">
                        <h2><?php $day_custom = new DateTime($value->appt_datetime); echo ($day_custom->format('h:i a')); ?></h2>
                    </div>  
                    <div class="booking__status_{{$value->booking_status}}">
                        <h5 style="text-align: center; color: white; font-weight: bold;">
                            <?php 
                                if ($value->booking_status == 1) {
                                    echo 'Booked';
                                } else if ($value->booking_status == 4) {
                                    echo 'Paid';
                                } else if ($value->booking_status == 7) {
                                    echo 'Working';
                                }
                            ?>
                        </h5>
                    </div>                  
                    <div class="booking__box">
                        <img src="{{$value->service_image}}" alt="Booking service image" class="booking__banner">
                        <div class="booking__copy">
                            <div class="d-flex align-items-end justify-content-between u-margin-bottom-small">
                                <h2 class="u-color-black u-text-transform-none">{{$value->service_name}}</h2>
                                <a href="/{{$company_url}}/about/{{$value->branch_id}}" class="booking__location-btn"><img src="{{asset('images/i-location-primary.svg')}}" alt="Location"></a>
                            </div>
                            <h3 class="u-color-primary u-text-transform-none u-weight-smb">{{$value->branch_name}}</h3>
                            <p class="paragraph--med u-weight-lgt u-color-primary-alt-4">{{$value->branch_address}}<br>{{$value->branch_tel_num}}<br>{{$value->package_name}}&nbsp;&nbsp;<span class="u-color-primary">{{$value->package_unit}}{{$value->package_price}}</span><br><span class="u-color-black">{{$value->client_notice}}</span><br>
                                <?php if ($value->service_type == 'seminar' && $value->duration_show == 1) {
                                    $start_date = new DateTime(explode("-", $value->service_duration)[0]);
                                    $end_date = new DateTime(explode("-", $value->service_duration)[1]);
                                    $start_time = new DateTime($value->start_time);
                                    ?>
                                    <div style="display: flex;align-items: center; justify-content: flex-start; margin-top: 5px; font-size: 10px;">
                                        <span class="u-color-primary"><i class="material-icons">date_range</i></span>
                                        <span class="u-color-primary"><?php echo ($start_date->format('j M Y')); ?> - <?php echo ($end_date->format('j M Y')); ?></span>&nbsp;&nbsp;
                                        <span class="u-color-primary"><?php echo ($start_time->format('h:i A')); ?></span>  
                                    </div>
                                <?php } ?>
                            </p>
                        </div>
                        <div class="booking__actions">
                            <div class="d-flex">
                                <?php 
                                if ($value->booking_status != 7) { ?>
                                <p class="btn btn--big btn--error" onclick="booking_cancel('{{$value->id}}')">Cancel</p>
                                <?php }
                                ?>
                                <?php 
                                if ($value->booking_status == 1) { ?>
                                <p onclick="booking_payment('{{$value->id}}')" class="btn btn--big btn--primary">Pay Now</p>
                                <?php }
                                ?>
                                @if($value->reschedule_allow == 1)
                                <?php 
                                if ($value->booking_status != 7 && $value->booking_status != 1) { ?>
                                <a onclick="booking_reschedule('{{$company_url}}', '{{$value->id}}')" class="btn btn--primary btn--icon">Reschedule</a>
                                <?php }
                                ?>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($value->arrived_check == 0)
                <div class="qrcode_div">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(38, 38, 38, 0.85)->backgroundColor(255, 255, 255, 0.82)->size(200)->generate($value->qrcode_field)) !!} ">
                </div>
                @endif
            </ul>
        </section>
        @endforeach
        <hr>

        @foreach($past_bookings as $value)
        <section class="bookings" style="margin-bottom: 30px; position: relative;">
            <h2 class="u-color-black u-weight-bld u-text-transform-none u-margin-bottom-med"><?php $day_custom = new DateTime($value->appt_datetime); echo ($day_custom->format('jS F Y')); ?></h2>
            <ul class="bookings__list">
                <div class="booking bookings__list-item">
                    <div class="past_booking__date">
                        <h2><?php $day_custom = new DateTime($value->appt_datetime); echo ($day_custom->format('h:i a')); ?></h2>
                    </div>
                    <div class="booking__status_{{$value->booking_status}}">
                        <h5 style="text-align: center; color: white; font-weight: bold;">
                            <?php 
                                if ($value->booking_status == 2) {
                                    echo 'Completed';
                                } else {
                                    echo 'Cancelled';
                                }
                            ?>
                        </h5>
                    </div>
                    <div class="booking__box">
                        <img src="{{$value->service_image}}" alt="Booking service image" class="booking__banner">
                        <div class="booking__copy">
                            <div class="d-flex align-items-end justify-content-between u-margin-bottom-small">
                                <h2 class="u-color-black u-text-transform-none">{{$value->service_name}}</h2>
                            </div>
                            <h3 class="u-color-primary u-text-transform-none u-weight-smb">{{$value->branch_name}}</h3>
                            <p class="paragraph--med u-weight-lgt u-color-primary-alt-4">{{$value->branch_address}}<br>{{$value->branch_tel_num}}<br>{{$value->package_name}}&nbsp;&nbsp;<span class="u-color-primary">{{$value->package_unit}}{{$value->package_price}}</span></p>
                        </div>
                        <div class="booking__actions">
                            <div class="d-flex">
                                <?php 
                                if ($value->booking_status == 2 && $value->rating_check == 0) { ?>
                                <a href="/{{$company_url}}/rating/{{$value->id}}" class="btn btn--big btn--primary" style="width: auto; padding: 0px 10px;">Rate me, please.</a>
                                <?php }
                                ?>
                                <?php 
                                if ($value->rating_check == 1) { ?>
                                <p class="btn btn--big btn--primary" style="width: auto; padding: 0px 10px;">Thanks for your rating!</p>
                                <?php }
                                ?>
                                <?php 
                                if ($value->booking_status == 2) { ?>
                                <p class="btn btn--big btn--primary" onclick="booking_share('https://queuemart.me/{{$company_url}}?promocode={{$value->promo_code}}')">Share</p>
                                <?php }
                                ?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </ul>
        </section>
        @endforeach
    </main>

    <section hidden>
        <form class="imagePoster" enctype="multipart/form-data" action="{{ URL::to('userImageUpload') }}" method="post" id="imageUpload_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
            <input id="filePoster" type="file" name="user_image" multiple="multiple" />
        </form>
    </section>

    <div class="popup" data-popup-target="share_modal" id="share_modal">
        <div class="popup__inner" data-popup-trigger="share_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="service-popup__header" style="background: white; height: 15rem; padding: 5px;">
                                <div class="form__group d-flex align-items-bottom justify-content-around">
                                    <input type="text" id="share_url" readonly>
                                </div> 
                                <button onclick="myFunction()" type="button" class="btn btn--accent u-color-white" style="margin-top: 1.25rem;">Copy Link</button>
                            </div>
                            <div class="service-popup__content">
                                <p class="u-color-black-alt u-margin-bottom-med u-letter-spacing-med"></p>
                                <div id="main-direction">                             
                                    <a href="https://www.facebook.com/" class="app google-map"><img src="{{asset('images/facebook_icon.png')}}">Facebook</a>
                                    <a href="https://twitter.com/" class="app waze"><img src="{{asset('images/twitter_icon.png')}}">twitter</a>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function booking_cancel($id) {
            $('div#cancel_modal').addClass('popup--active');
            
            $('div#cancel_modal input:radio[name="cancel"]').click(function() {
                if ($(this).attr('id') == 'ok_check') {
                    $.get('{{URL::to("booking_cancel")}}',
                        {
                            id: $id
                        }, function(data) {
                            location.reload();
                        })
                } else {
                    $('div#cancel_modal').removeClass('popup--active');
                }
            });
        }

        function booking_reschedule($company_url, $booking_id) {
            $('div#reschedule_modal').addClass('popup--active');
            
            $('div#reschedule_modal input:radio[name="reschedule"]').click(function() {
                if ($(this).attr('id') == 'ok_reschedule') {
                    location.href='/'+$company_url+'/reschedule/'+$booking_id+'/';
                } else {
                    $('div#reschedule_modal').removeClass('popup--active');
                }
            });
        }

        function booking_payment($id) {
            
            $.get('{{URL::to("booking_payment")}}',
                {
                    id: $id
                }, function(data) {
                    $('#payment_div').empty().html(data);
                    $('div#payment_modal').addClass('popup--active');
                });
        }

        function booking_share($url) {
            $('#share_url').val($url);
            $('#share_modal').addClass('popup--active');
        }

        function myFunction() {
          var copyText = document.getElementById("share_url");
          copyText.select();
          document.execCommand("copy");
        }

        function myImage() {
            $('#filePoster').trigger('click'); 
        }

        var files = document.getElementById('filePoster');
        files.addEventListener("change", function () {   
            var url = $("#imageUpload_form").attr('action');
            var post = $("#imageUpload_form").attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#imageUpload_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    $('.banner__profile-img-picture').attr('src', data.user_image);
                }
            })
        });

      
    </script>

    <script type="text/javascript">
        function resetPassword() {
            var current_password = $("#current_password").val();
            var new_password = $("#new_password").val();
            var confirm_password = $("#confirm_password").val();
            $.get("{{URL::to('admin/resetPassword')}}",
                {
                    current_password: current_password,
                    new_password: new_password,
                    confirm_password: confirm_password,
                }, function(data) {
                    if (data == 'success') {
                        location.reload();
                    } else {
                        $('#errorMSG').text(data);
                    }
            })
        }

        function logoutButton() {
            location.href = '/logout';
        }
    </script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>

</body>

</html>