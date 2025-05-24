<?php

namespace project;

// foreach ($_GET as $key => $val) {
//     echo $key . ':' . $_GET[$key] . '<BR>';
// }
// phpinfo();

use Dotenv\Dotenv;
use project\controllers\LoginController;
use project\core\Application;
use project\controllers\SubUserController;
use project\controllers\BackstageController;
use project\controllers\MusicController;
use project\controllers\AddMusicController;


require_once __DIR__ . '/vendor/autoload.php';

// $app = new Application(dirname(__DIR__), $config);

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Application();




header('Access-Control-Allow-Origin: http://127.0.0.1:5501'); // Replace with your frontend URL
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
// $app->on(Application::EVENT_BEFORE_REQUEST, function(){
//     // echo "Before request from second installation";
// });


// $app->router->get('/api/Login', [LoginController::class, 'demo']);
// $app->router->post('/api/Login', [LoginController::class, 'postDemo']);
// $app->router->delete('/api/Login', [LoginController::class, 'deleteDemo']);
// FIXME: Rename to $app->router->post('/api/auth/register', [AuthController::class, 'register']);
$app->router->post('/api/Login/register', [LoginController::class, 'register']);
$app->router->post('/api/Login/send-verification-email', [LoginController::class, 'sendVerificationEmail']);
$app->router->get('/api/Login/verify', [LoginController::class, 'verify']);
$app->router->post('/api/Login/login', [LoginController::class, 'login']);
//新增多個使用者
$app->router->get('/api/sub-users', [SubUserController::class, 'list']);
$app->router->post('/api/sub-users', [SubUserController::class, 'create']);
$app->router->get('/api/sub-users/:id', [SubUserController::class, 'get']);
$app->router->patch('/api/sub-users/:id', [SubUserController::class, 'update']);
$app->router->delete('/api/sub-users/:id', [SubUserController::class, 'delete']);
$app->router->post('/api/sub-users/login', [SubUserController::class, 'login']);
// $app->router->post('/api/Login/add', [LoginController::class, 'add']);
// FIXME: Rename to $app->router->patch('/api/me', [UserController::class, 'update']);
$app->router->post('/api/Login/Imedit', [LoginController::class, 'Imedit']);
$app->router->post('/api/Login/changePassword', [LoginController::class, 'changePassword']);
// $app->router->get('/api/Login/loginPinnum', [LoginController::class, 'loginPinnum']);
$app->router->post('/api/Login/forgetpwd', [LoginController::class, 'forgetpwd']);
$app->router->post('/api/Login/resetpwd', [LoginController::class, 'resetpwd']);

$app->router->get('/api/Backstage/memeberlist', [BackstageController::class, 'memeberlist']);
$app->router->get('/api/Backstage/musiclist', [BackstageController::class, 'musiclist']);
$app->router->post('/api/Backstage/insert', [BackstageController::class, 'insert']);
$app->router->get('/api/Backstage/music/:id', [BackstageController::class, 'get']);
$app->router->patch('/api/Backstage/music/:id', [BackstageController::class, 'update']);
$app->router->delete('/api/Backstage/music/:id', [BackstageController::class, 'delete']);

$app->router->get('/api/Backstage/musicCountByTag', [BackstageController::class, 'musicCountByTag']);
$app->router->get('/api/Backstage/musicCountByIsAdult', [BackstageController::class, 'musicCountByIsAdult']);
$app->router->get('/api/Backstage/musiccount', [BackstageController::class, 'musiccount']);
$app->router->get('/api/Backstage/membercount', [BackstageController::class, 'membercount']);

$app->router->get('/api/Music/playlists', callback: [MusicController::class, 'list']);
$app->router->post('/api/Music/playlists', callback: [MusicController::class, 'create']);
$app->router->post('/api/Music/musiclist', callback: [MusicController::class, 'musiclist']);

$app->router->get('/api/Music/musicplaycount', callback: [MusicController::class, 'musicplaycount']);

// $app->router->post('/api/Music/addmusictolist', [MusicController::class, 'addmusictolist']);
$app->router->post('/api/AddMusic/addmusic', [AddMusicController::class, 'create']);


$app->router->post('/api/PlayMusic/play', [PlayMusicController::class, 'playMusic']);
$app->router->get('/api/SearchMusic/getData', [SearchMusicController::class, 'getPagedMusicData']);

$app->run();