<header>
	<div class="container">
		<a href="<?php echo $this->url_base; ?>"><span>Home</span></a>
<?php
		if (!isset($_SESSION['user_login']) || !isset($_SESSION['user_id']) ||
		!$_SESSION['user_login'] || !$_SESSION['user_id']) {
?>
		<a href="<?php echo $this->url_base.'/login'; ?>"><span>Connexion</span></a>
<?php } else { ?>
		<a href="<?php echo $this->url_base.'/login/out'; ?>"><span>Déconnexion</span></a>
<?php } ?>
	</div>
</header>
