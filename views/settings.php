<p id="alert" style="display:none;"></p>
<div id="settings">
		<form class="settings_form">
			<label>User Name</label>
			<input  id="new_user_name" type="text" name="user_name" value="<?php echo $user_name ?>">
			<hr>
			<label>Email Address</label>
			<input  id="new_user_email" type="text" name="user_email" value="<?php echo $user_email ?>">
			<hr>
			<label>Receive an email when a picture is commented</label>
			<input id="new_user_comment_tag" type="checkbox" <?php if ($user_comment_tag) echo 'checked'?>>
			<button id="validate_change_settings" type="button" class="link-button">Validate Changes</button>
		</form>
		<form id="settings_form_password" class="settings_form">
			<label>I want to change my password</label>
			<input id="change_password" type="checkbox" value="false">
			<div class="settings_form" style="display:none;" id="password_div">
				<label>New Password</label>
				<input id="new_password" type="password" name="user_passeword" value="Dummy">
				<button id="validate_change_password" type="button" class="link-button">Validate Change</button>
		</form>
</div>

<!-- Load Utils Ajax functions -->
<script src=<?php echo '"' . $this->url_base . '/assets/js/ajax.js"' ?>></script>

<script>

	/*
	** Display message in alert tag.
	*/
	 const alert = (message) => {
		const alert = document.getElementById('alert');
		alert.setAttribute('style', 'display:flex;');
		alert.innerHTML = message;
	}

	/*
	** Ajax POST to settings/change_settings.
	*/
	const submit_change_settings = () => {

		const callback = (response) => {
			if(response === 'ok')
				alert('You have successfully changed your settings!');
			else 
				alert('Settings could not be changed. Please try again later.');
		}

		const new_user_name = document.getElementById('new_user_name').value;
		const new_user_email = document.getElementById('new_user_email').value;
		const new_user_comment_tag = document.getElementById('new_user_comment_tag').checked;

		sendRequest(null, setRequest('POST', 'settings/change_settings', { 
			new_user_name, 
			new_user_email, 
			new_user_comment_tag 
		}), callback);

	}

	/*
	** Show/Hide password form.
	*/
	const toggle_password_div = () => {
		const password_div = document.getElementById('password_div');
		if(password_div.getAttribute('style') == 'display:none;') {
			password_div.setAttribute('style', 'display:flex;');
		}
		else 
			password_div.setAttribute('style', 'display:none;');
	};

	/*
	** Ajax POST to settings/change_password.
	*/
	const submit_password_change = () => {

		const callback = (response) => {
			if(response === 'ok')
				alert('You have successfully changed your password!');
			else 
				alert('Password could not be changed. Please try again later.')
		}

		const new_password = document.getElementById('new_password').value;
		sendRequest(null, setRequest('POST', 'settings/change_password', { new_password }), callback);
	}


	const validate_change_password = document.getElementById('validate_change_password');
	const validate_change_settings = document.getElementById('validate_change_settings');
	const change_password_elem = document.getElementById('change_password');

	change_password_elem.addEventListener('change', toggle_password_div);
	validate_change_password.addEventListener('click', submit_password_change);
	validate_change_settings.addEventListener('click', submit_change_settings);

</script>