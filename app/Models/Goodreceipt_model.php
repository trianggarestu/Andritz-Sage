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
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,
		gpol.QTYPO,
        grcpl.QTYRCP,grcpl.RCPPOSTINGSTAT,grcpl.RCPOFFLINESTAT
        from (select x.*,y.PODATE,y.ETDDATE,y.CARGOREADINESSDATE,y.ORIGINCOUNTRY,y.POREMARKS 
		from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
		left join (	select POUNIQ,count(ITEMNO) as ROWITEMPO,sum(QTY) as QTYPO from webot_POL
		group by POUNIQ) gpol on gpol.POUNIQ=a.POUNIQ
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (select x.POUNIQ,COUNT(ITEMNO) as ROWITEMRCP,sum(QTY) as QTYRCP,MIN(y.POSTINGSTAT) as RCPPOSTINGSTAT,MAX(y.OFFLINESTAT) as RCPOFFLINESTAT,COUNT( DISTINCT y.POSTINGSTAT) as CTPOSTINGSTATRCPPOST 
		from webot_RCPL x inner join webot_RECEIPTS y on y.RCPUNIQ=x.RCPUNIQ
		group by x.POUNIQ) grcpl on grcpl.POUNIQ=a.POUNIQ
        where (a.POSTINGSTAT=1 and grcpl.POUNIQ IS NULL) or (a.POSTINGSTAT=1 and grcpl.RCPPOSTINGSTAT=0) or (grcpl.RCPPOSTINGSTAT=1 and grcpl.RCPOFFLINESTAT=1)
		or (gpol.QTYPO<>grcpl.QTYRCP)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }


    function get_grlist_on_gropen()
    {
        $query = $this->db->query("select distinct a.POUNIQ,c.CSRUNIQ,a.PODATE,c.RCPUNIQ,c.RECPNUMBER,c.RECPDATE,c.POSTINGSTAT as RCPPOSTINGSTAT,c.OFFLINESTAT as RCPOFFLINESTAT
        from webot_PO a
        inner join webot_RECEIPTS c on c.POUNIQ=a.POUNIQ 
        where (a.POSTINGSTAT=1 and c.RCPUNIQ IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1)
        order by c.RECPDATE asc,c.RECPNUMBER asc");

        return $query->getResultArray();
    }

    //PO Line
    function get_pol_list_post()
    {
        $query = $this->db->query("select a.*,c.SERVICETYPE,c.MATERIALNO,c.ITEMDESC,
        (select top 1 x.RECPDATE from webot_RECEIPTS x inner join webot_RCPL y on y.POUNIQ=d.POUNIQ and y.POLUNIQ=d.POLUNIQ
		order by RECPDATE desc) as L_RECPDATE,isnull(d.S_QTYRCP,0) as S_QTYRCP
        from webot_POL a
		inner join webot_PO b on b.POUNIQ=a.POUNIQ
		left join webot_CSRL c on c.CSRUNIQ=a.CSRUNIQ and c.CSRLUNIQ=a.CSRLUNIQ
		left join (select POUNIQ,POLUNIQ,sum(QTY) as S_QTYRCP from webot_RCPL
		group by POUNIQ,POLUNIQ) d on d.POUNIQ=a.POUNIQ and d.POLUNIQ=a.POLUNIQ
		where (b.POSTINGSTAT=1)
        order by a.POUNIQ asc, a.POLUNIQ asc,c.CSRUNIQ asc, c.CSRLUNIQ asc
		");
        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }

    function get_po_l_by_id($pouniq)
    {
        $query = $this->db->query("select * from (
            select a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,isnull(sum(e.QTY),0) as S_QTYRCP,(a.QTY-isnull(sum(e.QTY),0)) as QTYRCP_OUTS
                    from webot_CSRL a inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
                    left join webot_POL d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
                    left join webot_RCPL e on e.POUNIQ=d.POUNIQ and e.POLUNIQ=d.POLUNIQ
                    where d.POUNIQ='$pouniq'
                    group by a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ) polforrcpl 
                    where polforrcpl.QTYRCP_OUTS>0");
        return $query->getResultArray();
    }


    function get_po_pending_to_gr_search($keyword)
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,
		gpol.QTYPO,
        grcpl.QTYRCP,grcpl.RCPPOSTINGSTAT,grcpl.RCPOFFLINESTAT
        from (select x.*,y.PODATE,y.ETDDATE,y.CARGOREADINESSDATE,y.ORIGINCOUNTRY,y.POREMARKS 
		from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
		left join (	select POUNIQ,count(ITEMNO) as ROWITEMPO,sum(QTY) as QTYPO from webot_POL
		group by POUNIQ) gpol on gpol.POUNIQ=a.POUNIQ
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (select x.POUNIQ,COUNT(ITEMNO) as ROWITEMRCP,sum(QTY) as QTYRCP,MIN(y.POSTINGSTAT) as RCPPOSTINGSTAT,MAX(y.OFFLINESTAT) as RCPOFFLINESTAT,COUNT( DISTINCT y.POSTINGSTAT) as CTPOSTINGSTATRCPPOST 
		from webot_RCPL x inner join webot_RECEIPTS y on y.RCPUNIQ=x.RCPUNIQ
		group by x.POUNIQ) grcpl on grcpl.POUNIQ=a.POUNIQ
        where ((a.POSTINGSTAT=1 and grcpl.POUNIQ IS NULL) or (a.POSTINGSTAT=1 and grcpl.RCPPOSTINGSTAT=0) or (grcpl.RCPPOSTINGSTAT=1 and grcpl.RCPOFFLINESTAT=1)
		or (gpol.QTYPO<>grcpl.QTYRCP))
        and (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.NAMECUST like '%$keyword%'
        or a.PONUMBER like '%$keyword%' or a.VENDSHISTATUS like '%$keyword%')");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_po_pending_by_pouniq($pouniq)
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,b.EMAIL1CUST,
        b.CONTRACT,b.CTDESC,b.PROJECT,b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,
        c.RCPUNIQ,c.RECPNUMBER,c.RECPDATE,c.DESCRIPTIO,c.VDNAME,c.POSTINGSTAT as RCPPOSTINGSTAT,c.OFFLINESTAT as RCPOFFLINESTAT
        from (select x.*,y.PODATE,y.ETDDATE,y.CARGOREADINESSDATE,y.ORIGINCOUNTRY,y.POREMARKS 
		from webot_LOGISTICS x left join webot_PO y on y.POUNIQ=x.POUNIQ) a 
		left join (	select POUNIQ,count(ITEMNO) as ROWITEMPO,sum(QTY) as QTYPO from webot_POL
		group by POUNIQ) gpol on gpol.POUNIQ=a.POUNIQ
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join webot_RECEIPTS c on c.POUNIQ=a.POUNIQ
		left join (select POUNIQ,COUNT(ITEMNO) as ROWITEMRCP,sum(QTY) as QTYRCP from webot_RCPL
		group by POUNIQ) grcpl on grcpl.POUNIQ=a.POUNIQ
        where ((a.POSTINGSTAT=1 and c.POSTINGSTAT IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (c.POSTINGSTAT=1 and c.OFFLINESTAT=1)
		or (gpol.QTYPO<>grcpl.QTYRCP)) and a.POUNIQ='$pouniq' ");
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
        where b.PONUMBER='$ponumber'  and b.RCPNUMBER not in (select RECPNUMBER from webot_RECEIPTS) ");
        return $query->getResultArray();
    }

    function list_pol_by_po($po_uniq)
    {
        $query = $this->db->query("select a.*,b.MATERIALNO,b.ITEMDESC from webot_POL a
		inner join webot_CSRL b on b.CSRUNIQ=a.CSRUNIQ and b.CSRLUNIQ=a.CSRLUNIQ
        where a.POUNIQ='$po_uniq' ");
        return $query->getResultArray();
    }

    function get_po_l_item($pouniq, $itemno)
    {
        $query = $this->db->query("select * from (
            select a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,isnull(sum(e.QTY),0) as S_QTYRCP,(a.QTY-isnull(sum(e.QTY),0)) as QTYRCP_OUTS
            from webot_CSRL a inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
            left join webot_POL d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
            left join webot_RCPL e on e.POUNIQ=d.POUNIQ and e.POLUNIQ=d.POLUNIQ
            where d.POUNIQ='$pouniq' and d.ITEMNO='$itemno'
            group by a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ
            ) grouts 
            where grouts.QTYRCP_OUTS>0");
        return $query->getRowArray();
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

    function get_rcpuniq_open($csruniq, $po_number, $rcp_number)
    {
        $query = $this->db->query("select DISTINCT a.RCPUNIQ,a.PONUMBER,a.RECPNUMBER,a.RCPKEY,COUNT(b.RCPUNIQ) as CHKRCPL from webot_RECEIPTS a
        left join webot_RCPL b on b.RCPUNIQ=a.RCPUNIQ
        where a.CSRUNIQ='$csruniq' and a.PONUMBER='$po_number' and a.RECPNUMBER='$rcp_number'
        group by a.RCPUNIQ,a.PONUMBER,a.RECPNUMBER,a.RCPKEY");
        return $query->getRowArray();
    }

    function get_rcpjoincsr_by_rcp($rcpuniq)
    {
        $query = $this->db->query("select a.*,b.*,c.*,d.*,e.*
        from webot_RECEIPTS a
		left join webot_PO b on b.POUNIQ=a.POUNIQ
		left join webot_LOGISTICS c on c.POUNIQ=a.POUNIQ
		left join webot_REQUISITION d on d.RQNNUMBER=b.RQNNUMBER
        left join webot_CSR e on e.CSRUNIQ=a.CSRUNIQ and e.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.RCPUNIQ='$rcpuniq' ");
        return $query->getRowArray();
    }


    function get_rcp_open_by_id($rcpuniq, $csruniq)
    {
        $query = $this->db->query("select  
        b.CSRUNIQ,b.CSRLUNIQ,c.QTY,sum(b.QTY) as S_QTYRCP
        from webot_RECEIPTS a inner join webot_RCPL b on b.RCPUNIQ=a.RCPUNIQ
		left join webot_CSRL c on c.CSRUNIQ=b.CSRUNIQ and c.CSRLUNIQ=b.CSRLUNIQ
        where b.CSRUNIQ='$csruniq'
		group by b.CSRUNIQ,b.CSRLUNIQ,c.QTY");
        return $query->getResultArray();
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

    function rcpline_insert($datal)
    {
        $query = $this->db->table('webot_RCPL')->insertBatch($datal);
        return $query;
    }

    function goodreceipt_update($rcpuniq, $data)
    {
        $query = $this->db->table('webot_RECEIPTS')->update($data, array('RCPUNIQ' => $rcpuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_goodreceipt_update($csruniq, $csrluniq, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $csruniq, 'CSRLUNIQ' => $csrluniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function delete_rcp_open($rcpuniq)
    {
        return $this->db->table('webot_RECEIPTS')->delete(['RCPUNIQ' => $rcpuniq, 'POSTINGSTAT' => 0]);
    }

    function delete_rcpl_open($rcpuniq)
    {
        return $this->db->table('webot_RCPL')->delete(['RCPUNIQ' => $rcpuniq]);
    }


    function get_goodreceipt_post($po_uniq)
    {
        $query = $this->db->query("select top 1 * from webot_RECEIPTS 
        where POSTINGSTAT=1 and POUNIQ='$po_uniq'
        order by RECPDATE desc");
        return $query->getRowArray();
    }


    function count_gr_posting()
    {
        $builder = $this->db->table('webot_RECEIPTS');
        $builder->where('webot_RECEIPTS.POSTINGSTAT=', 1);
        return $builder->countAllResults();
    }
    function get_gr_preview($nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.*
        from webot_RECEIPTS a 
        left join webot_CSR b ON a.CSRUNIQ = b.CSRUNIQ
        left join webot_PO c on c.POUNIQ = a.POUNIQ and b.CSRUNIQ = c.CSRUNIQ 
        where (a.POSTINGSTAT=1 and (a.RECPDATE BETWEEN $nfromdate and $ntodate))");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_gr_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_RECEIPTS a left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ 
        left join webot_PO c on b.CSRUNIQ = c.CSRUNIQ and a.POUNIQ=c.POUNIQ
        where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
         or b.CRMREMARKS like '%$keyword%'  or c.PONUMBER like '%$keyword%' or a.RECPNUMBER like '%$keyword%' or a.VDNAME like '%$keyword%' 
       or a.DESCRIPTIO like '%$keyword%' ) and
        (a.RECPDATE>=$nfromdate and a.RECPDATE<=$ntodate)
        order by a.RECPDATE asc");
        return $query->getResultArray();
    }
}
