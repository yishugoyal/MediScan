<?php
$app = [
  'name' => 'MediScan',
  'env'  => getenv('APP_ENV') ?: 'production',
  'url'  => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?'https':'http').'://'.($_SERVER['HTTP_HOST']??'localhost'),
  'cookie_path' => '/',
  'secure_cookies' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on',
];
if ($app['env']==='development'){ ini_set('display_errors',1); error_reporting(E_ALL); } else { ini_set('display_errors',0); error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); }

$db = [
  'host' => getenv('DB_HOST') ?: 'localhost',
  'port' => getenv('DB_PORT') ?: '3306',
  'name' => getenv('DB_NAME') ?: 'nimados1_medisite',
  'user' => getenv('DB_USER') ?: 'nimados1_medisiteuser',
  'pass' => getenv('DB_PASS') ?: 'Yuvigoyal4@',
  'charset' => 'utf8mb4',
];

function db(){
  static $pdo=null; global $db;
  if($pdo instanceof PDO) return $pdo;
  $dsn=sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',$db['host'],$db['port'],$db['name'],$db['charset']);
  $opt=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false,PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES {$db['charset']} COLLATE utf8mb4_unicode_ci"];
  try{ $pdo=new PDO($dsn,$db['user'],$db['pass'],$opt); }catch(PDOException $e){ http_response_code(500); exit('Database connection failed.'); }
  return $pdo;
}

if(session_status()===PHP_SESSION_NONE){
  session_set_cookie_params(['lifetime'=>0,'path'=>$app['cookie_path'],'domain'=>'','secure'=>$app['secure_cookies'],'httponly'=>true,'samesite'=>'Lax']);
  session_name('mediscan_sid'); session_start();
}
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token']=bin2hex(random_bytes(32)); }
function csrf_token(){ return $_SESSION['csrf_token']??''; }
function verify_csrf($t){ return hash_equals($_SESSION['csrf_token']??'', $t??''); }

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function is_post(){ return ($_SERVER['REQUEST_METHOD']??'GET')==='POST'; }
function redirect($path='/', $code=302){ global $app; header('Location: '.rtrim($app['url'],'/').'/'.ltrim($path,'/'), true,$code); exit; }

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
$cdn="https://fonts.googleapis.com https://fonts.gstatic.com https://cdn.jsdelivr.net https://unpkg.com";

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com; font-src 'self' https://fonts.gstatic.com data:;");

function password_hash_mediscan($p){ return defined('PASSWORD_ARGON2ID') ? password_hash($p,PASSWORD_ARGON2ID) : password_hash($p,PASSWORD_BCRYPT,['cost'=>12]); }

