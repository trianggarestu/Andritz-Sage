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
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNDATE,b.RQNNUMBER,b.POSTINGSTAT,b.OFFLINESTAT," . 'it."DESC"' . " as ITEMDESC from webot_CSR a
        left join ICITEM it on it.ITEMNO=a.ITEMNO 
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where (a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1)");
        //where PrNumber IS NULL or PoVendor IS NULL And PrStatus= 'Open'  (yang ni nanti)
        return $query->getResultArray();
    }

    function get_requisition_open_search($keyword)
    {
        $query = $this->db->query("select a.*,b.RQNUNIQ,b.RQNDATE,b.RQNNUMBER,b.POSTINGSTAT,b.OFFLINESTAT," . 'it."DESC"' . " as ITEMDESC 
        from webot_CSR a
        left join ICITEM it on it.ITEMNO=a.ITEMNO 
        left join webot_REQUISITION b on b.CSRUNIQ=a.CSRUNIQ
        where ((a.POSTINGSTAT=1 and b.RQNNUMBER IS NULL) or ( a.POSTINGSTAT=1 and b.POSTINGSTAT=0) or ( b.POSTINGSTAT=1 and b.OFFLINESTAT=1))
        and (a.CONTRACT like '%$keyword%' or a.CTDESC like '%$keyword%' or a.CRMNO like '%$keyword%' or a.NAMECUST like '%$keyword%'
        or a.ITEMNO like '%$keyword%' or a.MATERIALNO like '%$keyword%' or " . 'it."DESC"' . " like '%$keyword%' or b.RQNNUMBER like '%$keyword%')");

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

    function count_rqn_posting()
    {
        $builder = $this->db->table('webot_REQUISITION');
        $builder->join('webot_CSR b', 'b.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left');
        $builder->join('webot_PO c', 'c.RQNUNIQ = webot_REQUISITION.RQNUNIQ', 'left');
        $builder->where('webot_REQUISITION.POSTINGSTAT', 1);
        return $builder->countAllResults();
    }

    function get_requisition_sage($contract)
    {
        // Sementara Untuk simulasi, Cari Request yang ketemu sampai Received
        // kalau sudah Go Live, Hapus Inner Join POPORH1 & inner join PORCPH1
        $query = $this->db->query("select a.RQNHSEQ,a.RQNNUMBER," . 'a."DATE"' . ",a.DESCRIPTIO,a.DOCSTATUS  
        from ENRQNH a 
        inner join POPORH1 b on b.PONUMBER=a.PONUMBERS
        inner join PORCPH1 c on c.PONUMBER=a.PONUMBERS
        where " . 'a."DATE"' . ">=20220101 and
        a.RQNNUMBER not in (select distinct RQNNUMBER from webot_REQUISITION where POSTINGSTAT=1)");


        // untuk simulasi 
        /*$query = $this->db->query("select a.RQNHSEQ,a.RQNNUMBER,a." . '"DATE"' . ",a.DESCRIPTIO,a.DOCSTATUS
        from ENRQNH a
        inner join (select r.PONUMBER,r." . '"DATE"' . ",s.PORHSEQ,s.PORLSEQ,s.CONTRACT,s.PROJECT,s.ITEMDESC from POPORH1 r inner join POPORL s on s.PORHSEQ=r.PORHSEQ) c on c.PONUMBER=a.PONUMBERS
        inner join (select x.PONUMBER,x.RCPNUMBER,x." . '"DATE"' . ",y.PORHSEQ,y.PORLSEQ,y.CONTRACT,y.PROJECT,y.ITEMDESC from PORCPH1 x inner join PORCPL y on y.RCPHSEQ=x.RCPHSEQ) d on d.PORHSEQ=c.PORHSEQ and d.PORLSEQ=c.PORLSEQ
        where c.CONTRACT='$contract'");*/
        return $query->getResultArray();
    }

    function get_requisition_by_id($rqnnumber)
    {
        $query = $this->db->query("select RQNHSEQ,RQNNUMBER," . '"DATE"' . ",DESCRIPTIO,DOCSTATUS  from ENRQNH where RQNNUMBER='$rqnnumber' ");
        return $query->getRowArray();
    }

    function get_requisition_by_so($id_so)
    {
        $query = $this->db->query("select * from webot_REQUISITION where CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_so_by_id($id_so)
    {
        $query = $this->db->query("select a.*," . 'b."DESC"' . " as ITEMDESC from webot_CSR a left join ICITEM b on b.ITEMNO=a.ITEMNO "
            . "where a.POSTINGSTAT=1 and a.CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }

    function get_requisition_post($rqnuniq)
    {
        $query = $this->db->query("select * from webot_REQUISITION where POSTINGSTAT=1 and RQNUNIQ='$rqnuniq' ");
        return $query->getRowArray();
    }

    function requisition_insert($data1)
    {
        $query = $this->db->table('webot_REQUISITION')->insert($data1);
        return $query;
    }

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
        or b.SERVICETYPE like '%$keyword%' or b.CRMREMARKS like '%$keyword%' or b.ITEMNO like '%$keyword%' or b.MATERIALNO like '%$keyword%'
        or b.STOCKUNIT like '%$keyword%' or a.RQNNUMBER like '%$keyword%' or a.RQNDATE like '%$keyword%') and
        (a.RQNDATE>=$nfromdate and a.RQNDATE<=$ntodate)
        order by a.RQNDATE asc");
        return $query->getResultArray();
    }
}
