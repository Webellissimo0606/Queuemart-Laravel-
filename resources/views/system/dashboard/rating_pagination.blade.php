<div id="load" style="position: relative;">
@foreach($ratings as $rating)
    <div class="comment-text w-100" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid #e4e4e4cc;">
        <h6>{{$rating->name}}</h6>
        <p class="m-b-5" style="font-size: 10px; color: gray;">{{$rating->rating_text}}</p>
        <p class="m-b-5" style="font-size: 10px; color: gray;"><?php echo (new DateTime($rating->appt_datetime))->format('j M Y h:i A'); ?></p>
        <div class="comment-footer">
            <span class="text-muted pull-right" style="font-size: 12px;"><?php echo (new DateTime($rating->created_at))->format('j M Y h:i A'); ?></span>
            <span class="label">
            	<?php $nums = (int)$rating->rating_star; for ($i=0; $i < $nums; $i++) { ?>
        			<i class="fa fa-star text-warning"></i>
            	<?php } ?>
            	<?php $nums_1 = 5 - (int)$rating->rating_star; for ($j=0; $j < $nums_1; $j++) { ?>
        			<i class="fa fa-star-o text-warning"></i>
            	<?php } ?>
            </span>
        </div>
    </div>
@endforeach
</div>
{{ $ratings->links() }}


<script type="text/javascript">

	$(function() {
		  $('body').on('click', '.pagination a', function(e) {
		      e.preventDefault();

		      $('#load a').css('color', '#dfecf6');
		      $('#load').append('<svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle> </svg>');

		      var url = $(this).attr('href');
		      getArticles(url);
		      window.history.pushState("", "", url);
		  });

		  function getArticles(url) {
		      $.ajax({
		          url : url
		      }).done(function (data) {
		          $('.articles').html(data);
		      }).fail(function () {
		          alert('Articles could not be loaded.');
		      });
		  }
	});
</script>