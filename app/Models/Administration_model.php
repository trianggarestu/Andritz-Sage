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
    function get_navigation()
    {
        $query = $this->db->query('select a.IDNAVL1 as mhid,a.NAVL1NAME as mhname,a.ICONDESC as mhicon, '
            . 'b.NAVDL1NAME as mdname,b.NAVLINK as mdcontroller,b.fa_icon as mdicon '
            . 'from webot_NAVIGATIONH a  left join webot_NAVIGATIONDL1 b on b.IDNAVL1=a.IDNAVL1 ORDER BY a.NAVL1SORTING,b.SORTING ASC');
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

    function get_mailsender()
    {
        $query = $this->db->query("SELECT * FROM webot_MAILSENDER where ID='1'");
        return $query->getRowArray();
    }
}
