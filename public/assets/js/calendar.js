$(document).ready(function() {

    $(".click_at_calendar").click(function(){
        var day_number=$(this).data("day_number");

        $(".book__time").hide();

        $(".book_time_"+day_number).show();
    });
});