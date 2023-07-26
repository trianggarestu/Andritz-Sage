<br /><p>Hello <?= ucwords(strtolower($TONAME)) ?>,</p>
<p>&nbsp;</p>
<p>Please to follow up PO Number : <strong><?= $PONUMBER ?></strong>, ETD Origin Date :<small><?= $ETDORIGINDATE ?></small> is pending for you to process Inventory Team.</p>
<p>ATD Origin Date : <?= $ATDORIGINDATE ?><br />ETA Port Date : <?= $ETAPORTDATE ?><br />PIB Date : <?= $PIBDATE ?><br />Shipment Status : <?= $VENDSHISTATUS ?></p>
<hr />
<p>You can access Order Tracking System Portal via the URL below: <br /><a href="http://jktsms025:8082/Andritz-sage/public">http://jktsms025:8082/Andritz-sage/public</a> Thanks for your cooperation.</p>
<p><br /><br /></p>
<p><?= $FROMNAME ?></p>
<p>&nbsp;</p>
<p><?= $ETDORIGINDATE ?> <?= $ATDORIGINDATE ?> <?= $ETAPORTDATE ?> <?= $ETAPORTDATE ?>&nbsp; <?= $PIBDATE ?> <?= $VENDSHISTATUS ?></p>