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
			<li class="header"><i class="fa fa-navicon"></i><strong>Menu</strong></li>
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
			if (!empty($menu_nav)) {
				foreach ($menu_nav as $menunav) :

					if ($navh != $menunav['mhid']) {
			?>
						<?php if ($navh <> '') {
							echo '</ul></li>';
						}
						?>

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
						<?php

					endforeach;

					//} 
						?>
							</ul>
						</li>
					<?php } ?>
					<!-- END DYNAMIC MENU -->

					<?php if ($issuperuserlgn == '1') {
					?>
						<li class="header"><i class="fa fa-cog"></i><strong>Admin Menu</strong></li>
						<li class="<?php if ($active_navd == 'rollbackprocess') {
										echo 'active';
									} ?>"><a href="<?= base_url() . 'rollbackprocess/'; ?>"><i class="fa fa-undo text-aqua"></i> <span>Rollback Process</span></a></li>
					<?php } ?>
					<!-- START ADMIN MENU - SETTINGS -->
					<?php if ($issuperuserlgn == '1') {
					?>
						<li class="header"><i class="fa fa-wrench"></i><strong>Settings</strong></li>

						<li class="<?php if ($active_navd == 'menusetup') {
										echo 'active';
									} ?>"><a href="<?= base_url() . 'menusetup/'; ?>"><i class="fa fa-navicon text-aqua"></i> <span>Menu Settings</span></a></li>
						<li class="treeview <?php if ($active_navh == 'mailsetup') {
												echo 'active';
											} ?>">
							<a href="#">
								<i class="fa fa-gears text-aqua"></i> <span>E-mail Settings</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li style="padding-left: 15px;" class="<?php if ($active_navd == 'mailsendersetup') {
																			echo 'active';
																		} ?>">
									<a href="<?= base_url() . 'mailsendersetup'; ?>"><i class="fa fa-paper-plane"></i>Mail Sender Setup</a>
								</li>
								<li style="padding-left: 15px;" class="<?php if ($active_navd == 'mailtemplatesetup') {
																			echo 'active';
																		} ?>">
									<a href="<?= base_url() . 'mailtemplatesetup'; ?>"><i class="fa  fa-sticky-note"></i>E-mail Templates</a>
								</li>
							</ul>
						</li>
						<li class="treeview <?php if ($active_navh == 'usersettings') {
												echo 'active';
											} ?>">
							<a href="#">
								<i class="fa fa-user text-aqua"></i> <span>User Settings</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li style="padding-left: 15px;" class="<?php if ($active_navd == 'usergroupsetup') {
																			echo 'active';
																		} ?>">
									<a href="<?= base_url() . 'usergroupsetup'; ?>"><i class="fa fa-group"></i>Groups</a>
								</li>
								<li style="padding-left: 15px;" class="<?php if ($active_navd == 'usersetup') {
																			echo 'active';
																		} ?>">
									<a href="<?= base_url() . 'usersetup'; ?>"><i class="fa fa-user-plus"></i>Users</a>
								</li>
							</ul>
						</li>
					<?php }
					?>
		</ul>
	</section>
</aside>