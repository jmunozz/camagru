<body>
	<section>
		<center><h1>Register</h1></center>
		<div class="container">
			<form method="post" action="<?php $this->url_base.'/signin' ?>">
				<h3>Inscrivez-vous</h3>
				<?php if ($this->_data) { ?>
					<p class="red"><?php echo $this->_data; ?></p>
				<?php }?>
				<label class="block">login</label>
				<input  type="text" name="login" placeholder="ex:root">
				<label class="block">Mot de passe</label>
				<input type="text" name="pwd" placeholder="ex:root">
				<label class="block">Mail</label>
				<input type="text" name="email" placeholder="ex:root@hotmail.com">
				<button class="link-button" type="submit" name="submit" value="1">Valider</button>
			</form>
		</div>
	</section>
</body>
</html>
