<?php
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
$books = $books ?? [];
?>
<h1>الكتب</h1>

<form method="get">
  <input type="hidden" name="controller" value="book">
  <input type="hidden" name="action" value="search">
  <input name="q" placeholder="ابحث بالعنوان أو المؤلف">
  <button>بحث</button>
</form>

<form method="post" action="/?controller=book&action=add">
  <input name="title" placeholder="العنوان" required>
  <input name="author" placeholder="المؤلف" required>
  <input name="copies" type="number" min="1" value="1">
  <button>إضافة</button>
</form>

<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Title</th><th>Author</th><th>Total</th><th>Available</th></tr>
  <?php foreach ($books as $b): ?>
    <tr>
      <td><?= e($b['id']) ?></td>
      <td><?= e($b['title']) ?></td>
      <td><?= e($b['author']) ?></td>
      <td><?= e($b['total_copies']) ?></td>
      <td><?= e($b['available_copies']) ?></td>
    </tr>
  <?php endforeach; ?>
</table>
