<?php

namespace App\Models;

use CodeIgniter\Model;

class Urut_Model extends Model
{

	private $tabel;
	private $kolom_id;
	private $parent_id;


	public function __construct($tabel, $kolom_id = 'id', $parent_id = '1')
	{
		parent::__construct();
		$this->tabel = $tabel;
		$this->kolom_id = $kolom_id;
		$this->parent_id = $parent_id;
	}

	/**
	 * Cari nomor urut terbesar untuk subset data
	 *
	 * @access	public
	 * @param		array		syarat kolom data yang akan diperiksa
	 * @return	integer	nomor urut maksimum untuk subset
	 */
	public function urut_max()
	{
		$urut = $this->db->selectMax('SORTING')
			->where($subset)
			->get($this->tabel)
			->row()->urut;
		return $urut;
	}

	private function urut_semua($subset = array('1' => '1'))
	{

		$urut_duplikat = $this->db->query('SELECT count(SORTING) as cx from ' . $this->tabel . ' group by SORTING having cx > 1')->getResultArray();

		$belum_diurut = $this->db->query('SELECT SORTING from ' . $this->tabel . ' where SORTING IS NULL limit 1')->getRowArray();

		/*$urut_duplikat = $this->db->select('SORTING, COUNT(*) c')
			->where($subset)
			->group_by('SORTING')
			->having('c > 1')
			->getResultArray();
		$belum_diurut = $this->db
			->where($subset)
			->where('SORTING IS NULL')
			->limit(1)
			->getRowArray();*/

		$daftar = array();
		if ($urut_duplikat or $belum_diurut) {
			$daftar = $this->db->select($this->kolom_id)
				->where($subset)
				->order_by("SORTING")
				->getResultArray();
		}
		for ($i = 0; $i < count($daftar); $i++) {
			$this->db->where($this->kolom_id, $daftar[$i][$this->kolom_id]);
			$data['SORTING'] = $i + 1;
			$this->db->update($this->tabel, $data);
		}
	}

	/**
	 * @param $id Id data yg akan digeser
	 * @param $arah Arah untuk menukar dengan unsur lain: 1) turun, 2) naik
	 * @return int Nomer urut unsur lain yang ditukar
	 **/
	public function urut($menuh, $id, $arah)
	{
		//$this->urut_semua();
		//$unsur1 = $this->db->where($this->kolom_id, $id);
		//->get($this->tabel)
		//->row_array();
		$unsur1 = $this->db->query('SELECT * FROM ' . $this->tabel . ' where ' . $this->kolom_id . '=' . "'$id'" . '')->getRowArray();
		//return $unsur1->getRowArray(); 

		/*$daftar = $this->db->select("{$this->kolom_id}, sorting")
			//->where($subset)
			->order_by("sorting")
			->get($this->tabel)
			->result_array();*/
		$daftar = $this->db->query('SELECT ' . $this->kolom_id . ',SORTING FROM ' . $this->tabel . ' where ' . $this->parent_id . '=' . "'$menuh'" . ' order by SORTING')->getResultArray();
		return $this->urut_daftar($id, $arah, $daftar, $unsur1);
	}

	private function urut_daftar($id, $arah, $daftar, $unsur1)
	{
		for ($i = 0; $i < count($daftar); $i++) {
			if ($daftar[$i][$this->kolom_id] == $id)
				break;
		}

		if ($arah == 1) {
			if ($i >= count($daftar) - 1) return;
			$unsur2 = $daftar[$i + 1];
		}
		if ($arah == 2) {
			if ($i <= 0) return;
			$unsur2 = $daftar[$i - 1];
		}

		// Tukar urutan
		//$this->db->where($this->kolom_id, $unsur2[$this->kolom_id])->
		//update($this->tabel, array('sorting' => $unsur1['sorting']));
		$this->db->table($this->tabel)->update(array('SORTING' => $unsur1['SORTING']), array($this->kolom_id => $unsur2[$this->kolom_id]));
		//$this->db->where($this->kolom_id, $unsur1[$this->kolom_id])->
		//update($this->tabel, array('sorting' => $unsur2['sorting']));
		$this->db->table($this->tabel)->update(array('SORTING' => $unsur2['SORTING']), array($this->kolom_id => $unsur1[$this->kolom_id]));
		return (int)$unsur2['SORTING'];
	}
}

//$query = $this->db->table('tbl_brand_product')->update($data, array('brand_id' => $id));
//Tanpa return juga bisa jalan
//return $query;
