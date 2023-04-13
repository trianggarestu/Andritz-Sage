<?php if (!empty($errors)) : ?>
	<div class="errors text-red" role="alert">
		<ul>
			<?php foreach ($errors as $error) : ?>
				<li><?= esc($error) ?></li>
			<?php endforeach ?>
		</ul>

	</div>
<?php endif ?>