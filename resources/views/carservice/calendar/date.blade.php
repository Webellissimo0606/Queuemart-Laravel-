{{--booked , available , disabled --}}

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

    {{--check if first day in not monday--}}
    <?php if($day=="1"&&date('l', strtotime("$current_year-$current_month-$day"))!="Monday"): ?>
        <?php
            $number_to_get_to_monday=0;
            $prev_strtotime=strtotime(" -$number_to_get_to_monday day",strtotime("$current_year-$current_month-$day"));
        ?>
        <?php while(date('l',$prev_strtotime)!="Monday"): ?>
            <div class="book__calendar-dates-item">
                <label class="disabled">
                    <?php
                    //echo date('d', $prev_strtotime);
                    $number_to_get_to_monday++;
                    $prev_strtotime=strtotime(" -$number_to_get_to_monday day",strtotime("$current_year-$current_month-$day"));
                    ?>
                </label>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php
        $day_status="available";

        if(isset($all_holidays["$current_year-$current_month-$day"])){
            $day_status="disabled";
        }

        if ($current_month == date('m')) {
            if($day < date('d')) {
                $day_status="disabled";
            }
        }

        $day_name=date('l',strtotime("$current_year-$current_month-$day"));
        $day_name=strtolower($day_name);

        if(
            !$timeslot_for_service->{$day_name."_active"}
        ){
            $day_status="disabled";
        }
    ?>

    <div class="book__calendar-dates-item">
        <input type="radio" name="date" value="{{$current_year}}-{{$current_month}}-{{$day}}" {{$day_status=="disabled"?"disabled":""}} id="{{$month_name}}-{{$day}}">
        <label for="{{$month_name}}-{{$day}}" class="{{$day_status}} click_at_calendar" data-day_number="{{$day}}">
            {{$day}}
        </label>
    </div>


<?php endfor; ?>
