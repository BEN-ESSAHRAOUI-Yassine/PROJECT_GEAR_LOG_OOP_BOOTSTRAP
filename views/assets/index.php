<?php
function canEditAssets(){
    return $_SESSION['role']==='Admin' || $_SESSION['role']==='Technician';
}
function canManageUsers(){
    return $_SESSION['role']==='Admin';
}

// 🔁 SORT LINK FUNCTION (same as original)
function sortLink($column, $label, $sort, $order, $search, $category, $page) {

    $newOrder = ($column === $sort && $order === 'ASC') ? 'DESC' : 'ASC';
    $arrow = '';
    if ($column === $sort) {
        $arrow = ($order === 'ASC') ? ' ↑' : ' ↓';
    }
    $query = http_build_query([
        'search'   => $search,
        'category' => $category,
        'page'     => $page,
        'sort'     => $column,
        'order'    => $newOrder
    ]);

    return "<a href='?$query'>$label$arrow</a>";
}

// keep filters in pagination + sorting
$queryBase = http_build_query([
    'search' => $search,
    'category' => $category,
    'page' => $page
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>GearLog Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>GearLog - Asset Dashboard</h1>

<p>
Welcome <?= htmlspecialchars($_SESSION['username']) ?>
(<?= htmlspecialchars($_SESSION['role']) ?>) |

<a href="index.php?action=logout" class="logout-btn">Logout</a>

<?php if(canManageUsers()): ?>
<a href="index.php?action=users" class="btn-manage">Manage Users</a>
<?php endif; ?>
</p>

<h3>Total Inventory Value: $<?= htmlspecialchars($totalValue) ?></h3>
<h3>Filtered Inventory Value: $<?= htmlspecialchars($filteredValue ?? 0) ?></h3>
<h3>Shown Assets: <?= htmlspecialchars($totalAssets) ?> / <?= htmlspecialchars($totalAssetsInventory) ?></h3>

<div class="toolbar">

    <?php if(canEditAssets()): ?>
        <a href="index.php?action=create" class="btn-add">Add New Asset</a>
    <?php endif; ?>

    <form method="GET" class="filter-form">

        <input name="search"
               placeholder="Search asset"
               value="<?= htmlspecialchars($search) ?>">

        <select name="category">
            <option value="">All Categories</option>

            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"
                    <?= ($category==$c['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>
</div>

<table>

<tr>
    <th><?= sortLink('device_name','Device',$sort,$order,$search,$category,$page) ?></th>
    <th><?= sortLink('serial_number','Serial',$sort,$order,$search,$category,$page) ?></th>
    <th><?= sortLink('price','Price',$sort,$order,$search,$category,$page) ?></th>
    <th><?= sortLink('status','Status',$sort,$order,$search,$category,$page) ?></th>
    <th>Category</th>
    <th>Actions</th>
</tr>

<?php foreach ($assets as $a): ?>
<tr>

    <td><?= htmlspecialchars($a['device_name']) ?></td>

    <td><?= htmlspecialchars($a['serial_number']) ?></td>

    <td>$<?= htmlspecialchars($a['price']) ?></td>

    <?php $statusClass = strtolower(str_replace(' ','-',$a['status'])); ?>
    <td>
        <span class="status-badge status-<?= $statusClass ?>">
            <?= htmlspecialchars($a['status']) ?>
        </span>
    </td>

    <td><?= htmlspecialchars($a['category_name']) ?></td>

    <td class="actions">

        <?php if(canEditAssets()): ?>

            <a href="index.php?action=edit&id=<?= $a['id'] ?>"
               class="btn-edit">
               Edit
            </a>

            <a href="index.php?action=delete&id=<?= $a['id'] ?>"
               class="btn-delete"
               onclick="return confirm('Delete this asset?')">
               Delete
            </a>

        <?php else: ?>
            <span style="color:gray">Read Only</span>
        <?php endif; ?>

    </td>

</tr>
<?php endforeach; ?>

</table>

<div class="pagination">

<div class="pagination">

<?php for ($i=1;$i<=$totalPages;$i++): ?>

    <a href="?<?= http_build_query([
        'search'   => $search,
        'category' => $category,
        'sort'     => $sort,
        'order'    => $order,
        'page'     => $i
    ]) ?>">
        <?= $i ?>
    </a>

<?php endfor; ?>

</div>

</body>
</html>