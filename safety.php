<?php require_once __DIR__ . '/config.php';
$title='Drug Safety - MediScan'; ob_start(); ?>
<div class="card">
  <h2>Drug safety information</h2>
  <details open><summary>General precautions</summary><p>Always follow prescribed dosages and consult a licensed professional before changes.</p></details>
  <details><summary>Storage</summary><p>Keep medicines in original containers and out of reach of children.</p></details>
  <details><summary>Expiry</summary><p>Check expiration dates and discard expired products responsibly.</p></details>
  <p style="margin-top:8px;font-size:13px;color:#6b7280;">Disclaimer: Information provided is for educational purposes and not medical advice.</p>
</div>
<?php $content=ob_get_clean(); include __DIR__ . '/views/layouts/base.php'; ?>
