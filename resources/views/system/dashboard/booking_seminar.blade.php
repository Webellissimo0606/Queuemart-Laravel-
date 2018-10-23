@extends('layouts.system')

@section('content')
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Booking Seminar</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Booking Seminar</li>
            </ol>
        </div>                    
    </div> -->
    <div class="row" style="margin-bottom: 20px;">                        
        <div class="col-12">
            <select class="custom-select" id="branch_service_list">
                @foreach($branch_services as $value)
                <option value="{{$value->id}}">{{$value->branch_name}}, {{$value->service_name}}</option>
                @endforeach               
            </select>
        </div>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-4">
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
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Candidates are not here yet!</h4>
                    <div class="row text-center">
                        <div class="col-12">
                            <h2 class="font-light m-b-0" id="paid_text">{{$paid_count}}</h2>
                            <span class="text-muted">Paid</span>
                        </div>
                        <!-- <div class="col-6">
                            <h2 class="font-light m-b-0" id="bookednofee_text">0</h2>
                            <span class="text-muted">BookedNoFee</span>
                        </div>  -->                       
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-4">
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
        <!-- Column -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-success" onclick="addAppointmentSeminar('{{$user_id}}', '{{$selected_id}}')">Add New Appt</button>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th id="appt_id_triger">Appt. ID</th>
                                    <th>Actual Datetime</th>
                                    <th>Client Name</th>
                                    <th>Branch Name</th>                                    
                                    <th>Sevice Name</th>
                                    <th>Package Name</th>
                                    <th>Status</th>
                                    <th>Action Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="branch_service_tbody">
                                @foreach($bookings_all as $value)
                                <tr>
                                    <td>{{$value->id}}</td>
                                    <td>{{$value->appt_datetime}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->branch_name}}</td>
                                    <td>{{$value->service_name}}</td>                                    
                                    <td>{{$value->package_name}}</td>                                    
                                    <td><span class="span-{{$value->booking_status_text}}">{{$value->booking_status_text}}</span></td>
                                    <td>{{$value->updated_at}}</td>     
                                    <td style="display: flex; align-items: center; justify-content: space-between;">
                                        @if($value->booking_status == 1)
                                        <button title="Paid" onclick="paidCheck('{{$value->id}}')" type="button" class="btn btn-info arrived_check_btn">
                                            <i class="ti-money text" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if($value->arrived_check == 0)
                                        <button title="Arrived" onclick="arriveCheck('{{$value->id}}')" type="button" class="btn btn-primary arrived_check_btn">
                                            <i class="ti-location-arrow text" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if($value->arrived_check == 1)
                                        <button title="Completed" onclick="completedCheck('{{$value->id}}')" type="button" class="btn btn-success complete_check_btn">
                                            <i class="ti-check text" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if($value->arrived_check == 0)
                                        <button title="Cancel" onclick="cancelCheck('{{$value->id}}')" type="button" class="btn btn-danger complete_check_btn">
                                            <i class="ti-close text" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        <button title="View" onclick="viewCheck('{{$value->id}}')" type="button" class="btn btn-warning complete_check_btn">
                                            <i class="ti-comment-alt text" aria-hidden="true"></i>
                                        </button>
                                    </td>     
                                </tr>
                                @endforeach  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
</div>

<div id="modal_add_appt_seminar"></div>

<div id="modal_detail_seminar"></div>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function(){
          $('#appt_id_triger').trigger( "click" );  
        }, 10);               
    })
</script>

<script type="text/javascript">

    var select_option_branch_service = '{{$selected_id}}';
    var options_branch_service = $('select#branch_service_list option');

    for (var i = options_branch_service.length - 1; i >= 0; i--) {
        if(options_branch_service.eq(i).val() == select_option_branch_service) {
            options_branch_service.eq(i).attr('selected', 'selected');
        }
    }

    var user_id = '{{$user_id}}';

    $('select#branch_service_list').on('change', function(){
        location.href = '{{URL::to("admin")}}/'+user_id+'/booking_seminar/'+$(this).val();
    });


    function addAppointmentSeminar(company_id, selected_id) {
        
        $('.preloader').css('display', 'block');
        $.get('{{URL::to("admin/addAppointmentSeminar")}}', 
            { 
                selected_id:selected_id,
                company_id: company_id
            }, function(data){
            $('#modal_add_appt_seminar').empty().html(data);
            $('#add_appt_seminar_model').modal('show');
            $('.preloader').css('display', 'none');
        }); 
    }

    function arriveCheck(id) {
        var arrived_check = confirm("This client has arrived and change the client status to 'Arrive'!");
        if (arrived_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateArrivedCheck")}}', { id:id }, function(data){
                if (data.status == 'success') {                    
                    $('.preloader').css('display', 'none');
                    location.reload();
                }
            });
        }
    }

    function completedCheck(id) {
        var completed_check = confirm("The appointment for this client has completed and change the client status to 'Complete'!");
        if (completed_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateOrder")}}', { id:id, booking_status: 2 }, function(data){
                if (data.status == 'success') {                    
                    $('.preloader').css('display', 'none');
                    location.reload();
                }
            });
        }
    }

    function paidCheck(id) {
        var paid_check = confirm("This client has made the payment and change the client status to 'Paid'!");
        if (paid_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/getPrice")}}', { id:id }, function(data){
                $.get('{{URL::to("admin/updateOrder")}}', { id:id, booking_status: 4, pay_price: data }, function(data){
                    if (data.status == 'success') {                    
                        $('.preloader').css('display', 'none');
                        location.reload();
                    }
                });
            });
            
        }
    }

    function cancelCheck(id) {
        var cancel_check = confirm("The appointment for this client has cancelled and change the client status to 'CancelledByAdmin'!");
        if (cancel_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateOrder")}}', { id:id, booking_status: 6 }, function(data){
                if (data.status == 'success') {                    
                    $('.preloader').css('display', 'none');
                    location.reload();
                }
            });
        }
    }

    function viewCheck(id) {        
        $('.preloader').css('display', 'block');
        $.get('{{URL::to("admin/modalDetailSeminar")}}', { id:id }, function(data){
            $('#modal_detail_seminar').empty().html(data);
            $('#edit_order_model').modal('show');
            $('.preloader').css('display', 'none');
        });        
    }

    
</script>
@endsection 