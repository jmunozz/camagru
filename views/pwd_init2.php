<body>
	<section>
		<center><h1>Register</h1></center>
		<div class="container">
			<form method="post" action="<?php $this->url_base.'/validate/init' ?>">
				<h3>Entrez votre nouveau mot de passe</h3>
				<?php if ($this->_data) { ?>
					<p class="red"><?php echo $this->_data; ?></p>
				<?php }?>
				<label class="block">Nouveau mot de passe</label>
				<input  type="text" name="pwd" placeholder="ex:root1234">
				<input  type="hidden" name="mail" value="<?php echo $mail ?>">
				<input  type="hidden" name="code" value="<?php echo $code ?>">
				<button class="link-button" type="submit" name="submit" value="1">Valider</button>
			</form>
		</div>
	</section>
</body>
</html>
