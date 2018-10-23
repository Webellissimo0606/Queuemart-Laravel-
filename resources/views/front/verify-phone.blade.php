<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <img src="{{asset('images/i-phone-alt.svg')}}" alt="Email" class="verify__intro-icon">
                        <h1 class="heading-primary--huge u-color-black u-weight-smb u-margin-bottom-med">Verify Number</h1>
                        <p class="paragraph--big u-opacity-7">We will send you an SMS to<br class="d-none d-sm-block"> verify your Number and send you<br class="d-none d-sm-block"> reminders for important events.</p>

                        <?php if(count($errors)): ?>
                            <div class="alert alert-danger">
                                <p class="paragraph--big u-opacity-7">
                                    {{implode(" <br> ",$errors)}}
                                </p>
                            </div>
                        <?php endif; ?>

                    </div>
                    <form action="{{url("/verify_phone")}}" method="post" class="form">
                        {!! csrf_field() !!}
                        <div class="form__content form__content--alt">
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <div class="form__group-division">
                                    <select class="simple" required name="phone_number_1">
                                        <option value="60">60</option>
                                        <option value="65">65</option>
                                    </select>
                                </div>
                                <div class="form__group-division">
                                    <input placeholder="127778888" required type="tel" name="phone_number_2">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="form__btn btn btn--accent u-color-white">
                            Send Verification Code
                        </button>


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