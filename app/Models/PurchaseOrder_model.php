<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Purchaseorder_model extends Model
{

    //protected $table = 'ARCUS';
    function __construct()
    {
        parent::__construct();
    }


    function get_requisition_pending()
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC
        from webot_REQUISITION a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
        left join webot_PO c on c.RQNUNIQ=a.RQNUNIQ 
        where a.POSTINGSTAT=1 and c.RQNUNIQ is NULL");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnuniq)
    {
        $query = $this->db->query("select * from webot_REQUISITION where POSTINGSTAT=1 and RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }

    function get_po_by_requisition($rqnuniq)
    {
        $query = $this->db->query("select * from webot_PO where RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }


    function get_po_list__sage_by_rqn($rqnnumber)
    {
        $query = $this->db->query("select RQNNUMBER," . '"DATE"' . " as PODATE,EXPARRIVAL,PONUMBER,VDCODE,VDNAME,DESCRIPTIO,REFERENCE from POPORH1 where RQNNUMBER='$rqnnumber'");
        return $query->getResultArray();
    }

    function get_PurchaseOrder_open()
    {
        $query = $this->db->query("select * from webot_REQUISITION WHERE POSTINGSTAT=1");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_PurchaseOrder_close()
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_PurchaseOrder_sage()
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER in (select distinct RQNNUMBER from DATAN1.dbo.POPORH1)");
        return $query->getResultArray();
    }

    function get_PurchaseOrder_by_id($rqnnumber)
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

    function PurchaseOrder_update($id, $data)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data, array('ID_SO' => $id));
        $query = $this->db->query("update webot_ORDERTRACKING SET PrStatus = 'Closed' where ID_SO = $id");
        //Tanpa return juga bisa jalan
        return $query;
    }
}
