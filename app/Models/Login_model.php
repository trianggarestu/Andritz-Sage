<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model
{

    public function ambilpengguna($username, $password)
    {
        return $this->db->table('webot_USERAUTH')
            ->where(array('USERNAME' => $username, 'PASSWORD' => $password))
            ->get()->getRowArray();
    }

    public function datapengguna($user)
    {
        /*return $this->db->table('z_isuserauth')
                ->where (array('USERNAME'=>$username,'INACTIVE'=>0))
                ->get()->getRowArray();*/

        $query = $this->db->query('select USERNAME as usernamelgn,PASSWORD as passlgn,EMAIL as emaillgn,NAME as namalgn,ISSUPERUSER as issuperuserlgn,
        PATH_PHOTO as photolgn,USERHASH as userhashlgn from webot_USERAUTH where username=' . "'$user'" . ' and INACTIVE=0');
        return $query->getRowArray();
    }

    public function datalevel($user)
    {
        $query = $this->db->query('select ISSUPERUSER from webot_USERAUTH where USERNAME=' . "'$user'" . '');
        $result = $query->getRowArray();
        if (isset($result)) {
            return $result['ISSUPERUSER']; //write your column name
        } else {
            return 0;
        }
    }
}
