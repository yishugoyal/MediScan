<?php
session_start();

// -----------------
// DB config
// -----------------
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php'; // must define $pdo as PDO instance
} else {
    $pdo = null; // fallback: session storage
}

// -----------------
// Helpers
// -----------------
function json_out($payload, int $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function get_json_with_retry(string $url, int $maxAttempts = 2, int $timeout = 15) : array {
    $attempt = 0;
    $lastErr = 'Unknown error';
    while ($attempt <= $maxAttempts) {
        $attempt++;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json']
        ]);
        $resp = curl_exec($ch);
        $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $cerr = curl_error($ch);
        curl_close($ch);

        if ($resp !== false && $http >= 200 && $http < 300) {
            $data = json_decode($resp, true);
            if (is_array($data)) return $data;
            $lastErr = 'Invalid JSON from upstream';
        } else {
            $lastErr = $cerr ?: ("HTTP " . $http);
        }
        usleep(250000);
    }
    return ['reply' => 'API error: ' . $lastErr];
}

// -----------------
// Initialize session chat ID
// -----------------
if (!isset($_SESSION['chat_id'])) {
    $_SESSION['chat_id'] = bin2hex(random_bytes(16));
}
$chat_id = $_SESSION['chat_id'];
$user_id = $_SESSION['user_id'] ?? 0; // must be set after login

// -----------------
// Handle API requests
// -----------------
$action = $_GET['action'] ?? '';

if ($action === 'external_api') {
    $query = $_GET['query'] ?? '';
    $apiUrl = "https://lamma8b.yuvigoyal4.workers.dev/?q=just+guide+me+the+answer+not+asking+you+anything+to+give+correct+You%20are%20Mediscan%20AI%2C%20a%20professional%20and%20friendly%20virtual%20medical%20assistant.%20You%20act%20like%20an%20experienced%20doctor%20who%20provides%20accurate%2C%20concise%2C%20and%20empathetic%20responses%20to%20health-related%20questions.%0A%0AYour%20goals%3A%0A-%20Respond%20**only%20to%20medical%20or%20health-related%20topics**%20(symptoms%2C%20medicines%2C%20treatments%2C%20precautions%2C%20etc.).%0A-%20If%20the%20question%20is%20unrelated%20to%20health%2C%20politely%20say%3A%20%0A%20%20%E2%80%9CI'm%20your%20AI%20medical%20assistant.%20Please%20ask%20something%20related%20to%20health%20or%20medicine.%E2%80%9D%0A-%20Always%20explain%20answers%20clearly%20and%20in%20simple%20words%20that%20anyone%20can%20understand.%0A-%20Include%3A%0A%20%20%E2%80%A2%20Likely%20causes%20and%20conditions%20related%20to%20the%20symptoms%20%20%0A%20%20%E2%80%A2%20Recommended%20home%20remedies%20or%20over-the-counter%20options%20(if%20safe)%20%20%0A%20%20%E2%80%A2%20Common%20medicines%20(with%20dosage%20only%20if%20safe%20and%20general)%20%20%0A%20%20%E2%80%A2%20Side%20effects+here+is+the+user+query+strictly+give+only+answer+noting+other+than+that.." . urlencode($query);
    $payload = get_json_with_retry($apiUrl, 2, 15);
    if (!isset($payload['reply'])) $payload['reply'] = 'No response';
    json_out($payload);
}

if ($action === 'save_interaction' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $_POST['query'] ?? '';
    $reply = $_POST['reply'] ?? '';
    if ($pdo) {
        $stmt = $pdo->prepare("INSERT INTO interactions (user_id, chat_id, query, reply, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $chat_id, $query, $reply]);
        $id = $pdo->lastInsertId();
    } else {
        $_SESSION['history'] = $_SESSION['history'] ?? [];
        $id = time() . mt_rand(1000,9999);
        $_SESSION['history'][] = ['id'=>$id,'query'=>$query,'reply'=>$reply,'created_at'=>date('Y-m-d H:i:s')];
    }
    json_out(['ok'=>true,'id'=>$id]);
}

if ($action === 'get_history') {
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM interactions WHERE user_id=? AND chat_id=? ORDER BY created_at ASC");
        $stmt->execute([$user_id, $chat_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $rows = $_SESSION['history'] ?? [];
    }
    json_out(['ok'=>true,'rows'=>$rows]);
}

if ($action === 'new_chat') {
    $_SESSION['chat_id'] = bin2hex(random_bytes(16));
    json_out(['ok'=>true]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MediScan Chat</title>

<link rel="preconnect" href="https://fonts.gstatic.com/"/>
<link rel="preconnect" href="https://fonts.googleapis.com/"/>
<link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter:wght@400;500;700;900" onload="this.rel='stylesheet'" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#1193d4",
        "background-light": "#f6f7f8",
        "background-dark": "#101c22",
      },
      fontFamily: { display: ["Inter"] },
      borderRadius: {
        DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px"
      }
    }
  }
}
</script>
<style>
/* keep selects consistent with theme */
.form-select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%231193d4' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
}

/* HealthPlus-like chat styles (only affect chat area) */
.hp-chat {
  --hp-blue: #1193d4;
  --hp-card: #ffffff;
  --hp-border: #e5e7eb;
  --hp-gray: #f3f4f6;
}

.hp-card {
  background: var(--hp-card);
  border: 1px solid var(--hp-border);
  border-radius: 12px;
  box-shadow: 0 1px 2px rgba(16,24,40,.04);
}

/* message bubbles */
.hp-bubble {
  position: relative;
  border-radius: 14px;
  padding: 12px 14px;
  max-width: 720px;
  line-height: 1.55;
  font-size: 14px;
  word-wrap: break-word;
  white-space: pre-wrap;
}

/* assistant bubble (left) */
.hp-left {
  background: var(--hp-gray);
  color: #111827;
  border: 1px solid #e5e7eb;
}
.hp-left:after {
  content: "";
  position: absolute;
  left: -6px;
  top: 12px;
  width: 0; height: 0;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-right: 6px solid var(--hp-gray);
}

/* user bubble (right) */
.hp-right {
  background: var(--hp-blue);
  color: #fff;
}
.hp-right:after {
  content: "";
  position: absolute;
  right: -6px;
  top: 12px;
  width: 0; height: 0;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-left: 6px solid var(--hp-blue);
}

/* timestamps */
.hp-ts {
  font-size: 11px;
  color: #6b7280;
  margin-top: 6px;
}
.hp-ts--light { color: rgba(255,255,255,.85); }

/* typing dots */
.hp-typing {
  display: inline-flex; gap: 4px; align-items: center;
}
.hp-typing i {
  width: 6px; height: 6px; border-radius: 9999px; background:#9ca3af; opacity:.6;
  animation: hpBlink 1.2s infinite ease-in-out;
}
.hp-typing i:nth-child(2){animation-delay:.2s}
.hp-typing i:nth-child(3){animation-delay:.4s}
@keyframes hpBlink { 0%,80%,100%{opacity:.3} 40%{opacity:1} }
</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex flex-col min-h-screen">
  <!-- Header (unchanged) -->
  <header class="bg-background-light dark:bg-background-dark/50 backdrop-blur-sm sticky top-0 z-10 border-b border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
          <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
          </svg>
          <h1 class="text-xl font-bold text-gray-900 dark:text-white">MediScan</h1>
        </div>
        <nav class="hidden md:flex items-center gap-8">
          <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Home</a>
          <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Articles</a>
          <a class="text-sm font-bold text-primary dark:text-primary" href="#">Dosage Calculator</a>
          <a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Medication Info</a>
        </nav>
        <div class="flex items-center gap-2">
          <button class="px-4 py-2 text-sm font-bold rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors dark:bg-primary/20 dark:hover:bg-primary/30">
            <a href="/logout.php">Log out</a>
          </button>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow flex">
    <!-- Sidebar (unchanged) -->
    <aside class="w-64 bg-background-light dark:bg-background-dark border-r border-gray-200 dark:border-gray-800 p-6 flex flex-col">
      <nav class="space-y-2">
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-white bg-primary" href="/dashboard.php">
          <span class="material-symbols-outlined">medication</span>
          <span class="font-medium">Medicine Info</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="calculator.php">
          <span class="material-symbols-outlined">calculate</span>
          <span class="font-medium">Dosage Calculator</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="calculator.php">
          <span class="material-symbols-outlined">sync_problem</span>
          <span class="font-medium">Drug Interaction Checker</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="calculator.php">
          <span class="material-symbols-outlined">psychology_alt</span>
          <span class="font-medium">Symptom-to-Medicine Finder</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="calculator.php">
          <span class="material-symbols-outlined">health_and_safety</span>
          <span class="font-medium">Side-Effect Analyzer</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="#">
          <span class="material-symbols-outlined">history</span>
          <span class="font-medium">Search History</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20 dark:hover:text-primary" href="#">
          <span class="material-symbols-outlined">person</span>
          <span class="font-medium">Profile</span>
        </a>
      </nav>
    </aside>

    <!-- Main column -->
    <div class="flex-1 flex flex-col">
     

      <!-- Chat shell (only area changed) -->
      <div class="px-6 py-4 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-gray-800">
        <div class="hp-chat">
          <div class="hp-card w-full h-[calc(120vh-100px)] flex flex-col">
            <!-- Faux section header like screenshot -->
            <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
              <div class="text-[15px] font-semibold text-gray-800">Interactive Query</div>
              <div class="text-xs text-gray-500">Ask about dosage, interactions, or side effects</div>
            </div>

            <!-- Messages -->
            <div id="chatBox" class="flex-1 overflow-y-auto px-5 py-4 space-y-4"></div>

            <!-- Input -->
            <div class="border-t border-gray-200 px-4 py-3 flex items-center gap-3">
              <input id="userQuery" type="text"
                class="flex-1 h-11 rounded-md border border-gray-300 px-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary"
                placeholder="Ask about dosage, interactions, or side effects..." />
              <button id="sendBtn"
                class="h-11 px-5 rounded-md bg-primary text-white text-sm font-medium hover:opacity-95 active:opacity-90">
                Send
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<script>
// Utilities
const chatBox = document.getElementById('chatBox');
const userInput = document.getElementById('userQuery');
const sendBtn  = document.getElementById('sendBtn');

function nowTime() { return new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}); }
function esc(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

function bubble({ side, html, time }) {
  const row = document.createElement('div');
  row.className = `w-full flex ${side==='right'?'justify-end':'justify-start'}`;
  const wrap = document.createElement('div');
  wrap.className = `hp-bubble ${side==='right'?'hp-right':'hp-left'}`;
  const content = document.createElement('div');
  content.className='whitespace-pre-wrap'; content.innerHTML=html;
  const ts = document.createElement('div');
  ts.className=`hp-ts ${side==='right'?'hp-ts--light':''}`; ts.textContent=time||nowTime();
  wrap.appendChild(content); wrap.appendChild(ts); row.appendChild(wrap);
  chatBox.appendChild(row); chatBox.scrollTop=chatBox.scrollHeight;
  return { row, wrap, content };
}

function typingBubble() {
  return bubble({ side:'left', html:`<span class="hp-typing"><i></i><i></i><i></i></span>`, time:nowTime() });
}

async function sendMessage() {
  const query=userInput.value.trim(); if(!query) return;
  bubble({ side:'right', html:esc(query), time:nowTime() });
  userInput.value='';
  const typing=typingBubble();
  try {
    const res=await fetch(`dashboard.php?action=external_api&query=${encodeURIComponent(query)}`,{headers:{'Accept':'application/json'}});
    let data; try{ data=await res.json(); } catch { data={reply:'Invalid JSON from API'}; }
    typing.row.remove();
    const reply=(data && data.reply)? String(data.reply):'No response';
    bubble({ side:'left', html:esc(reply), time:nowTime() });
    fetch('dashboard.php?action=save_interaction',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`query=${encodeURIComponent(query)}&reply=${encodeURIComponent(reply)}`});
  } catch(e){ typing.row.remove(); bubble({ side:'left', html:esc('Error fetching API'), time:nowTime() }); console.error(e); }
}

sendBtn.addEventListener('click', sendMessage);
userInput.addEventListener('keydown', e=>{ if(e.key==='Enter'&&!e.shiftKey) sendMessage(); });

async function newChat() {
  await fetch('dashboard.php?action=new_chat');
  chatBox.innerHTML=''; // clear UI
}

// Load history
window.addEventListener('DOMContentLoaded', async()=>{
  try{
    const res=await fetch('dashboard.php?action=get_history');
    const data=await res.json();
    if(data.ok && Array.isArray(data.rows)){
      data.rows.forEach(r=>{
        bubble({side:'right', html:esc(r.query||''), time:r.created_at||''});
        bubble({side:'left', html:esc(r.reply||''), time:r.created_at||''});
      });
    }
  } catch(e){ console.error(e); }
});
</script>
</body>
</html>

