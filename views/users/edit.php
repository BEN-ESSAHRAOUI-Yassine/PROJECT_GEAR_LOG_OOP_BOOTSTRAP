<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Edit User</h2>

<a href="index.php?action=users" class="btn-back">← Back</a>

<form method="POST">

<p>Username: <?= htmlspecialchars($user['username']) ?></p>

<input name="email" value="<?= htmlspecialchars($user['email']) ?>">
<br><br>

<select name="role">
<option value="Admin" <?= $user['his_role']=='Admin'?'selected':'' ?>>Admin</option>
<option value="Technician" <?= $user['his_role']=='Technician'?'selected':'' ?>>Technician</option>
<option value="Guest" <?= $user['his_role']=='Guest'?'selected':'' ?>>Guest</option>
</select>

<br><br>

<button>Update User</button>

</form>

</body>
</html>