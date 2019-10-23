$(function() {

	$(".camp-var").keyup(function() {
		$(".camp-var").trigger({type:"change"});
	})	

	$(".camp-var").change(function() {
		var nombre = $(this).attr("name");
		if( $(this).val() == "" ){
			$("."+nombre).css("opacity",1);
		}else{
			$("."+nombre).css("opacity",0);
		}
	})
})
