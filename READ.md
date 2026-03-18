I have a PHP project written in procedural style (PHP + MySQL + HTML + CSS).

I will paste ALL my project files below.

Your task is to **FULLY refactor the project into an Object-Oriented structure** while strictly respecting these rules:

---

### 🔒 RULES (VERY IMPORTANT)

1. **DO NOT add new features**
2. **DO NOT remove any existing functionality**
3. **KEEP the exact same behavior and logic**
4. **PRESERVE all features**, including but not limited to:

   * search
   * filters (e.g., category)
   * sorting
   * pagination
   * authentication / sessions / role
   * joins and SQL logic
   * SUM prices and COUNT assets
   
5. **DO NOT simplify logic unless necessary**
6. **DO NOT guess missing parts** — use ONLY what I provide

---

### 🎯 GOAL

Refactor the project into clean OOP using this structure:

/app
/Core
/Models
/Controllers
/views
/public
/assets

---

### 🧠 REQUIREMENTS

* Use a **Database class (PDO Singleton)**
* Convert each entity into a **Model**
* Move logic into **Controllers**
* Keep UI inside **Views**
* Use a **single entry point: public/index.php (router)**

---

### 🎨 OUTPUT REQUIRED (2 VERSIONS)

#### ✅ Version 1 — CSS

* Use my existing CSS (or keep styling as-is)
* Do not introduce new frameworks

#### ✅ Version 2 — Bootstrap

* Same exact features
* Only upgrade UI using Bootstrap

---

### 📦 OUTPUT FORMAT

1. Show full folder structure
2. Provide ALL files completely rewritten (no placeholders, no missing parts)
3. Clearly separate:

   * Models
   * Controllers
   * Views
   * Router (index.php)
4. Make sure everything is runnable

---

### ⚠️ IMPORTANT

* Do NOT leave empty files
* Do NOT skip any file
* Do NOT summarize — give FULL code
* Keep everything consistent with my original project

---

### 📥 FILES

(PASTE ALL YOUR FILES BELOW, EACH WITH ITS NAME)

Example:

--- db.php ---
<?php
$host = "localhost";
$dbname = "GearLog_db";
$user = "root";
$pasord = "";
try {
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pasord);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
die("Database connection failed: " . $e->getMessage());
}

--- index.php ---
<?php
require 'auth.php';
require 'db.php';
require 'role.php';
$search   = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort'] ?? 'device_name';
$order    = $_GET['order'] ?? 'ASC';
$page     = $_GET['page'] ?? 1;
$allowedSort = ['device_name','price','status','serial_number'];
if (!in_array($sort,$allowedSort)) $sort = 'device_name';
$order = ($order === 'DESC') ? 'DESC' : 'ASC';
$limit  = 10;
$page   = max(1,(int)$page);
$offset = ($page - 1) * $limit;
$sql = "SELECT assets.*, categories.name AS category_name
        FROM assets
        INNER JOIN categories ON assets.category_id = categories.id
        WHERE 1";
$params = [];
if ($search !== '') {
    $sql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
    $params['search'] = "%$search%";
}
if ($category !== '') {
    $sql .= " AND category_id = :category";
    $params['category'] = $category;
}
$sql .= " ORDER BY $sort $order
          LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
$countSql = "SELECT COUNT(*) FROM assets WHERE 1";
$countParams = [];
if ($search !== '') {
    $countSql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
    $countParams['search'] = "%$search%";
}
if ($category !== '') {
    $countSql .= " AND category_id = :category";
    $countParams['category'] = $category;
}
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$totalAssets = $countStmt->fetchColumn(); // filtered asset count
$totalPages  = ceil($totalAssets / $limit);
$valueStmt = $pdo->query("SELECT SUM(price) FROM assets");
$totalValue = $valueStmt->fetchColumn();
$filteredValueSql = "SELECT SUM(price) FROM assets WHERE 1";
$filteredParams = [];
if ($search !== '') {
    $filteredValueSql .= " AND (device_name LIKE :search OR serial_number LIKE :search)";
    $filteredParams['search'] = "%$search%";
}
if ($category !== '') {
    $filteredValueSql .= " AND category_id = :category";
    $filteredParams['category'] = $category;
}
$filteredStmt = $pdo->prepare($filteredValueSql);
$filteredStmt->execute($filteredParams);
$filteredValue = $filteredStmt->fetchColumn();
$totalAssetsStmt = $pdo->query("SELECT COUNT(*) FROM assets");
$totalAssetsInventory = $totalAssetsStmt->fetchColumn();
$categories = $pdo->query("SELECT * FROM categories")
                  ->fetchAll(PDO::FETCH_ASSOC);
$queryBase = http_build_query([
    'search' => $search,
    'category' => $category,
    'page' => $page
]);
function sortLink($column, $label, $sort, $order, $queryBase) {
    $newOrder = ($column === $sort && $order === 'ASC') ? 'DESC' : 'ASC';
    $arrow = '';
    if ($column === $sort) {
        $arrow = $order === 'ASC' ? ' ↑' : ' ↓';
    }
    $url = "?$queryBase&sort=$column&order=$newOrder";
    return "<a href='$url'>$label$arrow</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GearLog Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<h1>GearLog - Asset Dashboard</h1>
<p>
Welcome <?= htmlspecialchars($_SESSION['username']) ?>
(<?= htmlspecialchars($_SESSION['role']) ?>) |
<a href="logout.php" class="logout-btn">Logout</a> 
<?php if(canManageUsers()): ?>
<a href="admin/users.php" class="btn-manage">Manage Users</a>
<?php endif; ?>
</p>
<h3>Total Inventory Value: $<?= htmlspecialchars($totalValue) ?></h3>
<h3>Filtered Inventory Value: $<?= htmlspecialchars($filteredValue ?? 0) ?></h3>
<h3>Shown Assets: <?= htmlspecialchars($totalAssets) ?> / <?= htmlspecialchars($totalAssetsInventory) ?></h3>
<div class="toolbar">
    <?php if(canEditAssets()): ?>
        <a href="add_asset.php" class="btn-add">Add New Asset</a>
    <?php endif; ?>
    <form method="GET" class="filter-form">
        <input name="search" placeholder="Search asset" value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($category==$c['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
</div>
<table>
<tr>
    <th><?= sortLink('device_name','Device',$sort,$order,$queryBase) ?></th>
    <th><?= sortLink('serial_number','Serial',$sort,$order,$queryBase) ?></th>
    <th><?= sortLink('price','Price',$sort,$order,$queryBase) ?></th>
    <th><?= sortLink('status','Status',$sort,$order,$queryBase) ?></th>
    <th>Category</th>
    <th>Actions</th>
</tr>
<?php foreach ($assets as $a): ?>
<tr>
    <td><?= htmlspecialchars($a['device_name']) ?></td>
    <td><?= htmlspecialchars($a['serial_number']) ?></td>
    <td>$<?= htmlspecialchars($a['price']) ?></td>
    <?php $statusClass = strtolower(str_replace(' ','-',$a['status'])); ?>
    <td><span class="status-badge status-<?= $statusClass ?>"><?= htmlspecialchars($a['status']) ?></span></td>
    <td><?= htmlspecialchars($a['category_name']) ?></td>
    <td class="actions">
        <?php if(canEditAssets()): ?>
        <a href="update_asset.php?id=<?= $a['id'] ?>" class="btn-edit">Edit</a>
        <a href="delete_asset.php?id=<?= $a['id'] ?>" 
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
<?php for ($i=1;$i<=$totalPages;$i++): ?>
    <a href="?search=<?= $search ?>&category=<?= $category ?>&sort=<?= $sort ?>&order=<?= $order ?>&page=<?= $i ?>"><?= $i ?></a>
<?php endfor; ?>
</div>
</body>
</html>

--- add_asset.php ---
<?php
require 'db.php';
require 'auth.php';
require 'role.php';
if(!canEditAssets()){
die("Access denied");
}
$categories = $pdo->query("SELECT * FROM categories")
->fetchAll(PDO::FETCH_ASSOC);
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $serial = trim($_POST['serial'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $status = $_POST['status'] ?? '';
    $category = $_POST['category'] ?? '';
    $errors = [];
    if ($serial === '') {
        $errors[] = "Serial number is required";
    }
    if ($name === '') {
        $errors[] = "Device name is required";
    }
    if ($price === '' || !is_numeric($price)) {
        $errors[] = "Price must be a valid number";
    }
    if ($status === '') {
        $errors[] = "Status is required";
    }
    if ($category === '') {
        $errors[] = "Category is required";
    }
    if (empty($errors)) {
        $check = $pdo->prepare("SELECT id FROM assets WHERE serial_number = ?");
        $check->execute([$serial]);
        if ($check->rowCount() > 0) {
            $errors[] = "Serial number already exists.";
        }
    }
if (empty($errors)) {
$stmt = $pdo->prepare(
"INSERT INTO assets(serial_number,device_name,price,status,category_id)
VALUES(?,?,?,?,?)"
);
$stmt->execute([
$serial,
$name,
$price,
$status,
$category
]);
header("Location: index.php");
exit();
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Asset</title>
</head>
<body>
<h2>Add Asset</h2>
<form method="POST">
<input name="serial" placeholder="Serial Number">
<input name="name" placeholder="Device Name">
<input name="price" type="number" step="0.01" placeholder="Price">
<select name="status">
<option>Unavailable</option>
<option>Available</option>
<option>Deployed</option>
<option>Under Repair</option>
</select>
<select name="category">
<?php foreach($categories as $c): ?>
<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['name']) ?>
</option>
<?php endforeach; ?>
</select>
<button>Add</button>
</form>
<?php if(!empty($errors)): ?>
<div style="color:red">
<?php foreach($errors as $error): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>
</div>
<?php endif; ?>
<a href="index.php">Back</a>
</body>
</html>

--- auth.php ---
<?php
session_start();
if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit;
}

--- delete_asset.php ---
<?php
require 'auth.php';
require 'role.php';
if(!canEditAssets()){
die("Access denied");
}
if(isset($_GET['id'])){
$stmt = $pdo->prepare("DELETE FROM assets WHERE id=?");
$stmt->execute([$_GET['id']]);
}
header("Location: index.php");
exit();

--- login.php ---
<?php
session_start();
require 'db.php';
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$sql = "SELECT * FROM User_db WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username'=>$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if($user && password_verify($password,$user['password'])){
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['his_role'];
header("Location: index.php");
exit;
}else{
$error = "Invalid username or password";
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>GearLog Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
<div class="login-container">
    <form method="POST" class="login-form">
        <div class="login-header">
            <h2>U S E R L O G I N</h2>
        </div>
        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <input name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
    </form>
</div>
</body>
</html>

--- logout.php ---
<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;

--- role.php ---
<?php
function isAdmin(){
return $_SESSION['role'] === 'Admin';
}
function isTechnician(){
return $_SESSION['role'] === 'Technician';
}
function isGuest(){
return $_SESSION['role'] === 'Guest';
}
function canEditAssets(){
return isAdmin() || isTechnician();
}
function canManageUsers(){
return isAdmin();
}

--- update_asset.php ---
<?php
require 'db.php';
require 'auth.php';
require 'role.php';
if(!canEditAssets()){
die("Access denied");
}
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}
$categories = $pdo->query("SELECT * FROM categories")
->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT * FROM assets WHERE id = ?");
$stmt->execute([$id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$asset) {
    die("Asset not found");
}
$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serial = trim($_POST['serial'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $status = $_POST['status'] ?? '';
    $category = $_POST['category'] ?? '';
    if ($serial === '') {
        $errors[] = "Serial number is required";
    }
    if ($name === '') {
        $errors[] = "Device name is required";
    }
    if ($price === '' || !is_numeric($price)) {
        $errors[] = "Price must be a valid number";
    }
    if ($status === '') {
        $errors[] = "Status is required";
    }
    if ($category === '') {
        $errors[] = "Category is required";
    }
    if (empty($errors)) {
        $check = $pdo->prepare(
            "SELECT id FROM assets 
             WHERE serial_number = ? 
             AND id != ?"
        );
        $check->execute([$serial, $id]);
        if ($check->fetch()) {
            $errors[] = "Serial number already exists.";
        }
    }
    if (empty($errors)) {
        $update = $pdo->prepare(
            "UPDATE assets
             SET serial_number = ?, 
                 device_name = ?, 
                 price = ?, 
                 status = ?, 
                 category_id = ?
             WHERE id = ?"
        );
        $update->execute([
            $serial,
            $name,
            $price,
            $status,
            $category,
            $id
        ]);
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Asset</title>
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
<input type="number"
step="0.01"
name="price"
value="<?= htmlspecialchars($_POST['price'] ?? $asset['price']) ?>">
<br><br>
<label>Status</label><br>
<select name="status">
<option value="Available"
<?= ($asset['status']=="Available")?"selected":"" ?>>
Available
</option>
<option value="Deployed"
<?= ($asset['status']=="Deployed")?"selected":"" ?>>
Deployed
</option>
<option value="Under Repair"
<?= ($asset['status']=="Under Repair")?"selected":"" ?>>
Under Repair
</option>
<option value="Unavailable"
<?= ($asset['status']=="Unavailable")?"selected":"" ?>>
Unavailable
</option>
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
<a href="index.php">Back to Dashboard</a>
</body>
</html>

--- admin/add_user.php ---
<?php
require '../auth.php';
require '../db.php';
require '../role.php';
if(!canManageUsers()){
die("Access denied");
}
if($_SERVER['REQUEST_METHOD']=='POST'){
$username=$_POST['username'];
$email=$_POST['email'];
$password1=password_hash($_POST['password'],PASSWORD_DEFAULT);
$role=$_POST['role'];
$sql="INSERT INTO User_db(username,email,password,his_role)
VALUES(:u,:e,:p,:r)";
$stmt=$pdo->prepare($sql);
$stmt->execute([
'u'=>$username,
'e'=>$email,
'p'=>$password1,
'r'=>$role
]);
header("Location: users.php");
exit;
}
?>
<h2>Add User</h2>
<a href="users.php" class="btn-back">← Back to Users</a>
<form method="POST">
<input name="username" placeholder="Username" required>
<br><br>
<input name="email" placeholder="Email" required>
<br><br>
<input type="password" name="password" placeholder="Password" required>
<br><br>
<select name="role">
<option value="Admin">Admin</option>
<option value="Technician">Technician</option>
<option value="Guest">Guest</option>
</select>
<br><br>
<button>Create User</button>
</form>

--- admin/delete_user.php ---
<?php
require '../auth.php';
require '../db.php';
require '../role.php';
if(!canManageUsers()){
die("Access denied");
}
$id=$_GET['id'];
$stmt=$pdo->prepare("DELETE FROM User_db WHERE id=:id");
$stmt->execute(['id'=>$id]);
header("Location: users.php");
exit;

--- admin/edit_user.php ---
<?php
require '../auth.php';
require '../db.php';
require '../role.php';
if(!canManageUsers()){
die("Access denied");
}
$id=$_GET['id'];
$stmt=$pdo->prepare("SELECT * FROM User_db WHERE id=:id");
$stmt->execute(['id'=>$id]);
$user=$stmt->fetch(PDO::FETCH_ASSOC);
if($_SERVER['REQUEST_METHOD']=='POST'){
$email=$_POST['email'];
$role=$_POST['role'];
$sql="UPDATE User_db
SET email=:email, his_role=:role
WHERE id=:id";
$stmt=$pdo->prepare($sql);
$stmt->execute([
'email'=>$email,
'role'=>$role,
'id'=>$id
]);
header("Location: users.php");
exit;
}
?>
<h2>Edit User</h2>
<a href="users.php" class="btn-back">← Back to Users</a>
<form method="POST">
<p>Username: <?= htmlspecialchars($user['username']) ?></p>
<input name="email" value="<?= htmlspecialchars($user['email']) ?>">
<br><br>
<select name="role">
<option value="Admin" <?= $user['his_role']=='Admin'?'selected':'' ?>>Admin</option>
<option value="Technician" <?= $user['his_role']=='Technician'?'selected':'' ?>>Technician</option>
<option value="Guest" <?= $user['his_role']=='Guest'?'selected':'' ?>>Guest</option>
</select>
<br><br>
<button>Update User</button>
</form>

--- admin/users.php ---
<?php
require '../auth.php';
require '../db.php';
require '../role.php';
if(!canManageUsers()){
die("Access denied");
}
$users = $pdo->query("SELECT * FROM User_db")
->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>User Management</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<h1>User Management</h1>
<a href="../index.php" class="btn-back">← Back to Dashboard</a>
<a href="add_user.php" class="btn-add">Add User</a>
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
<a href="edit_user.php?id=<?= $u['id'] ?>" class="btn-edit">
Edit
</a>
<a href="delete_user.php?id=<?= $u['id'] ?>"
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

--- assets/css/style.css ---

body {
    font-family: Arial, sans-serif;
    padding: 20px;
    background: #f9f9f9;
}

h1 {
    margin-bottom: 10px;
}

h3 {
    margin: 5px 0;
}


.toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.filter-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-form input,
.filter-form select,
.filter-form button {
    padding: 6px 10px;
    font-size: 14px;
}

.btn-add {
    background: #2ecc71;
    color: white;
    padding: 10px 16px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}
.btn-add:hover {
    background: #27ae60;
}


table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: white;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background: #f4f4f4;
    cursor: pointer;
}

table tr:hover {
    background: #f1f1f1;
}


.actions {
    display: flex;
    gap: 8px;
}

.btn-edit {
    background: #3498db;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}
.btn-edit:hover { background: #2980b9; }

.btn-delete {
    background: #e74c3c;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}
.btn-delete:hover { background: #c0392b; }


.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    font-size: 13px;
    display: inline-block;
}

.status-available { background: #2ecc71; }      /* green */
.status-unavailable { background: #e74c3c; }    /* red */
.status-under-repair { background: #f39c12; }   /* orange */
.status-deployed { background: #3498db; }         /* blue */


.pagination a {
    margin: 0 5px;
    text-decoration: none;
    color: #3498db;
    font-weight: bold;
}
.pagination a:hover {
    text-decoration: underline;
}


.logout-btn {
  display: inline-block;
  padding: 10px 20px;
  background-color: #e74c3c;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
}

.logout-btn:hover {
  background-color: #c0392b;
}



.btn-manage{
background:#9b59b6;
color:white;
padding:10px 16px;
text-decoration:none;
border-radius:5px;
font-weight:bold;
}

.btn-manage:hover{
background:#8e44ad;
}


.btn-back{
background:#7f8c8d;
color:white;
padding:8px 14px;
text-decoration:none;
border-radius:4px;
margin-right:10px;
}

.btn-back:hover{
background:#636e72;
}


.login-page {
    background: #e6e6e6;
}


.login-page .login-container {
    max-width: 350px;
    margin: 80px auto;
}


.login-page .login-form {
    background: #0b1a3a;
    padding: 40px 25px 25px;
    border-radius: 12px;
    position: relative;
    border: 2px solid #00cfff;
    box-shadow: 0 0 10px rgba(0, 207, 255, 0.4);
}


.login-page .login-form input {
    width: 100%;
    padding: 10px;
    margin: 12px 0;
    border: 2px solid #ff2e88;
    border-radius: 5px;
    background: #f5f6fa;
    box-sizing: border-box;
}

.login-page .login-header {
    background: #0d224d;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    text-shadow: 5px 5px 10px #000000;

   
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.3);
}


.login-page .login-header h2 {
    color: #ff2e88;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}


.login-page .login-form button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    background: #1da1d2;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

.login-page .login-form button:hover {
    background: #0d8ecf;
}


.login-page .error {
    background: #df7284;
    color: rgb(90, 5, 16);
    font-weight: bold;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 5px;
    text-align: center;
}

--- database/schema.sql ---
CREATE DATABASE IF NOT EXISTS gearlog_db;
USE gearlog_db;

CREATE TABLE IF NOT EXISTS categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS User_db (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL,
password VARCHAR(200) NOT NULL,
his_role ENUM('Admin','Technician','Guest') DEFAULT 'Guest'
);

CREATE TABLE IF NOT EXISTS assets (
id INT AUTO_INCREMENT PRIMARY KEY,
serial_number VARCHAR(100) UNIQUE,
device_name VARCHAR(100),
price DECIMAL(10,2),
status ENUM('Unavailable','Available','Deployed','Under Repair') DEFAULT 'Available',
category_id INT,
FOREIGN KEY (category_id) REFERENCES categories(id)
);

--- add_user.php ---
[paste code]

--- add_user.php ---
[paste code]

--- add_user.php ---
[paste code]

