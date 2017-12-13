<div id="settings">
		<?php if ($this->_data) { ?>
			<p class="red"><?php echo $this->_data; ?></p>
		<?php }?>
		<form id="settings_form" method="post" action="<?php $this->url_base.'/settings' ?>">
			<label>User Name</label>
			<input  type="text" name="user_name" value="Dummy">
			<hr>
			<label>User Password</label>
			<input type="password" name="user_passeword" value="Dummy">
			<hr>
			<label>Email Address</label>
			<input  type="text" name="user_email" value="Dummy">
			<hr>
			<label>Receive an email when a picture is commented</label>
			<input type="checkbox" value="true">
			<button class="link-button" type="submit" name="submit" value="1">Validate Changes</button>
			<a href="<?php echo $this->url_base.'/signin'; ?>"></a>
		</form>
</div>