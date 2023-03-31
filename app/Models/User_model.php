<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{



	public function get_user($user)
	{
		return $this->db->table('webot_USERAUTH')
			->where(array('USERNAME' => $user))
			->get()->getRowArray();
	}

	/**
	 * Update password
	 * @param  integer $id Id user di database
	 * @return void
	 */
	public function update_password($id = 0)
	{
		$data = $this->periksa_input_password($id);
		if (!empty($data)) {
			$hasil = $this->db->where('id', $id)
				->update('user', $data);
			status_sukses($hasil, $gagal_saja = true);
		}
	}

	private function periksa_input_password($id)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';
		$password = $this->input->post('pass_lama');
		$pass_baru = $this->input->post('pass_baru');
		$pass_baru1 = $this->input->post('pass_baru1');
		$data = [];

		// Jangan edit password admin apabila di situs demo
		if ($id == 1 && $this->setting->demo_mode) {
			unset($data['password']);
			return $data;
		}

		// Ganti password
		if (
			$this->input->post('pass_lama') != ''
			|| $pass_baru != '' || $pass_baru1 != ''
		) {
			$sql = "SELECT password,username,id_grup,session FROM user WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			$row = $query->row();
			// Cek input password
			if (password_verify($password, $row->password) === FALSE) {
				$_SESSION['error_msg'] .= ' -> Kata sandi lama salah<br />';
			}

			if (empty($pass_baru1)) {
				$_SESSION['error_msg'] .= ' -> Kata sandi baru tidak boleh kosong<br />';
			}

			if ($pass_baru != $pass_baru1) {
				$_SESSION['error_msg'] .= ' -> Kata sandi baru tidak cocok<br />';
			}

			if (!empty($_SESSION['error_msg'])) {
				$_SESSION['success'] = -1;
			}
			// Cek input password lolos
			else {
				$_SESSION['success'] = 1;
				// Buat hash password
				$pwHash = $this->generatePasswordHash($pass_baru);
				// Cek kekuatan hash lolos, simpan ke array data
				$data['password'] = $pwHash;
			}
		}
		return $data;
	}

	/**
	 * Update user's settings
	 * @param  integer $id Id user di database
	 * @return void
	 */
	public function update_setting($id = 0)
	{
		$data = $this->periksa_input_password($id);

		$data['nama'] = alfanumerik_spasi($this->input->post('nama'));
		// Update foto
		$data['foto'] = $this->urusFoto($id);
		$hasil = $this->db->where('id', $id)
			->update('user', $data);
		status_sukses($hasil, $gagal_saja = true);
	}

	public function list_grup()
	{
		$sql = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//!===========================================================
	//! Helper Methods
	//!===========================================================

	/**
	 * Buat hash password (bcrypt) dari string sebuah password
	 * @param  [type]  $string  [description]
	 * @return  [type]  [description]
	 */
	private function generatePasswordHash($string)
	{
		// Pastikan inputnya adalah string
		$string = is_string($string) ? $string : strval($string);
		// Buat hash password
		$pwHash = password_hash($string, PASSWORD_BCRYPT);
		// Cek kekuatan hash, regenerate jika masih lemah
		if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
			$pwHash = password_hash($string, PASSWORD_BCRYPT);
		}

		return $pwHash;
	}

	/***
	 * @return
			- success: nama berkas yang diunggah
			- fail: nama berkas lama, kalau ada
	 */
	private function urusFoto($idUser = '')
	{
		if ($idUser) {
			$berkasLama = $this->db->select('foto')->where('id', $idUser)->get('user')->row();
			$berkasLama = is_object($berkasLama) ? $berkasLama->foto : 'kuser.png';
			$lokasiBerkasLama = $this->uploadConfig['upload_path'] . 'kecil_' . $berkasLama;
			$lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);
		} else {
			$berkasLama = 'kuser.png';
		}

		$nama_foto = $this->uploadFoto('gif|jpg|jpeg|png', LOKASI_USER_PICT, 'foto', 'man_user');

		if (!empty($nama_foto)) {
			// Ada foto yang berhasil diunggah --> simpan ukuran 100 x 100
			$tipe_file = TipeFile($_FILES['foto']);
			$dimensi = array("width" => 100, "height" => 100);
			resizeImage(LOKASI_USER_PICT . $nama_foto, $tipe_file, $dimensi);
			// Nama berkas diberi prefix 'kecil'
			$nama_kecil = 'kecil_' . $nama_foto;
			$fileRenamed = rename(
				LOKASI_USER_PICT . $nama_foto,
				LOKASI_USER_PICT . $nama_kecil
			);
			if ($fileRenamed) $nama_foto = $nama_kecil;
			// Hapus berkas lama
			if ($berkasLama and $berkasLama !== 'kecil_kuser.png') {
				unlink($lokasiBerkasLama);
				if (file_exists($lokasiBerkasLama)) $_SESSION['success'] = -1;
			}
		}

		return is_null($nama_foto) ? $berkasLama : str_replace('kecil_', '', $nama_foto);
	}

	/***
	 * @return
			- success: nama berkas yang diunggah
			- fail: NULL
	 */
	private function uploadFoto($allowed_types, $upload_path, $lokasi, $redirect)
	{
		// Adakah berkas yang disertakan?
		$adaBerkas = !empty($_FILES[$lokasi]['name']);
		if ($adaBerkas !== TRUE) {
			return NULL;
		}
		// Tes tidak berisi script PHP
		if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name'])) {
			$_SESSION['error_msg'] .= " -> Jenis file ini tidak diperbolehkan ";
			$_SESSION['success'] = -1;
			redirect($redirect);
		}

		if ((strlen($_FILES[$lokasi]['name']) + 20) >= 100) {
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Nama berkas foto terlalu panjang, maksimal 80 karakter';
			redirect($redirect);
		}

		$uploadData = NULL;
		// Inisialisasi library 'upload'
		$this->upload->initialize($this->uploadConfig);
		// Upload sukses
		if ($this->upload->do_upload($lokasi)) {
			$uploadData = $this->upload->data();
			// Buat nama file unik agar url file susah ditebak dari browser
			$namaClean = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
			$namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
			// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
			$fileRenamed = rename(
				$this->uploadConfig['upload_path'] . $uploadData['file_name'],
				$this->uploadConfig['upload_path'] . $namaFileUnik
			);
			// Ganti nama di array upload jika file berhasil di-rename --
			// jika rename gagal, fallback ke nama asli
			$uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
		}
		// Upload gagal
		else {
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
		}
		return (!empty($uploadData)) ? $uploadData['file_name'] : NULL;
	}
}
