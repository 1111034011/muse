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
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
        }

        $music_name = $request->body()['music_name'] ?? null;
        $artist = $request->body()['artist'] ?? null;
        $tag_id = $request->body()['tag_id'] ?? null;
        $is_adult = $request->body()['is_adult'] ?? null;
        $music_url = $request->body()['music_url'] ?? null;

        if (empty($music_name))
            return ['error' => '歌曲名稱是必填的'];
        if (empty($artist))
            return ['error' => '歌手是必填的'];
        if (empty($tag_id))
            return ['error' => '類型是必填的'];
        if (empty($is_adult))
            return ['error' => '是否成人是必填的'];
        if (empty($music_url))
            return ['error' => '歌曲網址是必填的'];

        $music = new Music();
        $music->music_name = $music_name;
        $music->artist = $artist;
        $music->tag_id=$tag_id;
        $music->is_adult = $is_adult;
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
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
        }

        $music_id = $request->getParam('id');
        $music_name = $request->body()['music_name'] ?? null;
        $artist = $request->body()['artist'] ?? null;
        $music_url = $request->body()['music_url'] ?? null;
        $tag_id = $request->body()['tag_id'] ?? null;
        $is_adult = $request->body()['is_adult'] ?? null;

        if (empty($music_name))
            return ['error' => '音樂名稱是必填的'];
        if (empty($artist))
            return ['error' => '歌手名稱是必填的'];
        if (empty($music_url))
            return ['error' => '音樂網址是必填的'];
        // if (empty($tag_id))
        //     return ['error' => '標籤ID是必填的'];

        try {
            $musicModel = new Music();

            // if (!$musicModel->tagExists($tag_id)) {
            //     return ['error' => '標籤ID不存在，請確認標籤是否正確'];
            // }

            $musicModel->updateMusic($music_id, $music_name, $artist, $music_url, $tag_id, $is_adult);
            return ['success' => '更新成功'];
        } catch (Exception $e) {
            return ['error' => '更新失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }

    public function get(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
        }

        $music_id = $request->getParam('id');

        $music = new Music();
        $current_music = $music->findById($music_id);

        if (!$current_music) {
            return ['error' => '找不到音樂'];
        }
        
        return ['data' => $current_music];
    }

    public function delete(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
        }

        $music_id = $request->getParam('id');

        $music = new Music();
        $current_music = $music->findById($music_id);

        if (!$current_music) {
            return ['error' => '找不到使用者'];
        }

        try {
            $music->delete($music_id);
            return ['success' => '刪除成功'];
        } catch (Exception $e) {
            return ['error' => '刪除失敗', 'detail' => $e->getMessage()];
        }
    }

    public function musiclist(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return json_encode(['error' => '未登入，請先登入'], 401);
        }
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
        }

        try {
            $musicModel = new Music();
            $music = $musicModel->getAllMusic([
                // 'is_adult' => $parsed_token->is_adult,
            ]);
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
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
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
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
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
        if (($parsed_token->role ?? null) !== 'admin') {
            return ['error' => '權限不足'];
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

