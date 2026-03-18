<?php
function canEditAssets(){
    return $_SESSION['role']==='Admin' || $_SESSION['role']==='Technician';
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Asset</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Add Asset</h2>

<?php if (!empty($errors)): ?>
<div style="color:red">
<?php foreach ($errors as $error): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<form method="POST">

<input name="serial" placeholder="Serial Number">

<input name="name" placeholder="Device Name">

<input name="price" type="number" step="0.01" placeholder="Price">

<select name="status">
<option>Unavailable</option>
<option>Available</option>
<option>Deployed</option>
<option>Under Repair</option>
</select>

<select name="category">
<?php foreach($categories as $c): ?>
<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['name']) ?>
</option>
<?php endforeach; ?>
</select>

<button>Add</button>

</form>

<a href="index.php">Back</a>

</body>
</html>