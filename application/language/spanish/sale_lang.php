<?php
$lang['sale'] = 'Venta';
$lang['sales'] = 'Ventas';

//warning message
$lang['wm_medical_unassign'] = '¿Desea desasignar atención médica?';
$lang['wm_payment_add'] = '¿Desea registrar pago?';
$lang['wm_payment_delete'] = '¿Desea eliminar pago?';
$lang['wm_sale_add'] = '¿Desea registrar la venta?';
$lang['wm_sale_cancel'] = '¿Desea cancelar venta?';
$lang['wm_voucher_make'] = '¿Desea generar comprobante de venta?';
$lang['wm_voucher_sunat'] = '¿Desea enviar comprobante a Sunat?';
$lang['wm_voucher_void'] = '¿Desea anular el comprobante? Esta acción no es reversible.';

//error message - view (list)
$lang['e_list_duplicate'] = 'Producto existe en la lista.';
$lang['e_list_currency'] = 'Moneda de productos deben ser iguales.';
$lang['e_item_select_least'] = 'Debe elegir al menos un ítem.';
$lang['e_item_select'] = 'Debe elegir un ítem.';
$lang['e_item_option'] = 'Debe elegir una opción del producto.';
$lang['e_item_stock'] = 'Debe ingresar cantidad menor que stock de opción.';

//error message
$lang['e_balance_update'] = 'Existe cambio de balance del pago. Actualice la página e intente nuevamente.';
$lang['e_balance_pending'] = 'Existe saldo pendiente de venta.';
$lang['e_voucher_exist'] = 'Existe un comprobante emitido. Vuelva a cargar la página.';
$lang['e_no_reservation'] = 'No hay reservación con documento ingresado. Genere una reserva con datos de cliente para continuar.';
$lang['e_sale_cenceled'] = 'Es una venta previamente cancelada.';
$lang['e_voucher_exists'] = 'Existe un comprobante vinculado con esta venta.';

//success message
$lang['s_sale_add'] = 'Venta ha sido agregada con éxito.';
$lang['s_sale_cancel'] = 'Venta ha sido cancelada con éxito.';
$lang['s_payment_add'] = 'Pago ha sido registrado con éxito.';
$lang['s_payment_delete'] = 'Pago ha sido eliminado con éxito.';
$lang['s_surgery_assigned'] = 'Cirugía ha sido asignado al producto de venta.';
$lang['s_appointment_assigned'] = 'Consulta ha sido asignado al producto de venta.';
$lang['s_item_unassign'] = 'El ítem del producto ha sido desasignado de la atención médica.';
$lang['s_voucher_voided'] = 'Comprobante anulado.';

//button
$lang['btn_add_payment'] = 'Agregar Pago';
$lang['btn_appointment'] = 'Datos de Consulta';
$lang['btn_cancel_sale'] = 'Anular Venta';
$lang['btn_emit'] = 'Emitir';
$lang['btn_next'] = 'Siguiente';
$lang['btn_payment_report'] = 'Reporte de Pagos';
$lang['btn_previous'] = 'Anterior';
$lang['btn_register_sale'] = 'Registrar Venta';
$lang['btn_send'] = 'Enviar';
$lang['btn_send_again'] = 'Enviar Nuevamente';
$lang['btn_void'] = 'Anular';
$lang['btn_voucher'] = 'Comprobante';

//word
$lang['w_add_product'] = 'Agregar Producto';
$lang['w_amount_to_pay'] = 'Monto a Pagar';
$lang['w_appointment'] = 'Consulta';
$lang['w_assign_appointment'] = 'Asignar Consulta';
$lang['w_assign_surgery'] = 'Asignar Cirugía';
$lang['w_attention'] = 'Atención';
$lang['w_balance'] = 'Saldo';
$lang['w_category'] = 'Categoría';
$lang['w_change'] = 'Vuelto';
$lang['w_client'] = 'Cliente';
$lang['w_data'] = 'Datos';
$lang['w_date'] = 'Fecha';
$lang['w_discount'] = 'Descuento';
$lang['w_discount_short'] = 'Desc.';
$lang['w_doc_number'] = 'Número de Documento';
$lang['w_document'] = 'Documento';
$lang['w_form_of_payment'] = 'F. Pago';
$lang['w_issuance_receipt'] = 'Emisión de Comprobante';
$lang['w_item'] = 'Ítem';
$lang['w_items'] = 'Ítems';
$lang['w_last_update'] = 'Última Operación';
$lang['w_medical'] = 'Atención Médica';
$lang['w_messages'] = 'Mensaje';
$lang['w_name'] = 'Nombre';
$lang['w_new_payment'] = 'Nuevo Pago';
$lang['w_new_sale'] = 'Nueva Venta';
$lang['w_number'] = 'Número';
$lang['w_option'] = 'Opción';
$lang['w_payment_method'] = 'Forma de Pago';
$lang['w_payments'] = 'Pagos';
$lang['w_pending_payment'] = 'Venta con pago pendiente';
$lang['w_product'] = 'Producto';
$lang['w_qty'] = 'Cant.';
$lang['w_quantity'] = 'Cantidad';
$lang['w_reason'] = 'Motivo';
$lang['w_received'] = 'Recibido';
$lang['w_reservations'] = 'Reservaciones';
$lang['w_sale'] = 'Venta';
$lang['w_sale_detail'] = 'Detalle de Venta';
$lang['w_sale_type'] = 'Tipo de Venta';
$lang['w_status'] = 'Estado';
$lang['w_subtotal'] = 'Subtotal';
$lang['w_sunat'] = 'Sunat';
$lang['w_surgery'] = 'Cirugía';
$lang['w_total'] = 'Total';
$lang['w_type'] = 'Tipo';
$lang['w_unit_discount'] = 'Descuento Unitario';
$lang['w_unit_price'] = 'Precio Unitario';
$lang['w_unit_price_short'] = 'Cant. * P/U';
$lang['w_unit_price_short'] = 'P/U';
$lang['w_void_voucher'] = 'Anular comprobante';
$lang['w_voucher'] = 'Comprobante';

//text
$lang['t_canceled_sale'] = 'Venta anulada. No requiere ninguna comunicación con Sunat.';
$lang['t_need_send_sunat'] = 'Requiere enviar a Sunat.';
$lang['t_no_medical'] = 'Esta venta no tiene vínculo con ninguna atención médica.';
$lang['t_no_sales'] = 'No existe ventas registradas o cumplan con datos de búsqueda.';
$lang['t_no_voucher'] = 'Venta sin comprobante emitido.';