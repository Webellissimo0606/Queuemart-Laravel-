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
    <link rel="stylesheet" href="{{asset('css/main_rating.css')}}">
</head>

<body>

    <main>
        <div class="rate rate--3">
            <div class="rate__bg rate__bg--default">&nbsp;</div>
            <div class="rate__bg rate__bg--1">&nbsp;</div>
            <div class="rate__bg rate__bg--2">&nbsp;</div>
            <div class="rate__bg rate__bg--3">&nbsp;</div>
            <div class="rate__bg rate__bg--4">&nbsp;</div>
            <div class="rate__bg rate__bg--5">&nbsp;</div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="rate__intro">
                            <h1 class="heading-primary--huge-2">Please rate your experience</h1>
                        </div>
                        <div class="rate__message">
                            <textarea placeholder="I like the services here because..." id="rating_text"></textarea>
                            <div class="u-align-inline-right">
                                <a href="javascript:history.go(-1)"><h2>SKIP</h2></a>
                            </div>
                        </div>
                        <div class="rate__face">
                            <div class="rate__face-eyes">
                                <div class="rate__face-eye rate__face-eye--left"></div>
                                <div class="rate__face-eye rate__face-eye--right"></div>
                            </div>
                            <div class="rate__face-mouth"></div>
                        </div>
                        <div class="rate__controls">
                            <div class="rate__stars">
                                <div class="rate__stars-item">
                                    <img src="{{asset('images/star.png')}}" alt="Star" class="rate__stars-item-icon">
                                </div>
                                <div class="rate__stars-item">
                                    <img src="{{asset('images/star.png')}}" alt="Star" class="rate__stars-item-icon">
                                </div>
                                <div class="rate__stars-item">
                                    <img src="{{asset('images/star.png')}}" alt="Star" class="rate__stars-item-icon">
                                </div>
                                <div class="rate__stars-item">
                                    <img src="{{asset('images/star.png')}}" alt="Star" class="rate__stars-item-icon">
                                </div>
                                <div class="rate__stars-item">
                                    <img src="{{asset('images/star.png')}}" alt="Star" class="rate__stars-item-icon">
                                </div>
                            </div>
                            <a onclick="submitRating()" class="btn btn--full btn--big btn--round"><h4 class="u-text-transform-none">Submit</h4></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist_rating.js')}}"></script>

    <script type="text/javascript">
        function submitRating() {
            var booking_id = '{{$booking_id}}';
            var rating_text = $('#rating_text').val();
            var rating_star = $('.rate__stars .rate__stars-item--active').length;
            $.get('{{URL::to("ratingStar")}}',
                {
                    booking_id: booking_id,
                    rating_text: rating_text,
                    rating_star: rating_star
                }, function(data) {
                    if (data == 'success') {
                        location.href='/{{$company_url}}/bookings'
                    }
                })
        }
    </script>

</body>

</html>