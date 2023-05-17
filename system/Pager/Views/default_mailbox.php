<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(0);
?>

<a href="<?= $pager->getPrevious() ?? '#' ?>" aria-label="<?= lang('Pager.previous') ?>">
	<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
</a>

<a href="<?= $pager->getnext() ?? '#' ?>" aria-label="<?= lang('Pager.next') ?>">
	<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
</a>