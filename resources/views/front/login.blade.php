<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="20x20" href="{{asset('images/favicon.png')}}">
    <title>Queuemart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
</head>

<body>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="{{url('/')}}">
                        <img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--back">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="login">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="verify__intro">
                        <img src="{{asset('images/i-share.svg')}}" alt="User" class="verify__intro-icon">
                        <h1 class="heading-primary--huge u-color-black u-weight-smb u-margin-bottom-med">User Login</h1>
                    </div>
                    <form action="#" class="form">
                        <div class="form__content form__content--alt">
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="password" placeholder="Enter Phone Number" required>
                            </div>

                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="password" placeholder="Password" required>
                            </div>
                        </div>
                        <a href="{{url('/bookings')}}" class="form__btn btn btn--accent u-color-white">Login</a>
                        <div class="u-align-inline-center just-space">
                            <a href="#" class="u-weight-smb paragraph--big social-btn facebook-btn"><i class="fa fa-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;FACEBOOK</a>
                            <a href="#" class="u-weight-smb paragraph--big social-btn google-btn"><i class="fa fa-google-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;GOOGLE+</a>
                        </div>
                        <div class="u-align-inline-center">
                            <p class="u-color-primary-drk u-letter-spacing-small sign-btn">New here? <a href="{{url('/register')}}" class="u-weight-smb paragraph--big">Sign Up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>

</body>

</html>