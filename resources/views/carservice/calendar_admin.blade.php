<?php
    $month_name=date('F', mktime(0, 0, 0, $current_month, 10));
?>

<input type="hidden" class="">


<div class="row book__date">
    <div class="col-12">
        <div class="book__date-inner">
            <div class="book__calendar">
                <div class="book__calendar-header">
                    <h3 class="u-color-black u-weight-bld">
                        {{$month_name}}, {{$current_year}}
                    </h3>
                    <div class="book__calendar-controls">
                        <div class="book__calendar-arrow book__calendar-arrow--prev book__calendar-arrow--available">
                            <p onclick="year_month_btn('{{($current_year)}}', '{{($current_month-1)}}')" style="text-align: center;display: block;height: 25px;width: 25px; margin-bottom: 0!important;">
                                <img src="{{asset('images/i-arrow.svg')}}" alt="Arrow" class="book__calendar-arrow-icon">
                            </p>
                        </div>
                        <div class="book__calendar-arrow book__calendar-arrow--next book__calendar-arrow--available">
                            <p onclick="year_month_btn('{{($current_year)}}', '{{($current_month+1)}}')" style="text-align: center;display: block;height: 25px;width: 25px; margin-bottom: 0!important;">
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
                        @include("carservice.calendar.date_admin")
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {


        var today = new Date().getDate().toString();
        if (today.length == 1) {
            var today = '0' + today;
        }

        var input_id = '{{$month_name}}-'+today;
        $('input#'+input_id).attr('checked', '');
        var selected_date = $('input#'+input_id).val();  
        var company_id = $('h3.company_id').attr('id'); 
        var branch_id = $('select#branch_list option:checked').val();
        $.get("{{URL::to('admin/getTimeslot')}}",
            {          
                company_id: company_id,  
                branch_id: branch_id,  
                date: selected_date
            }, function(data) {
                $('#timeslot_div').empty().html(data);
            });
        $('#date_display_view').text(selected_date);
        
        $(".click_at_calendar").click(function(){

            var day_number=$(this).data("day_number"); 
            var selected_date = $(this).parent('div').find('input').eq(0).val();
            var branch_id = $('select#branch_list option:checked').val();
            $('#date_display_view').text(selected_date);
            $('.preloader').css('display', 'block');
            $.get("{{URL::to('admin/getTimeslot')}}",
            {      
                company_id: company_id, 
                branch_id: branch_id,         
                date: selected_date
            }, function(data) {
                $('#timeslot_div').empty().html(data);
                $('#collapseOne').removeClass('show');
                if ($('#collapseOne').hasClass('show')) {
                    $('#calendar_icon').removeClass("fa fa-caret-up");
                    $('#calendar_icon').addClass("fa fa-caret-down");
                } else {
                    $('#calendar_icon').removeClass("fa fa-caret-down");
                    $('#calendar_icon').addClass("fa fa-caret-up");
                }
                $('.preloader').css('display', 'none');
            });          
        });

        $('select#branch_list').on('change', function(){
            var branch_id = $(this).val();
            $(this).attr('checked', '');
            var selected_date = $('#date_display_view').text();
            $('.preloader').css('display', 'block');
            $.get("{{URL::to('admin/getTimeslot')}}",
            {          
                company_id: company_id,  
                branch_id: branch_id,  
                date: selected_date
            }, function(data) {
                $('#timeslot_div').empty().html(data);
                $('.preloader').css('display', 'none');
            });         
        })

    });

</script>

