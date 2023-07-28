<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Finance_model extends Model
{

    protected $table = 'webot_FINANCE';

    function __construct()
    {
        parent::__construct();
    }



    //Finance

    function get_shi_pending_to_finance()
    {
        $query = $this->db->query("select 
        a.CSRUNIQ,a.CTDESC,a.PRJDESC,a.PONUMBERCUST,a.PODATECUST,a.NAMECUST," . 'a."CONTRACT"' . "  as CSRCONTRACT,a.CTDESC,a.PROJECT as CSRPROJECT,a.CRMNO,a.CRMREQDATE,
        a.CRMREMARKS,a.MANAGER,a.SALESNAME,a.ORDERDESC,
        b.ROWSHI,d.ROWSHI_F,c.ROWARINV,c.ARPOSTINGSTAT,c.AROFFLINESTAT,c.CTPOSTINGSTATARPOST
        from webot_CSR a 
		left join (select CSRUNIQ,count(SHIUNIQ) as ROWSHI from webot_SHIPMENTS where POSTINGSTAT=1 and EDNPOSTINGSTAT=1
		and DNPOSTINGSTAT=1 and DNSTATUS=1
		group by CSRUNIQ) b on b.CSRUNIQ=a.CSRUNIQ
        left join (select x.CSRUNIQ,COUNT(x.FINUNIQ) as ROWARINV,MIN(x.POSTINGSTAT) as ARPOSTINGSTAT,MAX(x.OFFLINESTAT) as AROFFLINESTAT,COUNT( DISTINCT x.POSTINGSTAT) as CTPOSTINGSTATARPOST 
		from webot_FINANCE x
		group by x.CSRUNIQ)  c on c.CSRUNIQ=a.CSRUNIQ
		left join (select CSRUNIQ,COUNT(FINLUNIQ) as ROWSHI_F from webot_FINMULTISHI group by CSRUNIQ) d on d.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1) and b.ROWSHI is NOT NULL and (c.ARPOSTINGSTAT=0 or c.ARPOSTINGSTAT IS NULL or b.ROWSHI<>d.ROWSHI_F)");

        return $query->getResultArray();
    }

    function get_shilist_on_shiopen()
    {
        $query = $this->db->query("select distinct c.CSRUNIQ,C.SHIUNIQ,c.SHIDATE,c.DOCNUMBER,c.SHINUMBER,c.CUSTRCPDATE,c.SHIATTACHED,c.POSTINGSTAT as SHIPOSTINGSTAT,c.OFFLINESTAT as SHIOFFLINESTAT,
        c.DNSTATUS,a.IDINVC,a.DATEINVC,a.FINSTATUS,a.POSTINGSTAT as FINPOSTINGSTAT
                from webot_SHIPMENTS c
                left join webot_FINMULTISHI b on b.SHIUNIQ=c.SHIUNIQ
                left join webot_FINANCE a on a.FINUNIQ=b.FINUNIQ
                where (c.POSTINGSTAT=1 and c.DNPOSTINGSTAT=1)
                order by c.SHIDATE asc,c.DOCNUMBER asc");

        return $query->getResultArray();
    }

    function get_finlist_on_csr()
    {
        $query = $this->db->query("select a.*
        from webot_FINANCE a
        where a.POSTINGSTAT<>2
        order by a.DATEINVC asc,a.IDINVC asc");

        return $query->getResultArray();
    }


    function get_shi_pending_to_finance_search($keyword)
    {
        $query = $this->db->query("select 
        a.CSRUNIQ,a.CTDESC,a.PRJDESC,a.PONUMBERCUST,a.PODATECUST,a.NAMECUST," . 'a."CONTRACT"' . "  as CSRCONTRACT,a.CTDESC,a.PROJECT as CSRPROJECT,a.CRMNO,a.CRMREQDATE,
        a.CRMREMARKS,a.MANAGER,a.SALESNAME,a.ORDERDESC,
        b.ROWSHI,d.ROWSHI_F,c.ROWARINV,c.ARPOSTINGSTAT,c.AROFFLINESTAT,c.CTPOSTINGSTATARPOST
        from webot_CSR a 
		left join (select CSRUNIQ,count(SHIUNIQ) as ROWSHI from webot_SHIPMENTS where POSTINGSTAT=1 and EDNPOSTINGSTAT=1
		and DNPOSTINGSTAT=1 and DNSTATUS=1
		group by CSRUNIQ) b on b.CSRUNIQ=a.CSRUNIQ
        left join (select x.CSRUNIQ,COUNT(x.FINUNIQ) as ROWARINV,MIN(x.POSTINGSTAT) as ARPOSTINGSTAT,MAX(x.OFFLINESTAT) as AROFFLINESTAT,COUNT( DISTINCT x.POSTINGSTAT) as CTPOSTINGSTATARPOST 
		from webot_FINANCE x
		group by x.CSRUNIQ)  c on c.CSRUNIQ=a.CSRUNIQ
		left join (select CSRUNIQ,COUNT(FINLUNIQ) as ROWSHI_F from webot_FINMULTISHI group by CSRUNIQ) d on d.CSRUNIQ=a.CSRUNIQ
        where ((a.POSTINGSTAT=1) and b.ROWSHI is NOT NULL and (c.ARPOSTINGSTAT=0 or c.ARPOSTINGSTAT IS NULL or b.ROWSHI<>d.ROWSHI_F))
        and (a.CONTRACT like '%$keyword%' or a.PROJECT like '%$keyword%' or a.CRMNO like '%$keyword%' or a.CTDESC like '%$keyword%' or a.PONUMBERCUST like '%$keyword%' 
        or a.NAMECUST like '%$keyword%')");

        return $query->getResultArray();
    }


    //RRSTATUS
    function get_fin_pending_to_rrstatus()
    {
        $query = $this->db->query("select a.*,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,c.TCURCOSTHM,c.TACTCOSTHM,a.IDINVC,a.DATEINVC
        from webot_FINANCE a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (select y.CONTRACT,x.TCURCOSTHM,x.TACTCOSTHM from PMCONTT x inner join PMCONTS y on y.CTUNIQ=x.CTUNIQ) c on c.CONTRACT=b.CONTRACT
        where a.POSTINGSTAT=1 and a.RRPOSTINGSTAT=0");

        return $query->getResultArray();
    }


    function get_fin_pending_to_rrstatus_search($keyword)
    {
        $query = $this->db->query("select a.*,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.ORDERDESC,c.TCURCOSTHM,c.TACTCOSTHM,a.IDINVC,a.DATEINVC
        from webot_FINANCE a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (select y.CONTRACT,x.TCURCOSTHM,x.TACTCOSTHM from PMCONTT x inner join PMCONTS y on y.CTUNIQ=x.CTUNIQ) c on c.CONTRACT=b.CONTRACT
        where (a.POSTINGSTAT=1 and a.RRPOSTINGSTAT=0)
        and (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.CRMNO like '%$keyword%' or b.NAMECUST like '%$keyword%'
        or a.IDINVC like '%$keyword%')");

        return $query->getResultArray();
    }


    function get_csr_by_id($csruniq)
    {
        $query = $this->db->query("select a.* from webot_CSR a
        where a.POSTINGSTAT=1 and a.CSRUNIQ='$csruniq' ");
        return $query->getRowArray();
    }

    function get_fin_by_id($finuniq)
    {
        $query = $this->db->query("select a.*,c.TCURCOSTHM,c.TACTCOSTHM
        from webot_FINANCE a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
		left join (select y.CONTRACT,x.TCURCOSTHM,x.TACTCOSTHM from PMCONTT x inner join PMCONTS y on y.CTUNIQ=x.CTUNIQ) c on c.CONTRACT=b.CONTRACT
        where a.FINUNIQ='$finuniq' ");
        return $query->getRowArray();
    }


    function list_sage_ar_by_contract($ct_no)
    {
        $query = $this->db->query("select DISTINCT a.IDCUST,a.IDINVC,a.DATEINVC,a.INVCDESC,a.AMTINVCTOT from ARIBH a
        left join ARIBC b on b.CNTBTCH=a.CNTBTCH
        inner join ARIBD c on c.CNTBTCH=a.CNTBTCH and c.CNTITEM=a.CNTITEM
        where b.BTCHSTTS=3 and
        c.CONTRACT='$ct_no' and a.IDINVC not in (select distinct IDINVC from webot_FINANCE where POSTINGSTAT<>2)");
        return $query->getResultArray();
    }


    function list_shipments_by_contract($csruniq)
    {
        $query = $this->db->query("select a.* from webot_SHIPMENTS a where a.DNPOSTINGSTAT=1 and
        a.CSRUNIQ='$csruniq' and a.DOCNUMBER not in (select distinct SHIDOCNUMBER from webot_FINMULTISHI)");
        return $query->getResultArray();
    }


    function get_arinvoice_by_id($idinvc)
    {
        $query = $this->db->query("select distinct a.IDCUST,a.IDINVC,a.DATEINVC,a.INVCDESC,a.AMTINVCTOT from ARIBH a
        left join ARIBC b on b.CNTBTCH=a.CNTBTCH
        inner join ARIBD c on c.CNTBTCH=a.CNTBTCH and c.CNTITEM=a.CNTITEM
        where b.BTCHSTTS=3 and a.IDINVC='$idinvc' ");
        return $query->getRowArray();
    }

    function get_finuniq_open($csruniq, $idinvc)
    {
        $query = $this->db->query("select DISTINCT a.FINUNIQ,a.IDINVC,a.FINKEY,COUNT(b.SHIUNIQ) as COUNTFSHI from webot_FINANCE a
        left join webot_FINMULTISHI b on b.FINUNIQ=a.FINUNIQ
        where a.CSRUNIQ='$csruniq' and a.IDINVC='$idinvc'
        group by a.FINUNIQ,a.IDINVC,a.FINKEY");
        return $query->getRowArray();
    }

    function get_finance_open($finuniq)
    {
        $query = $this->db->query("select * from webot_FINANCE where POSTINGSTAT <>2 and FINUNIQ='$finuniq'");
        return $query->getRowArray();
    }


    function get_fin_open_by_id($finuniq, $csruniq)
    {
        $query = $this->db->query("select b.CSRUNIQ,b.CSRLUNIQ,c.IDINVC,c.DATEINVC,c.ORIGDNRCPFINDATE,c.FINSTATUS
        from webot_FINMULTISHI a inner join webot_SHIL b on b.SHIUNIQ=a.SHIUNIQ
		left join webot_FINANCE c on c.FINUNIQ=a.FINUNIQ
        where b.CSRUNIQ='$csruniq' and c.FINUNIQ='$finuniq'
		group by b.CSRUNIQ,b.CSRLUNIQ,c.IDINVC,c.DATEINVC,c.ORIGDNRCPFINDATE,c.FINSTATUS");
        return $query->getResultArray();
    }


    function finance_insert($data)
    {
        $query = $this->db->table('webot_FINANCE')->insert($data);
        return $query;
    }

    function fin_m_line_insert($result)
    {
        $process = $this->db->table('webot_FINMULTISHI')->insertBatch($result);
        if ($process) {
            return true;
        } else {
            return false;
        }
    }


    function finance_update($finuniq, $data)
    {
        $query = $this->db->table('webot_FINANCE')->update($data, array('FINUNIQ' => $finuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_finance_update($csruniq, $csrluniq, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $csruniq, 'CSRLUNIQ' => $csrluniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function delete_fin_open($finuniq)
    {
        return $this->db->table('webot_FINANCE')->delete(['FINUNIQ' => $finuniq, 'POSTINGSTAT' => 0]);
    }

    function delete_fin_shi_open($finuniq)
    {
        return $this->db->table('webot_FINMULTISHI')->delete(['FINUNIQ' => $finuniq]);
    }


    // Untuk Fill Invoice List
    function count_fin_posting()
    {
        $builder = $this->db->table('webot_FINANCE');
        $builder->where('webot_FINANCE.POSTINGSTAT=', 1);
        return $builder->countAllResults();
    }

    function get_inv_preview()
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_FINANCE a
        left join webot_SHIPMENTS c on c.SHIUNIQ=a.SHIUNIQ
        left join webot_CSR b on a.CSRUNIQ =b.CSRUNIQ

        where a.POSTINGSTAT=1");
        return $query->getResultArray();
    }
    function get_inv_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_FINANCE a
        left join webot_SHIPMENTS c on c.SHIUNIQ=a.SHIUNIQ
        left join webot_CSR b on a.CSRUNIQ =b.CSRUNIQ
        where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
       or b.CRMREMARKS like '%$keyword%'   or a.SHIREFERENCE like '%$keyword%'  or a.SHIREFERENCE like '%$keyword%'
        or a.IDINVC like '%$keyword%' or c.SHIDATE like '%$keyword%') and
        (a.DATEINVC>=$nfromdate and a.DATEINVC<=$ntodate)
        order by a.DATEINVC asc");
        return $query->getResultArray();
    }
}
