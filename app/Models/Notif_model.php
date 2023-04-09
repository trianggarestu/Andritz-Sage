<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Notif_model extends Model
{

	//protected $table = 'ARCUS';
	function __construct()
	{
		parent::__construct();
	}


	function count_new_notif($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_read', 0);
		$builder->where('is_archived', 0);
		$builder->where('is_trashed', 0);
		$builder->where('is_deleted', 0);
		$builder->where('to_user', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_unread($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_read=0 and is_archived=0 and is_trashed=0 and is_deleted=0 and to_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function get_mailbox_in($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_archived=0 and is_trashed=0 and is_deleted=0 and to_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function count_mailbox_in($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_archived', 0);
		$builder->where('is_trashed', 0);
		$builder->where('is_deleted', 0);
		$builder->where('to_user', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_star($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_star=1 and is_trashed=0 and is_deleted=0 and to_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function count_mailbox_star($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_star', 1);
		$builder->where('is_trashed', 0);
		$builder->where('is_deleted', 0);
		$builder->where('to_user', $user);
		return $builder->countAllResults();
	}

	function get_sendto_user($groupuser)
	{
		$query = $this->db->query("select USERNAME,NAME,EMAIL,GROUPID from webot_USERAUTH where GROUPID='$groupuser' order by USERNAME asc");
		return $query->getResultArray();
	}

	function get_mailbox_archive($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_archived=1 and is_trashed=0 and is_deleted=0 and to_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function get_mailbox_sent($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_trashed=0 and is_deleted=0 and from_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function count_mailbox_sent($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_trashed', 0);
		$builder->where('is_deleted', 0);
		$builder->where('from_user', $user);
		return $builder->countAllResults();
	}

	function count_mailbox_archive($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_archived', 1);
		$builder->where('is_trashed', 0);
		$builder->where('is_deleted', 0);
		$builder->where('to_user', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_trash($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where is_trashed=1 and is_deleted=0 and to_user='$user' order by sending_date desc, id desc");
		return $query->getResultArray();
	}

	function count_mailbox_trash($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('is_trashed', 1);
		$builder->where('is_deleted', 0);
		$builder->where('to_user', $user);
		return $builder->countAllResults();
	}

	function get_notif_by_id($id)
	{
		$query = $this->db->query("SELECT * FROM webot_MAILBOX where id='$id'");
		return $query->getRowArray();
	}

	function update_is_read($id, $is_read)
	{
		$query = $this->db->table('webot_MAILBOX')->update($is_read, array('id' => $id));
		//Tanpa return juga bisa jalan
		return $query;
	}

	// Untuk update Mark read or Mark unread     
	public function mark_read($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET is_read = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_archive($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET is_archived = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_trash($id = '', $val = 0, $arch = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET is_trashed = ?, is_archived=? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $arch, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_star($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET is_star = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	function mailbox_insert($data_notif)
	{
		$query = $this->db->table('webot_MAILBOX')->insert($data_notif);
		return $query;
	}
}
