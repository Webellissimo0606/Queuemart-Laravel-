<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- CSRF Token -->

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="20x20" href="{{asset('images/favicon.png')}}">
    <title>Queuemart</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/clock.css')}}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('assets/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --> 

    <link rel="stylesheet" href="{{asset('css/main_admin.css') }}">

    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <style type="text/css">
        .footer_container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            width: 100%;
            position: fixed;
        }    
        .footer_wrap {
            overflow: hidden;
            padding-left: 0;
            padding-right: 0;
        } 
        .footer_wrap {
            background-image: -moz-linear-gradient( 0deg, rgb(106, 17, 203) 0%, rgb(37, 117, 252) 100% );
            background-image: -webkit-linear-gradient( 0deg, rgb(106, 17, 203) 0%, rgb(37, 117, 252) 100% );
            background-image: -ms-linear-gradient( 0deg, rgb(106, 17, 203) 0%, rgb(37, 117, 252) 100% );
            font-family: "poppins regular", sans-serif;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 20px;
            padding: 25px 30px;
            text-align: center;
            margin-right: 260px;
            width: auto;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            /* max-width: 1660px; */
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            color: #fff;
        }     
        .marquee {
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
        }    

        .marquee p {
            color: white!important;
            display: inline-block;
            padding-left: 100%;
            -webkit-animation: marquee 25s linear infinite;
            animation: marquee 25s linear infinite;
        }     
    </style>
</head>

<body class="fix-header fix-sidebar card-no-border" style="background: #f2f7f8; height: 100vh;">
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
  
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->

    <div class="row">
        <div class="col-md-5">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Order List</h4>
                </div>
                <div class="card-body" style="height: 80vh;" id="booking_tbody"></div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card card-outline-primary">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{$company->company_name}}</h4>
                </div>
                <div class="card-body">
                    <video src="{{$queuescreen->qr_screen_video}}" autoplay muted loop style="width: 100%; height: 50vh;" onclick="startQRCode()" id="videoDiv"></video>
                    <div id="reader" class="center-block" style="width:100%;height:50vh; margin:auto;" hidden>
                    </div>
                    <div id="message" class="text-center">
                    </div>
                    
                    <div style="width:100%; margin-top:30px;" id="workings_tbody"></div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
<div class="footer_container">
    <div class="footer_wrap">
        <footer class="marquee">
            <p>{{$queuescreen->qr_screen_news}}</p>
        </footer>
    </div>    
</div>
<div class="clock">
    <div class="clock__title">
        <p>Powerd by <span>QueueMart</span></p>
    </div>
    <div class="clock__inner">
        <div class="clock__insert"></div>
        <div id="clock" class="clock__digital">
            <p class="unit" id="hours"></p>
            <p class="unit" id="minutes"></p>
            <p class="unit" id="seconds"></p>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('/qr_login/jsqrcode-combined.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/qr_login/html5-qrcode.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.get("{{URL::to('admin/getBookingList')}}",
            {
                company_id: '{{$user_id}}',
                branch_id: '{{$branch_id}}',
            }, function(data) {
            $('#booking_tbody').empty().html(data);
        });
        $.get("{{URL::to('admin/getWorkingList')}}",
            {
                company_id: '{{$user_id}}',
                branch_id: '{{$branch_id}}',
            }, function(data) {
            $('#workings_tbody').empty().html(data);
        });
        setInterval(function()
        {
            $.get("{{URL::to('admin/getBookingList')}}",
                {
                    company_id: '{{$user_id}}',
                    branch_id: '{{$branch_id}}',
                }, function(data) {
                $('#booking_tbody').empty().html(data);
            });
            $.get("{{URL::to('admin/getWorkingList')}}",
                {
                    company_id: '{{$user_id}}',
                    branch_id: '{{$branch_id}}',
                }, function(data) {
                $('#workings_tbody').empty().html(data);
            });
        }, 10000);
    });
</script>

<script type="text/javascript">
    function startQRCode() {
        $("#videoDiv").attr('hidden', '');
        $('#reader').removeAttr('hidden');
        $('#reader').html5_qrcode(function(data){
            $('#message').html('<span class="text-success send-true">Scanning now....</span>');
            if (data!='') {

                     $.ajax({
                        type: "POST",
                        cache: false,
                        url : "{{URL::to('admin/checkQRCode')}}",
                        data: {"_token": "{{ csrf_token() }}",data:data, company_id:"{{$user_id}}"},
                            success: function(data) {
                              if (data.result==1) {
                                $("#booking_div_"+data.order.id).addClass('arrived_div');
                                $('#order_check_'+data.order.id+' span').text('Arrived');
                                $("#reader").html('');
                                $('#videoDiv').removeAttr('hidden');
                              }else{
                               return alert('There is no order with this qr code, today.'); 
                              }
                            }
                        })

            }else{return alert('There is no data');}
        },
        function(error){
           $('#message').html('Scaning now ....'  );
        }, function(videoError){
           $('#message').html('<span class="text-danger camera_problem"> there was a problem with your camera </span>');
        });  

        setTimeout(function(){
            $("#reader").attr('hidden', '');
            $("#reader").html('');
            $('#videoDiv').removeAttr('hidden');
        }, 15000);
    }
</script>
    <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    

    <script src="{{asset('assets/js/jquery.thooClock.js')}}"></script>  
    <script src="{{asset('assets/js/app-dist.js')}}"></script>  
</body>
</html>