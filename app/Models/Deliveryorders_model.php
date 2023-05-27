<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Deliveryorders_model extends Model
{

    protected $table = 'webot_SHIPMENTS';

    function __construct()
    {
        parent::__construct();
    }



    // Receipt
    function get_gr_pending_to_dn()
    {
        $query = $this->db->query("select a.RCPUNIQ as RCPRCPUNIQ,a.RECPNUMBER,a.RECPDATE,a.RECPQTY,a.RECPUNIT,a.GRSTATUS,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.ITEMNO,b.MATERIALNO,it." . '"DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.*
        from webot_RECEIPTS a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
		left join webot_SHIPMENTS c on c.RCPUNIQ=a.RCPUNIQ
        where (a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1)");

        return $query->getResultArray();
    }

    function get_rcp_pending_by_rcpuniq($rcpuniq)
    {
        $query = $this->db->query("select a.CSRUNIQ,a.RCPUNIQ as RCPRCPUNIQ,a.RECPNUMBER,a.RECPDATE,a.DESCRIPTIO,a.RECPITEMNO,a.RECPQTY,a.RECPUNIT,a.GRSTATUS,
        po.PONUMBER,po.PODATE,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.CUSTOMER,b.NAMECUST,
        " . 'b."CONTRACT"' . ",b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO," . 'it."DESC"' . " as ITEMDESC,
        b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.SHIUNIQ,c.DOCUNIQ,c.DOCNUMBER,c.SHINUMBER,c.SHIDATE,c.CUSTRCPDATE,c.SHIITEMNO,c.SHIQTY,c.SHIQTYOUTSTANDING,c.SHIUNIT,c.POCUSTSTATUS,c.DNSTATUS
        from webot_RECEIPTS a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join webot_PO po on po.POUNIQ=a.POUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
		left join webot_SHIPMENTS c on c.RCPUNIQ=a.RCPUNIQ
        where ((a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1)) and a.RCPUNIQ='$rcpuniq' ");
        return $query->getRowArray();
    }

    function list_sage_shi()
    {
        $query = $this->db->query("select DOCUNIQ,DOCNUM,HDRDESC,TRANSDATE,REFERENCE from ICTREH 
        where DOCNUM NOT IN (select x.DOCNUMBER from webot_SHIPMENTS x where x.POSTINGSTAT=1) and TRANSDATE>=20220101");
        return $query->getResultArray();
    }

    function list_sage_shi_by_id($sage_shidocuniq)
    {
        $query = $this->db->query("select DOCUNIQ,DOCNUM,HDRDESC,TRANSDATE,REFERENCE from ICTREH where DOCUNIQ='$sage_shidocuniq'");
        return $query->getRowArray();
    }

    function get_shiuniq_open($csruniq, $rcpuniq, $docuniq)
    {
        $query = $this->db->query("select SHIUNIQ from webot_SHIPMENTS where POSTINGSTAT =0 and CSRUNIQ='$csruniq' and RCPUNIQ='$rcpuniq' and DOCUNIQ='$docuniq'");
        return $query->getRowArray();
    }

    function get_shipment_open($shiuniq)
    {
        $query = $this->db->query("select a.* from webot_SHIPMENTS a where a.POSTINGSTAT <>2 and a.SHIUNIQ='$shiuniq'");
        return $query->getRowArray();
    }


    function get_shipment_post($shiuniq)
    {
        $query = $this->db->query("select a.*,b.EMAIL1CUST from webot_SHIPMENTS a
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.SHIUNIQ='$shiuniq' ");
        return $query->getRowArray();
    }



    function deliveryorders_insert($data)
    {
        $query = $this->db->table('webot_SHIPMENTS')->insert($data);
        return $query;
    }

    function deliveryorders_update($shiuniq, $data)
    {
        $query = $this->db->table('webot_SHIPMENTS')->update($data, array('SHIUNIQ' => $shiuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_deliveryorders_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }





    // RECEIPT, Nanti dihapus
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
}
