<?php

namespace App\Models;

use CodeIgniter\Model;

class Administration_model extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_navigation($username)
    {
        $query = $this->db->query('select a.IDNAVL1 as mhid,a.NAVL1NAME as mhname,a.ICONDESC as mhicon, '
            . 'b.NAVDL1NAME as mdname,b.NAVLINK as mdcontroller,b.fa_icon as mdicon '
            . 'from webot_USERGROUPROLE x left join '
            . 'webot_NAVIGATIONDL1 b on b.IDNAVDL1=x.IDNAVDL1 and b.IDNAVL1=x.IDNAVL1 left join '
            . 'webot_NAVIGATIONH a  on a.IDNAVL1=b.IDNAVL1 left join '
            . '(select USERNAME,GROUPID from webot_USERAUTH) y on y.GROUPID=x.GROUPID and y.USERNAME=' . "'$username'" . ''
            . 'where y.USERNAME=' . "'$username'" . ' and x.ISACTIVE=1 '
            . 'ORDER BY a.NAVL1SORTING,b.SORTING ASC');
        return $query->getResultArray();
        //return $this->db->table('admin_navd1')
        //->join('admin_navh','admin_navh.idnavh=admin_navd1.idnavh')
        //->get()->getResultArray();

    }

    function get_activenavh($activenavd)
    {
        $query = $this->db->query('select a.IDNAVL1 as breadcrumb_idnavh,a.NAVL1NAME as breadcrumb_navh,b.NAVDL1NAME as breadcrumb_navd,b.NAVLINK'
            . ' from webot_NAVIGATIONH a inner join webot_NAVIGATIONDL1 b on b.IDNAVL1=a.IDNAVL1'
            . ' where b.NAVLINK=' . "'$activenavd'" . ''
            . ' and a.inactive=0');

        return $query->getRowArray();
    }


    function get_mailbox_in($user)
    {
        $query = $this->db->query("select TOP 6 a.*,usr.PATH_PHOTO from webot_MAILBOX a
        left join webot_USERAUTH usr on usr.USERNAME=a.FROM_USER
        where a.IS_ARCHIVED=0 and a.IS_TRASHED=0 and a.IS_DELETED=0 and a.TO_USER='$user'
        order by a.SENDING_DATE desc, a.MAILSEQ desc");
        return $query->getResultArray();
    }

    # Dashboard
    function get_latest_csr()
    {
        $query = $this->db->query("select TOP 6 a.CONTRACT,a.CTDESC,a.PODATECUST,a.NAMECUST,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_CSR a
        order by a.PODATECUST desc, a.CSRUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_requisition()
    {
        $query = $this->db->query("select TOP 6 b.CONTRACT,b.CTDESC,a.RQNDATE,a.RQNNUMBER,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_REQUISITION a left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        order by a.RQNDATE desc, a.RQNUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_po()
    {
        $query = $this->db->query("select TOP 6 a.PODATE,a.PONUMBER,a.ORIGINCOUNTRY,a.POREMARKS,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_PO a
        order by a.PODATE desc, a.POUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_logistics()
    {
        $query = $this->db->query("select TOP 6 b.PODATE,a.PONUMBER,a.PIBDATE,a.VENDSHISTATUS,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_LOGISTICS a left join webot_PO b on b.POUNIQ=a.POUNIQ
        order by b.PODATE desc, a.LOGUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_gr()
    {
        $query = $this->db->query("select TOP 6 a.RECPDATE,a.RECPNUMBER,a.VDNAME,a.DESCRIPTIO,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_RECEIPTS a
        order by a.RECPDATE desc, a.RCPUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_shipments()
    {
        $query = $this->db->query("select TOP 6 a.SHIDATE,a.DOCNUMBER,a.SHINUMBER,a.CUSTRCPDATE,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_SHIPMENTS a
        order by a.SHIDATE desc, a.SHIUNIQ desc");
        return $query->getResultArray();
    }

    function get_latest_finance()
    {
        $query = $this->db->query("select TOP 6 a.INVOICEDATE,a.IDINVC,a.FINSTATUS,a.RRSTATUS,a.POSTINGSTAT,a.OFFLINESTAT 
        from webot_FINANCE a
        order by a.INVOICEDATE desc, a.FINUNIQ desc");
        return $query->getResultArray();
    }


    function get_mailsender()
    {
        $query = $this->db->query("SELECT * FROM webot_MAILSENDER where ID='1'");
        return $query->getRowArray();
    }

    // END Dashboard

    #Get Form View

    function get_csr_post($csruniq)
    {
        $query = $this->db->query("select a.* from webot_CSR a where a.POSTINGSTAT =1 and a.CSRUNIQ='$csruniq'");
        return $query->getRowArray();
    }

    function get_csrl_post($csruniq)
    {
        $query = $this->db->query("select b.* from webot_CSRL b inner join webot_CSR a on a.CSRUNIQ=b.CSRUNIQ 
        where a.POSTINGSTAT =1 and a.CSRUNIQ='$csruniq'");
        return $query->getResultArray();
    }


    function get_po_post($pouniq)
    {
        $query = $this->db->query("select a.*,b.*,c.* from webot_PO a 
        left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ
        left join webot_REQUISITION c on c.RQNUNIQ = a.RQNUNIQ
        
        where a.POSTINGSTAT = 1 and a.POUNIQ='$pouniq'");

        return $query->getRowArray();
    }

    function get_pol_post($pouniq)
    {
        $query = $this->db->query("select a.*,b.*,c.*,d.*,e.ITEMDESC from webot_PO a 
        left join webot_CSR b on a.CSRUNIQ = b.CSRUNIQ
        left join webot_REQUISITION c on c.RQNUNIQ = a.RQNUNIQ and c.CSRUNIQ = b.CSRUNIQ
        left join webot_POL d on d.POUNIQ = a.POUNIQ 
        left join webot_CSRL e on e.ITEMNO = d.ITEMNO and e.CSRUNIQ = d.CSRUNIQ
        where a.POSTINGSTAT <>2 and a.POUNIQ='$pouniq'");
        return $query->getResultArray();
    }
}
