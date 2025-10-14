<?php require_once __DIR__ . '/config.php'; if(empty($_SESSION['user_id'])) redirect('login.php');
$uid=$_SESSION['user_id'];
if(is_post()){
  if(!verify_csrf($_POST['csrf']??'')) { http_response_code(400); exit('Invalid CSRF'); }
  $dt=$_POST['datetime']??''; $tz=$_POST['timezone']??'UTC';
  $stmt=db()->prepare('INSERT INTO reminders(user_id,medicine_id,datetime,timezone,repeat_rule) VALUES(?,?,?,?,?)');
  $stmt->execute([$uid, null, $dt, $tz, $_POST['repeat_rule']??null]);
  redirect('calendar.php');
}
$rem=db()->prepare('SELECT * FROM reminders WHERE user_id=? ORDER BY datetime ASC'); $rem->execute([$uid]); $rem=$rem->fetchAll();
$title='Calendar - MediScan'; ob_start(); ?>
<div class="grid cols-2">
  <div class="card">
    <h2>Add reminder</h2>
    <form method="post" id="reminder-form">
      <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
      <div class="form-row"><label class="label">Date & time</label><input class="input" type="datetime-local" name="datetime" required></div>
      <div class="form-row"><label class="label">Timezone</label><input class="input" name="timezone" value="<?php echo e(date_default_timezone_get()); ?>"></div>
      <div class="form-row"><label class="label">Repeat rule</label><input class="input" name="repeat_rule" placeholder="e.g., daily"></div>
      <button class="btn" type="submit">Save</button>
    </form>
  </div>
  <div class="card">
    <h2>Upcoming reminders</h2>
    <ul>
      <?php foreach($rem as $r): ?>
        <li><?php echo e($r['datetime']); ?> (<?php echo e($r['timezone']); ?>) — <?php echo e($r['repeat_rule']?:'once'); ?></li>
      <?php endforeach; if(!$rem): ?>
        <li>No reminders yet.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
