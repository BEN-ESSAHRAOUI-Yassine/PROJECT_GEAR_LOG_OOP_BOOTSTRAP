<a href="index.php">Back</a>

<form method="POST"
action="index.php?controller=asset&action=<?= isset($asset) ? 'update&id='.$asset['id'] : 'store' ?>">

<input type="text" name="device_name"
value="<?= $asset['device_name'] ?? '' ?>">

<input type="text" name="serial_number"
value="<?= $asset['serial_number'] ?? '' ?>">

<button type="submit">Save</button>
</form>