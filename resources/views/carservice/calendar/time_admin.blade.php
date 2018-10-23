<?php
    $day_name=date('l',strtotime("$select_date_t"));
    $day_name=strtolower($day_name);
?>

<table class="main">
    <thead>
        <?php 
            $service_counts = count($all_services_t);
            foreach ($all_services_t as $service) { ?>
            <th><?php echo $service->service_name; ?></th>
        <?php } ?>
    </thead>
    <tbody>        
        <tr style="min-height: 675px;">
            <?php foreach ($all_services_t as $service) { ?>
                <td class="timeslots_td" onclick="addAppointment('{{$service->id}}')" id="timeslots_td_{{$service->id}}">
                    <?php
                    foreach ($all_bookings_t as $booking) {
                        if ($service->id == $booking->service_id) {

                            $active_arrive = $booking->booking_status == 2||$booking->booking_status == 7 ? 'active' : '';
                            $disable_arrive = $booking->booking_status == 2||$booking->booking_status == 7 ? 'disabled' : '';

                            $arrived_check = $booking->arrived_check == 0 ? 'hidden' : '';

                            $arrived_check_o = $booking->arrived_check == 1 ? 'hidden' : '';

                            $active_completed = $booking->booking_status == 2 ? 'active' : '';
                            $disable_completed = $booking->booking_status != 7 ? 'disabled' : '';

                            $order_duration_display = '';

                            if ($booking->booking_status == 2) {
                                $order_duration_display = $booking->order_duration.'mins';
                            } 

                            else if ($booking->booking_status == 7) {
                                $order_duration_display = 'Working...';
                            }

                            else if ($booking->booking_status == 1 || $booking->booking_status == 4) {
                                $order_duration_display = $booking->booking_status_text;
                            }

                            print '<div class="client_div row" id="booking_div_'.$booking->id.'" style="z-index:100;" onclick="editOrder('.$booking->id.')">
                                <div class="col-12 order_info_one" id="back_color_'.$booking->booking_status_text.'"><p><span>Client Name :</span><span>'.$booking->name.'</span></p><p><span>Package Name :</span><span>'.$booking->package_name.'</span></p><p><span>Client Note :</span><span>'.$booking->client_notice.'</span></p><p><span>Appt Time :</span><span>'.(new DateTime($booking->appt_datetime))->format('h:i a').'</span></p></div>
                                <div class="col-12 order_check" id="order_check_'.$booking->id.'">';
                                if ($booking->booking_status != 5 && $booking->booking_status != 6) {
                                print '<button onclick="arriveCheck('.$booking->id.')" id="arrive_check_btn_'.$booking->id.'" type="button" class="btn btn-primary arrived_check_btn" data-toggle="button" '.$arrived_check_o.'>
                                        <i class="ti-location-arrow text" aria-hidden="true"></i>
                                        <i class="ti-location-arrow text-active" aria-hidden="true"></i>
                                    </button>
                                    <button onclick="arrivedCheck('.$booking->id.')" id="arrived_check_btn_'.$booking->id.'" type="button" class="btn btn-info '.$active_arrive.' arrived_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" '.$disable_arrive.' '.$arrived_check .'>
                                        <i class="ti-control-stop text" aria-hidden="true"></i>
                                        <i class="ti-control-play text-active" aria-hidden="true"></i>
                                    </button>
                                    <span id="order_duration'.$booking->id.'">'.$order_duration_display.'</span>
                                    <button onclick="completedCheck('.$booking->id.')" id="complete_check_btn_'.$booking->id.'" type="button" class="btn btn-success '.$active_completed.' complete_check_btn" data-toggle="button" data-more="#sh" aria-pressed="false" '.$disable_completed.'>
                                        <i class="ti-na text" aria-hidden="true"></i>
                                        <i class="ti-check text-active" aria-hidden="true"></i>
                                    </button>';
                                } else {
                                    print '<span style="margin:auto;">'.$booking->booking_status_text.'</span>';
                                }
                            print '</div>
                            </div>';
                        }
                    } ?>
                </td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<div id="modal_detail"></div>

<div id="modal_add_appt"></div>


<script type="text/javascript">

    var type = "";
    var type1 = false;
    function editOrder(id) {
        type = "small";
        if(type1 == true) {
            type1 = false;
            return;
        }
        $('.preloader').css('display', 'block');
        $.get('{{URL::to("admin/modalDetail")}}', { id:id }, function(data){
            $('#modal_detail').empty().html(data);
            $('#edit_order_model').modal('show');
            $('.preloader').css('display', 'none');
        });        
    }

    var service_count = '{{$service_counts}}';

    if (service_count > 3) {
        $('#timeslot_div').css('overflow-x', 'auto');
        $('#timeslot_div .main').css('width', 'auto');
        $('table.main thead tr th').css('width', '350px');
        $('table.main tbody tr td').css('width', '350px');
    }

    function arriveCheck(id) {
        type = "small";
        type1 = true;
        var arrived_check = confirm("This client has arrived and change the client status to 'Arrive'!");
        if (arrived_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateArrivedCheck")}}', { id:id }, function(data){
                if (data.status == 'success') {
                    $('#arrive_check_btn_'+data.orderData.id).attr('hidden', '');
                    $('#arrived_check_btn_'+data.orderData.id).removeAttr('hidden');
                    $('.preloader').css('display', 'none');
                }
            });
        }
    }

    function arrivedCheck(id) {
        type = "small";
        type1 = true;
        var arrive_check = confirm("The appointment for this client has started and change the client status to 'Working'!");
        if (arrive_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateOrder")}}', { id:id, booking_status: 7 }, function(data){
                if (data.status == 'success') {
                    $('#arrived_check_btn_'+id).attr('disabled', ''); 
                    $('#complete_check_btn_'+id).removeAttr('disabled'); 
                    $('#order_duration'+id).empty().text('Working...');
                    $('#booking_div_'+id+' .order_info_one').attr('id', 'back_color_Arrived');
                    $('.preloader').css('display', 'none');
                }
            });
        } else {  
            setTimeout(function() {
               $('#arrived_check_btn_'+id).removeClass('active'); 
            }, 10);
        }
    }

    function completedCheck(id) {
        type = "small";
        type1 = true;
        var completed_check = confirm("The appointment for this client has completed and change the client status to 'Complete'!");
        if (completed_check == true) {
            $('.preloader').css('display', 'block');
            $.get('{{URL::to("admin/updateOrder")}}', { id:id, booking_status: 2 }, function(data){
                if (data.status == 'success') {
                    $('#complete_check_btn_'+id).attr('disabled', ''); 
                    $('#order_duration'+id).empty().text(data.orderData.order_duration+'mins');
                    $('#booking_div_'+id+' .order_info_one').attr('id', 'back_color_Completed');
                    $('.preloader').css('display', 'none');
                }
            });
        } else {
            setTimeout(function() {
               $('#complete_check_btn_'+id).removeClass('active'); 
            }, 10);
        }
    }

    function addAppointment(id) {
        if(type == "small") {
            type = "";
            return;
        }
        $('.preloader').css('display', 'block');
        var company_id = $('h3.company_id').attr('id'); 
        $.get('{{URL::to("admin/addAppointment")}}', 
            { 
                id:id,
                company_id: company_id
            }, function(data){
            $('#modal_add_appt').empty().html(data);
            $('#add_appt_model').modal('show');
            $('.preloader').css('display', 'none');
        }); 
    }

</script>