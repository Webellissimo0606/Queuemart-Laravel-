<?php
    if(strlen($current_month) < 2){
        $current_month="0".$current_month;
    }
?>

<?php for($day=1;$day<=$number_of_days;$day++): ?>
    <?php
        if($day<10){
            $day="0".$day;
        }
    ?>

    <div class="row book__time book_time_{{$day}}" style="display: none;">
        <div class="col-12">
            <div class="book__time-inner">
                <div class="book__time-grid">

                    <?php
                        $day_name=date('l',strtotime("$current_year-$current_month-$day"));
                        $day_name=strtolower($day_name);
                        if(
                            !$timeslot_for_service->{$day_name."_active"} ||
                            $timeslot_for_service->{$day_name."_val"} == null
                        ){
                            echo "service is not available this day";
                        }
                    ?>

                    <?php if(
                        $timeslot_for_service->{$day_name."_active"}&&
                        $timeslot_for_service->{$day_name."_val"} != null
                    ): ?>

                        <?php 
                            $timeslots = explode(',', $timeslot_for_service->{$day_name."_val"});
                        ?>


                        <?php for($i=0;$i<count($timeslots);$i++): ?>
                            <?php
                                $time=date_create($timeslots[$i].':00');

                                $time_val=$time->format("H:i");
                                $time=$time->format("g:i a");

                                $disabled="";
                                if(
                                    isset($all_bookings["$current_year-$current_month-$day"])
                                ){
                                    $this_day_bookings=$all_bookings["$current_year-$current_month-$day"]->groupBy("booking_time");


                                    if(
                                        isset($this_day_bookings[$time_val.":00"])&&
                                        $this_day_bookings[$time_val.":00"]->count()==$service_obj->service_participants
                                    ){
                                        $disabled="disabled";
                                    }

                                }

                                if(
                                    isset($all_bookings_p["$current_year-$current_month-$day"])
                                ){
                                    $this_day_bookings_p=$all_bookings_p["$current_year-$current_month-$day"]->groupBy("booking_time");


                                    if(
                                        isset($this_day_bookings_p[$time_val.":00"])&&
                                        $this_day_bookings_p[$time_val.":00"]->count()==$package_obj->package_participants
                                    ){
                                        $disabled="disabled";
                                    }

                                }
                                $today = now()->format("Y-m-d");

                                if ($today == "$current_year-$current_month-$day") {
                                    $today_time = now()->format("H:i");
                                    if ($today_time > $time_val.":00") {
                                        $disabled="disabled";
                                    }
                                }
                            ?>
                            <div class="book__time-item">
                                <input type="radio" name="book_time" value="{{$time_val}}:00" id="{{$day}}_{{$i}}" {{$disabled}}>
                                <label for="{{$day}}_{{$i}}" class="{{$disabled=="disabled"?"booked":""}}">
                                    {{$time}}
                                </label>
                            </div>
                        <?php endfor; ?>

                    <?php endif; ?>

                </div>
                <div class="book__references">
                    <div class="book__references-item">
                        <div class="book__references-item-icon book__references-item-icon--primary u-margin-bottom-med"></div>
                        <h3 class="u-color-black u-weight-med u-text-transform-none">Fully Booked</h3>
                    </div>
                    <div class="book__references-item">
                        <div class="book__references-item-icon book__references-item-icon--secondary u-margin-bottom-med"></div>
                        <h3 class="u-color-black u-weight-med u-text-transform-none">Available</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endfor; ?>

<script type="text/javascript">
    $(document).ready(function() {

        $(".book__time-item").click(function(){

            $("#client_notice_div").removeAttr('hidden');
        });

    });

</script>