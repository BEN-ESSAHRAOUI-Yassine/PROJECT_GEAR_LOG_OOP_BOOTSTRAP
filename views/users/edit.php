<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h2>Edit User</h2>

<a href="index.php?action=users" class="btn btn-secondary mb-3">← Back</a>

<form method="POST" action="index.php?action=updateUser&id=<?= $user['id'] ?>">

    <input name="username" class="form-control mb-2"
           value="<?= htmlspecialchars($user['username']) ?>" required>

    <input name="email" type="email" class="form-control mb-2"
           value="<?= htmlspecialchars($user['email']) ?>" required>

    <select name="role" class="form-select mb-3">
        <option value="Admin" <?= $user['his_role']=='Admin'?'selected':'' ?>>Admin</option>
        <option value="Technician" <?= $user['his_role']=='Technician'?'selected':'' ?>>Technician</option>
        <option value="Viewer" <?= $user['his_role']=='Viewer'?'selected':'' ?>>Viewer</option>
    </select>

    <button class="btn btn-primary">Update</button>

</form>

</div>

</body>
</html>