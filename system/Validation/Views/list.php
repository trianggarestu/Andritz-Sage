<?php if (!empty($errors)) : ?>

	<div class="text-muted errors text-red well well-sm" role="alert">
		Error Message :
		<ul>
			<?php foreach ($errors as $error) : ?>
				<li><?= esc($error) ?></li>
			<?php endforeach ?>
		</ul>

	</div>

<?php endif ?>