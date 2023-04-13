<?php

namespace App\Models;

use CodeIgniter\Model;

class Menusetup_model extends Model
{

    private $urut_model;
    function __construct()
    {
        parent::__construct();
        $this->urut_model = new Urut_Model('webot_NAVIGATIONDL1', 'IDNAVDL1', 'IDNAVL1');
    }

    // get all
    function get_menuheader()
    {
        $query = $this->db->query('select * from webot_NAVIGATIONH order by NAVL1SORTING asc');
        return $query->getResultArray();
    }

    function get_submenu($idnav)
    {
        $query = $this->db->query("select * from webot_NAVIGATIONDL1 where IDNAVL1='$idnav' order by SORTING asc");
        return $query->getResultArray();
    }

    public function get_data($id)
    {
        $query = $this->db->query("SELECT * FROM webot_NAVIGATIONH where IDNAVL1='$id'");
        return $query->getRowArray();
    }

    function menuh_update($idnav, $data)
    {
        $query = $this->db->table('webot_NAVIGATIONH')->update($data, array('IDNAVL1' => $idnav));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function submenu_update($idnavdl1, $data)
    {
        $query = $this->db->table('webot_NAVIGATIONDL1')->update($data, array('IDNAVDL1' => $idnavdl1));
        //Tanpa return juga bisa jalan
        return $query;
    }

    public function get_submenu_data($id)
    {
        $query = $this->db->query("SELECT * FROM webot_NAVIGATIONDL1 where IDNAVDL1='$id'");
        return $query->getRowArray();
    }

    public function list_icon()
    {
        $list_icon = array();

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);
            $list_icon = array_map(function ($a) {
                return explode(':', $a)[0];
            }, $list_icon);
            return $list_icon;
        }

        return FALSE;
    }

    // $arah:
    //		1 - turun
    // 		2 - naik
    public function sort($menuh = '', $id, $arah)
    {
        //$subset = !empty($menuh) ? array("tipe" => 3, "menuh" => $menuh) : array("tipe" => $tipe);
        $this->urut_model->urut($menuh, $id, $arah);
    }
}
