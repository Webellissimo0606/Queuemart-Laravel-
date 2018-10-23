@extends('layouts.system')

@section('content')
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Booking Log</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Booking Log</li>
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
        <div class="col-2">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Candidates are here!</h4>
                    <div class="text-center">
                        <h2 class="font-light m-b-0" id="arrived_text">{{$arrived_count}}</h2>
                        <span class="text-muted">Arrived</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Candidates are not here yet!</h4>
                    <div class="row text-center">
                        <div class="col-12">
                            <h2 class="font-light m-b-0" id="paid_text">{{$paid_count}}</h2>
                            <span class="text-muted">Paid</span>
                        </div>                                               
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Candidates have not made payment yet!</h4>
                    <div class="text-center">
                        <h2 class="font-light m-b-0" id="booked_text">{{$booked_count}}</h2>
                        <span class="text-muted">Booked</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Appointment has been cancelled or completed</h4>
                    <div class="row text-center">
                        <div class="col-4">
                            <h2 class="font-light m-b-0">{{$cancelledbyadmin_count}}</h2>
                            <span class="text-muted">CancelledByAdmin</span>
                        </div>
                        <div class="col-4">
                            <h2 class="font-light m-b-0">{{$cancelledbyclient_count}}</h2>
                            <span class="text-muted">CancelledByClient</span>
                        </div> 
                        <div class="col-4">
                            <h2 class="font-light m-b-0">{{$completedOrder_count}}</h2>
                            <span class="text-muted">Completed</span>
                        </div>                       
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Appt. ID</th>
                                    <th>Appt Date Time</th>
                                    <th>Client Name</th>
                                    <th>Branch Name</th>                                    
                                    <th>Sevice Name</th>
                                    <th>Package Name</th>
                                    <th>Status</th>
                                    <th id="action_time_triger">Action Time</th>
                                    <th>Action By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $value)
                                <tr>
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->appt_datetime}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->branch_name}}</td>
                                    <td>{{$value->service_name}}</td>                                    
                                    <td>{{$value->package_name}}</td>                                    
                                    <td><span class="span-{{$value->booking_status}}">{{$value->booking_status}}</span></td>                                    
                                    <td>{{$value->updated_at}}</td>     
                                    <td>{{$value->manager_name}}</td>     
                                </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function(){
          $('#action_time_triger').trigger( "click" );
          setTimeout(function(){
              $('#action_time_triger').trigger( "click" );
            }, 10);  
        }, 10);        
    })
</script>
<!-- ============================================================== -->
@endsection 