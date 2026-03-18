<!DOCTYPE html>
<html>
<head>
<title>GearLog Login</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">

<div class="login-container">
    <form method="POST" class="login-form">

        <div class="login-header">
            <h2>U S E R L O G I N</h2>
        </div>

        <?php if(!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <input name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">LOGIN</button>

    </form>
</div>

</body>
</html>