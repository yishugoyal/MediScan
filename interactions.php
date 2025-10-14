<?php require_once __DIR__ . '/config.php'; if(empty($_SESSION['user_id'])) redirect('login.php');
$uid=$_SESSION['user_id'];
$title='Interactions - MediScan';
$resultHtml='';
if(is_post()){
  if(!verify_csrf($_POST['csrf']??'')) { http_response_code(400); exit('Invalid CSRF'); }
  $names=trim($_POST['names']??'');
  $arr=array_values(array_filter(array_map('trim', explode(',', $names))));
  $severity='mild'; $details=['message'=>'Stub result: integrate with real API for authoritative checks.'];
  $hash=hash('sha256', strtolower(join(',', $arr)));
  $stmt=db()->prepare('INSERT INTO interactions(user_id,combo_hash,severity,details) VALUES(?,?,?,?)');
  $stmt->execute([$uid,$hash,$severity,json_encode($details)]);
  ob_start(); ?>
  <div class="card">
    <h3>Results</h3>
    <p>Combination: <?php echo e(join(', ', $arr)); ?></p>
    <span class="badge mild">Mild</span>
    <p style="margin-top:8px;">Note: Replace with real interaction service (e.g., openFDA + rules or a commercial API).</p>
  </div>
  <?php $resultHtml=ob_get_clean();
}
ob_start(); ?>
<div class="card">
  <h2>Check interactions</h2>
  <form method="post" id="interactions-form">
    <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
    <div class="form-row"><label class="label">Medicine names (comma separated)</label>
      <input class="input" name="names" placeholder="e.g., ibuprofen, paracetamol" required>
    </div>
    <button class="btn" type="submit">Check</button>
  </form>
</div>
<div id="results"><?php echo $resultHtml; ?></div>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
