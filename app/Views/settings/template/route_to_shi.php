<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up Good Receipt Number : <strong><?= trim($RECPNUMBER) ?></strong>, Receipt Date : <small><?= $RECPDATE ?></small>
    is pending for you to process Delivery Team.
</p>
<small>
    <p>
        PO Number : <strong><?= trim($PONUMBER) ?></strong><br>
        Vendor Name : <?= trim($VDNAME) ?><br>
        Description : <?= trim($DESCRIPTIO) ?><br>
        <hr>
    </p>
</small>
<p>You can access Order Tracking System Portal via the URL below:
    <br><a href="http://jktsms025:8082/Andritz-sage/public">http://jktsms025:8082/Andritz-sage/public</a>
    Thanks for your cooperation.

</p>
<br>
<br>
<p><?= $FROMNAME ?></p>