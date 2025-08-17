<?php
$title = htmlspecialchars($title ?? 'Library', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ar">
<head><meta charset="utf-8"><title><?= $title ?></title></head>
<body>
    <nav>
      <a href="/?controller=book&action=index">Books</a> |
      <a href="/?controller=user&action=index">Users</a> |
      <a href="/?controller=borrow&action=index">Borrow</a>
    </nav>
    <hr>
    <?php require __DIR__ . '/' . $path . '.php'; ?>
</body>
</html>
