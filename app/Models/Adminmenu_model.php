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

    function get_contract()
    {
        $query = $this->db->query("select CSRUNIQ,CONTRACT,CTDESC,PODATECUST from webot_CSR");

        if ($query->getResult() > 0) {
            return $query->getResultArray();
        }
    }

    function get_otheader($keyword)
    {
        $query = $this->db->query("select csr.*,rqn.POSTINGSTAT as RQNSTAT,po.CTPO,logs.CTLOG,gr.CTGR,dn.CTDN,edn.CTEDN,fin.CTFIN,rr.CTRR from webot_CSR csr 
        left join webot_REQUISITION rqn on rqn.CSRUNIQ=csr.CSRUNIQ
        left join (select CSRUNIQ,RQNUNIQ,COUNT(PONUMBER) as CTPO from webot_PO where POSTINGSTAT=1
        group by CSRUNIQ,RQNUNIQ) po on po.CSRUNIQ=csr.CSRUNIQ and po.RQNUNIQ=rqn.RQNUNIQ
        left join (select CSRUNIQ,COUNT(PONUMBER) as CTLOG from webot_LOGISTICS
        where POSTINGSTAT=1 group by CSRUNIQ) logs on logs.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(RECPNUMBER) as CTGR from webot_RECEIPTS
        group by CSRUNIQ) gr on gr.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTDN from webot_SHIPMENTS
        where POSTINGSTAT=1 
        group by CSRUNIQ) dn on dn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTEDN from webot_SHIPMENTS
        where EDNPOSTINGSTAT=1
        group by CSRUNIQ) edn on edn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTFIN from webot_FINANCE
        where POSTINGSTAT=1
        group by CSRUNIQ) fin on fin.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTRR from webot_FINANCE
        where POSTINGSTAT=1 and RRPOSTINGSTAT=1
        group by CSRUNIQ) rr on rr.CSRUNIQ=csr.CSRUNIQ
        where csr.CONTRACT ='$keyword'");
        return $query->getResultArray();
    }

    function get_otheader_search($keyword)
    {
        $query = $this->db->query("select csr.*,rqn.POSTINGSTAT as RQNSTAT,po.CTPO,logs.CTLOG,gr.CTGR,dn.CTDN,edn.CTEDN,fin.CTFIN,rr.CTRR from webot_CSR csr 
        left join webot_REQUISITION rqn on rqn.CSRUNIQ=csr.CSRUNIQ
        left join (select CSRUNIQ,RQNUNIQ,COUNT(PONUMBER) as CTPO from webot_PO where POSTINGSTAT=1
        group by CSRUNIQ,RQNUNIQ) po on po.CSRUNIQ=csr.CSRUNIQ and po.RQNUNIQ=rqn.RQNUNIQ
        left join (select CSRUNIQ,COUNT(PONUMBER) as CTLOG from webot_LOGISTICS
        where POSTINGSTAT=1 group by CSRUNIQ) logs on logs.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(RECPNUMBER) as CTGR from webot_RECEIPTS
        group by CSRUNIQ) gr on gr.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTDN from webot_SHIPMENTS
        where POSTINGSTAT=1 
        group by CSRUNIQ) dn on dn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTEDN from webot_SHIPMENTS
        where DNPOSTINGSTAT=1
        group by CSRUNIQ) edn on edn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTFIN from webot_FINANCE
        where POSTINGSTAT=1
        group by CSRUNIQ) fin on fin.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTRR from webot_FINANCE
        where POSTINGSTAT=1 and RRPOSTINGSTAT=1
        group by CSRUNIQ) rr on rr.CSRUNIQ=csr.CSRUNIQ
        where csr.CONTRACT ='$keyword'");
        return $query->getResultArray();
    }

    function get_otheader_by_csruniq($csruniq)
    {
        $query = $this->db->query("select csr.*,rqn.POSTINGSTAT as RQNSTAT,po.CTPO,logs.CTLOG,gr.CTGR,dn.CTDN,edn.CTEDN,fin.CTFIN,rr.CTRR from webot_CSR csr 
        left join webot_REQUISITION rqn on rqn.CSRUNIQ=csr.CSRUNIQ
        left join (select CSRUNIQ,RQNUNIQ,COUNT(PONUMBER) as CTPO from webot_PO where POSTINGSTAT=1
        group by CSRUNIQ,RQNUNIQ) po on po.CSRUNIQ=csr.CSRUNIQ and po.RQNUNIQ=rqn.RQNUNIQ
        left join (select CSRUNIQ,COUNT(PONUMBER) as CTLOG from webot_LOGISTICS
        where POSTINGSTAT=1 group by CSRUNIQ) logs on logs.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(RECPNUMBER) as CTGR from webot_RECEIPTS
        group by CSRUNIQ) gr on gr.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTDN from webot_SHIPMENTS
        where POSTINGSTAT=1 
        group by CSRUNIQ) dn on dn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,count(DOCNUMBER) as CTEDN from webot_SHIPMENTS
        where DNPOSTINGSTAT=1
        group by CSRUNIQ) edn on edn.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTFIN from webot_FINANCE
        where POSTINGSTAT=1
        group by CSRUNIQ) fin on fin.CSRUNIQ=csr.CSRUNIQ
        left join (
        select CSRUNIQ,COUNT(IDINVC) as CTRR from webot_FINANCE
        where POSTINGSTAT=1 and RRPOSTINGSTAT=1
        group by CSRUNIQ) rr on rr.CSRUNIQ=csr.CSRUNIQ
        where csr.CSRUNIQ ='$csruniq'");
        return $query->getRowArray();
    }

    // UNtuk hapus e-DN
    function get_shi_data($csruniq)
    {
        $query = $this->db->query("select SHIUNIQ,CSRUNIQ,SHIATTACHED,EDNFILENAME,EDNFILEPATH from webot_SHIPMENTS where CSRUNIQ='$csruniq'");
        return $query->getResultArray();
    }

    function rollback_dn($csruniq)
    {
        return $this->db->table('webot_SHIPMENTS')->delete(['CSRUNIQ' => $csruniq]);
    }

    function rollback_dnl($csruniq)
    {
        return $this->db->table('webot_SHIL')->delete(['CSRUNIQ' => $csruniq]);
    }

    function rollback_edn($csruniq, $dataedn)
    {
        $query = $this->db->table('webot_SHIPMENTS')->update($dataedn, array('CSRUNIQ' => $csruniq));
        return $query;
    }

    function rollback_fin($csruniq)
    {
        return $this->db->table('webot_FINANCE')->delete(['CSRUNIQ' => $csruniq]);
    }

    function rollback_finl($csruniq)
    {
        return $this->db->table('webot_FINMULTISHI')->delete(['CSRUNIQ' => $csruniq]);
    }

    function rollback_rr($csruniq, $datarr)
    {
        $query = $this->db->table('webot_FINANCE')->update($datarr, array('CSRUNIQ' => $csruniq));
        return $query;
    }

    //Roll Back Order Tracking
    function rollback_ot($csruniq, $dataot)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($dataot, array('CSRUNIQ' => $csruniq, 'OTSEQ >' => 181));
        return $query;
    }
}
