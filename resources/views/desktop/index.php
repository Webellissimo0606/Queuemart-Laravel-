<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Landing Page</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/front_assets/styles/css/mainCSS.css">
</head>

<body>
    <div class="global_wrap">
        <div class="social_area">
            <ul class="social_icons">
                <li>
                    <a href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="main_area">
            <div class="main_area_wrap">
                <div class="logo">
                    <p>Logo</p>
                </div>
                <h4>Bumiraya <br> Motor Sdn Bhd!</h4>
                <p class="text">To make an appointment or take your queue <span class="break"></span>number anywhere at anytime!</p>
                <ul class="stores_wrap">
                    <li>
                        <a href="#">
                            <img src="/front_assets/images/main_area/playstore_ic.png" alt="playstore" class="playstore_ic">
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/front_assets/images/main_area/appstore_ic.png" alt="appstore" class="appstore_ic">
                        </a>
                    </li>
                </ul>
                <img src="/front_assets/images/main_area/qr_code.png" alt="qr_code" class="qr_code">
            </div>
        </div>

        <div class="phone_area">
            
            <img src="/front_assets/images/phone_area/phone_img.png" alt="phone_img" class="phone_img">
            <div class="phone_area-content">
                <iframe src="https://queuemart.me/<?php echo $company_url; ?>" frameborder="0" class="phone_area-iframe"></iframe>
            </div>
        </div>
    </div>
</body>

</html>