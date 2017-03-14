<body>
	<section>
		<center><h1>Installation du site 2/3</h1></center>
		<div class="container">
		<form method="post" 
		action=<?=$this->url_base.'/install/_2'?>>
			<h3>Création d'un profil administrateur</h3>
			<?php if ($success == FALSE && $alert) { ?>
				<p class="red"><?php echo $alert; ?></p>
			<?php } if ($success == TRUE) { ?>
				<p class="green">L'administrateur a été créé</p>
			<?php }?>
			<label class="block">login</label>
			<input  type="text" name="login" placeholder="ex:root">
			<label class="block">Mot de passe</label>
			<input type="text" name="pwd" placeholder="ex:root">
			<label class="block">Mail</label>
			<input type="text" name="email" placeholder="ex:abc@hotmail.com">
			<button class="link-button" type="submit" name="submit" value="1">Valider</button>
			</form>
		</div>
		<div class="container">
			<a href=<?=$this->url_base.'/install/_3'?>>
			<span class="button inline right">Suivant</span>
		</a>
	</div>
</body>
</html>
