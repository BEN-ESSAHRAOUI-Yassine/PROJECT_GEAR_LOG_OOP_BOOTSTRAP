<!DOCTYPE html>
<html>
<head>
<title>User Management</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>User Management</h1>

<a href="index.php" class="btn-back">← Back to Dashboard</a>

<a href="index.php?action=createUser" class="btn-add">
Add User
</a>

<br><br>

<table>

<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Actions</th>
</tr>

<?php foreach($users as $u): ?>
<tr>

    <td><?= $u['id'] ?></td>

    <td><?= htmlspecialchars($u['username']) ?></td>

    <td><?= htmlspecialchars($u['email']) ?></td>

    <td><?= htmlspecialchars($u['his_role']) ?></td>

    <td class="actions">

        <a href="index.php?action=editUser&id=<?= $u['id'] ?>"
           class="btn-edit">
           Edit
        </a>

        <a href="index.php?action=deleteUser&id=<?= $u['id'] ?>"
           class="btn-delete"
           onclick="return confirm('Delete this user?')">
           Delete
        </a>

    </td>

</tr>
<?php endforeach; ?>

</table>

</body>
</html>