<?php

namespace App\Models;

use CodeIgniter\Model;


/**
 * Description of Settingproducts_model
 *
 * @author ICT-Notebook
 */
class Finance_model extends Model
{

    protected $table = 'webot_FINANCE';

    function __construct()
    {
        parent::__construct();
    }



    //Finance

    function get_shi_pending_to_finance()
    {
        $query = $this->db->query("select a.SHIUNIQ,a.DOCNUMBER,a.SHINUMBER,a.SHIDATE,a.CUSTRCPDATE,a.SHIITEMNO,a.SHIQTY,a.SHIQTYOUTSTANDING,a.SHIUNIT,a.POCUSTSTATUS,
        a.EDNFILENAME,a.EDNFILEPATH,a.POSTINGSTAT as SHIPOSTINGSTAT,a.DNSTATUS,a.DNPOSTINGSTAT,
        b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST," . 'b."CONTRACT"' . " as CSRCONTRACT,b.CTDESC,b.PROJECT as CSRPROJECT,b.CRMNO,b.CRMREQDATE,
        b.ITEMNO,b.MATERIALNO,it." . '"DESC"' . " as ITEMDESC,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.FINUNIQ,c.IDINVC,c.INVOICEDATE,c.FINSTATUS,c.RRSTATUS,c.POSTINGSTAT,c.OFFLINESTAT
        from webot_SHIPMENTS a 
        left join webot_CSR b on b.CSRUNIQ=a.CSRUNIQ
        left join ICITEM it on it.ITEMNO=b.ITEMNO
        left join webot_FINANCE c on c.SHIUNIQ=a.SHIUNIQ
        where (a.POSTINGSTAT=1 and a.EDNFILENAME IS NOT NULL and a.DNPOSTINGSTAT=1) and c.POSTINGSTAT=0");

        return $query->getResultArray();
    }


    function get_shi_by_id($shiuniq)
    {
        $query = $this->db->query("select a.*,b.NAMECUST," . 'it."DESC"' . " as SHIITEMDESC,
        c.FINUNIQ,c.IDINVC,c.FINSTATUS,c.POSTINGSTAT as FINPOSTINGSTAT from webot_SHIPMENTS a
        left join ARCUS b on b.IDCUST=a.CUSTOMER
        left join ICITEM it on it.ITEMNO=a.SHIITEMNO
        left join webot_FINANCE c on c.SHIUNIQ=a.SHIUNIQ
        where a.POSTINGSTAT=1 and a.SHIUNIQ='$shiuniq' ");
        return $query->getRowArray();
    }

    function get_fin_by_id($finuniq)
    {
        $query = $this->db->query("select a.* from webot_FINANCE a
        where a.FINUNIQ='$finuniq' ");
        return $query->getRowArray();
    }


    function list_sage_ar_by_contract($ct_no)
    {
        $query = $this->db->query("select a.IDCUST,a.IDINVC,a.DATEINVC,a.INVCDESC,c.CONTRACT,c.PROJECT,c.CATEGORY,c.TEXTDESC from ARIBH a
        left join ARIBC b on b.CNTBTCH=a.CNTBTCH
        inner join ARIBD c on c.CNTBTCH=a.CNTBTCH and c.CNTITEM=a.CNTITEM
        where b.BTCHSTTS=3 and
        c.CONTRACT='$ct_no' and a.IDINVC not in (select distinct IDINVC from webot_FINANCE where POSTINGSTAT=1)");
        return $query->getResultArray();
    }


    function get_arinvoice_by_id($idinvc)
    {
        $query = $this->db->query("select a.IDCUST,a.IDINVC,a.DATEINVC,a.INVCDESC,c.CONTRACT,c.PROJECT,c.CATEGORY,c.TEXTDESC from ARIBH a
        left join ARIBC b on b.CNTBTCH=a.CNTBTCH
        inner join ARIBD c on c.CNTBTCH=a.CNTBTCH and c.CNTITEM=a.CNTITEM
        where b.BTCHSTTS=3 and a.IDINVC='$idinvc' ");
        return $query->getRowArray();
    }

    function finance_insert($data)
    {
        $query = $this->db->table('webot_FINANCE')->insert($data);
        return $query;
    }


    function finance_update($finuniq, $data1)
    {
        $query = $this->db->table('webot_FINANCE')->update($data1, array('FINUNIQ' => $finuniq));
        //Tanpa return juga bisa jalan
        return $query;
    }

    function ot_finance_update($id_so, $data2)
    {
        $query = $this->db->table('webot_ORDERTRACKING')->update($data2, array('CSRUNIQ' => $id_so));
        //Tanpa return juga bisa jalan
        return $query;
    }




    // Untuk Fill Invoice List
    function count_fin_posting()
    {
        $builder = $this->db->table('webot_FINANCE');
        $builder->where('webot_FINANCE.POSTINGSTAT=', 1);
        return $builder->countAllResults();
    }
}
