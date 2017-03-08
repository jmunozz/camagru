<header>
	<div class="container menu j-center">
		<a href="<?php echo $this->url_base; ?>">
			<div class="header"><h1>Home</h1></div>
		</a>
<?php
		if (!isset($_SESSION['user_login']) || !isset($_SESSION['user_id']) ||
		!$_SESSION['user_login'] || !$_SESSION['user_id']) {
?>
		<a href="<?php echo $this->url_base.'/login'; ?>">
			<div class="header"><h1>Connexion</h1></div>
		</a>
<?php } else { ?>
		<a href="<?php echo $this->url_base.'/login/out'; ?>">
			<div class="header"><h1>Déconnexion<h1></div>
		</a>
<?php } ?>
	</div>
</header>
