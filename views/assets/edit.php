<!DOCTYPE html>
<html>
<head>
    <title>Edit Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h2>Edit Asset</h2>

<a href="index.php" class="btn btn-secondary mb-3">← Back</a>

<form method="POST" action="index.php?action=update&id=<?= $asset['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Device Name</label>
        <input name="device_name" class="form-control"
               value="<?= htmlspecialchars($asset['device_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Serial Number</label>
        <input name="serial_number" class="form-control"
               value="<?= htmlspecialchars($asset['serial_number']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control"
               value="<?= $asset['price'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">

            <option value="Available" <?= $asset['status']=='Available'?'selected':'' ?>>Available</option>

            <option value="Deployed" <?= $asset['status']=='Deployed'?'selected':'' ?>>Deployed</option>

            <option value="Under Repair" <?= $asset['status']=='Under Repair'?'selected':'' ?>>Under Repair</option>

            <option value="Unavailable" <?= $asset['status']=='Unavailable'?'selected':'' ?>>Unavailable</option>

        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>"
                    <?= ($asset['category_id']==$c['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>

</form>

</div>

</body>
</html>