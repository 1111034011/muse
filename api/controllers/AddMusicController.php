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
use project\models\Playlist;
use project\models\PlaylistMusic;
use project\models\SubUser;
use project\controllers\Controller;
use project\models\User;

class AddMusicController extends Controller
{
    public function create(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $playlist_id = $request->body()['playlist_id'] ?? null;
        $music_id = $request->body()['music_id'] ?? null;


        $playlistmusic = new PlaylistMusic();
        $existing_playlistmusic = $playlistmusic->findByMusicname($playlist_id, $music_id);
        if ($existing_playlistmusic) {
            return ['error' => '此歌曲已被加入清單'];
        }

        $playlistmusic->playlist_id = $playlist_id;
        $playlistmusic->music_id = $music_id;

        try {
            $playlistmusic->save();
            return ['success' => '註冊成功'];
        } catch (Exception $e) {
            return ['error' => '註冊失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }
}