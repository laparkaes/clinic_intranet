var default_lang = "sp";

//component_list[default_lang].key or component_list[default_lang][key]
var component_list = {
	sp: {
		alert_success_title		: '¡ Éxito !',
		alert_error_title		: '¡ Error !',
		alert_warning_title		: '¡ Un Momento !',
		alert_confirm_btn		: 'Confirmar',
		alert_cancel_btn		: 'Cancelar',
		j_datatable_search		: 'Buscar',
		j_datatable_no_record	: 'No hay registro',
		bd_select				: 'Elegir',
		bd_cancel				: 'Cancelar',
		bd_clean				: 'Limpiar',
	},
}

//msg_list[default_lang].key
var msg_list = {
	sp: {
		//system init
		wm_company_remove	: '¿Desea eliminar empresa?',
		wm_account_remove	: '¿Desea eliminar usuario?',
		wm_sunat_remove		: '¿Desea eliminar datos de Sunat?',
		wm_sale_type_remove	: '¿Desea eliminar tipo de venta?',
		wm_sale_type_finish	: '¿Desea finalizar configuracion de tipo de ventas?',
		wm_sys_init_finish	: '¿Desea finalizar inicialización del sistema?',
		
		//auth
		wm_change_password	: 'Deberá ingresar de nuevo después de aplicar el cambio.',
		
		//doctor
		wm_doctor_register	: "¿Desea registrar nuevo médico?",
		wm_disable_doctor 	: '¿Está seguro de desactivar médico?',
		wm_enable_doctor	: '¿Está seguro de activar médico?',
		
		//patient
		wm_patient_register	: "¿Desea registrar nuevo patiente?",
		wm_register_app		: '¿Desea generar nueva consulta del paciente?',
		wm_register_sur		: '¿Desea generar nueva cirugía del paciente?',
		wm_add_credit		: '¿Desea agregar credito al paciente?',
		wm_reverse_credit	: '¿Desea revertir monto de historial?',
		wm_delete_file		: '¿Está seguro de eliminar archivo?',
		
		//appointment
		wm_appointment_register		: '¿Desea generar nueva consulta?',
		wm_appointment_cancel		: '¿Desea cancelar consulta?',
		wm_appointment_finish		: '¿Desea finalizar consulta?',
		wm_appointment_reschedule	: '¿Desea reprogramar consulta?',
		
		//surgery
		wm_surgery_cancel		: '¿Desea cancelar cirugía?',
		wm_surgery_reschedule	: '¿Desea reprogramar cirugía?',
		wm_surgery_finish		: '¿Desea finalizar cirugía?',
		wm_surgery_register		: '¿Desea generar nueva cirugía?',
		
		//product
		wm_product_register	: "¿Desea registrar nuevo producto?",
		wm_category_move	: "¿Desea mover todos los productos a otra categoría? Este cambio no es reversible.",
		wm_category_delete	: "¿Desea eliminar categoría?",
		wm_category_update	: "Los productos también modificará su categoría. ¿Desea actualizar categoría?",
		wm_category_add		: "¿Desea agregar nueva categoría?",
		wm_image_delete		: "¿Desea eliminar imagen?",
		wm_image_main		: "¿Desea configurar como imagen de producto?",
		wm_option_add		: "¿Desea agregar nueva opción del producto?",
		wm_option_delete	: "¿Desea eliminar opción del producto?",
		wm_option_edit		: "¿Desea actualizar opción del producto?",
		wm_provider_delete	: "¿Seguro de eliminar proveedor?",
		wm_product_delete	: "¿Seguro de eliminar producto?",
		
		//sale
		wm_sale_add			: '¿Desea registrar la venta?',
		wm_sale_cancel		: '¿Desea cancelar venta?',
		wm_medical_unassign	: '¿Desea desasignar atención médica?',
		wm_payment_add		: '¿Desea registrar pago?',
		wm_payment_delete	: '¿Desea eliminar pago?',
		wm_voucher_make		: '¿Desea generar comprobante de venta?',
		wm_voucher_sunat	: '¿Desea enviar comprobante a Sunat?',
		wm_voucher_void		: '¿Desea anular el comprobante? Esta acción no es reversible.',
		e_list_duplicate	: 'Producto existe en la lista.',
		e_list_currency		: 'Moneda de productos deben ser iguales.',
		e_item_select_least	: 'Debe elegir al menos un ítem.',
		e_item_select		: 'Debe elegir un ítem.',
		e_item_option		: 'Debe elegir una opción del producto.',
		e_item_stock		: 'Debe ingresar cantidad menor que stock de opción.',
		
		//account
		wm_account_add		: '¿Desea registrar nuevo usuario?',
		wm_account_remove	: '¿Desea eliminar usuario elegido?',
		wm_password_reset	: '¿Desea restablecer contraseña de usuario por su número de documento de identidad?',

		//config
		wm_profile_remove	: '¿Desea eliminar perfil elegido?',
		wm_medicine_remove	: '¿Desea eliminar medicina elegida?',
		wm_image_remove		: '¿Desea eliminar imagen elegida?',
		wm_system_init		: 'Su sesión finalizará para acceder a la configuración del sistema. ¿Desea continuar?',
	},
};