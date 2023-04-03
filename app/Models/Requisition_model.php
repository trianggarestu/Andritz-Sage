<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Requisition_model extends Model
{

    //protected $table = 'ARCUS';
    function __construct()
    {
        parent::__construct();
    }



    function get_requisition_open()
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'");
        return $query->getResultArray();
    }

    function get_requisition_sage()
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER in (select distinct RQNNUMBER from DATAN1.dbo.POPORH1)");
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnnumber)
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER='$rqnnumber' ");
        return $query->getRowArray();
    }

    function get_so_by_id($id_so)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING "
            . "where ID_SO='$id_so' ");
        return $query->getRowArray();
    }

    function requisition_update($id, $data)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data, array('ID_SO' => $id));
        $query = $this->db->query("update webot_ORDERTRACKING SET PrStatus = 'Closed' where ID_SO = $id");
        //Tanpa return juga bisa jalan
        return $query;
    }
}
