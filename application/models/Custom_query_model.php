<?php

class Custom_query_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
    }

    function custom_report_sale($first_date, $second_date){
        $sql = "SELECT a.*, 
        b.code AS statusCode, 
        c.description AS saleTypeDesc, 
        d.name AS clientFullName, 
        e.description AS currencyDesc,
        f.price AS priceProduct,
        f.discount AS discountProduct,
        f.qty AS quantyProduct, 
        g.description AS productDesc, 
        h.received AS paymentReceived, 
        h.`change` AS paymentChange, 
        h.registed_at AS PaymentRegistedAt,
        h.balance AS paymentBalance, 
        i.description AS paymentMethodDesc
        FROM sale a 
        INNER JOIN status b ON a.status_id = b.id
        INNER JOIN sale_type c ON a.sale_type_id=c.id
        INNER JOIN person d ON a.client_id=d.id
        INNER JOIN currency e ON a.currency_id=e.id
        INNER JOIN sale_product f ON a.id = f.sale_id
        INNER JOIN product g ON f.product_id = g.id
        INNER JOIN payment h ON a.id = h.sale_id
        INNER JOIN payment_method i ON h.payment_method_id = i.id
        

        WHERE a.registed_at  BETWEEN '$first_date 00:00:00' AND '$second_date 23:59:59';
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        if ($result) return $result; else return null;
	}
	

}
?>