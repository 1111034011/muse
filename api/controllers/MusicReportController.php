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
use project\models\MusicRanking;
use project\models\MusicReport;

class MusicReportController extends Controller
{
    public function create(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $music_id = $request->body()['music_id'] ?? null;

        if (empty($music_id)) {
            return ['error' => '音樂 ID 是必填的'];
        }

        $report = new MusicReport();
        $report->music_id = $music_id;

        try {
            $report->reportInsert();
            return ['success' => '舉報成功'];
        } catch (Exception $e) {
            return ['error' => '舉報失敗', 'detail' => $e->getMessage()];
        }
    }

    public function list(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $report = new MusicReport();
        try {
            $reported_music = $report->getAllReportedMusic();
            return ['success' => '舉報音樂列表', 'data' => $reported_music];
        } catch (Exception $e) {
            return ['error' => '獲取舉報音樂失敗', 'detail' => $e->getMessage()];
        }
    }

    public function update(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $music_id = $request->getParam('id');
        $music_name = $request->body()['music_name'] ?? null;
        $artist = $request->body()['artist'] ?? null;
        $tag_id = $request->body()['tag_id'] ?? null;
        $is_adult = $request->body()['is_adult'] ?? null;

        if (empty($is_adult))
            return ['error' => '音樂名稱是必填的'];

        try {
            $musicReport = new MusicReport();
            $musicReport->updateIsAdult($music_id, $is_adult);
            return ['success' => '更新成功'];
        } catch (Exception $e) {
            return ['error' => '更新失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
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

        $report_id = $request->getParam('id'); // 這裡要用 report_id

        $report = new MusicReport();
        $existing_report = $report->getReportById($report_id);

        try {
            $report->deleteReport($report_id);
            return ['success' => '刪除成功'];
        } catch (Exception $e) {
            return ['error' => '刪除失敗', 'detail' => $e->getMessage()];
        }
    }
}