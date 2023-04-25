$(document).ready(function() {
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	$('#gr_to').bootstrapMaterialDatePicker({
		weekStart: 0, format: 'DD/MM/YYYY', time: false
	}).on('change', function(e, date) {
		$('#gr_from').bootstrapMaterialDatePicker('setMaxDate', date);
	}); 

	$('#gr_from').bootstrapMaterialDatePicker({
		weekStart: 0, format: 'DD/MM/YYYY', time: false
	}).on('change', function(e, date) {
		$('#gr_to').bootstrapMaterialDatePicker('setMinDate', date);
	}); 
	
});