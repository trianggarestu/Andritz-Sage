<?php

namespace App\Models;

use CodeIgniter\Database\Query;
use CodeIgniter\Model;

class Setup_model extends Model
{

    protected $table = 'webot_USERAUTH';
    protected $allowedFields = ['USERNAME', 'NAME', 'EMAIL', 'PASSWORD', 'PATH_PHOTO', 'ISSUPERUSER', 'INACTIVE', 'GROUPID'];
    private $urut_model;
    function __construct()
    {
        parent::__construct();
        $this->urut_model = new Urut_Model('webot_NAVIGATIONDL1', 'IDNAVDL1', 'IDNAVL1');
    }

    // Menu Setup
    function get_menuheader()
    {
        $query = $this->db->query('select * from webot_NAVIGATIONH where INACTIVE=0 order by NAVL1SORTING asc');
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


    // Mail Sender Setup
    public function get_mailsender()
    {
        $query = $this->db->query("SELECT * FROM webot_MAILSENDER where ID='1'");
        return $query->getRowArray();
    }

    public function mailsender_update($id, $data)
    {
        $query = $this->db->table('webot_MAILSENDER')->update($data, array('ID' => $id));
        //Tanpa return juga bisa jalan
        return $query;
    }

    // Usergroup Setup
    public function get_usergroup()
    {
        $query = $this->db->query("SELECT * FROM webot_USERGROUP order by groupid asc");
        return $query->getResultArray();
    }

    public function get_data_groups($groupid)
    {
        $query = $this->db->query("SELECT * FROM webot_USERGROUP where GROUPID='$groupid'");
        return $query->getRowArray();
    }

    public function usergroup_insert($data)
    {
        $query = $this->db->table('webot_USERGROUP')->insert($data);
        return $query;
    }

    function usergroup_update($groupid, $data)
    {
        $query = $this->db->table('webot_USERGROUP')->update($data, array('GROUPID' => $groupid));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function insert_usergrouprole()
    {
        $insertsql = "INSERT INTO webot_USERGROUPROLE (GROUPID,IDNAVL1,IDNAVDL1)
        SELECT IDNAVL1,IDNAVDL1,0 
        from webot_NAVIGATIONDL1";
        $this->db->query($insertsql);
    }

    function get_all_navigation($groupid)
    {
        $query = $this->db->query("select a.*,b.NAVL1NAME,b.COMMENT as HCOMMENT,b.ICONDESC,isnull(gr.ISACTIVE,0) as ISACTIVE from webot_NAVIGATIONDL1 a
        inner join webot_NAVIGATIONH b on b.IDNAVL1=a.IDNAVL1
        left join (select * from webot_USERGROUPROLE where GROUPID='$groupid') gr on gr.IDNAVL1=a.IDNAVL1 and gr.IDNAVDL1=a.IDNAVDL1
        order by b.NAVL1SORTING Asc, a.SORTING Asc");
        return $query->getResultArray();
    }

    function grouprole_delete($idgrusergroup)
    {
        //$sql = 'DELETE FROM webot_USERGROUPROLE WHERE GROUPID=' . "'$idgrusergroup'" . '';
        //$this->db->query($sql);
        return $this->db->table('webot_USERGROUPROLE')->delete(['GROUPID' => $idgrusergroup]);
    }

    function insert_grouprole($result)
    {
        $process = $this->db->table('webot_USERGROUPROLE')->insertBatch($result);
        if ($process) {
            return true;
        } else {
            return false;
        }
    }


    //Users Setup
    public function get_users()
    {
        $query = $this->db->query("SELECT a.*,b.GROUPNAME FROM webot_USERAUTH a join webot_USERGROUP b on b.GROUPID=a.GROUPID
        WHERE a.ISDELETED=0
        order by a.GROUPID,a.USERNAME asc");
        return $query->getResultArray();
    }

    public function checkusername($username)
    {
        $query = $this->db->query("select USERNAME from webot_USERAUTH where USERNAME='$username'");
        return $query->getNumRows();
    }


    public function get_users_by_hash($hashuser)
    {
        $query = $this->db->query("SELECT a.*,b.GROUPNAME FROM webot_USERAUTH a join webot_USERGROUP b on b.GROUPID=a.GROUPID where a.USERHASH='$hashuser'");
        return $query->getRowArray();
    }

    public function user_insert($data)
    {
        $query = $this->db->table('webot_USERAUTH')->insert($data);
        return $query;
    }

    public function user_update($username, $data)
    {
        $query = $this->db->table('webot_USERAUTH')->update($data, array('USERNAME' => $username));
        //Tanpa return juga bisa jalan
        return $query;
    }

    public function password_update($userhash, $data)
    {
        $query = $this->db->table('webot_USERAUTH')->update($data, array('USERHASH' => $userhash));
        //Tanpa return juga bisa jalan
        return $query;
    }

    public function set_active($hashuser = '', $val = 0)
    {
        $sql = "UPDATE webot_USERAUTH SET INACTIVE = ? WHERE USERHASH = ?";
        $outp = $this->db->query($sql, array($val, $hashuser));
        //Tanpa return juga bisa jalan
        return $outp;
    }


    function user_delete($hashuser, $val = 0)
    {
        $sql = "UPDATE webot_USERAUTH SET ISDELETED = ? WHERE USERHASH = ?";
        $outp = $this->db->query($sql, array($val, $hashuser));
        //Tanpa return juga bisa jalan
        return $outp;
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
