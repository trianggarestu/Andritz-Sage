<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Ordertracking_model extends Model
{
    protected $table = 'webot_ORDERTRACKING';

    //protected $table = 'ARCUS';
    function __construct()
    {

        parent::__construct();
    }



    function get_ordertracking()
    {
        $query = $this->db->query('select * from webot_ORDERTRACKING where (RRSTATUS is NULL or RRSTATUS = 0) order by PODATECUST asc,CUSTOMER asc, CONTRACT asc,CSRLUNIQ asc');
        return $query->getResultArray();
    }


    function get_ordertracking_search($keyword)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING where (RRSTATUS is NULL or RRSTATUS = 0) and 
        (CONTRACT like '%$keyword%' or CTDESC like '%$keyword%'
        or MANAGER like '%$keyword%' or SALESNAME like '%$keyword%' or PROJECT like '%$keyword%' or PRJDESC like '%$keyword%' or PONUMBERCUST like '%$keyword%'
        or CUSTOMER like '%$keyword%' or NAMECUST like '%$keyword%' or EMAIL1CUST like '%$keyword%' or CRMNO like '%$keyword%' or ORDERDESC like '%$keyword%'
        or SERVICETYPE like '%$keyword%' or CRMREMARKS like '%$keyword%' or ITEMNO like '%$keyword%' or MATERIALNO like '%$keyword%'
        or STOCKUNIT like '%$keyword%' or RQNNUMBER like '%$keyword%' or PONUMBER like '%$keyword%' or ORIGINCOUNTRY like '%$keyword%' or POREMARKS like '%$keyword%'
        or VENDSHISTATUS like '%$keyword%' or LOGREMARKS like '%$keyword%' or RECPNUMBER like '%$keyword%' or SHIDOCNUMBER like '%$keyword%'
        or SHINUMBER like '%$keyword%' or IDINVC like '%$keyword%')
        order by PODATECUST asc,CUSTOMER asc, CONTRACT asc,CSRLUNIQ asc");
        return $query->getResultArray();
    }

    function get_ot_by_id($id_so)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING "
            . "where CSRUNIQ='$id_so' ");
        return $query->getRowArray();
    }
    function get_inv_preview()
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING where RRSTATUS = 1");
        return $query->getResultArray();
    }
    function get_inv_preview_filter($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING
                where ( RRSTATUS = 1) and 
        (CONTRACT like '%$keyword%' or CTDESC like '%$keyword%' or MANAGER like '%$keyword%' or SALESNAME like '%$keyword%'
        or PROJECT like '%$keyword%' or PRJDESC like '%$keyword%' or PONUMBERCUST like '%$keyword%' or CUSTOMER like '%$keyword%'
        or NAMECUST like '%$keyword%' or EMAIL1CUST like '%$keyword%' or CRMNO like '%$keyword%' or ORDERDESC like '%$keyword%'
        or SERVICETYPE like '%$keyword%' or CRMREMARKS like '%$keyword%' or ITEMNO like '%$keyword%' or MATERIALNO like '%$keyword%'
        or STOCKUNIT like '%$keyword%' or IDINVC like '%$keyword%' or RQNNUMBER like '%$keyword%' or PONUMBER like '%$keyword%' 
        or SHINUMBER like '%$keyword%' or RECPNUMBER like '%$keyword%' or SHIDOCNUMBER like '%$keyword%' or ORIGINCOUNTRY like '%$keyword%' ) and
        (PODATECUST>=$nfromdate and PODATECUST<=$ntodate)
        order by PODATECUST asc,CUSTOMER asc, CONTRACT asc,CSRLUNIQ asc");
        return $query->getResultArray();
    }
    function get_inv_preview_open()
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING where RRSTATUS = 0 or RRSTATUS is NULL");
        return $query->getResultArray();
    }
    function get_inv_preview_filter_open($keyword, $nfromdate, $ntodate)
    {
        $query = $this->db->query("select * from webot_ORDERTRACKING
                where (RRSTATUS = 0 or RRSTATUS is NULL) and 
        (CONTRACT like '%$keyword%' or CTDESC like '%$keyword%' or MANAGER like '%$keyword%' or SALESNAME like '%$keyword%'
        or PROJECT like '%$keyword%' or PRJDESC like '%$keyword%' or PONUMBERCUST like '%$keyword%' or CUSTOMER like '%$keyword%'
        or NAMECUST like '%$keyword%' or EMAIL1CUST like '%$keyword%' or CRMNO like '%$keyword%' or ORDERDESC like '%$keyword%'
        or SERVICETYPE like '%$keyword%' or CRMREMARKS like '%$keyword%' or ITEMNO like '%$keyword%' or MATERIALNO like '%$keyword%'
        or STOCKUNIT like '%$keyword%' or IDINVC like '%$keyword%' or RQNNUMBER like '%$keyword%' or PONUMBER like '%$keyword%' 
        or SHINUMBER like '%$keyword%' or RECPNUMBER like '%$keyword%' or SHIDOCNUMBER like '%$keyword%'  ) and
        (PODATECUST>=$nfromdate and PODATECUST<=$ntodate)
        order by PODATECUST asc,CUSTOMER asc, CONTRACT asc,CSRLUNIQ asc");
        return $query->getResultArray();
    }

    //Check Duplikat Entry - Return False if double post
    function get_ot_key($contract, $project, $itemno, $csruniq, $csrluniq)
    {
        $query = $this->db->query("select OTSEQ,CSRUNIQ,CSRLUNIQ,OTKEY,CONTRACT,PROJECT,CUSTOMER,ITEMNO 
        from webot_ORDERTRACKING where CONTRACT='$contract' and PROJECT='$project' and ITEMNO='$itemno' and 
        CSRUNIQ='$csruniq' and CSRLUNIQ='$csrluniq'");
        return $query->getRowArray();
    }
}
