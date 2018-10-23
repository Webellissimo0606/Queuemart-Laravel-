<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="20x20" href="{{asset('images/favicon.png')}}">
    <title>Queuemart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
</head>

<body>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="{{url('/bookings')}}"><img src="images/i-back.svg" alt="Menu" class="header__icon header__icon--left header__icon--back"></a>
                        <h2 class="u-color-black u-text-transform-none">Past Bookings</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @foreach($bookings as $value)
        <section class="bookings">
            <h2 class="u-color-black u-weight-bld u-text-transform-none u-margin-bottom-med">{{$value->updated_at->format('jS F Y')}}</h2>
            <ul class="bookings__list">
                <div class="booking bookings__list-item">
                    <div class="booking__date">
                        <h2>{{$value->updated_at->format('H:i')}}</h2>
                    </div>
                    <div class="booking__box">
                        <img src="{{$value->service_image}}" alt="Booking service image" class="booking__banner">
                        <div class="booking__copy">
                            <div class="d-flex align-items-end justify-content-between u-margin-bottom-small">
                                <h2 class="u-color-black u-text-transform-none">{{$value->service_name}}</h2>
                            </div>
                            <h3 class="u-color-primary u-text-transform-none u-weight-smb">{{$value->branch_name}}</h3>
                            <p class="paragraph--med u-weight-lgt u-color-primary-alt-4">{{$value->branch_address}}<br>{{$value->branch_tel_num}}<br>{{$value->package_name}}.{{$value->package_price}}</p>
                        </div>
                    </div>
                </div>
            </ul>
        </section>
        <hr>
        @endforeach
        
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>

</body>

</html>