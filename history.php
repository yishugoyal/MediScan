<?php require_once __DIR__ . '/config.php'; if(empty($_SESSION['user_id'])) redirect('login.php');
$uid=$_SESSION['user_id'];
$title='History - MediScan';
$rows=db()->prepare('SELECT * FROM medicines WHERE user_id=? ORDER BY created_at DESC'); $rows->execute([$uid]); $rows=$rows->fetchAll();
ob_start(); ?>
<div class="card">
  <h2>Medicine history</h2>
  <table class="table">
    <thead><tr><th>Name</th><th>Dosage</th><th>Frequency</th><th>Added</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?php echo e($r['name']); ?></td>
          <td><?php echo e($r['dosage']); ?></td>
          <td><?php echo e($r['frequency']); ?></td>
          <td><?php echo e($r['created_at']); ?></td>
        </tr>
      <?php endforeach; if(!$rows): ?>
        <tr><td colspan="4">No history.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
