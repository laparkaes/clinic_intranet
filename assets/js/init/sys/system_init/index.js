

		/* start sunat access */
		function sunat_access_init(dom){
			ajax_form(dom, "sys/system_init/sunat").done(function(res) {
				set_msg(res.msgs);
				swal_redirection(res.type, res.msg, window.location.href);
			});
		}

		function remove_sunat(){
			ajax_simple_warning({}, "sys/system_init/remove_sunat", "wm_sunat_remove").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		}

		function test_sunat(){
			ajax_simple({}, "sys/system_init/test_sunat").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		}
		/* end sunat access */

		/* start sale type */
		function set_sale_types(sale_types){
			$(".row_sale_type").remove();
			$.each(sale_types, function(index, item) {
				$("#tbody_sale_types").append('<tr class="row_sale_type"><td>' + item.description + '</td><td>' + item.sunat_serie + '</td><td>' + item.start_factura + '</td><td>' + item.start_boleta + '</td><td class="text-right"><button type="button" class="btn btn-danger light btn_remove_sale_type" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			});
			
			$(".btn_remove_sale_type").on('click',(function(e) {remove_sale_type($(this).val());}));
		}

		function add_sale_type(dom){
			ajax_form(dom, "sys/system_init/add_sale_type").done(function(res) {
				set_msg(res.msgs);
				swal(res.type, res.msg);
				if (res.type == "success"){
					dom.reset();
					$("#btn_finish_sale_type").removeClass("d-none");
					set_sale_types(res.sale_types);
				}
				
			});
		}

		function remove_sale_type(id){
			ajax_simple_warning({id:id}, "sys/system_init/remove_sale_type", "wm_sale_type_remove").done(function(res) {
				swal(res.type, res.msg);
				if (res.type == "success"){
					$("#btn_finish_sale_type").removeClass("d-none");
					set_sale_types(res.sale_types);
				}
			});
		}

		function finish_sale_type(){
			ajax_simple_warning({}, "sys/system_init/finish_sale_type", "wm_sale_type_finish").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		}
		/* end sale type */

		function finish_init(){
			ajax_simple_warning({}, "sys/system_init/finish_init", "wm_sys_init_finish").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		}
