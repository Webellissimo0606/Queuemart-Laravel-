<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
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
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h2 class="font-light m-b-0" id="arrived_text">{{$total_count}}</h2>
                    <span class="text-muted">Total Bookings</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="timeslots_td" style="height: 50vh; overflow: auto;">
    <?php 
    foreach ($services_view as $service) { 
        $flag = 0;                          
        foreach ($all_bookings as $booking) {
            $arrived_check = $booking->arrived_check == 1 ? 'arrived_div' : '';
            $client_notice = '';

            $bookingStatus = $booking->booking_status_text;
            if ($booking->arrived_check == 1) {
                $bookingStatus = 'Arrived';
            }
            if ($booking->booking_status_text == 'Arrived') {
                $bookingStatus = 'Working';
            }
            if ($queuescreen->display_client == 1) {
                $client_notice = $booking->client_notice;
            }

            if ($booking->service_name == $service->service_name) {
                if ($flag == 0) {
                    print '<h3>'.$service->service_name.'</h3>';
                    $flag = 1;
                }            
            print '<div class="client_div row '.$arrived_check.'" id="booking_div_'.$booking->id.'">
                <div class="col-8 order_info" id="back_color_'.$booking->booking_status_text.'"><span>'.$booking->name.'</span><span>'.$client_notice.'</span><span>'.(new DateTime($booking->appt_datetime))->format('h:i a').'</span></div>
                <div class="col-4 order_check" id="order_check_'.$booking->id.'">';
                print '<span style="margin:auto;">'.$bookingStatus.'</span>';
            print '</div>
            </div>';
    } } }?>
</div>

<div class="row">
    <div class="col-4" style="border: 3px solid black; padding: 10px;color: white;font-weight: bold;font-size: 15px; text-align: center;background-color: #ffc107;">Booked</div>
    <div class="col-4" style="border: 3px solid black; padding: 10px;color: white;font-weight: bold;font-size: 15px; text-align: center;background-color: #fd7e14;">Paid</div>
    <div class="col-4" style="border: 3px solid green; padding: 10px;color: black;font-weight: bold;font-size: 15px; text-align: center;background-color: white;">Arrived</div>    
</div>