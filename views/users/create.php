<!DOCTYPE html>
<html>
<head>
<title>Add User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<h2>Add User</h2>

<a href="index.php?action=users" class="btn btn-secondary mb-3">← Back</a>

<form method="POST" action="index.php?action=storeUser">

    <input name="username" class="form-control mb-2" placeholder="Username" required>

    <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>

    <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>

    <select name="role" class="form-select mb-3">
        <option value="Admin">Admin</option>
        <option value="Technician">Technician</option>
        <option value="Viewer">Viewer</option>
    </select>

    <button class="btn btn-success">Create</button>

</form>

</div>

</body>
</html>