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



    // DN
    function get_gr_pending_to_dn()
    {
        $query = $this->db->query("select a.CSRUNIQ as RCPCSRUNIQ,a.POSTINGSTAT,
        b.CSRUNIQ as CRMCSRUNIQ,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . "  as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,
		b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC, 
		grcpl.RCPQTY,gshil.SHIQTY,gshil.SHIATTACHED,gshil.SHIPOSTINGSTAT,gshil.SHIOFFLINESTAT
        from (select DISTINCT CSRUNIQ,POSTINGSTAT from webot_RECEIPTS where POSTINGSTAT=1) a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (	select CSRUNIQ,count(ITEMNO) as ROWITEMPO,sum(QTY) as RCPQTY from webot_RCPL
		group by CSRUNIQ) grcpl on grcpl.CSRUNIQ=a.CSRUNIQ
		left join (select x.CSRUNIQ,COUNT(ITEMNO) as ROWITEMSHI,sum(QTY) as SHIQTY,MIN(y.SHIATTACHED) as SHIATTACHED,MIN(y.POSTINGSTAT) as SHIPOSTINGSTAT,MAX(y.OFFLINESTAT) as SHIOFFLINESTAT,COUNT( DISTINCT y.POSTINGSTAT) as CTPOSTINGSTATSHIPOST 
		from webot_SHIL x inner join webot_SHIPMENTS y on y.SHIUNIQ=x.SHIUNIQ
		group by x.CSRUNIQ) gshil on gshil.CSRUNIQ=b.CSRUNIQ
        where (a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT IS NULL) or (a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT=0) 
        or (a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT=0) 
        or (gshil.SHIPOSTINGSTAT=1 and gshil.SHIOFFLINESTAT=1) or (grcpl.RCPQTY<>gshil.SHIQTY) or (gshil.SHIPOSTINGSTAT=1 and gshil.SHIOFFLINESTAT=0 and gshil.SHIATTACHED=0)");

        return $query->getResultArray();
    }

    //PO Line
    function get_csrl_list_to_ship_post()
    {
        $query = $this->db->query("select a.*,b.POUNIQ,b.PONUMBER,isnull(c.QTYRCP,0) as RCPQTY,
        CASE WHEN (a.QTY-isnull(c.QTYRCP,0)=0) THEN 'COMPLETED'
            WHEN (c.QTYRCP is NULL) THEN 'WAITING'
            WHEN (a.QTY-isnull(c.QTYRCP,0)>0) THEN 'PARTIAL'
        END as RCPSTATUS,(select top 1 x.SHIDATE from webot_SHIPMENTS x inner join webot_SHIL y on y.CSRUNIQ=d.CSRUNIQ and y.CSRLUNIQ=d.CSRLUNIQ
		order by SHIDATE desc) as L_SHIDATE,
		d.SHIQTY,d.SHIQTYOUTSTANDING
                from webot_CSRL a
                left join webot_PO b on b.CSRUNIQ=a.CSRUNIQ
                left join (select x.CSRUNIQ,x.CSRLUNIQ,sum(x.QTY) as QTYRCP from webot_RCPL x 
                inner join webot_RECEIPTS y on y.RCPUNIQ=x.RCPUNIQ
				where y.POSTINGSTAT=1
                group by x.CSRUNIQ,x.CSRLUNIQ) c on c.CSRUNIQ=a.CSRUNIQ and c.CSRLUNIQ=a.CSRLUNIQ
				left join (select x.CSRUNIQ,x.CSRLUNIQ,sum(x.QTY) as SHIQTY,sum(x.SHIQTYOUTSTANDING) as SHIQTYOUTSTANDING 
				from webot_SHIL x inner join webot_SHIPMENTS y on y.SHIUNIQ=x.SHIUNIQ
				group by x.CSRUNIQ,x.CSRLUNIQ) 
				d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
                order by a.CSRUNIQ asc, a.CSRLUNIQ asc
		");
        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }


    function get_shilist_on_shiopen()
    {
        $query = $this->db->query("select distinct c.CSRUNIQ,C.SHIUNIQ,c.SHIDATE,c.DOCNUMBER,c.SHINUMBER,c.SHIATTACHED,c.POSTINGSTAT as SHIPOSTINGSTAT,c.OFFLINESTAT as SHIOFFLINESTAT
        from webot_CSR a
        inner join webot_SHIPMENTS c on c.CSRUNIQ=a.CSRUNIQ 
        where (a.POSTINGSTAT=1 and c.RCPUNIQ IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1)
        order by c.SHIDATE asc,c.DOCNUMBER asc");

        return $query->getResultArray();
    }


    function get_gr_pending_to_dn_search($keyword)
    {
        $query = $this->db->query("select a.CSRUNIQ as RCPCSRUNIQ,a.POSTINGSTAT,
        b.CSRUNIQ as CRMCSRUNIQ,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . "  as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,
		b.CRMNO,b.CRMREQDATE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC, 
		grcpl.RCPQTY,gshil.SHIQTY,gshil.SHIATTACHED,gshil.SHIPOSTINGSTAT,gshil.SHIOFFLINESTAT
        from (select DISTINCT CSRUNIQ,POSTINGSTAT from webot_RECEIPTS where POSTINGSTAT=1) a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (	select CSRUNIQ,count(ITEMNO) as ROWITEMPO,sum(QTY) as RCPQTY from webot_RCPL
		group by CSRUNIQ) grcpl on grcpl.CSRUNIQ=a.CSRUNIQ
		left join (select x.CSRUNIQ,COUNT(ITEMNO) as ROWITEMSHI,sum(QTY) as SHIQTY,MIN(y.SHIATTACHED) as SHIATTACHED,MIN(y.POSTINGSTAT) as SHIPOSTINGSTAT,MAX(y.OFFLINESTAT) as SHIOFFLINESTAT,COUNT( DISTINCT y.POSTINGSTAT) as CTPOSTINGSTATSHIPOST 
		from webot_SHIL x inner join webot_SHIPMENTS y on y.SHIUNIQ=x.SHIUNIQ
		group by x.CSRUNIQ) gshil on gshil.CSRUNIQ=b.CSRUNIQ

        where ((a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT IS NULL) or (a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT=0) 
        or (a.CSRUNIQ IS NOT NULL and gshil.SHIPOSTINGSTAT=0) 
        or (gshil.SHIPOSTINGSTAT=1 and gshil.SHIOFFLINESTAT=1) or (grcpl.RCPQTY<>gshil.SHIQTY) or (gshil.SHIPOSTINGSTAT=1 and gshil.SHIOFFLINESTAT=0 and gshil.SHIATTACHED=0))
        and (b.CONTRACT like '%$keyword%' or b.PROJECT like '%$keyword%' or b.CRMNO like '%$keyword%' or b.CTDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.NAMECUST like '%$keyword%'
        )");

        return $query->getResultArray();
    }

    function get_grlist_posting($pouniq, $itemno)
    {
        $query = $this->db->query("select a.POUNIQ,a.RCPUNIQ,a.RECPNUMBER,a.RECPDATE,b.ITEMNO,b.QTY,a.POSTINGSTAT as RCPPOSTINGSTAT,a.OFFLINESTAT as RCPOFFLINESTAT
        from webot_RECEIPTS a
		inner join webot_RCPL b on b.RCPUNIQ=a.RCPUNIQ
        where a.POUNIQ='$pouniq' and b.ITEMNO='$itemno' and a.POSTINGSTAT=1
        order by RECPDATE asc,RECPNUMBER asc");

        return $query->getResultArray();
    }

    function get_shilist_posting($pouniq, $itemno)
    {
        $query = $this->db->query("select b.POUNIQ,a.SHIUNIQ,a.DOCNUMBER,a.SHINUMBER,a.SHIDATE,b.ITEMNO,b.QTY,a.POSTINGSTAT as SHIPOSTINGSTAT,a.OFFLINESTAT as SHIOFFLINESTAT
        from webot_SHIPMENTS a
		inner join webot_SHIL b on b.SHIUNIQ=a.SHIUNIQ
        where b.POUNIQ='$pouniq' and b.ITEMNO='$itemno'
        order by SHIDATE asc,DOCNUMBER asc");

        return $query->getResultArray();
    }


    function get_shi_pending_to_dnorigin()
    {
        $query = $this->db->query("select a.*,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC
        from webot_SHIPMENTS a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where (a.EDNPOSTINGSTAT=1 and a.EDNFILENAME IS NOT NULL and (a.DNPOSTINGSTAT is NULL or a.DNPOSTINGSTAT=0 or a.DNOFFLINESTAT=1))");

        return $query->getResultArray();
    }


    function get_shi_pending_to_dnorigin_search($keyword)
    {
        $query = $this->db->query("select a.*,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC
        from webot_SHIPMENTS a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1 and a.EDNFILENAME IS NOT NULL and (a.DNPOSTINGSTAT is NULL or a.DNPOSTINGSTAT=0 or a.DNOFFLINESTAT=1))
        and (b.CONTRACT like '%$keyword%' or b.PROJECT like '%$keyword%' or b.CRMNO like '%$keyword%' or b.CTDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.NAMECUST like '%$keyword%'
        or a.DOCNUMBER like '%$keyword%' or a.SHINUMBER like '%$keyword%')");

        return $query->getResultArray();
    }

    function get_rcp_l_by_id($csruniq)
    {
        $query = $this->db->query("select polforrcpl .*,(S_RCPQTY-S_SHIQTY) as SHIQTY_OUTS
        from (
                    select a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,d.PONUMBER,d.PODATE,isnull(sum(e.S_RCPQTY),0) as S_RCPQTY,(a.QTY-isnull(sum(e.S_RCPQTY),0)) as RCPQTY_OUTS,
                    isnull(sum(f.SHIQTY),0) as S_SHIQTY
                            from webot_CSRL a inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
                            left join (select y.PONUMBER,y.PODATE,x.* from webot_POL x inner join webot_PO y on y.POUNIQ=x.POUNIQ) d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
                            left join (select x.POUNIQ,y.POLUNIQ,sum(y.QTY) as S_RCPQTY from webot_RECEIPTS x inner join webot_RCPL y on y.RCPUNIQ=x.RCPUNIQ where x.POSTINGSTAT=1
							group by x.POUNIQ,y.POLUNIQ) e on e.POUNIQ=d.POUNIQ and e.POLUNIQ=d.POLUNIQ
                            left join (select x.CSRUNIQ,x.CSRLUNIQ,sum(x.QTY) as SHIQTY,sum(x.SHIQTYOUTSTANDING) as SHIQTYOUTSTANDING 
				from webot_SHIL x inner join webot_SHIPMENTS y on y.SHIUNIQ=x.SHIUNIQ
				group by x.CSRUNIQ,x.CSRLUNIQ) f on f.CSRUNIQ=a.CSRUNIQ and f.CSRLUNIQ=a.CSRLUNIQ
                            where d.CSRUNIQ='$csruniq'
                            group by a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,d.PONUMBER,d.PODATE) polforrcpl 
                            where (polforrcpl.S_RCPQTY-polforrcpl.S_SHIQTY)>0 ");
        return $query->getResultArray();
    }


    function get_csr_outstanding_shi($csruniq)
    {
        $query = $this->db->query("select * from webot_CSR where POSTINGSTAT=1 and CSRUNIQ='$csruniq' ");
        return $query->getRowArray();
    }

    function list_sage_shi_tf($uf_pocustdate)
    {
        $query = $this->db->query("select DOCUNIQ,DOCNUM,HDRDESC,TRANSDATE,REFERENCE from ICTREH 
        where TRANSDATE>='$uf_pocustdate' and DOCNUM NOT IN (select x.DOCNUMBER from webot_SHIPMENTS x where x.POSTINGSTAT=1)
        order by TRANSDATE desc,DOCNUM asc");
        return $query->getResultArray();
    }

    function list_sage_shi_pm($ct_no, $uf_pocustdate)
    {
        $query = $this->db->query("select a.SEQ,a.MATERIALNO,a." . '"DESC"' . " as HDRDESC,a.TRANSDATE,a.REFERENCE from PMMATH a
		where a.TRANSDATE>='$uf_pocustdate' and a.SEQ in (select distinct SEQ from PMMATD where FMTCONTNO='$ct_no' )
		and a.MATERIALNO NOT IN (select x.DOCNUMBER from webot_SHIPMENTS x where x.POSTINGSTAT=1) and COMPLETE=40
		order by a.TRANSDATE desc,a.MATERIALNO asc");
        return $query->getResultArray();
    }

    function get_ic_transfer_sage_by_doc($sage_tfdocuniq)
    {
        $query = $this->db->query("select DOCUNIQ,DOCNUM,HDRDESC,TRANSDATE,REFERENCE from ICTREH where DOCNUM='$sage_tfdocuniq'");
        return $query->getRowArray();
    }

    function get_pjc_material_sage_by_doc($sage_tfdocuniq)
    {
        $query = $this->db->query("select SEQ,MATERIALNO," . '"DESC"' . " as HDRDESC,TRANSDATE,REFERENCE from PMMATH where MATERIALNO='$sage_tfdocuniq'");
        return $query->getRowArray();
    }

    function get_shiuniq_open($csruniq, $docnumber)
    {
        $query = $this->db->query("select DISTINCT a.SHIUNIQ,a.DOCNUMBER,a.SHIKEY,COUNT(b.SHIUNIQ) as CHKSHIL from webot_SHIPMENTS a
        left join webot_SHIL b on b.SHIUNIQ=a.SHIUNIQ
        where a.CSRUNIQ='$csruniq' and a.DOCNUMBER='$docnumber'
        group by a.SHIUNIQ,a.DOCNUMBER,a.SHIKEY");
        return $query->getRowArray();
    }

    function get_shipment_open($shiuniq)
    {
        $query = $this->db->query("select a.*,b.CSRUNIQ," . 'b."CONTRACT"' . " as CONTRACT,b.PROJECT,b.CRMNO,b.CTDESC,b.PRJDESC,b.NAMECUST,b.EMAIL1CUST,b.MANAGER,b.SALESNAME,b.PONUMBERCUST,b.PODATECUST,
        b.CRMREQDATE,b.PODATECUST from webot_SHIPMENTS a 
                left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ where a.POSTINGSTAT <>2 and a.SHIUNIQ='$shiuniq'");
        return $query->getRowArray();
    }

    function get_shi_l_by_id($shiuniq)
    {
        $query = $this->db->query("select a.* from webot_SHIL a 
        where a.SHIUNIQ='$shiuniq'");
        return $query->getResultArray();
    }


    function get_csr_uniq($csr_uniq)
    {
        $query = $this->db->query("select a.* from webot_CSR a where a.POSTINGSTAT =1 and a.CSRUNIQ='$csr_uniq'");
        return $query->getRowArray();
    }


    function get_shipment_post($shiuniq)
    {
        $query = $this->db->query("select a.*,b.EMAIL1CUST from webot_SHIPMENTS a
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.SHIUNIQ='$shiuniq' ");
        return $query->getRowArray();
    }

    function get_po_l_item($csr_uniq, $itemno)
    {
        $query = $this->db->query("select polforrcpl .*,(S_RCPQTY-S_SHIQTY) as SHIQTY_OUTS
        from (
                    select a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,d.PONUMBER,d.PODATE,isnull(sum(e.S_RCPQTY),0) as S_RCPQTY,(a.QTY-isnull(sum(e.S_RCPQTY),0)) as RCPQTY_OUTS,
                    isnull(sum(f.SHIQTY),0) as S_SHIQTY
                            from webot_CSRL a inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
                            left join (select y.PONUMBER,y.PODATE,x.* from webot_POL x inner join webot_PO y on y.POUNIQ=x.POUNIQ) d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
                            left join (select x.POUNIQ,y.POLUNIQ,sum(y.QTY) as S_RCPQTY from webot_RECEIPTS x inner join webot_RCPL y on y.RCPUNIQ=x.RCPUNIQ where x.POSTINGSTAT=1
							group by x.POUNIQ,y.POLUNIQ) e on e.POUNIQ=d.POUNIQ and e.POLUNIQ=d.POLUNIQ
                            left join (select x.CSRUNIQ,x.CSRLUNIQ,sum(x.QTY) as SHIQTY,sum(x.SHIQTYOUTSTANDING) as SHIQTYOUTSTANDING 
				from webot_SHIL x inner join webot_SHIPMENTS y on y.SHIUNIQ=x.SHIUNIQ
				group by x.CSRUNIQ,x.CSRLUNIQ) f on f.CSRUNIQ=a.CSRUNIQ and f.CSRLUNIQ=a.CSRLUNIQ
                            where d.CSRUNIQ='$csr_uniq' and a.ITEMNO='$itemno'
                            group by a.CSRUNIQ,a.CSRLUNIQ,a.SERVICETYPE,a.ITEMNO,a.MATERIALNO,a.ITEMDESC,a.STOCKUNIT,a.QTY,d.POUNIQ,d.POLUNIQ,d.PONUMBER,d.PODATE) polforrcpl");
        return $query->getRowArray();
    }


    function get_shi_open_by_id($shiuniq, $csruniq)
    {
        $query = $this->db->query("select * from (select b.CSRUNIQ,b.CSRLUNIQ,c.QTY,sum(b.QTY) as S_SHIQTY
        from webot_SHIPMENTS a inner join webot_SHIL b on b.SHIUNIQ=a.SHIUNIQ
		left join webot_CSRL c on c.CSRUNIQ=b.CSRUNIQ and c.CSRLUNIQ=b.CSRLUNIQ
        where b.CSRUNIQ='$csruniq'
		group by b.CSRUNIQ,b.CSRLUNIQ,c.QTY ) ot where ot.CSRLUNIQ in (select distinct CSRLUNIQ from webot_SHIL where SHIUNIQ='$shiuniq')");
        return $query->getResultArray();
    }


    function get_shijoincsr_by_shi($shiuniq)
    {
        $query = $this->db->query("select a.*,b.*,c.*,d.*,e.*
        from webot_SHIPMENTS a
		left join webot_PO b on b.CSRUNIQ=a.CSRUNIQ
		left join webot_LOGISTICS c on c.POUNIQ=b.POUNIQ
		left join webot_REQUISITION d on d.RQNNUMBER=b.RQNNUMBER
        left join webot_CSR e on e.CSRUNIQ=a.CSRUNIQ and e.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.SHIUNIQ='$shiuniq' ");
        return $query->getRowArray();
    }


    function deliveryorders_insert($data)
    {
        $query = $this->db->table('webot_SHIPMENTS')->insert($data);
        return $query;
    }

    function shiline_insert($datal)
    {
        $query = $this->db->table('webot_SHIL')->insertBatch($datal);
        return $query;
    }

    function deliveryorders_update($shiuniq, $data)
    {
        $query = $this->db->table('webot_SHIPMENTS')->update($data, array('SHIUNIQ' => $shiuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_deliveryorders_update($csruniq, $csrluniq, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $csruniq, 'CSRLUNIQ' => $csrluniq));
        //Tanpa return juga bisa jalan
        return $query;
    }


    function delete_shi_open($shiuniq)
    {
        return $this->db->table('webot_SHIPMENTS')->delete(['SHIUNIQ' => $shiuniq, 'POSTINGSTAT' => 0]);
    }

    function delete_shil_open($shiuniq)
    {
        return $this->db->table('webot_SHIL')->delete(['SHIUNIQ' => $shiuniq]);
    }


    function get_dn_by_id($shiuniq)
    {
        $query = $this->db->query("select a.*,b.NAMECUST from webot_SHIPMENTS a
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.SHIUNIQ='$shiuniq' ");
        return $query->getRowArray();
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




    function get_delivery_post($csruniq, $shiuniq)
    {
        $query = $this->db->query("select top 1 a.*,c.CRMREQDATE,c.PODATECUST from webot_SHIPMENTS a 
        inner join webot_SHIL b on b.SHIUNIQ=a.SHIUNIQ
                left join webot_CSR c on c.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.CSRUNIQ='$csruniq' and b.SHIUNIQ='$shiuniq' 
        order by SHIDATE desc");
        return $query->getRowArray();
    }


    function count_gr_posting()
    {
        $builder = $this->db->table('webot_RECEIPTS');
        $builder->where('webot_RECEIPTS.POSTINGSTAT=', 1);
        return $builder->countAllResults();
    }
    function count_delivery_posting()
    {
        $query = $this->db->query("select a.*,b.NAMECUST," . 'it."DESC"' . " as SHIITEMDESC from webot_SHIPMENTS a
        left join ARCUS b on b.IDCUST=a.CUSTOMER
        left join ICITEM it on it.ITEMNO=a.SHIITEMNO
        where a.POSTINGSTAT=1");
        return $query->getRowArray();
    }

    function get_delivery_preview($nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,c.*,b.NAMECUST,d.* from webot_SHIPMENTS a
        left join ARCUS b on b.IDCUST=a.CUSTOMER
        left join webot_CSR c on a.CSRUNIQ =c.CSRUNIQ
        left join webot_REQUISITION d on c.CSRUNIQ =d.CSRUNIQ

        where a.POSTINGSTAT=1 and (a.SHIDATE>=$nfromdate and a.SHIDATE<=$ntodate)");
        return $query->getResultArray();
    }
    function get_delivery_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.NAMECUST,e.*,f.* from webot_SHIPMENTS a
        left join ARCUS c on c.IDCUST=a.CUSTOMER
        left join webot_CSR b on a.CSRUNIQ =b.CSRUNIQ
        left join webot_RECEIPTS d on d.RCPUNIQ = a.RCPUNIQ
        left join webot_REQUISITION e on b.CSRUNIQ =e.CSRUNIQ
        left join webot_PO f on b.CSRUNIQ = f.CSRUNIQ
        where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
      or b.CRMREMARKS like '%$keyword%'
       or a.SHINUMBER like '%$keyword%' or a.DOCNUMBER like '%$keyword%' or a.SHIREFERENCE like '%$keyword') and
        (a.SHIDATE>=$nfromdate and a.SHIDATE<=$ntodate)
        order by a.SHIDATE asc");
        return $query->getResultArray();
    }
}
