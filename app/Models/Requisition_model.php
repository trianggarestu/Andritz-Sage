<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Requisition_model extends Model
{

    protected $table = 'webot_REQUISITION';

    function __construct()
    {
        parent::__construct();
    }



    function get_requisition_open()
    {
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNKEY,b.RQNDATE,b.RQNNUMBER,b.RQNREMARKS,b.POSTINGSTAT as RQNPOSTINGSTAT,b.OFFLINESTAT as RQNOFFLINESTAT 
        from webot_CSR a
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1)
        order by a.PODATECUST asc, a.CUSTOMER asc,a.CONTRACT asc");

        return $query->getResultArray();
    }

    function get_requisition_open_search($keyword)
    {
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNDATE,b.RQNNUMBER,b.POSTINGSTAT as RQNPOSTINGSTAT,b.OFFLINESTAT as RQNOFFLINESTAT
        from webot_CSR a
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where ((a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1))
        and (a.CONTRACT like '%$keyword%' or a.CTDESC like '%$keyword%' or a.MANAGER like '%$keyword%' or a.SALESNAME like '%$keyword%'
        or a.PROJECT like '%$keyword%' or a.PRJDESC like '%$keyword%' or a.PONUMBERCUST like '%$keyword%' or a.CUSTOMER like '%$keyword%'
        or a.NAMECUST like '%$keyword%' or a.EMAIL1CUST like '%$keyword%' or a.CRMNO like '%$keyword%' or a.ORDERDESC like '%$keyword%'
        or a.CRMREMARKS like '%$keyword%'
        or b.RQNNUMBER like '%$keyword%')
        order by a.PODATECUST asc, a.CUSTOMER asc,a.CONTRACT asc");

        return $query->getResultArray();
    }


    function get_requisition_close()
    {
        $query = $this->db->query("select a.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.PONUMBER,c.PODATE
        from webot_REQUISITION a left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join webot_PO c on c.RQNUNIQ=a.RQNUNIQ 
        where a.POSTINGSTAT=1");
        return $query->getResultArray();
    }


    function get_csruniq_open()
    {
        $query = $this->db->query("select c.CSRUNIQ,c.CSRLUNIQ 
        from webot_CSR a
        inner join webot_CSRL c on c.CSRUNIQ=a.CSRUNIQ
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }


    function get_csrl_list_post()
    {
        $query = $this->db->query("select a.* from webot_CSRL a
        inner join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where b.POSTINGSTAT=1 order by CSRUNIQ asc, CSRLUNIQ asc");
        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }


    function count_rqn_posting()
    {
        $builder = $this->db->table('webot_REQUISITION');
        $builder->join('webot_CSR b', 'b.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left');
        $builder->join('webot_PO c', 'c.RQNUNIQ = webot_REQUISITION.RQNUNIQ', 'left');
        $builder->where('webot_REQUISITION.POSTINGSTAT', 1);
        return $builder->countAllResults();
    }

    function get_requisition_sage($pocust_date)
    {
        // Sementara Untuk simulasi, Cari Request yang ketemu sampai Received
        // kalau sudah Go Live, Hapus Inner Join POPORH1 & inner join PORCPH1
        // Kalau mau job relate tambahkan where $contract
        //inner join POPORH1 b on b.RQNNUMBER=a.RQNNUMBER
        //inner join PORCPH1 c on c.PONUMBER=b.PONUMBER

        $query = $this->db->query("select DISTINCT a.RQNHSEQ,a.RQNNUMBER," . 'a."DATE"' . ",a.DESCRIPTIO,a.DOCSTATUS  
        from ENRQNH a 
        where " . 'a."DATE"' . ">='$pocust_date' 
        and a.RQNNUMBER not in (select distinct RQNNUMBER from webot_REQUISITION where POSTINGSTAT=1)
        order by " . 'a."DATE"' . " desc");


        // untuk Data Live
        // Condition jika request sudah di pilih
        //
        //
        /*
        
        inner join PORCPH1 c on c.PONUMBER=a.PONUMBERS
        $query = $this->db->query("select a.RQNHSEQ,a.RQNNUMBER," . 'a."DATE"' . ",a.DESCRIPTIO,a.DOCSTATUS  
        from ENRQNH a 
        where " . 'a."DATE"' . ">=20220101 and
        a.RQNNUMBER not in (select distinct RQNNUMBER from webot_REQUISITION where POSTINGSTAT=1)
        order by " . 'a."DATE"' . " asc");*/
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnnumber)
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . " as RQNDATE,DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER='$rqnnumber' ");
        return $query->getRowArray();
    }

    /*
    function get_rqn_uniq($rqnnumber)
    {
        $query = $this->db->query("select DISTINCT a.RQNUNIQ,a.RQNNUMBER,a.RQNKEY,COUNT(b.RQNLUNIQ) as CHKRQNL 
        from webot_REQUISITION a
        left join webot_REQUISITIONL b on b.RQNUNIQ=a.RQNUNIQ
        where a.RQNNUMBER='$rqnnumber'
        group by a.RQNUNIQ,a.CSRUNIQ,a.RQNKEY");
        return $query->getRowArray();
    }
*/
    function get_requisition_by_so($id_so)
    {
        $query = $this->db->query("select a.* from webot_REQUISITION a
        where a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_rqnjoincsr_by_so($id_so)
    {
        $query = $this->db->query("select a.*,b.* from webot_REQUISITION a left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        where a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }


    function get_rqnuniq_open($id_so, $rqnnumber)
    {
        $query = $this->db->query("select a.RQNUNIQ,a.RQNNUMBER,a.RQNKEY,a.CSRUNIQ from webot_REQUISITION a
        where a.CSRUNIQ='$id_so' and a.RQNNUMBER='$rqnnumber'");
        return $query->getRowArray();
    }

    function get_so_by_id($id_so)
    {
        $query = $this->db->query("select a.* from webot_CSR a
        where a.POSTINGSTAT=1 and a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_so_detail_by_id($id_so)
    {
        $query = $this->db->query("select a.* from webot_CSR a
        where a.POSTINGSTAT=1 and a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }


    function get_so_l_by_id($id_so)
    {
        $query = $this->db->query("select a.*
        from webot_CSRL a
        where a.CSRUNIQ='$id_so' ");
        return $query->getResultArray();
    }

    function get_requisition_post($rqnuniq)
    {
        $query = $this->db->query("select a.*,b.* from webot_REQUISITION a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ where a.POSTINGSTAT=1 and RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }


    function requisition_insert($data1)
    {
        $query = $this->db->table('webot_REQUISITION')->insert($data1);
        return $query;
    }

    /*
    function rqnline_insert($datal)
    {
        $query = $this->db->table('webot_REQUISITIONL')->insert($datal);
        return $query;
    }
    */


    function requisition_update($id_pr, $data1)
    {
        $query = $this->db->table('webot_REQUISITION')->update($data1, array('RQNUNIQ' => $id_pr));
        return $query;
    }

    function rqn_post_update($rqnuniq, $data2)
    {
        $query = $this->db->table('webot_REQUISITION')->update($data2, array('RQNUNIQ' => $rqnuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_requisition_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }


    function get_pr_preview($nfromdate, $ntodate)
    {
        $query = $this->db->query(
            "select a.*,b.* from webot_REQUISITION a left join webot_CSR b on b.CSRUNIQ = a.CSRUNIQ where (b.POSTINGSTAT = '1') 
            and (a.RQNDATE BETWEEN $nfromdate and $ntodate)"
        );
        return $query->getResultArray();
    }
    function get_pr_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select a.*,b.* from webot_REQUISITION a left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ where (a.POSTINGSTAT = '1') and 
        (b.CONTRACT like '%$keyword%' or b.CTDESC like '%$keyword%' or b.MANAGER like '%$keyword%' or b.SALESNAME like '%$keyword%'
        or b.PROJECT like '%$keyword%' or b.PRJDESC like '%$keyword%' or b.PONUMBERCUST like '%$keyword%' or b.CUSTOMER like '%$keyword%'
        or b.NAMECUST like '%$keyword%' or b.EMAIL1CUST like '%$keyword%' or b.CRMNO like '%$keyword%' or b.ORDERDESC like '%$keyword%'
        or  b.CRMREMARKS like '%$keyword%' or a.RQNNUMBER like '%$keyword%' or a.RQNDATE like '%$keyword%') and
        (a.RQNDATE>=$nfromdate and a.RQNDATE<=$ntodate)
        order by a.RQNDATE asc");
        return $query->getResultArray();
    }

    function get_csr_open($csruniq)
    {
        $query = $this->db->query("select a.*,b.* from webot_REQUISITION a 
        left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ
         where a.POSTINGSTAT <>2 and a.CSRUNIQ='$csruniq'");
        return $query->getRowArray();
    }

    function get_csrl_open($csruniq)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_REQUISITION a
        left Join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ
        left join webot_CSRL c on b.CSRUNIQ = c.CSRUNIQ 
        where a.POSTINGSTAT <> 2  and a.CSRUNIQ='$csruniq'");
        return $query->getResultArray();
    }
}
