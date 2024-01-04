(function($){
    $(function(){
        $('#id_0').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY hh:mm:ss A",
        });
        $('#id_1').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY HH:mm:ss",
        });
        $('#id_2').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "hh:mm:ss A",
        });
        $('#id_3').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "HH:mm:ss",
        });
        $('#id_4').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });
        $('#id_5').datetimepicker({
			"allowInputToggle": true,
			"showClose": false,
			"showClear": false,
			"showTodayButton": true,
			"format": "YYYY-MM-DD",
			locale: 'es',
			icons: {
				time: "bi bi-clock",
				date: "bi bi-calendar",
				up: "bi bi-chevron-up",
				down: "bi bi-chevron-down",
				previous: "bi bi-chevron-left",
				next: "bi bi-chevron-right",
				today: "bi bi-calendar",
				clear: "bi bi-trash",
				close: "bi bi-x",
			}
		});
    });
})(jQuery);
