<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up Good Receipt Number : <strong><?= $RECPNUMBER ?></strong>, G/R Date :<small><?= $RECPDATE ?></small>
    is pending for you to process Delivery Team.
</p>
<small>
    <p>
        PO Number : <strong><?= $PONUMBER ?></strong><br>
        Receipt Number :<strong><?= $RECPNUMBER ?></strong><br>
        Receipt Date : <?= $RECPDATE ?><br>
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