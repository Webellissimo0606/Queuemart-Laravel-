<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <a href="{{url('/login')}}">
                        <img src="{{asset('images/back_icon.svg')}}" alt="Menu" class="header__icon header__icon--back">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="register">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="verify__intro">
                        <h1 class="heading-primary--huge u-color-black u-weight-smb u-margin-bottom-huge-2">New Account</h1>
                    </div>
                    <form action="{{route('register')}}" method="POST" class="form">
                        <h2 class="u-color-black u-weight-smb u-margin-bottom-med">Social Media Sign Up</h1>
                        <div class="u-align-inline-center just-space">                            
                            <a href="#" class="u-weight-smb paragraph--big social-btn facebook-btn"><i class="fa fa-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;FACEBOOK</a>
                            <a href="#" class="u-weight-smb paragraph--big social-btn google-btn"><i class="fa fa-google-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;GOOGLE+</a>
                        </div>
                        
                        <div class="form__content form__content--alt">   
                            <h2 class="u-color-black u-weight-smb u-margin-bottom-med">Phone Number Signsssssssssssssss Up</h1>  
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="text" placeholder="Enter Full Name" name="name" required>sdsdasdasdasdasdasdasd
                            </div>                      
                            <div class="form__group form__group--divided d-flex align-items-bottom justify-content-around">
                                <div class="form__group-division">
                                    <select class="simple" required>
                                        <option value="+60">+80</option>
                                        <option value="+50">+50</option>
                                        <option value="+40">+40</option>
                                    </select>
                                </div>
                                <div class="form__group-division">
                                    <input type="phone" name="phone" placeholder="Enter Phone Number" required>
                                </div>
                            </div>
                            <div class="form__group d-flex align-items-bottom justify-content-around">
                                <input type="password" name="password" placeholder="Password" required>
                            </div>
                        </div>
                        <button type="submit" class="form__btn btn btn--accent u-color-white">Next</button>
                        
                        <div class="u-align-inline-center">
                            <p class="u-color-primary-drk u-letter-spacing-small sign-btn">Not the first time here? <a href="{{url('/login')}}" class="u-weight-smb paragraph--big">Log In</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    </main>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="js/app-dist.js"></script>

</body>

</html>