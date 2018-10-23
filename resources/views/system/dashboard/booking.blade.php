@extends('layouts.system')

@section('content')
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Bookings Carservice</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Bookings Carservice</li>
            </ol>
        </div>                    
    </div> -->
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="col-4" hidden>
        <label>Company Name:</label>
        <h3 class="company_id" id="{{$company->user_id}}">{{$company->company_name}}</h3>
    </div>
    <div class="row" style="height: 70px; position: relative;">
        <div class="col-md-3" id="accordion">            
            <div class="card card-outline-primary">
                <div onclick="occordionClick()" class="card-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                    <span id="date_display_view" style="color: white; font-weight: bold;"></span>
                    <a class="card-link fa fa-caret-down" data-toggle="collapse" id="calendar_icon" style="font-size: 30px;">
                    </a>
                </div>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <div id="calendar_div"></div>
                    </div>
                </div>
            </div>

        </div>  

        <div class="col-md-3 offset-md-4" style=" z-index: 1049;">
            <select class="custom-select" id="branch_list" style="height: calc(2.25rem + 15px); background: yellow!important; font-weight: bold; color: black;">
                @foreach($branches as $value)
                <option value="{{$value->id}}">{{$value->branch_name}}</option>
                @endforeach               
            </select>
        </div> 

        <div class="col-md-3 offset-md-9" id="accordion">  
            <div class="card card-outline-success">
                <div onclick="occordionClick1()" class="card-header" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                    <span style="color: black; font-weight: bold;">LEGEND</span>
                    <a class="card-link fa fa-caret-down" data-toggle="collapse" id="calendar_icon1" style="font-size: 30px;">
                    </a>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>BOOKED</span></div>
                            <div class="col-6" style="background: #ffc107; border: 1px solid gray; border-left: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>PAID</span></div>
                            <div class="col-6" style="background: #fd7e14; border: 1px solid gray; border-left: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>ARRIVED</span></div>
                            <div class="col-6" style="background: #7460ee; border: 1px solid gray; border-left: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>CALL QUEUE</span></div>
                            <div class="col-6" style="background: #07b191; border: 1px solid gray; border-left: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>COMPLETED</span></div>
                            <div class="col-6" style="background: #76c100; border: 1px solid gray; border-left: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col-6" style="padding: 5px 10px; border: 1px solid gray; font-weight: bold;"><span>CANCELLED</span></div>
                            <div class="col-6" style="background: #f62d51; border: 1px solid gray; border-left: none;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div> 

    </div>

    <div class="row">        
        <div class="col-md-12" id="timeslot_div"></div>                
    </div>

    
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>

<script type="text/javascript">
    
    $.get("{{URL::to('admin/getCalendar')}}",
    {
        company_url: '{{$company->company_url}}',
        selected_month: new Date().getMonth()+1,
        selected_year: new Date().getFullYear()
    }, function(data) {
        $('#calendar_div').empty().html(data);
    });

    function year_month_btn(year, month){
        $.get("{{URL::to('admin/getCalendar')}}",
        {
            company_url: '{{$company->company_url}}',
            selected_month: month,
            selected_year: year
        }, function(data) {
            $('#calendar_div').empty().html(data);
        });
    } 

    function occordionClick() {
        if ($('#collapseOne').hasClass('show')) {
            $('#calendar_icon').removeClass("fa fa-caret-up");
            $('#calendar_icon').addClass("fa fa-caret-down");
            $('#collapseOne').removeClass('show');
        } else {
            $('#calendar_icon').removeClass("fa fa-caret-down");
            $('#calendar_icon').addClass("fa fa-caret-up");
            $('#collapseOne').addClass('show');
        }
    }

    function occordionClick1() {
        if ($('#collapseTwo').hasClass('show')) {
            $('#calendar_icon1').removeClass("fa fa-caret-up");
            $('#calendar_icon1').addClass("fa fa-caret-down");
            $('#collapseTwo').removeClass('show');
        } else {
            $('#calendar_icon1').removeClass("fa fa-caret-down");
            $('#calendar_icon1').addClass("fa fa-caret-up");
            $('#collapseTwo').addClass('show');
        }
    }


</script>

@endsection 