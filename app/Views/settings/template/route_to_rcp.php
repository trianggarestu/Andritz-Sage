<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up PO Number : <strong><?= $PONUMBER ?></strong>, ETD Origin Date :<small><?= $ETDORIGINDATE ?></small>
    is pending for you to process Inventory Team.
</p>
<small>
    <p>
        ATD Origin Date : <?= $ATDORIGINDATE ?><br>
        ETA Port Date : <?= $ETAPORTDATE ?><br>
        PIB Date : <?= $PIBDATE ?><br>
        Shipment Status : <?= $VENDSHISTATUS ?><br>
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