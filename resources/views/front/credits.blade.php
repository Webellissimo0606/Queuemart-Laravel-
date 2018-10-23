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
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
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
                <a href="/{{$company_url}}/bookings" class="banner__profile-info-group">
                    <h2 class="u-margin-bottom-small">{{$total_count}}</h2>
                    <p class="u-text-transform-none u-color-white u-weight-smb">Total Bookings</p>
                </a>
                <a href="/{{$company_url}}/credits" class="banner__profile-info-group banner__profile-info-group--active">
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
        <section class="credits">
            @foreach($credits as $credit)
            <div class="credits__item">
                <div class="credits__item-main credits__item-main--alt">
                    <div class="credits__item-date">
                        <h1 class="u-margin-bottom-smallest"><?php $day_custom = new DateTime($credit->created_at); echo ($day_custom->format('j')); ?></h1>
                        <h3 class="u-text-transform-none u-color-white u-weight-med"><?php $day_custom1 = new DateTime($credit->created_at); echo ($day_custom1->format('M')); ?></h3>
                    </div>
                    <div class="credits__item-info credits__item-info--alt">
                        <div class="credits__item-info-main">
                            <img src="{{asset('images/ticket-1.svg')}}" alt="Ticket icon" class="credits__item-icon u-margin-bottom-smallest">
                            <h2 class="u-color-black u-letter-spacing-big u-text-transform-none u-margin-bottom-small">{{$credit->service_name}}</h2>
                            <p class="u-color-black-alt-2 u-letter-spacing-small d-inline-flex align-items-center">Ref ID : &nbsp;<span class="u-color-black">{{$credit->othername}}</span></p>
                        </div>
                        <div class="credits__item-info-sub">
                            <h1 class="heading-primary--huge-2 u-color-black u-weight-smb">{{$credit->credit_amount}}</h1>
                            <h3 class="u-color-black-alt-2 u-text-transform-none u-weight-med u-letter-spacing-small">{{$credit->credit_unit}}</h3>
                        </div>
                    </div>
                </div>
                <div class="credits__item-sub">
                    <button class="credits__item-btn">
                        <a href="/{{$company_url}}/book/{{$credit->branch_id}}/{{$credit->service_id}}" class="u-color-black-alt-2 u-weight-med">View More</a>
                    </button>
                </div>
            </div>
            @endforeach
            <!-- <div class="credits__item">
                <div class="credits__item-main">
                    <div class="credits__item-date">
                        <h1 class="u-margin-bottom-smallest">18</h1>
                        <h3 class="u-text-transform-none u-color-white u-weight-med">Aug</h3>
                    </div>
                    <div class="credits__item-info">
                        <div class="credits__item-info-main">
                            <img src="{{asset('images/ticket-2.svg')}}" alt="Ticket icon" class="credits__item-icon u-margin-bottom-smallest">
                            <h2 class="u-color-black u-letter-spacing-big u-text-transform-none u-margin-bottom-small">Hair Cut</h2>
                            <p class="u-color-black-alt-2 u-letter-spacing-small d-inline-flex align-items-center">Ref ID : <img src="{{asset('images/i-googleplus.svg')}}" alt="Google Plus" class="credits__item-ref"> <span class="u-color-black">Nicholas</span></p>
                        </div>
                        <div class="credits__item-info-sub">
                            <h1 class="heading-primary--huge-2 u-color-black u-weight-smb">5</h1>
                            <h3 class="u-color-black-alt-2 u-text-transform-none u-weight-med u-letter-spacing-small">Rm</h3>
                        </div>
                    </div>
                </div>
                <div class="credits__item-sub">
                    <button class="credits__item-btn">
                        <p class="u-color-black-alt-2 u-weight-med">View More</p>
                    </button>
                </div>
            </div>
            <div class="credits__item">
                <div class="credits__item-main">
                    <div class="credits__item-date">
                        <h1 class="u-margin-bottom-smallest">18</h1>
                        <h3 class="u-text-transform-none u-color-white u-weight-med">Aug</h3>
                    </div>
                    <div class="credits__item-info">
                        <div class="credits__item-info-main">
                            <img src="{{asset('images/ticket-3.svg')}}" alt="Ticket icon" class="credits__item-icon u-margin-bottom-smallest">
                            <h2 class="u-color-black u-letter-spacing-big u-text-transform-none u-margin-bottom-small">Hair Cut</h2>
                            <p class="u-color-black-alt-2 u-letter-spacing-small d-inline-flex align-items-center">Ref ID : <img src="{{asset('images/i-whatsapp.svg')}}" alt="Whatsapp" class="credits__item-ref"> <span class="u-color-black">Nicholas</span></p>
                        </div>
                        <div class="credits__item-info-sub">
                            <h1 class="heading-primary--huge-2 u-color-black u-weight-smb">5</h1>
                            <h3 class="u-color-black-alt-2 u-text-transform-none u-weight-med u-letter-spacing-small">Rm</h3>
                        </div>
                    </div>
                </div>
                <div class="credits__item-sub">
                    <button class="credits__item-btn">
                        <p class="u-color-black-alt-2 u-weight-med">View More</p>
                    </button>
                </div>
            </div> -->
        </section>
    </main>

    <section hidden>
        <form class="imagePoster" enctype="multipart/form-data" action="{{ URL::to('userImageUpload') }}" method="post" id="imageUpload_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
            <input id="filePoster" type="file" name="user_image" multiple="multiple" />
        </form>
    </section>

    <script type="text/javascript">
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