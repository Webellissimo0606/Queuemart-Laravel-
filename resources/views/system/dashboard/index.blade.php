@extends('layouts.system')

@section('content')
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>                    
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daily Sales</h4>
                    <div class="text-right">
                        <h2 class="font-light m-b-0">RM {{$sum_day}}</h2>
                    </div>                    
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Monthly Sales</h4>
                    <div class="text-right">
                        <h2 class="font-light m-b-0">RM {{$sum_month}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Yearly Sales</h4>
                    <div class="text-right">
                        <h2 class="font-light m-b-0">RM {{$sum_year}}</h2>
                    </div>                    
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-info">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white">
                        @if($nexmo_balance != '')
                            <?php $english_format_number = number_format($nexmo_balance->balance, 2, '.', ''); echo '$ '.$english_format_number; ?>
                        @endif
                    </h1>
                    <h6 class="text-white">SMS credit left</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-primary card-inverse">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{$total_today_booking}}</h1>
                    <h6 class="text-white">Number of booking today</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-success">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{$total_today_not_arrived}}</h1>
                    <h6 class="text-white">Number of booking not arrive yet</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-inverse card-warning">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{$rating_month}}</h1>
                    <h6 class="text-white">Feed Back in a month</h6>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
<!-- ============================================================== -->
@endsection 