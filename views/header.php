<header>
	<nav>
		<ul>
			<li>
				<a href="<?php echo $this->url_base; ?>">
					<b>Home</b>
				</a>
			</li>
			<li>
				<a href="<?php echo $this->url_base; ?>/gallery">
					<b>Gallery</b>
				</a>
			</li>
<?php
	if (!isset($_SESSION['user_login']) || !isset($_SESSION['user_id']) ||
		!$_SESSION['user_login'] || !$_SESSION['user_id']) {
?>	
			<li>
				<a href="<?php echo $this->url_base.'/login'; ?>">
					<b>Login</b>
				</a>
			</li>
<?php } else { ?>
			<li>
				<a href="<?php echo $this->url_base.'/settings'; ?>">
					<b>Settings</b>
				</a>
			</li>
			<li>
				<a href="<?php echo $this->url_base.'/login/out'; ?>">
					<b>Logout</b>
				</a>
			</li>
<?php } ?>
		</ul>
	</nav>
</header>



