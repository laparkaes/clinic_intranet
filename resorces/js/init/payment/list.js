$(document).ready(function() {
	var table = $('#payment_list').DataTable({
		pageLength: 25,
		language: {
			lengthMenu: "_MENU_",
			search: "",
			searchPlaceholder: "Buscar",
			zeroRecords: "<span class='text-danger'>No hay registro</span>",
			info: "_START_ - _END_ / _TOTAL_",
			infoEmpty: "0",
			infoFiltered: "/ Total: _MAX_",
			paginate: { 
				first: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>', 
				previous: '<i class="fa fa-angle-left" aria-hidden="true"></i>', 
				next: '<i class="fa fa-angle-right" aria-hidden="true"></i>', 
				last: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>'
			}
		}, 
    });
});