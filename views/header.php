<header>
	<nav>
		<ul>
			<li>
				<a href="/">
					<b>Home</b>
				</a>
			</li>
			<li>
				<a href="/gallery">
					<b>Gallery</b>
				</a>
			</li>
<?php
	if (!isset($_SESSION['user_login']) || !isset($_SESSION['user_id']) ||
		!$_SESSION['user_login'] || !$_SESSION['user_id']) {
?>	
			<li>
				<a href="/login">
					<b>Login</b>
				</a>
			</li>
<?php } else { ?>
			<li>
				<a href="/settings">
					<b>Settings</b>
				</a>
			</li>
			<li>
				<a href="/login/out">
					<b>Logout</b>
				</a>
			</li>
<?php } ?>
		</ul>
	</nav>
</header>



