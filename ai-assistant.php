<?php require_once __DIR__ . '/config.php'; if(empty($_SESSION['user_id'])) redirect('login.php');
$title='AI Assistant - MediScan';
$answer='';
if(is_post()){
  if(!verify_csrf($_POST['csrf']??'')) { http_response_code(400); exit('Invalid CSRF'); }
  $q=trim($_POST['q']??'');
  // Stub: replace with real API call (Hugging Face Inference or your own endpoint)
  $answer = $q ? "Stub response for: ".e($q).". Connect this to your AI API." : '';
}
ob_start(); ?>
<div class="card">
  <h2>MediScan Assistant</h2>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>">
    <div class="form-row">
      <label class="label">Ask a question</label>
      <textarea class="input" name="q" rows="3" placeholder="e.g., What are common side effects of ibuprofen?"></textarea>
    </div>
    <button class="btn" type="submit">Ask</button>
  </form>
</div>
<?php if($answer): ?>
<div class="card" style="margin-top:16px;">
  <h3>Assistant</h3>
  <p><?php echo e($answer); ?></p>
</div>
<?php endif; ?>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
