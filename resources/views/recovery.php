<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Recovery - FL</title>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text],
input[type=password],
input[type=email]
{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>

<link href="/public/css/app.css" rel="stylesheet" type="text/css">
</head>
<body>

<h2 class="text-center"></h2>

<div class="container">

  <?php
    if (isset($messages)) {
      foreach ($messages as $key => $message) {
        foreach($message as $msg){ ?>

          <div class="alert alert-<?php echo $key?>">
            <?php echo $msg ?>
          </div>

        <?php }
      }
    }
  ?>

    <form action="/login/recoveryPost" method="POST">
  <div class="imgcontainer">
    <img src="/public/logo.png" alt="Logo" class="avatar" style="width: 200px; height: 50px">
  </div>

  <div class="container <?php echo !isset($noForm) ? '' : 'hidden' ?>">

    <?php if (!isset($noForm)): ?>
      <label for="email"><b>Email</b></label>
      <input type="email" name="email" required readonly="readonly" value="<?php echo $user->email ?>">
    <?php endif ?>

    <label for="password"><b>New Password</b></label>
    <input type="password" placeholder="" name="password" required>

    <label for="confirm_password"><b>Retype Password</b></label>
    <input type="password" placeholder="" name="confirm_password" required>

    <button type="submit">Change</button>
  </div>
</form>
</div>

</body>
</html>
