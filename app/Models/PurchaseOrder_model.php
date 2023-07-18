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

    protected $table = 'webot_PO';

    function __construct()
    {
        parent::__construct();
    }


    function get_requisition_pending()
    {
        $query = $this->db->query("select a.RQNUNIQ,a.RQNNUMBER,a.RQNDATE,a.POSTINGSTAT as RQNPOSTINGSTAT,a.OFFLINESTAT as RQNOFFLINESTAT,
        b.*,c.RQNUNIQPOPOST,c.POPOSTINGSTAT,c.POOFFLINESTAT,c.CTPOSTINGSTATPOPOST,ISNULL(c.CTITEMPO,0) as CTITEMPO,
		d.RQNUNIQPOOPEN,ISNULL(d.CTITEMPOOPEN,0) as CTITEMPOOPEN
        from webot_REQUISITION a 
        left join (select x.CSRUNIQ,x.CONTRACT,x.CTDESC,x.PROJECT,x.PRJDESC,x.PONUMBERCUST,x.PODATECUST,x.CUSTOMER,x.NAMECUST,x.CRMNO,x.CRMREQDATE,x.CRMREMARKS,
        x.MANAGER,x.SALESNAME,x.ORDERDESC,x.POSTINGSTAT as CSRPOSTINGSTAT,x.OFFLINESTAT as CSROFFLINESTAT,count(y.CSRLUNIQ) as CTITEMCSR
		from webot_CSR x inner join webot_CSRL y on y.CSRUNIQ=x.CSRUNIQ
		where x.POSTINGSTAT=1
		group by x.CSRUNIQ,x.CONTRACT,x.CTDESC,x.PROJECT,x.PRJDESC,x.PONUMBERCUST,x.PODATECUST,x.CUSTOMER,x.NAMECUST,x.CRMNO,x.CRMREQDATE,x.CRMREMARKS,
        x.MANAGER,x.SALESNAME,x.ORDERDESC,x.POSTINGSTAT,x.OFFLINESTAT
		) b on b.CSRUNIQ=a.CSRUNIQ
        left join (select x.RQNUNIQ as RQNUNIQPOPOST,MIN(x.POSTINGSTAT) as POPOSTINGSTAT,MAX(x.OFFLINESTAT) as POOFFLINESTAT,COUNT( DISTINCT x.POSTINGSTAT) as CTPOSTINGSTATPOPOST,count(y.POLUNIQ) as CTITEMPO
		from webot_PO x inner join webot_POL y on y.POUNIQ=x.POUNIQ
		where x.POSTINGSTAT=1
		group by 
		x.RQNUNIQ
		) c on c.RQNUNIQPOPOST=a.RQNUNIQ 
		left join (select x.RQNUNIQ as RQNUNIQPOOPEN,count(y.POLUNIQ) as CTITEMPOOPEN
		from webot_PO x inner join webot_POL y on y.POUNIQ=x.POUNIQ
		where x.POSTINGSTAT=0
		group by 
		x.RQNUNIQ) d on d.RQNUNIQPOOPEN=a.RQNUNIQ 
		
        where (a.POSTINGSTAT=1 and c.RQNUNIQPOPOST IS NULL) or (b.CTITEMCSR<>c.CTITEMPO) or (c.POPOSTINGSTAT=0) or (c.POOFFLINESTAT=1)
        order by a.RQNDATE asc,a.RQNNUMBER asc, b.CUSTOMER asc,b.CONTRACT asc");

        return $query->getResultArray();
    }

    function get_polist_on_poopen()
    {
        $query = $this->db->query("select distinct a.RQNUNIQ,a.RQNNUMBER,a.RQNDATE,c.POUNIQ,c.PONUMBER,c.PODATE,c.ETDDATE,c.CARGOREADINESSDATE,c.ORIGINCOUNTRY,c.POREMARKS,c.POSTINGSTAT as POPOSTINGSTAT,c.OFFLINESTAT as POOFFLINESTAT
        from webot_REQUISITION a
        inner join webot_PO c on c.RQNUNIQ=a.RQNUNIQ 
        where (a.POSTINGSTAT=1 and c.RQNUNIQ IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1 and c.CARGOREADINESSDATE IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1 and c.OFFLINESTAT=1)
        order by c.PONUMBER asc,c.PODATE asc");

        return $query->getResultArray();
    }

    //CSR Line
    function get_csrl_list_post()
    {
        $query = $this->db->query("select b.*,c.PONUMBER,c.PODATE,c.ETDDATE,c.CARGOREADINESSDATE,c.ORIGINCOUNTRY,c.POREMARKS,
        c.POSTINGSTAT as POPOSTINGSTAT,c.OFFLINESTAT as POOFFLINESTAT
        from webot_REQUISITION a 
        left join (select x.* from webot_CSRL x inner join webot_CSR y on y.CSRUNIQ=x.CSRUNIQ) b on b.CSRUNIQ=a.CSRUNIQ
        left join (select n.*,m.CSRLUNIQ from webot_POL m inner join webot_PO n on n.POUNIQ=m.POUNIQ) c on c.CSRUNIQ=b.CSRUNIQ and c.CSRLUNIQ=b.CSRLUNIQ 
        where (a.POSTINGSTAT=1 and c.RQNUNIQ IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=0) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1 and c.CARGOREADINESSDATE IS NULL) or (a.POSTINGSTAT=1 and c.POSTINGSTAT=1 and c.OFFLINESTAT=1)
        order by c.PONUMBER asc,c.CSRUNIQ asc, c.CSRLUNIQ asc,b.CSRUNIQ asc, b.CSRLUNIQ asc");
        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }


    function get_requisition_pending_search($keyword)
    {
        $query = $this->db->query("select a.RQNUNIQ,a.RQNNUMBER,a.RQNDATE,a.POSTINGSTAT as RQNPOSTINGSTAT,a.OFFLINESTAT as RQNOFFLINESTAT,
        b.*,c.RQNUNIQPOPOST,c.POPOSTINGSTAT,c.POOFFLINESTAT,c.CTPOSTINGSTATPOPOST,ISNULL(c.CTITEMPO,0) as CTITEMPO,
		d.RQNUNIQPOOPEN,ISNULL(d.CTITEMPOOPEN,0) as CTITEMPOOPEN
        from webot_REQUISITION a 
        left join (select x.CSRUNIQ,x.CONTRACT,x.CTDESC,x.PROJECT,x.PRJDESC,x.PONUMBERCUST,x.PODATECUST,x.CUSTOMER,x.NAMECUST,x.CRMNO,x.CRMREQDATE,x.CRMREMARKS,
        x.MANAGER,x.SALESNAME,x.ORDERDESC,x.POSTINGSTAT as CSRPOSTINGSTAT,x.OFFLINESTAT as CSROFFLINESTAT,count(y.CSRLUNIQ) as CTITEMCSR
		from webot_CSR x inner join webot_CSRL y on y.CSRUNIQ=x.CSRUNIQ
		where x.POSTINGSTAT=1
		group by x.CSRUNIQ,x.CONTRACT,x.CTDESC,x.PROJECT,x.PRJDESC,x.PONUMBERCUST,x.PODATECUST,x.CUSTOMER,x.NAMECUST,x.CRMNO,x.CRMREQDATE,x.CRMREMARKS,
        x.MANAGER,x.SALESNAME,x.ORDERDESC,x.POSTINGSTAT,x.OFFLINESTAT
		) b on b.CSRUNIQ=a.CSRUNIQ
        left join (select x.RQNUNIQ as RQNUNIQPOPOST,MIN(x.POSTINGSTAT) as POPOSTINGSTAT,MAX(x.OFFLINESTAT) as POOFFLINESTAT,COUNT( DISTINCT x.POSTINGSTAT) as CTPOSTINGSTATPOPOST,count(y.POLUNIQ) as CTITEMPO
		from webot_PO x inner join webot_POL y on y.POUNIQ=x.POUNIQ
		where x.POSTINGSTAT=1
		group by 
		x.RQNUNIQ
		) c on c.RQNUNIQPOPOST=a.RQNUNIQ 
		left join (select x.RQNUNIQ as RQNUNIQPOOPEN,count(y.POLUNIQ) as CTITEMPOOPEN
		from webot_PO x inner join webot_POL y on y.POUNIQ=x.POUNIQ
		where x.POSTINGSTAT=0
		group by 
		x.RQNUNIQ) d on d.RQNUNIQPOOPEN=a.RQNUNIQ 
		
        where ((a.POSTINGSTAT=1 and c.RQNUNIQPOPOST IS NULL) or (b.CTITEMCSR<>c.CTITEMPO) or (c.POPOSTINGSTAT=0) or (c.POOFFLINESTAT=1))
        
        and (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.PROJECT like '%$keyword%' or b.CRMNO like '%$keyword%' 
        or b.PONUMBERCUST like '%$keyword%' or b.NAMECUST like '%$keyword%' or a.RQNNUMBER like '%$keyword%')
        order by a.RQNDATE asc,a.RQNNUMBER asc, b.CUSTOMER asc,b.CONTRACT asc");
        return $query->getResultArray();
    }

    function get_pobeforeetd()
    {
        $query = $this->db->query("select x.*,
        a.RQNUNIQ,a.RQNNUMBER,a.RQNDATE,a.POSTINGSTAT as RQNPOSTINGSTAT,a.OFFLINESTAT as RQNOFFLINESTAT,
                b.CONTRACT,b.CTDESC,b.MANAGER,b.SALESNAME,b.PROJECT,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.CUSTOMER,b.NAMECUST,
				b.EMAIL1CUST,b.CRMNO,b.CRMREQDATE,b.ORDERDESC,b.CRMREMARKS
                from (
                    select *,
                    convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101) as F_ETDDATE,
                    DATEDIFF(day, convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101),GETDATE())as diff 
                    from webot_PO where POSTINGSTAT=1 or OFFLINESTAT=1
                    ) x 
                left join webot_REQUISITION a on a.RQNUNIQ=x.RQNUNIQ
                left join webot_CSR b on b.CSRUNIQ=x.CSRUNIQ
        
                 where (x.CARGOREADINESSDATE is NULL and ((x.diff BETWEEN -15 and 0) or x.diff>0) or x.OFFLINESTAT=1)
                    order by x.ETDDATE asc");
        //Show when Cargoreadiness NULL and x
        return $query->getResultArray();
    }

    function get_pobeforeetd_search($keyword)
    {
        $query = $this->db->query("select x.*,
        a.RQNUNIQ,a.RQNNUMBER,a.RQNDATE,a.POSTINGSTAT as RQNPOSTINGSTAT,a.OFFLINESTAT as RQNOFFLINESTAT,
                b.CONTRACT,b.CTDESC,b.MANAGER,b.SALESNAME,b.PROJECT,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.CUSTOMER,b.NAMECUST,
				b.EMAIL1CUST,b.CRMNO,b.CRMREQDATE,b.ORDERDESC,b.CRMREMARKS
                from (
                    select *,
                    convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101) as F_ETDDATE,
                    DATEDIFF(day, convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101),GETDATE())as diff 
                    from webot_PO where POSTINGSTAT=1 or OFFLINESTAT=1
                    ) x 
                left join webot_REQUISITION a on a.RQNUNIQ=x.RQNUNIQ
                left join webot_CSR b on b.CSRUNIQ=x.CSRUNIQ
        
                 where ((x.CARGOREADINESSDATE is NULL and ((x.diff BETWEEN -15 and 0) or x.diff>0) or x.OFFLINESTAT=1))
            and (b.CONTRACT like '%$keyword%' or b.PROJECT like '%$keyword%' or b.CRMNO like '%$keyword%' or b.CTDESC like '%$keyword%' 
            or b.NAMECUST like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or a.RQNNUMBER like '%$keyword%' 
            or x.PONUMBER like '%$keyword%' or x.ORIGINCOUNTRY like '%$keyword%' or x.POREMARKS like '%$keyword%')
            order by x.ETDDATE asc");
        //Show when Cargoreadiness NULL and x
        return $query->getResultArray();
    }


    function get_requisition_by_id($rqnuniq)
    {
        $query = $this->db->query("select a.RQNUNIQ,a.RQNDATE,a.RQNNUMBER,a.POSTINGSTAT as RQNPOSTINGSTAT,a.OFFLINESTAT as RQNOFFLINESTAT,
        b.* from webot_REQUISITION a 
        inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ 
        where a.POSTINGSTAT=1 and a.RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }


    function get_so_l_by_id($rqnuniq)
    {
        $query = $this->db->query("select a.*,d.POUNIQ,d.POLUNIQ
        from webot_CSRL a inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        inner join webot_REQUISITION c on c.CSRUNIQ=b.CSRUNIQ and c.CSRUNIQ=a.CSRUNIQ
		left join webot_POL d on d.CSRUNIQ=a.CSRUNIQ and d.CSRLUNIQ=a.CSRLUNIQ
        where c.RQNUNIQ='$rqnuniq' and d.POUNIQ is NULL");
        return $query->getResultArray();
    }

    function get_po_l_by_id($id_po)
    {
        $query = $this->db->query("select b.*,c.POUNIQ,c.POLUNIQ,c.PONUMBER,c.PODATE,c.ETDDATE,c.ORIGINCOUNTRY,c.POREMARKS,
        c.POSTINGSTAT as POPOSTINGSTAT,c.OFFLINESTAT as POOFFLINESTAT
        from (select x.* from webot_CSRL x inner join webot_CSR y on y.CSRUNIQ=x.CSRUNIQ) b
        left join (select n.*,m.POLUNIQ,m.CSRLUNIQ from webot_POL m inner join webot_PO n on n.POUNIQ=m.POUNIQ) c on c.CSRUNIQ=b.CSRUNIQ and c.CSRLUNIQ=b.CSRLUNIQ 
        where c.POUNIQ='$id_po'
        order by b.CSRUNIQ asc, b.CSRLUNIQ asc");
        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }


    function get_pouniq_open($id_so, $rqnnumber, $ponumber)
    {
        $query = $this->db->query("select DISTINCT a.POUNIQ,a.RQNNUMBER,a.PONUMBER,a.POKEY,COUNT(b.POUNIQ) as CHKPOL from webot_PO a
        left join webot_POL b on b.POUNIQ=a.POUNIQ
        where a.CSRUNIQ='$id_so' and a.RQNNUMBER='$rqnnumber' and a.PONUMBER='$ponumber'
        group by a.POUNIQ,a.RQNNUMBER,a.PONUMBER,a.POKEY");
        return $query->getRowArray();
    }

    function get_po_open_by_id($pouniq)
    {
        $query = $this->db->query("select a.POUNIQ,a.POKEY,a.PODATE,a.PONUMBER,a.ETDDATE,a.CARGOREADINESSDATE,a.ORIGINCOUNTRY,a.POREMARKS, 
        b.CSRUNIQ,b.CSRLUNIQ
        from webot_PO a inner join webot_POL b on b.POUNIQ=a.POUNIQ
        where a.POUNIQ='$pouniq'");
        return $query->getResultArray();
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

    function get_pojoincsr_by_po($pouniq)
    {
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNDATE,b.RQNNUMBER, 
        c.*
        from webot_PO a inner join webot_REQUISITION b on b.RQNUNIQ=a.RQNUNIQ
        left join webot_CSR c on c.CSRUNIQ=b.CSRUNIQ and c.CSRUNIQ=a.CSRUNIQ
        where a.POSTINGSTAT=1 and a.POUNIQ='$pouniq' ");
        return $query->getRowArray();
    }



    function get_po_list_sage_by_rqn($rqnnumber)
    {
        $query = $this->db->query("select RQNNUMBER," . '"DATE"' . " as PODATE,EXPARRIVAL,PONUMBER,VDCODE,VDNAME,DESCRIPTIO,REFERENCE 
        from POPORH1 
        where PONUMBER not in (select DISTINCT PONUMBER from webot_PO where POSTINGSTAT=1) and RQNNUMBER='$rqnnumber'");
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



    function count_po_posting()
    {
        $builder = $this->db->table('webot_PO');
        $builder->join('webot_REQUISITION a', 'a.RQNUNIQ = webot_PO.RQNUNIQ', 'left');
        $builder->join('webot_CSR b', 'b.CSRUNIQ = webot_PO.CSRUNIQ', 'left');
        $builder->where('webot_PO.POSTINGSTAT', 1);
        $builder->where('webot_PO.CARGOREADINESSDATE>=', 1);
        return $builder->countAllResults();
    }

    /*
    function count_po_beforeetd()
    {
        $builder = $this->db->table('webot_PO');
        $builder->where('webot_PO.POSTINGSTAT', 1);
        $builder->where('webot_PO.CARGOREADINESSDATE>=', 1);
        $builder->where('DATEDIFF(day, convert(nvarchar(20),cast(cast(ETDDATE as nvarchar(20)) as date), 101),GETDATE())>=', -15);
        return $builder->countAllResults();
    }*/

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


    function poline_insert($datal)
    {
        $query = $this->db->table('webot_POL')->insertBatch($datal);
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

    function ot_purchaseorder_update($id_so, $csrluniq, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so, 'CSRLUNIQ' => $csrluniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function delete_po_open($pouniq)
    {
        return $this->db->table('webot_PO')->delete(['POUNIQ' => $pouniq, 'POSTINGSTAT' => 0]);
    }

    function delete_pol_open($pouniq)
    {
        return $this->db->table('webot_POL')->delete(['POUNIQ' => $pouniq]);
    }



    function get_purchaseorder_preview($nfromdate, $ntodate)
    {
        $query = $this->db->query("select c.RQNDATE,b.CONTRACT,b.CTDESC,b.NAMECUST,a.*
        from webot_PO a 
        left join webot_CSR b on b.CSRUNIQ= a.CSRUNIQ
		left join webot_REQUISITION c on a.RQNUNIQ=c.RQNUNIQ and  b.CSRUNIQ = c.CSRUNIQ
        where (a.POSTINGSTAT=1) and (a.PODATE BETWEEN $nfromdate and $ntodate)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }
    function get_po_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_PO a left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ 
        left join webot_REQUISITION c on b.CSRUNIQ = c.CSRUNIQ and a.RQNUNIQ=c.RQNUNIQ
        where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
        or b.CRMREMARKS like '%$keyword%' or c.RQNNUMBER like '%$keyword%' or a.PONUMBER like '%$keyword%' or a.ORIGINCOUNTRY like '%$keyword%'
        or a.POREMARKS like '%$keyword%') and
        (a.PODATE>=$nfromdate and a.PODATE<=$ntodate)
        order by a.PODATE asc");
        return $query->getResultArray();
    }
}
