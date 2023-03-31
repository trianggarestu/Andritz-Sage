<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Customer_model extends Model
{

    //protected $table = 'ARCUS';
    function __construct()
    {
        parent::__construct();
    }



    function get_customer()
    {
        $query = $this->db->query('select IDCUST,IDGRP,TEXTSNAM,NAMECUST from ARCUS');
        return $query->getResultArray();
    }
}
