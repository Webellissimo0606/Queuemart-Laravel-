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
                    <a href="javascript:history.go(-1)">
                        <img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--back">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="verify">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="verify__intro">
                        <img src="{{asset('images/i-email.svg')}}" alt="Email" class="verify__intro-icon">
                        <h1 class="heading-primary--huge u-color-black u-weight-smb">Enter Verification<br>Code</h1>
                        {{session("msg")}}
                    </div>
                    <form action="{{route('verify')}}?phone={{$phone}}" method="POST" class="form">
                        {{ csrf_field() }}
                        <div class="form__content">
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="text" name="code" class="" maxlength="4" required>
                            </div>
                            @if ($errors->has('code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form__checkbox">
                            <input type="checkbox" id="read" required>
                            <label for="read">
                                <p class="paragraph--med-2">I have read & Understood the</p>
                                <p class="u-color-primary paragraph--med-2"><a href="#" class="u-underline">EULA, Privacy Policy</a></p>
                            </label>
                        </div>

                        <button type="submit" class="form__btn btn btn--accent u-color-white">Verify Now</button>

                        <div class="u-align-inline-center">
                            <p class="u-color-primary-drk u-letter-spacing-small">
                                <a href="{{url("/resend_code?phone_number=$phone")}}" class="u-weight-smb paragraph--big">RESEND</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="verify__help">
            <h2 class="u-color-gray-alt-4 u-weight-reg u-text-transform-none u-margin-bottom-smaller">Unable to Verify?</h2>
            <a href="#" class="u-text-transform-none heading-secondary u-color-primary-lgt u-weight-smb">Contact us</a>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="{{asset('js/app-dist.js')}}"></script>

</body>

</html>