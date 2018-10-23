<?php
    $month_name=date('F', mktime(0, 0, 0, $current_month, 10));
?>

<input type="hidden" class="">


<div class="row book__date">
    <div class="col-12">
        <div class="book__date-inner">
            <h3>PICK DATE & TIME</h3>
            <div class="book__calendar">
                <div class="book__calendar-header">
                    <h1 class="u-color-black u-weight-bld">
                        {{$month_name}}, {{$current_year}}
                    </h1>
                    <div class="book__calendar-controls">
                        <div class="book__calendar-arrow book__calendar-arrow--prev book__calendar-arrow--available">
                            <p onclick="year_month_btn('{{($current_year)}}', '{{($current_month-1)}}')" style="text-align: center;display: block;height: 25px;width: 25px;">
                                <img src="{{asset('images/i-arrow.svg')}}" alt="Arrow" class="book__calendar-arrow-icon">
                            </p>
                        </div>
                        <div class="book__calendar-arrow book__calendar-arrow--next book__calendar-arrow--available">
                            <p onclick="year_month_btn('{{($current_year)}}', '{{($current_month+1)}}')" style="text-align: center;display: block;height: 25px;width: 25px;">
                                <img src="{{asset('images/i-arrow.svg')}}" alt="Arrow" class="book__calendar-arrow-icon">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="book__calendar-main">
                    <div class="book__calendar-labels">
                        <p class="paragrapg--huge">MO</p>
                        <p class="paragrapg--huge">TU</p>
                        <p class="paragrapg--huge">WE</p>
                        <p class="paragrapg--huge">TU</p>
                        <p class="paragrapg--huge">FR</p>
                        <p class="paragrapg--huge">SA</p>
                        <p class="paragrapg--huge">SU</p>
                    </div>
                    <div class="book__calendar-dates">
                        @include("carservice.calendar.date")
                    </div>
                </div>
            </div>
            <div class="book__references">
                <div class="book__references-item">
                    <div class="book__references-item-icon book__references-item-icon--primary u-margin-bottom-med"></div>
                    <h3 class="u-color-black u-weight-med u-text-transform-none">Fully Booked</h3>
                </div>
                <div class="book__references-item">
                    <div class="book__references-item-icon book__references-item-icon--primary-drk u-margin-bottom-med"></div>
                    <h3 class="u-color-black u-weight-med u-text-transform-none">Available</h3>
                </div>
            </div>
        </div>
    </div>
</div>


@include("carservice.calendar.time")

<script type="text/javascript">
    $(document).ready(function() {

        $(".click_at_calendar").click(function(){
            var day_number=$(this).data("day_number");
            if (!$(this).hasClass('disabled')) {

                $(".book__time").hide();

                $(".book_time_"+day_number).show();

                $("#client_notice_div").attr('hidden', '');
            }
        });

    });

</script>

