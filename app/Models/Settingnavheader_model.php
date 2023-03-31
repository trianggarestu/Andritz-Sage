<?php

namespace App\Models;

use CodeIgniter\Model;

class Settingnavheader_model extends Model
{


    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $query = $this->db->query("SELECT * FROM tbl_admnavh order by sorting asc");
        return $query->getResultArray();
    }

    function insertnya($data)
    {
        $query = $this->db->table('tbl_admnavh')->insert($data);
        return $query;
    }

    // get data by id
    function get_by_id($id)
    {
        $query = $this->db->query("SELECT * FROM tbl_admnavh where idnavh=$id");
        return $query->getRowArray();
        // $this->db->where($this->id, $id);
        //return $this->db->get($this->table)->row();
    }



    function updatedata($id, $data)
    {
        $query = $this->db->table('tbl_admnavh')->update($data, array('idnavh' => $id));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function total_rows_nav_in_navd1($id)
    {
        $builder = $this->db->table('tbl_admnavd1');
        $builder->where('idnavh', $id);
        return $builder->countAllResults();

        // Jika Pakai Count SQL
        //$query = $this->db->query("SELECT count(idnavh) as 'idnavhrows' FROM admin_navd1 where idnavh=$id");
        //return $query->getRowArray();;
    }

    function deletedata($id)
    {
        return $this->db->table('tbl_admnavh')->delete(['idnavh' => $id]);
        //$this->db->where($this->id, $id);
        //$this->db->delete($this->table);
    }

    function get_combonavheader()
    {
        $query = $this->db->query("select idnavh,navhname from tbl_admnavh ORDER BY sorting asc");
        if ($query->getResult() > 0) {
            foreach ($query->getResultArray() as $row) {
                $data[0] = '--PILIH GROUP ITEM--';
                $data[$row['idnavh']] = $row['navhname'];
            }
        }
        return $data;
    }
}
