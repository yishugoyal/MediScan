<?php require_once __DIR__ . '/config.php'; if(empty($_SESSION['user_id'])) redirect('login.php');
$uid=$_SESSION['user_id'];
if(is_post()){
  if(!verify_csrf($_POST['csrf']??'')) { http_response_code(400); exit('Invalid CSRF'); }
  $name=trim($_POST['name']??''); $dosage=trim($_POST['dosage']??''); $frequency=trim($_POST['frequency']??''); $notes=trim($_POST['notes']??'');
  if($name){
    $stmt=db()->prepare('INSERT INTO medicines(user_id,name,dosage,frequency,notes) VALUES(?,?,?,?,?)');
    $stmt->execute([$uid,$name,$dosage,$frequency,$notes]);
  }
  redirect('medicines.php');
}
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $d=db()->prepare('DELETE FROM medicines WHERE id=? AND user_id=?'); $d->execute([$id,$uid]);
  redirect('medicines.php');
}
$title='Medicines - MediScan';
$list=db()->prepare('SELECT * FROM medicines WHERE user_id=? ORDER BY id DESC'); $list->execute([$uid]); $list=$list->fetchAll();
ob_start(); ?>
<div class="grid cols-2">
  <div class="card">
    <h2>Add medicine</h2>
    <form method="post" id="medicine-form">
      <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
      <div class="form-row"><label class="label">Name</label><input class="input" name="name" required></div>
      <div class="form-row"><label class="label">Dosage</label><input class="input" name="dosage" placeholder="e.g., 500 mg"></div>
      <div class="form-row"><label class="label">Frequency</label><input class="input" name="frequency" placeholder="e.g., twice daily"></div>
      <div class="form-row"><label class="label">Notes</label><textarea class="input" name="notes" rows="3"></textarea></div>
      <button class="btn" type="submit">Save</button>
    </form>
  </div>
  <div class="card">
    <h2>Your medicines</h2>
    <table class="table">
      <thead><tr><th>Name</th><th>Dosage</th><th>Frequency</th><th></th></tr></thead>
      <tbody>
      <?php foreach($list as $m): ?>
        <tr>
          <td><?php echo e($m['name']); ?></td>
          <td><?php echo e($m['dosage']); ?></td>
          <td><?php echo e($m['frequency']); ?></td>
          <td><a class="btn btn-outline" href="?delete=<?php echo (int)$m['id']; ?>" onclick="return confirm('Delete?')">Delete</a></td>
        </tr>
      <?php endforeach; if(!$list): ?>
        <tr><td colspan="4">No items yet.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
