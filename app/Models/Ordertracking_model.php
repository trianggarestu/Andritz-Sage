<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Ordertracking_model extends Model
{

    //protected $table = 'ARCUS';
    function __construct()
    {
        parent::__construct();
    }



    function get_ordertracking()
    {
        $query = $this->db->query('select * from webot_ORDERTRACKING');
        return $query->getResultArray();
    }

    function get_ot_by_id($id_so)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING "
            . "where CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }
}
