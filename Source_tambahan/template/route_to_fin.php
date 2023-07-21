<p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p><Br>
<p>Please to follow up DN Origin : <strong><?= trim($DOCNUMBER) ?> - <?= trim($SHINUMBER) ?></strong>, Delivery Note Date : <small><?= $SHIDATE ?></small>
    is pending for you to process Finance Team.
</p>
<small>
    <p>
        Customer Received Date : <?= trim($CUSTRCPDATE) ?><br>
        Customer : <strong><?= trim($NAMECUST) ?></strong><br>
        D/N Status : <strong><?= trim($DNSTATUS) ?></strong><br>
        Original Receipt D/N Date : <strong><?= trim($ORIGDNRCPSLSDATE) ?></strong><br>
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