<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up Requisition Number : <strong><?= $RQNNUMBER ?></strong>(<?= $RQNDATE ?> ) / Contract : <strong><?= $CONTRACT ?></strong>(<?= $PODATECUST ?>)
    is pending for you to process Purchase Order Vendor.
</p>
<br>
<small>
    <p>Contract : <?= $CONTRACT ?>-<?= $CTDESC ?><br>
    </p>
</small>
<p>You can access Order Tracking System Portal via the URL below:
    <br><a href="http://jktsms025:8082/Andritz-sage/public">http://jktsms025:8082/Andritz-sage/public</a>
    Thanks for your cooperation.

</p>
<br>
<br>
<p><?= $FROMNAME ?></p>