<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= base_url() ?>assets/images/logo/andritz-a-logo.png">
			</div>
			<!--<div class="pull-left info">
				<img src="<?= base_url() ?>assets/images/logo/andritz-logo.png">
				</br>

			</div>-->
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">Menu</li>
			<li class="<?php if ($active_navd == 'Administration') {
							echo 'active';
						} ?>">
				<a href="<?= base_url() ?>administration">
					<i class="fa fa-home "></i> <span>Home</span>
					<span class="pull-right-container"></span>
				</a>
			</li>
			<?php
			if (isset($active_navh['breadcrumb_idnavh'])) {
				$idnavactive = $active_navh['breadcrumb_idnavh'];
			} else {
				$idnavactive = 0;
			}
			//if ($chkusernav>0) {
			$key = 0;
			$navh = '';
			foreach ($menu_nav as $menunav) :
				if ($navh != $menunav['mhid']) {
			?>
					<?php if ($navh <> '') {
						echo '</ul></li>';
					} ?>

					<li class="treeview <?php if ($menunav['mhid'] == $idnavactive) {
											echo 'active';
										} ?>">
						<a href="#">
							<i class="fa <?php echo $menunav['mhicon']; ?>"></i> <span><?php echo $menunav['mhname']; ?></span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php
							$navh = $menunav['mhid']; ?>

						<?php } ?>
						<li style="padding-left: 15px;" class="<?php if ($menunav['mdcontroller'] == $active_navd) {
																	echo 'active';
																} ?>"><a href="<?= base_url() . $menunav['mdcontroller']; ?>"><i class="fa <?= $menunav['mdicon']; ?>"></i><?php echo $menunav['mdname']; ?></a></li>
					<?php endforeach;
				//} 
					?>
						</ul>
					</li>
					<!-- END DYNAMIC MENU -->
					<?php //if ($issuperuserlgn == '1') { 
					?>
					<li class="header">Settings</li>

					<li><a href="<?= base_url() . '/admin/administration'; ?>"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
					<?php //} 
					?>
		</ul>
	</section>
</aside>