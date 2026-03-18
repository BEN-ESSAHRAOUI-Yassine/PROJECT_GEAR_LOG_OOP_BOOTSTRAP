<?php
function canEditAssets(){
    return $_SESSION['role']==='Admin' || $_SESSION['role']==='Technician';
}
function canManageUsers(){
    return $_SESSION['role']==='Admin';
}

function sortLink($column, $label, $sort, $order, $search, $category, $page) {
    $newOrder = ($column === $sort && $order === 'ASC') ? 'DESC' : 'ASC';

    $arrow = '';
    if ($column === $sort) {
        $arrow = $order === 'ASC' ? ' ↑' : ' ↓';
    }

    $query = http_build_query([
        'search'   => $search,
        'category' => $category,
        'page'     => $page,
        'sort'     => $column,
        'order'    => $newOrder
    ]);

    return "<a class='text-decoration-none text-dark' href='?$query'>$label$arrow</a>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GearLog Dashboard</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>GearLog Dashboard</h2>

        <div>
            <span class="me-2">
                <?= htmlspecialchars($_SESSION['username']) ?>
                (<?= htmlspecialchars($_SESSION['role']) ?>)
            </span>

            <a href="index.php?action=logout" class="btn btn-danger btn-sm">Logout</a>

            <?php if(canManageUsers()): ?>
                <a href="index.php?action=users" class="btn btn-dark btn-sm">Users</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="alert alert-info p-2">
                Total Value: <strong>$<?= $totalValue ?></strong>
            </div>
        </div>
        <div class="col">
            <div class="alert alert-warning p-2">
                Filtered Value: <strong>$<?= $filteredValue ?? 0 ?></strong>
            </div>
        </div>
        <div class="col">
            <div class="alert alert-secondary p-2">
                Assets: <strong><?= $totalAssets ?>/<?= $totalAssetsInventory ?></strong>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-3">

        <?php if(canEditAssets()): ?>
            <a href="index.php?action=create" class="btn btn-primary">
                + Add Asset
            </a>
        <?php endif; ?>

        <form method="GET" class="d-flex gap-2">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search..."
                   value="<?= htmlspecialchars($search) ?>">

            <select name="category" class="form-select">
                <option value="">All</option>

                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= ($category==$c['id'])?'selected':'' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-success">Filter</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">

            <thead class="table-primary">
            <tr>
                <th><?= sortLink('device_name','Device',$sort,$order,$search,$category,$page) ?></th>
                <th><?= sortLink('serial_number','Serial',$sort,$order,$search,$category,$page) ?></th>
                <th><?= sortLink('price','Price',$sort,$order,$search,$category,$page) ?></th>
                <th><?= sortLink('status','Status',$sort,$order,$search,$category,$page) ?></th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($assets as $a): ?>
                <tr>

                    <td><?= htmlspecialchars($a['device_name']) ?></td>

                    <td><?= htmlspecialchars($a['serial_number']) ?></td>

                    <td>$<?= $a['price'] ?></td>

                    <td>
                        <?php
                        $statusColor = match(strtolower($a['status'])) {
                            'deployed'      => 'primary',   // blue
                            'available'     => 'success',   // green
                            'under repair'  => 'warning',   // orange
                            'unavailable'   => 'danger',    // red
                            default         => 'secondary'
                        };
                        ?>

                        <span class="badge bg-<?= $statusColor ?>">
                            <?= htmlspecialchars($a['status']) ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars($a['category_name']) ?></td>

                    <td>

                        <?php if(canEditAssets()): ?>

                            <a href="index.php?action=edit&id=<?= $a['id'] ?>"
                               class="btn btn-sm btn-warning">
                               Edit
                            </a>

                            <a href="index.php?action=delete&id=<?= $a['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this asset?')">
                               Delete
                            </a>

                        <?php else: ?>
                            <span class="text-muted">Read Only</span>
                        <?php endif; ?>

                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <!-- ✅ Pagination -->
    <nav>
        <ul class="pagination">

        <?php for ($i=1;$i<=$totalPages;$i++): ?>
            <li class="page-item <?= ($i==$page)?'active':'' ?>">
                <a class="page-link" href="?<?= http_build_query([
                    'search'=>$search,
                    'category'=>$category,
                    'sort'=>$sort,
                    'order'=>$order,
                    'page'=>$i
                ]) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        </ul>
    </nav>

</div>

</body>
</html>