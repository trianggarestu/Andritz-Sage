<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Logistics_model extends Model
{

    protected $table = 'webot_LOGISTICS';

    function __construct()
    {
        parent::__construct();
    }


    function get_po_pending_to_arrangeshipment()
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.ETDORIGINDATE,c.ATDORIGINDATE,c.ETAPORTDATE,c.PIBDATE,c.VENDSHISTATUS,c.LOGREMARKS,c.POSTINGSTAT as LOGPOSTINGSTAT,c.OFFLINESTAT as LOGOFFLINESTAT
        from webot_PO a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
        left join webot_LOGISTICS c on c.POUNIQ=a.POUNIQ 
        where (a.POSTINGSTAT=1 and c.LOGUNIQ IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1 and c.OFFLINESTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnuniq)
    {
        $query = $this->db->query("select * from webot_REQUISITION where POSTINGSTAT=1 and RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }

    function get_posage_by_id($ponumber)
    {
        $query = $this->db->query("select PONUMBER," . '"DATE"' . " as PODATE,VDCODE,VDNAME,DESCRIPTIO from POPORH1 where PONUMBER='$ponumber' ");
        return $query->getRowArray();
    }

    function get_po_by_requisition($rqnuniq)
    {
        $query = $this->db->query("select * from webot_PO where RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }

    function get_po_by_pouniq($pouniq)
    {
        $query = $this->db->query("select * from webot_PO where POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }


    function get_po_list_sage_by_rqn($rqnnumber)
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

    function get_purchaseorder_close()
    {
        $query = $this->db->query("select * from webot_PO where POSTINGSTAT=1");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_pobeforeetd()
    {
        $query = $this->db->query("select x.*,y.RQNDATE,z.CONTRACT,z.CTDESC,z.NAMECUST,z.ITEMNO,z.QTY,z.STOCKUNIT from (
            select *,
            convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101) as F_ETDDATE,
            DATEDIFF(day, convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101),GETDATE())as diff 
            from webot_PO
            ) x 
            left join webot_REQUISITION y on y.RQNUNIQ=x.RQNUNIQ
            left join webot_CSR z on z.CSRUNIQ=x.CSRUNIQ
            where x.POSTINGSTAT=1 and x.CARGOREADINESSDATE>=1 and x.diff>=-15
            order by x.ETDDATE asc");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function count_po_posting()
    {
        $builder = $this->db->table('webot_PO');
        $builder->join('webot_REQUISITION a', 'a.RQNUNIQ = webot_PO.RQNUNIQ', 'left');
        $builder->join('webot_CSR b', 'b.CSRUNIQ = webot_PO.CSRUNIQ', 'left');
        $builder->where('webot_PO.POSTINGSTAT', 1);
        $builder->where('webot_PO.CARGOREADINESSDATE>=', 1);
        return $builder->countAllResults();
    }

    function count_po_beforeetd()
    {
        $builder = $this->db->table('webot_PO');
        $builder->where('webot_PO.POSTINGSTAT', 1);
        $builder->where('webot_PO.CARGOREADINESSDATE>=', 1);
        $builder->where('DATEDIFF(day, convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101),GETDATE())>=', -15);
        return $builder->countAllResults();
    }

    function get_PurchaseOrder_sage()
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER in (select distinct RQNNUMBER from DATAN1.dbo.POPORH1)");
        return $query->getResultArray();
    }

    function get_so_by_id($id_so)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING "
            . "where ID_SO='$id_so' ");
        return $query->getRowArray();
    }

    function get_purchaseorder_post($pouniq)
    {
        $query = $this->db->query("select * from webot_PO where POSTINGSTAT=1 and POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }


    function purchaseorder_insert($data1)
    {
        $query = $this->db->table('webot_PO')->insert($data1);
        return $query;
    }


    function purchaseorder_update($id_po, $data1)
    {
        $query = $this->db->table('webot_PO')->update($data1, array('POUNIQ' => $id_po));
        return $query;
    }

    function po_post_update($pouniq, $data2)
    {
        $query = $this->db->table('webot_PO')->update($data2, array('POUNIQ' => $pouniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_purchaseorder_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }
}
