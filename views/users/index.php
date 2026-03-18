<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>User Management</h2>

        <div>
            <a href="index.php" class="btn btn-secondary btn-sm">← Dashboard</a>
            <a href="index.php?action=createUser" class="btn btn-primary btn-sm">+ Add User</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach($users as $u): ?>
                <tr>

                    <td><?= $u['id'] ?></td>

                    <td><?= htmlspecialchars($u['username']) ?></td>

                    <td><?= htmlspecialchars($u['email']) ?></td>

                    <td>
                        <span class="badge bg-<?= 
                            $u['his_role']=='Admin' ? 'danger' :
                            ($u['his_role']=='Technician' ? 'warning' : 'secondary')
                        ?>">
                            <?= htmlspecialchars($u['his_role']) ?>
                        </span>
                    </td>

                    <td>

                        <a href="index.php?action=editUser&id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-warning">
                           Edit
                        </a>

                        <a href="index.php?action=deleteUser&id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this user?')">
                           Delete
                        </a>

                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>