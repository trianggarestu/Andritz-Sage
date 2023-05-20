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
        c.LOGUNIQ,c.ETDORIGINDATE,c.ATDORIGINDATE,c.ETAPORTDATE,c.PIBDATE,c.VENDSHISTATUS,c.LOGREMARKS,c.POSTINGSTAT as LOGPOSTINGSTAT,c.OFFLINESTAT as LOGOFFLINESTAT
        from webot_PO a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
        left join webot_LOGISTICS c on c.POUNIQ=a.POUNIQ 
        where (a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.LOGUNIQ IS NULL) or (a.POSTINGSTAT=1 and a.CARGOREADINESSDATE IS NOT NULL and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1) 
        or (c.POSTINGSTAT=1 and (c.ETDORIGINDATE is NULL or c.ATDORIGINDATE is NULL or c.ETAPORTDATE is NULL or c.PIBDATE is NULL or c.VENDSHISTATUS is NULL))");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
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

    function ot_logistics_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }
    function get_log_preview()
    {
        $query = $this->db->query("select a.*,b.*
        from webot_LOGISTICS a 
        left join webot_PO b on b.POUNIQ = a.POUNIQ
		where (a.POSTINGSTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }
}
