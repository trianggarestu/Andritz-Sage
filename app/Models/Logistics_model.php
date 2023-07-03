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
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,
        c.LOGUNIQ,c.ETDORIGINDATE,c.ATDORIGINDATE,c.ETAPORTDATE,c.PIBDATE,c.VENDSHISTATUS,c.LOGREMARKS,c.POSTINGSTAT as LOGPOSTINGSTAT,c.OFFLINESTAT as LOGOFFLINESTAT
        from webot_PO a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join webot_LOGISTICS c on c.POUNIQ=a.POUNIQ 
        where (a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.LOGUNIQ IS NULL) or (a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1) 
        or (c.POSTINGSTAT=1 and (c.ETDORIGINDATE is NULL or c.ATDORIGINDATE is NULL or c.ETAPORTDATE is NULL or c.PIBDATE is NULL or c.VENDSHISTATUS is NULL))");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_po_pending_to_arrangeshipment_search($keyword)
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,
        c.LOGUNIQ,c.ETDORIGINDATE,c.ATDORIGINDATE,c.ETAPORTDATE,c.PIBDATE,c.VENDSHISTATUS,c.LOGREMARKS,c.POSTINGSTAT as LOGPOSTINGSTAT,c.OFFLINESTAT as LOGOFFLINESTAT
        from webot_PO a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join webot_LOGISTICS c on c.POUNIQ=a.POUNIQ 
        where ((a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.LOGUNIQ IS NULL) or (a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1) 
        or (c.POSTINGSTAT=1 and (c.ETDORIGINDATE is NULL or c.ATDORIGINDATE is NULL or c.ETAPORTDATE is NULL or c.PIBDATE is NULL or c.VENDSHISTATUS is NULL)))
        and (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.CRMNO like '%$keyword%' or b.NAMECUST like '%$keyword%'
        or a.PONUMBER like '%$keyword%' or a.ORIGINCOUNTRY like '%$keyword%' or a.POREMARKS like '%$keyword%')");
        return $query->getResultArray();
    }


    function get_po_by_id($pouniq)
    {
        $query = $this->db->query("select * from webot_PO where POSTINGSTAT=1 and POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }

    function get_log_by_po($pouniq)
    {
        $query = $this->db->query("select * from webot_LOGISTICS where POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }

    function get_arrangeshipment_post($loguniq)
    {
        $query = $this->db->query("select * from webot_LOGISTICS where POSTINGSTAT=1 and 
        (ETDORIGINDATE is NOT NULL and ATDORIGINDATE is NOT NULL and ETAPORTDATE is NOT NULL and PIBDATE is NOT NULL and VENDSHISTATUS is NOT NULL)
        and LOGUNIQ='$loguniq' ");
        return $query->getRowArray();
    }

    function get_loguniq_open($id_so, $id_po)
    {
        $query = $this->db->query("select a.LOGUNIQ,a.PONUMBER,a.LOGKEY from webot_LOGISTICS a
        where a.CSRUNIQ='$id_so' and a.POUNIQ='$id_po'");
        return $query->getRowArray();
    }


    function get_logjoincsr_by_po($n_loguniq)
    {
        $query = $this->db->query("select a.*,b.POUNIQ,b.PODATE,b.PONUMBER,b.ETDDATE,b.CARGOREADINESSDATE,b.ORIGINCOUNTRY,b.POREMARKS, 
        c.RQNNUMBER,c.RQNDATE,d.*
        from webot_LOGISTICS a inner join webot_PO b on b.POUNIQ=a.POUNIQ
        left join webot_REQUISITION c on c.RQNUNIQ=b.RQNUNIQ
        left join webot_CSR d on d.CSRUNIQ=b.CSRUNIQ and c.CSRUNIQ=d.CSRUNIQ
        where a.POSTINGSTAT=1 and a.LOGUNIQ='$n_loguniq' ");
        return $query->getRowArray();
    }


    function count_log_posting()
    {
        $builder = $this->db->table('webot_LOGISTICS');
        $builder->where('webot_LOGISTICS.POSTINGSTAT=', 1);
        $builder->where('webot_LOGISTICS.ETDORIGINDATE IS NOT NULL');
        $builder->where('webot_LOGISTICS.ATDORIGINDATE IS NOT NULL');
        $builder->where('webot_LOGISTICS.ETAPORTDATE IS NOT NULL');
        $builder->where('webot_LOGISTICS.PIBDATE IS NOT NULL');
        $builder->where('webot_LOGISTICS.VENDSHISTATUS IS NOT NULL');
        return $builder->countAllResults();
    }

    function arrangeshipment_insert($data1)
    {
        $query = $this->db->table('webot_LOGISTICS')->insert($data1);
        return $query;
    }

    function arrangeshipment_update($id_log, $data1)
    {
        $query = $this->db->table('webot_LOGISTICS')->update($data1, array('LOGUNIQ' => $id_log));
        return $query;
    }

    function logistics_post_update($loguniq, $data2)
    {
        $query = $this->db->table('webot_LOGISTICS')->update($data2, array('LOGUNIQ' => $loguniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_logistics_update($id_so, $po_number, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so, 'PONUMBER' => $po_number));
        //Tanpa return juga bisa jalan
        return $query;
    }
    function get_log_preview($nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.*
        from webot_LOGISTICS a 
        left join webot_CSR b ON a.CSRUNIQ = b.CSRUNIQ
        left join webot_PO c on c.POUNIQ = a.POUNIQ and b.CSRUNIQ = c.CSRUNIQ 
        where (a.POSTINGSTAT=1) and (a.ETDORIGINDATE BETWEEN $nfromdate and $ntodate)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }
    function get_log_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_LOGISTICS c 
        left join webot_CSR b on c.CSRUNIQ = b.CSRUNIQ 
        left join webot_PO a on b.CSRUNIQ = a.CSRUNIQ and a.POUNIQ=c.POUNIQ
        where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
        or b.SERVICETYPE like '%$keyword%' or b.CRMREMARKS like '%$keyword%' or b.ITEMNO like '%$keyword%' or b.MATERIALNO like '%$keyword%'
        or b.STOCKUNIT like '%$keyword%' or a.PONUMBER like '%$keyword%'
        or a.ORIGINCOUNTRY like '%$keyword%' or a.POREMARKS like '%$keyword%' or c.VENDSHISTATUS like '%$keyword%') and
        (c.ETDORIGINDATE>=$nfromdate and c.ETDORIGINDATE<=$ntodate)
        order by c.ETDORIGINDATE asc");
        return $query->getResultArray();
    }
}
