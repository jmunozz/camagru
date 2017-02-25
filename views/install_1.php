<body>
	<section>
	<center><h1>Installation du site 1/2</h1></center>
	<div class ="container">
		<div class="center log">
			<textarea readonly><?php echo $log; ?></textarea>
		</div>
	</div>
	<div class="container center">
		
		<form class="inline" method="post" 
		action=<?=$this->url_base.'/install/_1'?>>
			<button type="submit" name="submit" 
			value="1" class="link-button">Checker</button>
			<input type="hidden" name="action" value="check" />
		</form>
		<form class="inline" method="post" 
		action=<?=$this->url_base.'/install/_1'?>>
			<button type="submit" name="submit" 
			value="1" class="link-button">Reset</button>
			<input type="hidden" name="action" value="reset" />
		</form>
	</div>
	</section>
	<div class="container">
		<a href=<?=$this->url_base.'/install/_2'?>>
		<span class="button inline right">Suivant</span>
		</a>
	</div>
</body>
</html>
