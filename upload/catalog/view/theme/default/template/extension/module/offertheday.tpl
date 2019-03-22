<div id="offertheday">
<a href="">
		<img src="<?php echo $image; ?>" alt="" />
		<span id="offername"></span>
		<div id="offertimer"></div>
		<div class="prices">
			<span id="new-price"></span>
			<span id="old-price"></span>
			
		</div>
	</a>
</div>

<script>
function pretty_time_string(num) {
	return ( num < 10 ? "0" : "" ) + num;
}
   	var timezone = '<?php echo date("Z"); ?>';
		
function getter(){
	$.ajax({
		url: 'index.php?route=extension/module/offertheday',
		type: 'POST',
		dataType: 'json',
		success: function(item) {
			if(item != false) {
				$('#offername').text(item['product_name']);
				$('#new-price').text(item['new_price']);
				$('#old-price').text(item['old_price']);	
				$('#offertheday a').attr('href', item['href'].replace("&amp;", '&'));
				$('#offertheday').show(300);
				var start = new Date( item['date_end']);
			
				setInterval(function() {
					var total_seconds = ((start - new Date) / 1000) - timezone;   
				
					var days = Math.floor(total_seconds / 86400);
					total_seconds = total_seconds % 86400;

					var hours = Math.floor(total_seconds / 3600);
					total_seconds = total_seconds % 3600;

					var minutes = Math.floor(total_seconds / 60);
					total_seconds = total_seconds % 60;

					var seconds = Math.floor(total_seconds);

					days = pretty_time_string(days);
					hours = pretty_time_string(hours);
					minutes = pretty_time_string(minutes);
					seconds = pretty_time_string(seconds);

					if(days > 0){
						var currentTimeString = days + "d " + hours + ":" + minutes + ":" + seconds;
					} else {
						var currentTimeString = hours + ":" + minutes + ":" + seconds;
					}

					if (total_seconds > 0){
						$('#offertimer').text(currentTimeString);
					} else{
						getter();
					}
				}, 1000);
			} else {
				$('#offertheday').hide();
			}
		} //end ajax succes
	}); //end ajax	
} //end getter

$(document).ready(function() {
	getter();
});
</script>
