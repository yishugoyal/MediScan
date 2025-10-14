<?php
// analyze_medicine.php
session_start();
require_once __DIR__ . '/config.php';

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
if (!function_exists('verify_csrf')) {
    function verify_csrf($t) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t);
    }
}
if (!function_exists('db')) {
    function db(){
        static $pdo;
        if ($pdo === null) {
            $dsn = 'mysql:host=localhost;dbname=mediscan;charset=utf8mb4';
            $pdo = new PDO($dsn, 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return $pdo;
    }
}

header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Not authenticated']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$csrf = $_POST['csrf'] ?? '';
if (!verify_csrf($csrf)) {
    echo json_encode(['status'=>'error','message'=>'Invalid CSRF token']);
    exit;
}

/* get user profile */
$db = db();
$stmt = $db->prepare('SELECT * FROM profiles WHERE user_id = ? LIMIT 1');
$stmt->execute([$userId]);
$profile = $stmt->fetch() ?: [];

/* receive inputs */
$med_text = trim($_POST['med_text'] ?? '');
$mg_per_kg = isset($_POST['mg_per_kg']) && $_POST['mg_per_kg'] !== '' ? (float)$_POST['mg_per_kg'] : null;
$age_override = isset($_POST['age_override']) && $_POST['age_override'] !== '' ? (int)$_POST['age_override'] : null;

$uploaded_file_path = null;

/* handle upload */
if (!empty($_FILES['med_file']) && $_FILES['med_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['med_file'];
    $allowedTypes = ['image/jpeg','image/png','application/pdf','image/jpg'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'error','message'=>'Upload error']);
        exit;
    }
    if ($f['size'] > 4 * 1024 * 1024) {
        echo json_encode(['status'=>'error','message'=>'File too large (max 4MB)']);
        exit;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $f['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedTypes)) {
        echo json_encode(['status'=>'error','message'=>'Invalid file type']);
        exit;
    }
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $destDir = __DIR__ . '/uploads';
    if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
    $safeName = bin2hex(random_bytes(12)) . '.' . $ext;
    $dest = $destDir . '/' . $safeName;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        echo json_encode(['status'=>'error','message'=>'Failed to save upload']);
        exit;
    }
    $uploaded_file_path = 'uploads/' . $safeName;
}

/* Basic analysis logic (mock) */

/* Normalize profile lists */
$allergies_list = [];
if (!empty($profile['allergies'])) {
    $allergies_list = array_map('trim', explode(',', strtolower($profile['allergies'])));
}

/* Try to detect medicine name from text (very basic) */
$detected_meds = [];
if ($med_text !== '') {
    // find words that look like drug names - naive approach: words with letters and numbers like "Paracetamol", "Dolo 650"
    preg_match_all('/[A-Za-z0-9\-\+]{3,}/', $med_text, $matches);
    if (!empty($matches[0])) {
        // pick top 3 unique tokens as "names"
        $tokens = array_unique(array_map('trim', $matches[0]));
        $detected_meds = array_slice($tokens, 0, 4);
    }
}

/* Mock side effects DB (demo) */
$mock_db = [
    'paracetamol' => [
        'side_effects' => ['nausea','rash','liver toxicity (high dose)'],
        'contra' => ['severe_liver_disease'],
        'typical_dose_mg' => 500
    ],
    'ibuprofen' => [
        'side_effects' => ['stomach pain','heartburn','bleeding (rare)'],
        'contra' => ['ulcer','kidney_disease','pregnancy_late'],
        'typical_dose_mg' => 200
    ]
];

/* helper to lower and strip */
function normal($s){ return strtolower(trim(preg_replace('/[^a-z0-9 ]/i',' ', $s))); }

/* analysis result object we'll return */
$result = [
    'detected_meds' => $detected_meds,
    'warnings' => [],
    'dosage' => null,
    'notes' => [],
];

/* check allergies by detected meds */
foreach ($detected_meds as $token) {
    $n = normal($token);
    if (in_array($n, $allergies_list)) {
        $result['warnings'][] = "User allergy matches detected token: {$token}. Avoid this medication.";
    }
    if (isset($mock_db[$n])) {
        $entry = $mock_db[$n];

        // check contraindications vs profile conditions
        if (!empty($profile['conditions'])) {
            $conds = array_map('trim', explode(',', strtolower($profile['conditions'])));
            foreach ($entry['contra'] as $c) {
                foreach ($conds as $pc) {
                    if (strpos($pc, str_replace('_',' ',$c)) !== false || strpos($c, $pc) !== false) {
                        $result['warnings'][] = "{$token} may be contraindicated for condition: {$pc}";
                    }
                }
            }
        }

        // pregnancy check
        if (!empty($profile['pregnant']) && in_array('pregnancy_late', $entry['contra'])) {
            $result['warnings'][] = "{$token} is not recommended in pregnancy (late).";
        }

        // example typical dose
        $result['notes'][] = "Typical single dose (approx): {$entry['typical_dose_mg']} mg";
    } else {
        $result['notes'][] = "No internal data for '{$token}' — consult an authoritative drug DB or AI model for details.";
    }
}

/* calculate mg/kg dosage if mg_per_kg provided and weight known */
$usedWeight = null;
if ($mg_per_kg !== null) {
    if (!empty($profile['weight_kg'])) {
        $usedWeight = (float)$profile['weight_kg'];
    } elseif (!empty($profile['weight_kg']) && $_POST['age_override']) {
        $usedWeight = (float)$profile['weight_kg'];
    }
    // allow user-supplied override via age_override? (we keep simple)
    if ($usedWeight) {
        $total_mg = $mg_per_kg * $usedWeight;
        $result['dosage'] = "{$mg_per_kg} mg/kg × {$usedWeight} kg = " . round($total_mg, 1) . " mg (single dose)";
    } else {
        $result['warnings'][] = "Weight not found in profile — cannot calculate mg/kg dose.";
    }
}

/* If no tokens and file uploaded: we would run OCR / AI to extract text (placeholder) */
if (empty($detected_meds) && $uploaded_file_path) {
    $result['notes'][] = "File uploaded. To extract medication names from an image/PDF, integrate an OCR + NLP pipeline. (Placeholder for now)";
}

/* Build HTML for frontend */
$htmlParts = [];
if (!empty($result['detected_meds'])) {
    $htmlParts[] = "<strong>Detected tokens:</strong> " . htmlspecialchars(implode(', ', $result['detected_meds']));
}
if (!empty($result['dosage'])) {
    $htmlParts[] = "<strong>Calculated dosage:</strong> " . htmlspecialchars($result['dosage']);
}
if (!empty($result['warnings'])) {
    $htmlParts[] = "<div style='color:#b91c1c'><strong>Warnings:</strong><ul>";
    foreach ($result['warnings'] as $w) $htmlParts[] = "<li>" . htmlspecialchars($w) . "</li>";
    $htmlParts[] = "</ul></div>";
}
if (!empty($result['notes'])) {
    $htmlParts[] = "<strong>Notes:</strong><ul>";
    foreach ($result['notes'] as $n) $htmlParts[] = "<li>" . htmlspecialchars($n) . "</li>";
    $htmlParts[] = "</ul>";
}

$html = implode("\n", $htmlParts);

/* Save in medicines_history */
$ins = $db->prepare('INSERT INTO medicines_history (user_id, input_text, uploaded_file, analysis_result) VALUES (?, ?, ?, ?)');
$ins->execute([$userId, $med_text, $uploaded_file_path, json_encode($result)]);

echo json_encode(['status'=>'success','result'=>$result,'html'=>$html]);
