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

    <header class="header header--absolute">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="javascript:history.go(-1)">
                        <img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--back header__icon--back">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="banner">
            <img src="{{$branch->branch_image}}" alt="Banner" class="banner__img">
        </section>
        <section class="contact">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="contact__box">
                            <h1 class="u-margin-bottom-small">{{$branch->branch_name}}</h1>
                            <div class="contact__info">
                                <img src="{{asset('images/i-location.svg')}}" alt="Location icon" class="contact__info-icon">
                                <div class="contact__info-copy" style="width: 80%;">
                                    <p class="paragraph--med">{{$branch->branch_address}}</p>
                                </div>
                            </div>
                            <a href="tel:{{$branch->branch_tel_num}}" class="contact__btn"><img src="{{asset('images/i-phone.svg')}}" alt="Phone" class="contact__btn-icon"></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="info">
            <div class="container">
                <div class="row">
                    <div class="col-12 u-margin-bottom-med">
                        <h3 class="u-margin-bottom-med">About Branch</h3>
                        <p class="u-margin-bottom-med">{{$branch->branch_des}}</p>
                    </div>
                    <div class="col-12">
                        <div class="location">
                            <h3 class="u-margin-bottom-med">Location</h3>
                            <a data-popup-trigger="share_modal" class="location__map-btn" style="z-index: 3; color: white;"><img src="{{asset('images/i-direction.svg')}}" alt="Direction" class="location__map-btn-icon"> GET DIRECTION</a>
                            <div id="map" style="height: 200px; margin-bottom: 30px;"></div>     
                                                                                   
                        </div>
                    </div>
                </div>
            </div>
        </section>        
    </main>

    <div class="popup" data-popup-target="share_modal">
        <div class="popup__inner" data-popup-trigger="share_modal">
            <div class="container popup__content">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="service-popup">
                            <div class="service-popup__header" style="background-image: url(https://maps.googleapis.com/maps/api/staticmap?markers={{$branch->latitude}},{{$branch->longitude}}&zoom=10&size=300x200&scale=2&sensor=false&key=AIzaSyBqpRV8vEZVT887-dVL8Nlhit4nS6IU3XE)">&nbsp;</div>
                            <div class="service-popup__content">
                                <div class="d-flex align-items-start justify-content-between u-margin-bottom-med" style="text-align: center;">
                                    <h3 class="u-text-transform-none u-weight-smb" style="margin: auto;">{{$branch->branch_name}}</h3>
                                </div>
                                <p class="u-color-black-alt u-margin-bottom-med u-letter-spacing-med">{{$branch->branch_address}}</p>
                                <div id="main-direction">                             
                                    <a href="https://www.google.ca/maps?q={{$branch->latitude}},{{$branch->longitude}}" class="app google-map"><img src="https://s3-ap-southeast-1.amazonaws.com/encorehealthcare/mobile/app/images/app-google-maps.png">GOOGLE MAP</a>

                                    <a href="https://www.waze.com/ul?ll={{$branch->latitude}},{{$branch->longitude}}" class="app waze"><img src="https://s3-ap-southeast-1.amazonaws.com/encorehealthcare/mobile/app/images/app-waze.png">WAZE</a>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Initialize and add the map
        function initMap() {

            var longitude = parseFloat('{{$branch->longitude}}');
            var latitude = parseFloat('{{$branch->latitude}}');

          // The location of Uluru
          var uluru = {lat: latitude, lng: longitude};
          // The map, centered at Uluru
          var map = new google.maps.Map(
              document.getElementById('map'), {zoom: 10, center: uluru});
          // The marker, positioned at Uluru
          var marker = new google.maps.Marker({position: uluru, map: map});

          var infoWindow = new google.maps.InfoWindow({
            content:'<h1 style="color:black;">{{$branch->branch_name}}</h1>'
          });

          marker.addListener('click', function(){
            infoWindow.open(map, marker);
          })
        }
    </script>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqpRV8vEZVT887-dVL8Nlhit4nS6IU3XE&callback=initMap">
    </script>
</body>

</html>