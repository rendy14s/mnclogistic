<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCUserToken;


class RefreshToken extends BaseController
{
    public function index()
    {
        //
    }

     public function refresh()
    {
        helper('cookie');

        $refreshToken = $this->request->getCookie('access_token');

        if (!$refreshToken) {
            // return $this->response->setStatusCode(401)->setJSON(['error' => 'Token missing']);
            return redirect()->to('/login');
        }

        $tokenModel = new MNCUserToken();
        $tokenData = $tokenModel->GetAccessToken($refreshToken);

        if (!$tokenData) {
            // return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid token']);
            return redirect()->to('/login');
        }

        // Check if refresh token expired
        if (time() > strtotime($tokenData['expires_at'])) {
            // return $this->response->setStatusCode(401)->setJSON(['error' => 'Token expired']);
            return redirect()->to('/login');
        }

        $newAccessToken = bin2hex(random_bytes(32));
        $accessExpires = date('Y-m-d H:i:s', time() + 900);

        // Update DB
        $tokenModel->update($tokenData['id'], [
            'access_token' => $newAccessToken,
            'expires_at' => $accessExpires,
        ]);

        // Update cookies
        setcookie('access_token', $newAccessToken, time() + 900, '/', '', false, true);

        return $this->response->setJSON([
            'access_token' => $newAccessToken,
            'expires_at' => $accessExpires
        ]);
    }
}
