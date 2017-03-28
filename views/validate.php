<body>
	<section>
		<center><h1>Register</h1></center>
		<div class="container">
			<form method="get" action="<?php $this->url_base.'/validate' ?>">
				<h3>Entrez le code que vous avez reçu par email</h3>
				<?php if ($this->_data) { ?>
					<p class="red"><?php echo $this->_data; ?></p>
				<?php }?>
				<?php if (!$mail) { ?>
				<label class="block">Mail</label>
				<input  type="text" name="mail" placeholder="ex:root@hotmail.com">
				<?php } ?>
				<label class="block">Code</label>
				<input  type="text" name="code" placeholder="ex:3e9k089">
				<button class="link-button" type="submit" name="submit" value="1">Valider</button>
			</form>
		</div>
	</section>
</body>
</html>
