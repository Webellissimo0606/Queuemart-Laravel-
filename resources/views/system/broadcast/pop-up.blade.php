@extends('layouts.system')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Pop Up</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Pop Up</li>
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
                    <form action="{{ URL::to('admin/updatePopup') }}" method="POST" id="update_popup_form" enctype="multipart/form-data" class="form-horizontal">
                        <input type="text" name="id" id="id" value="{{$popup->id}}" hidden>
                        <div class="form-body">
                            <h3 class="box-title">Additional Remark</h3>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Question:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="question" id="question" value="{{$popup->question}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Answer:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="answer" id="answer">{{$popup->answer}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <h3 class="box-title">Pop Up Reminders – Before Booking</h3>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="before_popup_title" id="before_popup_title" value="{{$popup->before_popup_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="before_popup_des" id="before_popup_des">{{$popup->before_popup_des}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="box-title">Pop Up Reminders – After Booking</h3>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success col-12">                                    
                                        <input id="sms_after_booking" type="checkbox" name="sms_after_booking" value="{{$popup->sms_after_booking}}">
                                        <label for="sms_after_booking">WhatsApp Reminder</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="after_popup_title" id="after_popup_title" value="{{$popup->after_popup_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="after_popup_des" id="after_popup_des">{{$popup->after_popup_des}}</textarea>
                                            <p><span class="hashtag">#NoHoldCountDown</span> (Will be replaced by Number of Hour(s) before appointment where the holding time is 15 minutes); Value= <input style="width: 50px;" type="number" name="noholdcountdown" value="{{$popup->noholdcountdown}}"> hours</p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="box-title">Home Top Bar – Payment</h3>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success col-12">                                    
                                        <input id="sms_payment" type="checkbox" name="sms_payment" value="{{$popup->sms_payment}}">
                                        <label for="sms_payment">WhatsApp Reminder</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="home_top_1_title" id="home_top_1_title" value="{{$popup->home_top_1_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="home_top_1_des" id="home_top_1_des">{{$popup->home_top_1_des}}</textarea>
                                            <p><span class="hashtag">#HoldingHours</span> (Will be replaced by Number of hour(s) that the user gets to hold the appointment); Value= <input style="width: 50px;" type="number" name="holdinghours" value="{{$popup->holdinghours}}"> hours </p>
                                            <p><span class="hashtag">#HoldingHoursRemain</span> = #HoldingHours-(#TimeNow-#BookingDateTime) </p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="box-title">Home Top Bar – CancelledByAdmin</h3>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success col-12">                                    
                                        <input id="sms_cancel_admin" type="checkbox" name="sms_cancel_admin" value="{{$popup->sms_cancel_admin}}">
                                        <label for="sms_cancel_admin">WhatsApp Reminder</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="home_top_2_title" id="home_top_2_title" value="{{$popup->home_top_2_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="home_top_2_des" id="home_top_2_des">{{$popup->home_top_2_des}}</textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="box-title">Home Top Bar – CancelledByClient</h3>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success col-12">                                    
                                        <input id="sms_cancel_client" type="checkbox" name="sms_cancel_client" value="{{$popup->sms_cancel_client}}">
                                        <label for="sms_cancel_client">WhatsApp Reminder</label>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="home_top_4_title" id="home_top_4_title" value="{{$popup->home_top_4_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="home_top_4_des" id="home_top_4_des">{{$popup->home_top_4_des}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="box-title">Home Top Bar 4</h3>
                                <div class="form-group">
                                    <div class="checkbox checkbox-success col-12">                                    
                                        <input id="sms_near_appt" type="checkbox" name="sms_near_appt" value="{{$popup->sms_near_appt}}">
                                        <label for="sms_near_appt">WhatsApp Reminder</label>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="m-t-0 m-b-20">
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Title:</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="home_top_3_title" id="home_top_3_title" value="{{$popup->home_top_3_title}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-2">Description:</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" name="home_top_3_des" id="home_top_3_des">{{$popup->home_top_3_des}}</textarea>
                                            <p><span class="hashtag">#DaysB4Appointment</span> (Will be replaced by Number of day(s) before appointment) Value= <input style="width: 50px;" type="number" name="daysb4appointment" value="{{$popup->daysb4appointment}}"> day(s) </p>
                                            <p><span class="hashtag">#AppointmentDateTime</span> (Will be replaced by the actual appointment date and time) </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                         
                        </div>                                    
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                            <button type="button" class="btn btn-warning" id="popup_default" data-id="{{ $popup->id }}">Default</button>
                                        </div>                                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </form> 
                </div>
            </div>
        </div>                    
    </div>
    <!-- Row -->               
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->                
</div>
@endsection 

@section('script_popup')

    <script type="text/javascript"> 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sms_after_booking = $('#sms_after_booking');
        if (sms_after_booking.val() == 1) {
            sms_after_booking.attr('checked', true);
        } else {
            sms_after_booking.attr('checked', false);
        }

        sms_after_booking.change(function(){
            if (sms_after_booking.val() == 1) {
                sms_after_booking.val(0);
            } else {
                sms_after_booking.val(1);
            }
        })

        var sms_payment = $('#sms_payment');
        if (sms_payment.val() == 1) {
            sms_payment.attr('checked', true);
        } else {
            sms_payment.attr('checked', false);
        }

        sms_payment.change(function(){
            if (sms_payment.val() == 1) {
                sms_payment.val(0);
            } else {
                sms_payment.val(1);
            }
        })

        var sms_cancel_admin = $('#sms_cancel_admin');
        if (sms_cancel_admin.val() == 1) {
            sms_cancel_admin.attr('checked', true);
        } else {
            sms_cancel_admin.attr('checked', false);
        }

        sms_cancel_admin.change(function(){
            if (sms_cancel_admin.val() == 1) {
                sms_cancel_admin.val(0);
            } else {
                sms_cancel_admin.val(1);
            }
        })

        var sms_cancel_client = $('#sms_cancel_client');
        if (sms_cancel_client.val() == 1) {
            sms_cancel_client.attr('checked', true);
        } else {
            sms_cancel_client.attr('checked', false);
        }

        sms_cancel_client.change(function(){
            if (sms_cancel_client.val() == 1) {
                sms_cancel_client.val(0);
            } else {
                sms_cancel_client.val(1);
            }
        })

        var sms_near_appt = $('#sms_near_appt');
        if (sms_near_appt.val() == 1) {
            sms_near_appt.attr('checked', true);
        } else {
            sms_near_appt.attr('checked', false);
        }
        sms_near_appt.change(function(){            
            if (sms_near_appt.val() == 1) {
                sms_near_appt.val(0);
            } else {
                sms_near_appt.val(1);
            }
        })



        $('#update_popup_form').on('submit', function(e) {
            
            e.preventDefault();
            var data = $(this).serialize();
            var url = $(this).attr('action');
            var post = $(this).attr('method');
            $.ajax({
                type : post,
                url : url,
                data: data,
                dataTy:'json',
                success:function(data){
                        alert('Update Success!');                        
                    }
                })
        });

        $('body').delegate('#popup_default', 'click', function(e) {
            var id = $(this).data('id');
            $.post('{{URL::to("admin/updatePopup")}}', 
                { 
                    id:id,
                    question : 'What is your car plate number, color and car model?',
                    answer : 'VE 1002, Black, Toyota Vios',
                    before_popup_title : 'Be on time!',
                    before_popup_des : 'Please be reminded to arrive 30mins before your appointment time to avoid disappointment. Normally we are fully packed and your slot will be taken if you are late for 10 mins. Thanks!',
                    after_popup_title : '( #TimeNow - #bookingDateTime ) left, please complete your payment!',
                    after_popup_des : 'You are less than #NoHoldCountDown hours away from the appointment, please complete your payment within 15 mins to avoid auto-cancellation.',
                    home_top_1_title : 'Make Payment!',
                    home_top_1_des : 'Please be reminded to make payment. You have #HoldingHoursRemain hours remaining before your appointment is automatically cancelled.',
                    home_top_2_title : 'Your appointment has been cancelled!',
                    home_top_2_des : 'Please make your payment in time in the future. You can now reschedule from the system.',
                    home_top_4_title : 'Your appointment has been cancelled!',
                    home_top_4_des : 'Your appointment has been cancelled by yourself.',
                    home_top_3_title : 'Your Appointment is near!',
                    home_top_3_des : 'Please be reminded about your appointment on #AppointmentDateTime ',
                    noholdcountdown : 24,
                    holdinghours : 48,
                    daysb4appointment : 1,
                    sms_after_booking : 0,
                    sms_payment : 0,
                    sms_cancel_admin : 0,
                    sms_cancel_client : 0,
                    sms_near_appt : 0,
                }, function(data){
                alert('Update Success!'); 
                location.reload();                   
            })
        })

    </script>

@endsection 