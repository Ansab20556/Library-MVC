
<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

use App\Controllers\BookController;
use App\Controllers\UserController;
use App\Controllers\BorrowController;

$controller = $_GET['controller'] ?? 'book';
$action     = $_GET['action'] ?? 'index';

$cmap = [
  'book'   => BookController::class,
  'user'   => UserController::class,
  'borrow' => BorrowController::class,
];

if (!isset($cmap[$controller])) { http_response_code(404); exit('Controller not found'); }

$c = new $cmap[$controller]();
if (!method_exists($c, $action)) { http_response_code(404); exit('Action not found'); }

$c->$action();
