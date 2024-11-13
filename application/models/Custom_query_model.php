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
        i.description AS paymentMethodDesc,
        j.description AS optionDesc
        FROM sale a 
        INNER JOIN status b ON a.status_id = b.id
        INNER JOIN sale_type c ON a.sale_type_id=c.id
        INNER JOIN person d ON a.client_id=d.id
        INNER JOIN currency e ON a.currency_id=e.id
        INNER JOIN sale_product f ON a.id = f.sale_id
        INNER JOIN product g ON f.product_id = g.id
        INNER JOIN payment h ON a.id = h.sale_id
        INNER JOIN payment_method i ON h.payment_method_id = i.id
        LEFT JOIN product_option j ON f.option_id=j.id
        

        WHERE a.registed_at  BETWEEN '$first_date 00:00:00' AND '$second_date 23:59:59';
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        if ($result) return $result; else return null;
	}


    function custom_report_consolidated_sale($first_date, $second_date){
        $sql = "SELECT 
        a.id
        ,e.id AS ProductId
        ,e.description
        ,d.price
        ,d.qty
        ,(d.price*d.qty)AS total
        ,b.payment_method_id
        ,c.description 

        FROM  sale a 
        inner join payment b ON a.id = b.sale_id
        INNER JOIN payment_method c ON b.payment_method_id = c.id
        INNER JOIN sale_product d ON a.id = d.sale_id
        INNER JOIN product e ON d.product_id = e.id

        WHERE a.status_id = 3 AND a.registed_at  BETWEEN '$first_date 00:00:00' AND '$second_date 23:59:59';
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        if ($result) return $result; else return null;
	}
	

}
?>