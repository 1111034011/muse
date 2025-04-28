<?php

namespace project\controllers;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use project\core\Request;
use project\core\Response;
use project\models\Backstage;
use project\models\Music;
use project\models\SubUser;
use project\controllers\Controller;
use project\models\User;

class MusicController extends Controller
{
    // public function create(Request $request)
    // {
    //     $parsed_token = $this->verifyToken($request);
    //     if (!$parsed_token) {
    //         return ['error' => '未登入，請先登入'];
    //     }

    //     $listname = $request->body()['listname'] ?? null;

    //     if (empty($listname))
    //         return ['error' => '歌單名稱是必填的'];

    //     $sub_user = new SubUser();
    //     $existing_user = $sub_user->findByUsername($listname, $parsed_token->sub);
    //     if ($existing_user) {
    //         return ['error' => '此使用者名稱已被註冊'];
    //     }

    //     $sub_user->member_id = $parsed_token->sub;
    //     $sub_user->username = $username;

    //     try {
    //         $sub_user->save();
    //         return ['success' => '註冊成功'];
    //     } catch (Exception $e) {
    //         return ['error' => '註冊失敗，請稍後再試', 'detail' => $e->getMessage()];
    //     }
    // }

    protected function addmusiclist(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->getHeader('Authorization'));
        try {
            return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (ExpiredException | Exception $e) {
            return null;
        }

        $playlist_id = $request->body()['playlist_id'] ?? null;
        $artist = $request->body()['music_id'] ?? null;

        if (empty($playlist_id))
            return ['error' => '歌曲名稱是必填的'];
        if (empty($music_id))
            return ['error' => '歌手是必填的'];

        $music = new Music();
        $music->playlist_id = $playlist_id;
        $music->music_id = $music_id;

        try {
            $music->addlist();
            return ['success' => '新增成功'];
        } catch (Exception $e) {
            return ['error' => '新增失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }
}
