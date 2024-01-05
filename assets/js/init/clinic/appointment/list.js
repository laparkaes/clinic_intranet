$(document).ready(function() {
	//general
	var params = get_params();
	if (params.a == "add") $("#btn_add").trigger("click");
	
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
});