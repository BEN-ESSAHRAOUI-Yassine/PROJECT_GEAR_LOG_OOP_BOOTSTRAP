<!DOCTYPE html>
<html>
<head>
    <title>Add Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h2>Add New Asset</h2>

<a href="index.php" class="btn btn-secondary mb-3">← Back</a>

<form method="POST" action="index.php?action=store">

    <div class="mb-3">
        <label class="form-label">Device Name</label>
        <input name="device_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Serial Number</label>
        <input name="serial_number" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="Available">Available</option>
            <option value="Deployed">Deployed</option>
            <option value="Under Repair">Under Repair</option>
            <option value="Unavailable">Unavailable</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <?php foreach($categories as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-success">Create</button>

</form>

</div>

</body>
</html>