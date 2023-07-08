<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up PO Number : <strong><?= $PONUMBER ?></strong><small>(<?= $PODATE ?> )</small> / Contract : <strong><?= $CONTRACT ?></strong>
    is pending for you to process Logistics Team.
</p>
<small>
    <p>PO Number :<?= $PONUMBER ?><br>
        PO Vendor Date :<?= $PODATE ?><br>
        ETD Date :<?= $ETDDATE ?><br>
        Cargo Readiness Date :<?= $CARGOREADINESSDATE ?><br>
        Origin Country :<?= $ORIGINCOUNTRY ?><br>
        Remarks :<?= $POREMARKS ?><br>
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