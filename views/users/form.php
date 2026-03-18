<a href="index.php?controller=user">Back</a>

<form method="POST"
action="index.php?controller=user&action=<?= isset($user) ? 'update&id='.$user['id'] : 'store' ?>">

<input type="text" name="name" placeholder="Name"
value="<?= $user['name'] ?? '' ?>">

<input type="email" name="email" placeholder="Email"
value="<?= $user['email'] ?? '' ?>">

<select name="role">
<option value="Admin">Admin</option>
<option value="Technician">Technician</option>
<option value="Guest">Guest</option>
</select>

<button type="submit">Save</button>
</form>