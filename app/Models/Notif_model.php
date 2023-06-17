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

	protected $table = 'webot_MAILBOX';
	function __construct()
	{
		parent::__construct();
	}

	function get_template($groupuser)
	{
		$query = $this->db->query("select * from webot_MAILTEMPLATE where MAILROUTE='$groupuser'");
		return $query->getRowArray();
	}

	function count_new_notif($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('IS_READ', 0);
		$builder->where('IS_ARCHIVED', 0);
		$builder->where('IS_TRASHED', 0);
		$builder->where('IS_DELETED', 0);
		$builder->where('TO_USER', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_unread($user)
	{
		$query = $this->db->query("select a.*,usr.PATH_PHOTO from webot_MAILBOX a 
		left join webot_USERAUTH usr on usr.USERNAME=a.FROM_USER
		where a.IS_READ=0 and a.IS_ARCHIVED=0 and a.IS_TRASHED=0 and a.IS_DELETED=0 and a.TO_USER='$user' 
		order by a.SENDING_DATE desc, a.MAILSEQ desc");
		return $query->getResultArray();
	}

	function get_mailbox_in($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where IS_ARCHIVED=0 and IS_TRASHED=0 and IS_DELETED=0 and TO_USER='$user' order by SENDING_DATE desc, MAILSEQ desc");
		return $query->getResultArray();
	}

	function count_mailbox_in($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('IS_ARCHIVED', 0);
		$builder->where('IS_TRASHED', 0);
		$builder->where('IS_DELETED', 0);
		$builder->where('TO_USER', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_star($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where IS_STAR=1 and IS_TRASHED=0 and IS_DELETED=0 and TO_USER='$user' order by SENDING_DATE desc, MAILSEQ desc");
		return $query->getResultArray();
	}

	function count_mailbox_star($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('IS_STAR', 1);
		$builder->where('IS_TRASHED', 0);
		$builder->where('IS_DELETED', 0);
		$builder->where('TO_USER', $user);
		return $builder->countAllResults();
	}

	function get_mailbox_archive($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where IS_ARCHIVED=1 and IS_TRASHED=0 and IS_DELETED=0 and TO_USER='$user' order by SENDING_DATE desc, MAILSEQ desc");
		return $query->getResultArray();
	}

	function count_mailbox_archive($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->groupStart();
		$builder->where('IS_ARCHIVED', 1);
		$builder->where('IS_TRASHED', 0);
		$builder->where('IS_DELETED', 0);
		$builder->where('TO_USER', $user);
		$builder->groupEnd();
		return $builder->countAllResults();
	}

	function get_mailbox_sent($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where IS_ARCHIVED=0 and IS_TRASHED=0 and IS_DELETED=0 and FROM_USER='$user' order by SENDING_DATE desc, MAILSEQ desc");
		return $query->getResultArray();
	}

	function count_mailbox_sent($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->where('IS_ARCHIVEDSENDER', 0);
		$builder->where('IS_TRASHEDSENDER', 0);
		$builder->where('IS_DELETEDSENDER', 0);
		$builder->where('FROM_USER', $user);
		return $builder->countAllResults();
	}

	function get_sendto_user($groupuser)
	{
		$query = $this->db->query("select a.USERNAME,a.NAME,a.EMAIL,a.GROUPID from webot_USERAUTH a where GROUPID='$groupuser' order by USERNAME asc");
		return $query->getResultArray();
	}


	function get_edn_sendto_user($groupuser, $id_so)
	{
		$query = $this->db->query("select * from (select a.USERNAME,a.NAME,a.EMAIL,a.GROUPID from webot_USERAUTH a where GROUPID='$groupuser' 
		union all
		select 'CUSTOMER' as USERNAME,NAMECUST,EMAIL1CUST,'7' as GROUPID from webot_CSR where CSRUNIQ='$id_so') x

		order by x.USERNAME asc");
		return $query->getResultArray();
	}


	function get_mailbox_trash($user)
	{
		$query = $this->db->query("select * from webot_MAILBOX where IS_TRASHED=1 and IS_DELETED=0 and TO_USER='$user' order by SENDING_DATE desc, MAILSEQ desc");
		return $query->getResultArray();
	}

	function count_mailbox_trash($user)
	{
		$builder = $this->db->table('webot_MAILBOX');
		$builder->groupStart();
		$builder->where('IS_TRASHED', 1);
		$builder->where('IS_DELETED', 0);
		$builder->where('TO_USER', $user);
		$builder->groupEnd();
		$builder->orgroupStart();
		$builder->where('IS_TRASHEDSENDER', 1);
		$builder->where('IS_DELETEDSENDER', 0);
		$builder->where('FROM_USER', $user);
		$builder->groupEnd();
		return $builder->countAllResults();
	}

	function get_notif_by_id($id)
	{
		$query = $this->db->query("SELECT * FROM webot_MAILBOX where MAILSEQ='$id'");
		return $query->getRowArray();
	}

	function update_is_read($id, $is_read)
	{
		$query = $this->db->table('webot_MAILBOX')->update($is_read, array('MAILSEQ' => $id));
		//Tanpa return juga bisa jalan
		return $query;
	}

	// Untuk update Mark read or Mark unread     
	public function mark_read($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_READ = ? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}


	public function mark_archive($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_ARCHIVED = ? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_trash($id = '', $val = 0, $arch = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_TRASHED = ?, IS_ARCHIVED=? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $arch, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_star($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_STAR = ? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	// Untuk update dari sisi Sender  
	public function mark_senderread($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_READSENDER = ? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_senderarchive($id = '', $val = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_ARCHIVEDSENDER = ? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	public function mark_sendertrash($id = '', $val = 0, $arch = 0)
	{
		$sql = "UPDATE webot_MAILBOX SET IS_TRASHEDSENDER = ?, IS_ARCHIVEDSENDER=? WHERE MAILSEQ = ?";
		$outp = $this->db->query($sql, array($val, $arch, $id));
		//Tanpa return juga bisa jalan
		return $outp;
	}

	function mailbox_insert($data_notif)
	{
		$query = $this->db->table('webot_MAILBOX')->insert($data_notif);
		return $query;
	}
}
