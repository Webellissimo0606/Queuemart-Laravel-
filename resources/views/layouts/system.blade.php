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
    <!-- chartist CSS -->
  <!--   <link href="{{asset('assets/plugins/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/css-chart/css-chart.css')}}" rel="stylesheet"> -->

    <!-- toast CSS -->
    <link href="{{asset('assets/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/dist/css/dropify.min.css')}}">
    <link href="{{asset('assets/plugins/calendar/dist/fullcalendar.css')}}" rel="stylesheet" />    
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />

    <!-- Custom CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/clock.css')}}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('assets/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --> 

    <link rel="stylesheet" href="{{asset('css/main_admin.css') }}">

    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

    <style type="text/css">
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
        .pagination>li {
            display: inline;
        }
        .pagination>li:first-child>a, .pagination>li:first-child>span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .pagination>li>a, .pagination>li>span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }
    </style>
    
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ url('admin/')}}">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{asset('assets/images/nic_logo.png')}}" height="40" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="{{asset('assets/images/nic_logo.png')}}" height="40" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a> </li>                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- <li class="nav-item hidden-sm-down">
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search for..."> <a class="srh-btn"><i class="ti-search"></i></a> </form>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php $action = ($user_obj->user_image == null) ? url('images/i-share.svg') : $user_obj->user_image; echo($action); ?>"  height="30" width="30" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img">
                                                <img src="<?php $action = ($user_obj->user_image == null) ? url('images/i-share.svg') : $user_obj->user_image; echo($action); ?>" alt="user">
                                            </div>
                                            <div class="u-text">
                                                <h4>{{$user_obj->name}}</h4>
                                                <p class="text-muted">{{$user_obj->email}}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a data-toggle="modal" data-target="#edit_profile"><i class="ti-user"></i> My Profile</a></li>
                                    <li><a href="{{url('admin/logout')}}"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>                        
                    </ul>
                </div>
            </nav>
        </header>

        <div class="modal fade" id="edit_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Edit My Profile</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ URL::to('admin/editProfile') }}" method="post" enctype="multipart/form-data" id="edit_profile_form">
                        <div class="modal-body">
                            <div id="errorMsg"></div>
                            <div class="form-group">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">                                       
                                <input type="file" name="user_image" id="input-file-now-custom-1" class="dropify" data-default-file="{{$user_obj->user_image}}" />
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Full Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$user_obj->name}}">
                                <input type="text" class="form-control" id="id" name="id" value="{{$user_obj->id}}" hidden>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{$user_obj->email}}">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Current Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">New Password:</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <div class="user-profile">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="<?php $action = ($user_single->user_image == null) ? url('images/i-share.svg') : $user_single->user_image; echo($action); ?>" alt="user" height="50" width="50" /> </div>
                    <!-- User profile text-->
                    <div class="profile-text">{{$user_single->name}}</div>
                    @if($user_obj->role_id == 2 || $user_obj->role_id == 3)
                    <div class="row" style="margin-bottom: 20px;">                        
                        <div class="col-12">
                            <select class="custom-select" id="employee_list">
                                @foreach($users as $value)
                                <option value="{{$value->user_id}}">{{$value->company_url}}</option>
                                @endforeach               
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/admin/{{$user_id}}">Overview</a></li>
                                
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false">Bookings</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <?php if (isset($branch_services)) { ?>
                                            <li><a href="/admin/{{$user_id}}/bookings_seminar">Seminar</a></li>
                                        <?php }  ?> 
                                        <?php if (isset($branch_services_car)) { ?>                                     
                                        <li><a href="/admin/{{$user_id}}/bookings">Fixed-hour</a></li>
                                        <?php }  ?> 
                                    </ul>
                                </li>
                                @if($user_obj->role_id == 2 || $user_obj->role_id == 3 || $user_obj->role_id == 4)
                                <li><a href="/admin/{{$user_id}}/statistic">Statistic</a></li>
                                @endif
                                <!-- <li><a href="/admin/{{$user_id}}/total_sms">Total SMS</a></li> -->
                            </ul>
                        </li>  
                        @if($user_obj->role_id == 2 || $user_obj->role_id == 3 || $user_obj->role_id == 4 || $user_obj->role_id == 5)          
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Client</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/admin/{{$user_id}}/client_list">Client List</a></li>
                                <li><a href="/admin/{{$user_id}}/booking_log">Booking Log</a></li>
                                <!-- <li><a href="/admin/{{$user_id}}/referral">Referral</a></li> -->
                            </ul>
                        </li> 
                        @endif 
                        @if($user_obj->role_id == 2 || $user_obj->role_id == 3 || $user_obj->role_id == 4)                     
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Booking</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/admin/{{$user_id}}/company">Company</a></li>
                                <li><a href="/admin/{{$user_id}}/branches">Branches</a></li>
                                <li><a href="/admin/{{$user_id}}/service">Services</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-arrange-send-backward"></i><span class="hide-menu">Broadcast</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/admin/{{$user_id}}/panel_news">Panel News</a></li>
                                <li><a href="/admin/{{$user_id}}/tricker_news">Floating News</a></li>
                                <li><a href="/admin/{{$user_id}}/pop_up">Pop up</a></li>                                
                                <!-- <li><a href="/admin/{{$user_id}}/feedback_form">Feedback Form</a></li>
                                <li><a href="/admin/{{$user_id}}/message_log">Message Log</a></li> -->
                                
                            </ul>
                        </li>
                        <li><a href="/admin/{{$user_id}}/member_management"><i class="mdi mdi-account-check"></i><span class="hide-menu">Member Management</span></a></li>
                        @endif
                        <li><a href="/admin/{{$user_id}}/qrcode_scan"><i class="mdi mdi-account-check"></i><span class="hide-menu">Queuescreen Management</span></a></li>                        
                        @if($user_obj->role_id == 2)
                        <li><a href="/admin/{{$user_id}}/admin_role"><i class="mdi mdi-account-check"></i><span class="hide-menu">Admin Role</span></a></li>
                        <!-- <li><a href="/admin/{{$user_id}}/member"><i class="mdi mdi-account-check"></i><span class="hide-menu">Member Manage</span></a></li> -->
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
                <!-- item-->
                <a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->
        </aside>
     
        <div class="page-wrapper">
            <!-- Container fluid  -->

           @yield('content')
           @yield('script_company')
           @yield('script_service')
           @yield('script_branch')
           @yield('script_panel_news')
           @yield('script_float_news')
           @yield('script_popup')
    
            <footer class="footer">
                © 2018 queuemart.me
            </footer>
        
        </div>
        
    </div>

    <script type="text/javascript">

        var select_option_user = '{{$user_id}}';
        var options_employee = $('select#employee_list option');

        for (var i = options_employee.length - 1; i >= 0; i--) {
            if(options_employee.eq(i).val() == select_option_user) {
                options_employee.eq(i).attr('selected', 'selected');
            }
        }

        $('select#employee_list').on('change', function(){
            location.href = '{{URL::to("admin")}}/'+$(this).val();
        })

        $('#edit_profile_form').on('submit', function(e) {
            e.preventDefault();            
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: new FormData($("#edit_profile_form")[0]),
                contentType: false,
                processData: false,
                success:function(data){
                    if (data == 'success') {
                        location.reload();
                    } else {
                        $('#errorMsg').text(data);
                    }
                }
            })
        })
    </script>

    <!-- All Jquery -->
    <!-- ============================================================== -->
    
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('assets/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('assets/js/sidebarmenu.js')}}"></script>
    <!--stickey kit -->
    <script src="{{asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('assets/js/custom.min.js')}}"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- <script src="{{asset('assets/plugins/chartist-js/dist/chartist.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script> -->
    <!-- Chart JS -->
    <!-- <script src="{{asset('assets/plugins/echarts/echarts-all.js')}}"></script> -->
    <script src="{{asset('assets/plugins/toast-master/js/jquery.toast.js')}}"></script>
    <!-- Chart JS -->

    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>    
      
    <script src="{{asset('assets/plugins/calendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/plugins/calendar/dist/jquery.fullcalendar.js')}}"></script>
    <script src="{{asset('assets/plugins/calendar/dist/cal-init.js')}}"></script>    
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    
    <script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>

    <!-- <script src="{{asset('assets/js/dashboard1.js')}}"></script> -->
    <!-- <script src="{{asset('assets/js/dashboard4.js')}}"></script> -->
    <script src="{{asset('assets/js/toastr.js')}}"></script>  

    <script src="{{asset('assets/js/jquery.thooClock.js')}}"></script>  
    <script src="{{asset('assets/js/app-dist.js')}}"></script>  

    <script>    
    // Daterange picker
    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-daterange-timepicker').daterangepicker({
        timePicker: true,
        format: 'MM/DD/YYYY h:mm A',
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-limit-datepicker').daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '06/01/2015',
        maxDate: '06/30/2015',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        dateLimit: {
            days: 6
        }
    });
    </script>

    <script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
    </script> 
     
    <script>
    $(document).ready(function() {

        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });    
    </script>    


    <!-- Style switcher -->
    <!-- ============================================================== -->
</body>
</html>