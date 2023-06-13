<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
//use App\Models\Settingnavheader_model;


class Mailbox extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;

    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        //$this->SettingnavheaderModel = new Settingnavheader_model();
        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            header('Location: ' . base_url());
            exit();
        } else {
            $user = session()->get('username');
            $infouser = $this->LoginModel->datapengguna($user);
            if (session()->get('keylog') == $infouser['passlgn'] and session()->get('userhash') == $infouser['userhashlgn']) {
                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'photolgn' => $infouser['photolgn'],
                    'userhashlgn' => $infouser['userhashlgn'],
                    'notif_messages' => $mailbox_unread,
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'mailbox';
                $activenavh = $this->AdministrationModel->get_activenavh($activenavd);
                $this->nav_data = [
                    'active_navd' => $activenavd,
                    'active_navh' => $activenavh,
                    'menu_nav' => $this->AdministrationModel->get_navigation($user),
                    //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                    //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                    //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
                ];

                date_default_timezone_set('Asia/Jakarta');
                $today = date("d/m/Y H:i:s");

                $this->audtuser = [
                    'TODAY' => $today,
                    'AUDTDATE' => substr($today, 6, 4) . "" . substr($today, 3, 2) . "" . substr($today, 0, 2),
                    'AUDTTIME' => substr($today, 11, 2) . "" . substr($today, 14, 2) . "" . substr($today, 17, 2),
                    'AUDTUSER' => $infouser['usernamelgn'],

                ];
            } else {
                header('Location: ' . base_url());
                exit();
            }
        }
    }


    public function index()
    {
        /*
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->get_mailbox_unread($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $data = array(
            'mailbox_list' => $mailbox_data,
            'ct_messages' => $this->NotifModel->count_new_notif($user),
            'mailbox_active' => 'Inbox (unread)',
            //'usernamelgn' => $user,
        );

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
        */
    }

    public function unread()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->where('IS_READ=', 0)
            ->where('IS_ARCHIVED=', 0)
            ->where('IS_TRASHED=', 0)
            ->where('IS_DELETED=', 0)
            ->where('TO_USER=', $user)
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');
        //$mailbox_data = $this->NotifModel->get_mailbox_unread($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_new_notif($user),
            'mailbox_active' => 'Inbox (unread)',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            'usernamelgn' => $user,
        );


        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function inbox()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->where('IS_ARCHIVED=', 0)
            ->where('IS_TRASHED=', 0)
            ->where('IS_DELETED=', 0)
            ->where('TO_USER=', $user)
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');
        //$mailbox_data = $this->NotifModel->get_mailbox_in($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_mailbox_in($user),
            'mailbox_active' => 'Inbox',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            //'usernamelgn' => $user,
        );


        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function star()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->where('IS_STAR=', 1)
            ->where('IS_TRASHED=', 0)
            ->where('IS_DELETED=', 0)
            ->where('TO_USER=', $user)
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');
        //$mailbox_data = $this->NotifModel->get_mailbox_star($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_mailbox_star($user),
            'mailbox_active' => 'Star',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            //'usernamelgn' => $user,
        );

        /*$data = array(
            'mailbox_list' => $mailbox_data,
            'ct_messages' => $this->NotifModel->count_mailbox_star($user),
            'mailbox_active' => 'Star',
            //'usernamelgn' => $user,
        );*/

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function archive()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->groupStart()
            ->where('IS_ARCHIVED=', 1)
            ->where('IS_TRASHED=', 0)
            ->where('IS_DELETED=', 0)
            ->where('TO_USER=', $user)
            ->groupEnd()
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');
        //$mailbox_data = $this->NotifModel->get_mailbox_archive($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_mailbox_archive($user),
            'mailbox_active' => 'Archive',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            //'usernamelgn' => $user,
        );


        /*$data = array(
            'mailbox_list' => $mailbox_data,
            'ct_messages' => $this->NotifModel->count_mailbox_archive($user),
            'mailbox_active' => 'Archive',
            //'usernamelgn' => $user,
        );*/

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function sent()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->where('IS_ARCHIVEDSENDER=', 0)
            ->where('IS_TRASHEDSENDER=', 0)
            ->where('IS_DELETEDSENDER=', 0)
            ->where('FROM_USER=', $user)
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');

        //$mailbox_data = $this->NotifModel->get_mailbox_sent($user);

        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_mailbox_sent($user),
            'mailbox_active' => 'Sent',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            //'usernamelgn' => $user,
        );

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox_sent', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function trash()
    {
        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $mailbox_data = $this->NotifModel->select('*')
            ->groupStart()
            ->where('IS_TRASHED=', 1)
            ->where('IS_DELETED=', 0)
            ->where('TO_USER=', $user)
            ->groupEnd()
            ->orgroupStart()
            ->where('IS_TRASHEDSENDER=', 1)
            ->where('IS_DELETEDSENDER=', 0)
            ->where('FROM_USER=', $user)
            ->groupEnd()
            ->orderBy('SENDING_DATE', 'DESC')
            ->orderBy('MAILSEQ', 'DESC');

        //$mailbox_data = $this->NotifModel->get_mailbox_trash($user);
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;
        $perpage = 20;
        $data = array(
            'mailbox_list' => $mailbox_data->paginate($perpage, 'mailbox_list'),
            'pager' => $mailbox_data->pager,
            'ct_messages' => $this->NotifModel->count_mailbox_trash($user),
            'mailbox_active' => 'Trash',
            'perpage' => $perpage,
            'currentpage' => $mailbox_data->pager->getCurrentPage('mailbox_list'),
            'totalpages'  => $mailbox_data->pager->getPageCount('mailbox_list'),
            'usernamelgn' => $user,
        );

        /*$data = array(
            'mailbox_list' => $mailbox_data,
            'ct_messages' => $this->NotifModel->count_mailbox_trash($user),
            'mailbox_active' => 'Trash',
            //'usernamelgn' => $user,
        );*/

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('mailbox/view_mailbox', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function view_messages($id)
    {

        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $row = $this->NotifModel->get_notif_by_id($id);

        if ($row) {
            $is_read = array(
                'IS_READ' => 1,
                'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
            );
            $this->NotifModel->update_is_read($id, $is_read);
            $sending_date = substr($row['SENDING_DATE'], 4, 2) . "/" . substr($row['SENDING_DATE'], 6, 2) . "/" .  substr($row['SENDING_DATE'], 0, 4);
            $sending_date = date('M d Y', strtotime($sending_date));
            $time = strlen($row['SENDING_TIME']);
            switch ($time) {
                case "0":
                    $sending_time = '00:00';
                    break;
                case "1":
                    $sending_time = '00:00';
                    break;
                case "2":
                    $sending_time = '00:00';
                    break;
                case "3":
                    $sending_time = '00:0' . substr($row['SENDING_TIME'], 0, 1);
                    break;
                case "4":
                    $sending_time = '00:' . substr($row['SENDING_TIME'], 0, 2);
                    break;
                case "5":
                    $sending_time = '0' . substr($row['SENDING_TIME'], 0, 1) . ':' . substr($row['SENDING_TIME'], 1, 2);
                    break;
                case "6":
                    $sending_time = substr($row['SENDING_TIME'], 0, 2) . ":" . substr($row['SENDING_TIME'], 2, 2);
                    break;
                default:
                    $sending_time = '00:00';
            }
            $data = array(
                'id' => $row['MAILSEQ'],
                'from_email' => $row['FROM_EMAIL'],
                'from_name' => $row['FROM_NAME'],
                'to_email' => $row['TO_EMAIL'],
                'to_name' => $row['TO_NAME'],
                'sending_date' => $sending_date,
                'sending_time' => $sending_time,
                'subject' => $row['SUBJECT'],
                'message' => $row['MESSAGE'],
                'is_attached' => $row['IS_ATTACHED'],
                'attachment_filename' => $row['ATTACHMENT_FILENAME'],
                'attachment_filepath' => $row['ATTACHMENT_FILEPATH'],
            );
            echo view('mailbox/ajax_view_messages', $data);
        }
    }

    public function mark_read($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_READ'] == 0) {
            $this->NotifModel->mark_read($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_unread($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_READ'] == 1) {
            $this->NotifModel->mark_read($id, 0);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_archive($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_ARCHIVED'] == 0) {
            $this->NotifModel->mark_archive($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_atoinbox($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_ARCHIVED'] == 1) {
            $this->NotifModel->mark_archive($id, 0);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_trash($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_TRASHED'] == 0) {
            $this->NotifModel->mark_trash($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }


    public function mark_ttoinbox($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_TRASHED'] == 1) {
            $this->NotifModel->mark_trash($id, 0, 0);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_star($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_STAR'] == 0) {
            $this->NotifModel->mark_star($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_unstar($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_STAR'] == 1) {
            $this->NotifModel->mark_star($id, 0);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_senderread($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_READSENDER'] == 0) {
            $this->NotifModel->mark_senderread($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_senderunread($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_READSENDER'] == 1) {
            $this->NotifModel->mark_senderread($id, 0);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_sendertrash($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_TRASHEDSENDER'] == 0) {
            $this->NotifModel->mark_sendertrash($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }

    public function mark_senderarchive($id = '')
    {
        $chkmail = $this->NotifModel->get_notif_by_id($id);
        if ($chkmail['IS_ARCHIVEDSENDER'] == 0) {
            $this->NotifModel->mark_senderarchive($id, 1);
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }
    }
}
