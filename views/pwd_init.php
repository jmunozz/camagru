<body>
	<section>
		<center><h1>Mot de passe oublié</h1></center>
		<div class="container">
			<form method="post" action="<?php $this->url_base.'/validate/init' ?>">
				<h3>Entrez votre adresse mail</h3>
				<?php if ($this->_data) { ?>
					<p class="red"><?php echo $this->_data; ?></p>
				<?php }?>
				<label class="block">Mail</label>
				<input  type="text" name="mail" placeholder="root@hotmail.com">
				<button class="link-button" type="submit" name="submit" value="1">Valider</button>
			</form>
		</div>
	</section>
</body>
</html>
