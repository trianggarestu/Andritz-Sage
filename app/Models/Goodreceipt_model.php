<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Goodreceipt_model extends Model
{

    protected $table = 'webot_RECEIPTS';

    function __construct()
    {
        parent::__construct();
    }



    // Receipt
    function get_po_pending_to_gr()
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        " . 'b."CONTRACT"' . ",b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.RCPUNIQ,c.RECPNUMBER,c.RECPDATE,c.DESCRIPTIO,c.VDNAME,c.RECPQTY,c.RECPUNIT,c.GRSTATUS,c.POSTINGSTAT as RCPPOSTINGSTAT,c.OFFLINESTAT as RCPOFFLINESTAT
        from (select x.*,y.PODATE from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
		left join webot_RECEIPTS c on c.POUNIQ=a.POUNIQ
        where (a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_po_pending_to_gr_search($keyword)
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        " . 'b."CONTRACT"' . ",b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.RCPUNIQ,c.RECPNUMBER,c.RECPDATE,c.DESCRIPTIO,c.VDNAME,c.RECPQTY,c.RECPUNIT,c.GRSTATUS,c.POSTINGSTAT as RCPPOSTINGSTAT,c.OFFLINESTAT as RCPOFFLINESTAT
        from (select x.*,y.PODATE from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
		left join webot_RECEIPTS c on c.POUNIQ=a.POUNIQ
        where ((a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1))
        and (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.CRMNO like '%$keyword%' or b.NAMECUST like '%$keyword%'
        or b.ITEMNO like '%$keyword%' or b.MATERIALNO like '%$keyword%' or " . 'it."DESC"' . " like '%$keyword%' or a.PONUMBER like '%$keyword%'
        or a.VENDSHISTATUS like '%$keyword%' or c.RECPNUMBER like '%$keyword%' or c.DESCRIPTIO like '%$keyword%' or c.VDNAME like '%$keyword%')");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_po_pending_by_pouniq($pouniq)
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        " . 'b."CONTRACT"' . ",b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.RCPUNIQ,c.RCPHSEQ,c.RECPNUMBER,c.RECPDATE,c.VDNAME,c.DESCRIPTIO,c.RECPITEMNO,c.ITEMDESC as RECPITEMDESC,c.RECPQTY,c.RECPUNIT,c.GRSTATUS,c.POSTINGSTAT as RCPPOSTINGSTAT,c.OFFLINESTAT as RCPOFFLINESTAT
        from (select x.*,y.PODATE from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
		left join webot_RECEIPTS c on c.POUNIQ=a.POUNIQ
        where ((a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1)) and a.POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }


    function get_receipt_sage_by_id($rcphseq)
    {
        $query = $this->db->query("select DISTINCT b.PONUMBER,b.RCPNUMBER," . 'b."DATE"' . " as RCPDATE,b.VDNAME,b.DESCRIPTIO from PORCPH1 b
        where b.RCPHSEQ='$rcphseq'");
        return $query->getRowArray();
    }

    function list_gr_by_po($ponumber)
    {
        $query = $this->db->query("select DISTINCT b.RCPHSEQ,b.PONUMBER,b.RCPNUMBER," . 'b."DATE"' . " as RCPDATE,b.VDNAME from PORCPH1 b
        where b.PONUMBER='$ponumber' ");
        return $query->getResultArray();
    }

    function get_rcpl_by_receipt($rcphseq, $contract)
    {
        $query = $this->db->query("select a.RCPHSEQ,a.RCPLSEQ,b.PONUMBER,b.RCPNUMBER," . 'b."DATE"' . " as RCPDATE,b.VDNAME,
        a.CONTRACT,a.ITEMNO,a.ITEMDESC,a.RCPUNIT,a.RQRECEIVED
        from PORCPH1 b
        left join PORCPL a on a.RCPHSEQ=b.RCPHSEQ
        where b.RCPHSEQ='$rcphseq'" . " and a.CONTRACT='$contract'");
        return $query->getResultArray();
    }

    function get_receiptline_sage_by_id($sage_rcphseq, $sage_rcplseq)
    {
        $query = $this->db->query("select b.PONUMBER,b.RCPNUMBER," . 'b."DATE"' . " as RCPDATE,b.VDNAME,b.DESCRIPTIO,a.RCPHSEQ,a.RCPLSEQ,a.CONTRACT,a.PROJECT,
        a.ITEMNO,a.ITEMDESC,a.RCPUNIT,a.RQRECEIVED from PORCPH1 b
        left join PORCPL a on a.RCPHSEQ=b.RCPHSEQ
        where " . 'a."CONTRACT"' . "<>'' and " . 'b."DATE"' . ">=20220101 and a.RCPHSEQ='$sage_rcphseq' and a.RCPLSEQ='$sage_rcplseq' ");
        return $query->getRowArray();
    }

    function get_rcpuniq_open($csruniq, $pouniq, $rcph_seq)
    {
        $query = $this->db->query("select RCPUNIQ from webot_RECEIPTS where POSTINGSTAT =0 and CSRUNIQ='$csruniq' and POUNIQ='$pouniq' and RCPHSEQ='$rcph_seq'");
        return $query->getRowArray();
    }

    function get_goodreceipt_open($rcpuniq)
    {
        $query = $this->db->query("select a.* from webot_RECEIPTS a where a.POSTINGSTAT <>2 and a.RCPUNIQ='$rcpuniq'");
        return $query->getRowArray();
    }


    function goodreceipt_insert($data)
    {
        $query = $this->db->table('webot_RECEIPTS')->insert($data);
        return $query;
    }

    function goodreceipt_update($rcpuniq, $data)
    {
        $query = $this->db->table('webot_RECEIPTS')->update($data, array('RCPUNIQ' => $rcpuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_goodreceipt_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }


    function get_goodreceipt_post($rcpuniq)
    {
        $query = $this->db->query("select * from webot_RECEIPTS 
        where POSTINGSTAT=1 and RCPUNIQ='$rcpuniq' ");
        return $query->getRowArray();
    }


    function count_gr_posting()
    {
        $builder = $this->db->table('webot_RECEIPTS');
        $builder->where('webot_RECEIPTS.POSTINGSTAT=', 1);
        return $builder->countAllResults();
    }
    function get_gr_preview()
    {
        $query = $this->db->query("select a.*,b.*
        from webot_RECEIPTS a 
        left join webot_PO b on b.POUNIQ = a.POUNIQ and b.PONUMBER = a.PONUMBER
		where (a.POSTINGSTAT=1)");
        return $query->getResultArray();
    }
}
