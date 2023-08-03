<?php

namespace App\Models;

use CodeIgniter\Database\Query;
use CodeIgniter\Model;

class Adminmenu_model extends Model
{

    protected $table = 'webot_CSR';

    function __construct()
    {
        parent::__construct();
    }

    // Menu Setup
    function get_otheader()
    {
        $query = $this->db->query('select * from webot_CSR where POSTINGSTAT=1 order by CSRUNIQ asc');
        return $query->getResultArray();
    }
}
