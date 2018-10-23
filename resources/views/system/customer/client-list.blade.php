@extends('layouts.system')

@section('content')
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Client List</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Client List</li>
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
        <div class="col-lg-12">
            <div class="card"> 
                <div class="card-body">
                    <h3 class="box-title">Seminar</h3>
                    <hr class="m-t-0">
                    <div class="table-responsive m-t-20">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Nationality</th>
                                    <th>I/C</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>         
                                    <th>Last Visit</th>
                                    <th>Numbers of Booking</th>
                                    <th>Total revenue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $value)
                                <tr id="{{$value['customer']->id}}">
                                    <td>{{$value['customer']->id}}</td>
                                    <td>{{$value['customer']->name}}</td>
                                    <td>{{$value['customer']->nationality}}</td>
                                    <td>{{$value['customer']->ic}}</td>
                                    <td>{{$value['customer']->email}}</td>
                                    <td>{{$value['customer']->phone_number}}</td>                
                                    <td>{{$value['last_visit']}}</td>
                                    <td>{{$value['numbers_booking']}}</td>
                                    <td>
                                        @if($value['total_revenue'] != 0)
                                        RM {{$value['total_revenue']}}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        <button onclick="booking_history_modal('{{$value['customer']->id}}')"><i class="profile fa fa-search text-primary"></i></button>
                                        <!-- <i class="delete fa fa-trash-o text-danger"></i> -->
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
    <div id="booking_history_modal"></div>    
    <div id="customer_edit_modal"></div>    

    <script type="text/javascript">
        function booking_history_modal($id){
        var id = $id;
        var company_id = '{{$user_id}}';
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/bookingHistory")}}', { id:id, company_id:company_id }, function(data){
                $('#booking_history_modal').empty().html(data);
                $('#booking_history').modal('show');
                $('.preloader').css('display', 'none');
            })
        }

        $( "#myTable tbody tr" ).dblclick(function() {
            var user_id = $(this).find('td').eq(0).text();
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/editCustomer")}}', { id:user_id }, function(data){
                $('#customer_edit_modal').empty().html(data);
                $('#customer_edit').modal('show');
                $('.preloader').css('display', 'none');
            })
        })
    </script>

    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection   