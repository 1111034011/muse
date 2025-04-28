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

class BackstageController extends Controller
{
    public function insert(Request $request)
    {
        $music_name = $request->body()['music_name'] ?? null;
        $artist = $request->body()['artist'] ?? null;
        $music_url = $request->body()['music_url'] ?? null;

        if (empty($music_name))
            return ['error' => '歌曲名稱是必填的'];
        if (empty($artist))
            return ['error' => '歌手是必填的'];
        if (empty($music_url))
            return ['error' => '歌曲網址是必填的'];

        $music = new Music();
        $music->music_name = $music_name;
        $music->artist = $artist;
        $music->music_url = $music_url;

        try {
            $music->musicinsert();
            return ['success' => '新增成功'];
        } catch (Exception $e) {
            return ['error' => '新增失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }

    public function update(Request $request)
    {
        $music_id = $request->body()['music_id'] ?? null;
        $music_name = $request->body()['music_name'] ?? null;
        $artist = $request->body()['artist'] ?? null;
        $music_url = $request->body()['music_url'] ?? null;
        $tag_id = $request->body()['tag_id'] ?? null;

        if (empty($music_id))
            return ['error' => '音樂ID是必填的'];
        if (empty($music_name))
            return ['error' => '音樂名稱是必填的'];
        if (empty($artist))
            return ['error' => '歌手名稱是必填的'];
        if (empty($music_url))
            return ['error' => '音樂網址是必填的'];
        if (empty($tag_id))
            return ['error' => '標籤ID是必填的'];

        try {
            $musicModel = new Music();

            if (!$musicModel->tagExists($tag_id)) {
                return ['error' => '標籤ID不存在，請確認標籤是否正確'];
            }

            $musicModel->updateMusic($music_id, $music_name, $artist, $music_url, $tag_id);
            return ['success' => '更新成功'];
        } catch (Exception $e) {
            return ['error' => '更新失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }

    public function musiclist(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return json_encode(['error' => '未登入，請先登入'], 401);
        }

        try {

            $musicModel = new Music();
            $music = $musicModel->getAllMusic();
            return [
                'success' => true,
                'music' => $music
            ];
        } catch (Exception $e) {
            return [
                'error' => '獲取列表失敗',
                'detail' => $e->getMessage()
            ];
        }
    }

    public function musiccount(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return json_encode(['error' => '未登入，請先登入'], 401);
        }

        try {
            $musicModel = new Music();
            $musicCount = $musicModel->countAllMusic();
            return [
                'success' => true,
                'musicCount' => $musicCount
            ];
        } catch (Exception $e) {
            return [
                'error' => '獲取列表失敗',
                'detail' => $e->getMessage()
            ];
        }
    }

    public function memeberlist(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return json_encode(['error' => '未登入，請先登入'], 401);
        }

        try {
            $memberModel = new User();
            $members = $memberModel->getAllMembers();
            return [
                'success' => true,
                'users' => $members
            ];
        } catch (Exception $e) {
            return [
                'error' => '獲取列表失敗',
                'detail' => $e->getMessage()
            ];
        }
    }

    public function membercount(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return json_encode(['error' => '未登入，請先登入'], 401);
        }

        try {
            $memberModel = new User();
            $members = $memberModel->countAllMembers();
            return [
                'success' => true,
                'users' => $members
            ];
        } catch (Exception $e) {
            return [
                'error' => '獲取列表失敗',
                'detail' => $e->getMessage()
            ];
        }
    }

}

