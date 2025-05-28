<?php

namespace project\controllers;

use Exception;
use Firebase\JWT\JWT;
use project\core\Request;
use project\models\SubUser;
use project\controllers\Controller;

class SubUserController extends Controller
{
    public function create(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (!($parsed_token->is_owner ?? false)) {
            return ['error' => '權限不足'];
        }

        $username = $request->body()['username'] ?? null;
        $pin_num = $request->body()['pin_num'] ?? null;
        $is_adult = $request->body()['is_adult'] ?? false;

        $sub_user = new SubUser();
        $sub_user_count = $sub_user->countSubUsers($parsed_token->sub);
        $is_owner = $sub_user_count < 1;

        if (empty($username))
            return ['error' => '使用者名稱是必填的'];
        if (empty($pin_num))
            return ['error' => 'PIN 碼是必填的'];
        if (!preg_match('/^\d{4}$/', $pin_num))
            return ['error' => 'PIN 碼必須是 4 位數字'];


        $existing_user = $sub_user->findByUsername($username, $parsed_token->sub);
        if ($existing_user) {
            return ['error' => '此使用者名稱已被註冊'];
        }

        // TODO: Hash the PIN number
        // $hashed_pin_num = password_hash($pin_num, PASSWORD_BCRYPT);

        $sub_user->member_id = $parsed_token->sub;
        $sub_user->username = $username;
        $sub_user->pin_num = $pin_num;
        $sub_user->is_adult = $is_adult;
        $sub_user->is_owner = $is_owner;

        try {
            $sub_user->save();
            return ['success' => '註冊成功', 'data' => $sub_user];
        } catch (Exception $e) {
            return ['error' => '註冊失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }

    public function list(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (!($parsed_token->is_owner ?? false)) {
            return ['error' => '權限不足'];
        }

        try {
            $sub_user = new SubUser();
            $sub_users = $sub_user->findByMemberId($parsed_token->sub);
            // return ['data' => $sub_users];
            return ['success' => true, 'users' => $sub_users];

        } catch (Exception $e) {
            return ['error' => '獲取列表失敗', 'detail' => $e->getMessage()];
        }
    }

    public function get(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (!($parsed_token->is_owner ?? false)) {
            return ['error' => '權限不足'];
        }

        $sub_user_id = $request->getParam('id');

        $sub_user = new SubUser();
        $current_sub_user = $sub_user->findById($sub_user_id, $parsed_token->sub);

        if (!$current_sub_user) {
            return ['error' => '找不到使用者'];
        }

        return ['data' => $current_sub_user];
    }

    public function update(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (!($parsed_token->is_owner ?? false)) {
            return ['error' => '權限不足'];
        }

        $sub_user_id = $request->getParam('id');

        $sub_user = new SubUser();
        $current_sub_user = $sub_user->findById($sub_user_id, $parsed_token->sub);

        if (!$current_sub_user) {
            return ['error' => '找不到使用者'];
        }

        $username = $request->body()['username'] ?? null;
        $pin_num = $request->body()['pin_num'] ?? null;
        $preferences = $request->body()['preferences'] ?? null;

        if (!empty($pin_num) && !preg_match('/^\d{4}$/', $pin_num)) {
            return ['error' => 'PIN 碼必須是 4 位數字'];
        }

        if ($username) {
            if ($username !== $current_sub_user->username) {
                $existing_sub_user = $sub_user->findByUsername($username, $parsed_token->sub);
                if ($existing_sub_user) {
                    return ['error' => '此使用者名稱已被註冊'];
                }
            }
        }

        // TODO: Hash the PIN number
        // $hashed_pin_num = password_hash($pin_num, PASSWORD_BCRYPT);

        $sub_user->sub_member_id = $sub_user_id;
        $sub_user->username = $username;
        $sub_user->pin_num = $pin_num;
        $sub_user->preferences = json_encode($preferences);

        try {
            $sub_user->update();
            return ['success' => '更新成功'];
        } catch (Exception $e) {
            return ['error' => '更新失敗', 'detail' => $e->getMessage()];
        }
    }

    public function delete(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }
        if (!($parsed_token->is_owner ?? false)) {
            return ['error' => '權限不足'];
        }

        $sub_user_id = $request->getParam('id');

        $sub_user = new SubUser();
        $current_sub_user = $sub_user->findById($sub_user_id, $parsed_token->sub);

        if (!$current_sub_user) {
            return ['error' => '找不到使用者'];
        }

        try {
            $sub_user->delete($sub_user_id, $parsed_token->sub);
            return ['success' => '刪除成功'];
        } catch (Exception $e) {
            return ['error' => '刪除失敗', 'detail' => $e->getMessage()];
        }
    }


    public function login(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $username = $request->body()['username'] ?? null;
        $pin_num = $request->body()['pin_num'] ?? null;

        if (empty($username))
            return ['error' => '使用者名稱是必填的'];
        if (empty($pin_num))
            return ['error' => 'PIN 碼是必填的'];
        if (!preg_match('/^\d{4}$/', $pin_num))
            return ['error' => 'PIN 碼必須是 4 位數字'];

        $sub_user = new SubUser();
        $existing_user = $sub_user->findByUsername($username, $parsed_token->sub);

        if (!$existing_user) {
            return ['error' => '使用者不存在'];
        }
        // TODO: Check hashed PIN number
        if ($existing_user->pin_num !== $pin_num) {
            return ['error' => 'PIN 碼錯誤'];
        }

        $payload = [
            'sub' => $parsed_token->sub,
            'iat' => time(),
            'exp' => time() + 3600 * 24 * 7, // 7days
            'role' => 'sub_user',
            'sub_user_id' => $existing_user->sub_member_id,
            'username' => $existing_user->username,
            'preferences' => $existing_user->preferences,
            'is_adult' => $existing_user->is_adult,
            'is_owner' => $existing_user->is_owner,
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return [
            'success' => true,
            'message' => '登入成功',
            'token' => $token,
        ];
    }

    public function updateisadult(Request $request)
    {
        $parsed_token = $this->verifyToken($request);
        if (!$parsed_token) {
            return ['error' => '未登入，請先登入'];
        }

        $sub_user_id = $request->getParam('id');
        $is_adult = $request->body()['is_adult'] ?? null;

        try {
            $sub_user = new SubUser();

            $sub_user->updateIsAdult($sub_user_id, $is_adult);
            return ['success' => '更新成功'];
        } catch (Exception $e) {
            return ['error' => '更新失敗，請稍後再試', 'detail' => $e->getMessage()];
        }
    }
}

