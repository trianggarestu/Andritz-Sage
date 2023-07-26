<br /><p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p>
<p>&nbsp;</p>
<p>Please to follow up Delivery Note Number : <strong><?= trim($DOCNUMBER) ?> - <?= trim($SHINUMBER) ?></strong>, Delivery Note Date : <small><?= $SHIDATE ?></small> is pending for you to process Sales Admin Team.</p>
<p>Customer Received Date : <?= trim($CUSTRCPDATE) ?><br />Customer : <strong><?= trim($NAMECUST) ?></strong><br />Original Receipt D/N Date : <?= $ORIGDNRCPSHIDATE ?></p>
<hr />
<p>You can access Order Tracking System Portal via the URL below: <br /><a href="http://jktsms025:8082/Andritz-sage/public">http://jktsms025:8082/Andritz-sage/public</a> Thanks for your cooperation.</p>
<p><br /><br /></p>
<p><?= $FROMNAME ?></p>