<?php
function canEditAssets(){
    return $_SESSION['role']==='Admin' || $_SESSION['role']==='Technician';
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Asset</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Edit Asset</h2>

<?php if (!empty($errors)): ?>
<div style="color:red">
<?php foreach ($errors as $error): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<form method="POST">

<label>Serial Number</label><br>
<input name="serial"
value="<?= htmlspecialchars($_POST['serial'] ?? $asset['serial_number']) ?>">

<br><br>

<label>Device Name</label><br>
<input name="name"
value="<?= htmlspecialchars($_POST['name'] ?? $asset['device_name']) ?>">

<br><br>

<label>Price</label><br>
<input type="number" step="0.01"
name="price"
value="<?= htmlspecialchars($_POST['price'] ?? $asset['price']) ?>">

<br><br>

<label>Status</label><br>
<select name="status">
<option value="Available" <?= ($asset['status']=="Available")?"selected":"" ?>>Available</option>
<option value="Deployed" <?= ($asset['status']=="Deployed")?"selected":"" ?>>Deployed</option>
<option value="Under Repair" <?= ($asset['status']=="Under Repair")?"selected":"" ?>>Under Repair</option>
<option value="Unavailable" <?= ($asset['status']=="Unavailable")?"selected":"" ?>>Unavailable</option>
</select>

<br><br>

<label>Category</label><br>
<select name="category">
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>"
<?= ($asset['category_id']==$c['id'])?"selected":"" ?>>
<?= htmlspecialchars($c['name']) ?>
</option>
<?php endforeach; ?>
</select>

<br><br>

<button type="submit">Update Asset</button>

</form>

<br>
<a href="index.php">Back</a>

</body>
</html>