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
                        <h1 class="heading-primary--huge u-color-black u-weight-smb u-margin-bottom-med">Client Detail</h1>
                    </div>
                    <form action="{{url("/complete_profile")}}" method="post" class="form">
                        {!! csrf_field() !!}
                        <div class="form__content form__content--alt">                            
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="text" name="name" placeholder="Full Name" value="{{$user->name}}" required>
                            </div>
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="text" name="nationality" placeholder="Nationality" value="{{$user->nationality}}" required>
                            </div>
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="text" name="ic" placeholder="IC Number" value="{{$user->ic}}" required>
                            </div>
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="email" name="email" placeholder="Email" value="{{$user->email}}" required>
                            </div>
                        </div>
                        <button type="submit" class="form__btn btn btn--accent u-color-white">Input Data</button>
                    </form>
                </div>
            </div>
        </div>        
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>

</body>

</html>