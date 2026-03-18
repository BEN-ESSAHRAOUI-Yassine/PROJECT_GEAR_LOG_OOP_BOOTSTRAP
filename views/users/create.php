<!DOCTYPE html>
<html>
<head>
<title>Add User</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Add User</h2>

<a href="index.php?action=users" class="btn-back">← Back</a>

<form method="POST">

<input name="username" placeholder="Username" required>
<br><br>

<input name="email" placeholder="Email" required>
<br><br>

<input type="password" name="password" placeholder="Password" required>
<br><br>

<select name="role">
<option value="Admin">Admin</option>
<option value="Technician">Technician</option>
<option value="Guest">Guest</option>
</select>

<br><br>

<button>Create User</button>

</form>

</body>
</html>