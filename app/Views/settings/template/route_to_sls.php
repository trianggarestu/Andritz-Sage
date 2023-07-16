<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up Delivery Note Number : <strong><?= trim($DOCNUMBER) ?> - <?= trim($SHINUMBER) ?></strong>, Delivery Note Date : <small><?= $SHIDATE ?></small>
    is pending for you to process Sales Admin Team.
</p>
<small>
    <p>
        Customer Received Date : <?= trim($CUSTRCPDATE) ?><br>
        Customer : <strong><?= trim($NAMECUST) ?></strong><br>
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