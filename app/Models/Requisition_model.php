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

    protected $table = 'webot_REQUISITION';

    function __construct()
    {
        parent::__construct();
    }



    function get_requisition_open()
    {
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNDATE,b.RQNNUMBER,b.POSTINGSTAT,b.OFFLINESTAT from webot_CSR a
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_requisition_close()
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.PONUMBER,c.PODATE
        from webot_REQUISITION a left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join webot_PO c on c.RQNUNIQ=a.RQNUNIQ 
        where a.POSTINGSTAT=1");
        return $query->getResultArray();
    }

    function get_requisition_sage()
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER not in (select distinct RQNNUMBER from webot_REQUISITION where POSTINGSTAT=1)");
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnnumber)
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER='$rqnnumber' ");
        return $query->getRowArray();
    }

    function get_requisition_by_so($id_so)
    {
        $query = $this->db->query("select * from webot_REQUISITION where CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_so_by_id($id_so)
    {
        $query = $this->db->query("select a.*," . 'b."DESC"' . " as ITEMDESC from webot_CSR a left join ICITEM b on b.ITEMNO=a.ITEMNO "
            . "where a.POSTINGSTAT=1 and a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_requisition_post($rqnuniq)
    {
        $query = $this->db->query("select * from webot_REQUISITION where POSTINGSTAT=1 and RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }

    function requisition_insert($data1)
    {
        $query = $this->db->table('webot_REQUISITION')->insert($data1);
        return $query;
    }

    function requisition_update($id_pr, $data1)
    {
        $query = $this->db->table('webot_REQUISITION')->update($data1, array('RQNUNIQ' => $id_pr));
        return $query;
    }

    function rqn_post_update($rqnuniq, $data2)
    {
        $query = $this->db->table('webot_REQUISITION')->update($data2, array('RQNUNIQ' => $rqnuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_requisition_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }
}
