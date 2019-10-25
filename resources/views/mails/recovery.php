<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Recovery</title>
</head>
<body>

	<h4>Hello, <?php echo $user->name ?></h4>

	<p>
		Someone has requested a password reset for the following account:  <?php echo $user->email ?>
	</p>

	<p>
		If this was a mistake, just ignore this email and nothing will happen.
	</p>

	<p>
		To reset your password, visit the following address:
	</p>

	<p>
		<<a href="<?php echo $link ?>">
			<?php echo $link ?>
		</a>>
	</p>

</body>
</html>
