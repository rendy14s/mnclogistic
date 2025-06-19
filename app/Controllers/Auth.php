<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MNCUser;
use App\Models\MNCUserToken;

class Auth extends BaseController
{
    public function index()
    {
        //
    }

    public function login()
    {
        return view('admin/pages/login/index'); 
    }

    public function loginPost()
    {
        $session = session();
        $userModel = new MNCUser();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username dan password wajib diisi');
        }

        $user = $userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set('user', [
                'id'        => $user['id'],
                'fullname'  => $user['full_name'],
                'username'  => $user['username'],
                'logged_in' => true,
            ]);

            // Token
            $accessToken   = bin2hex(random_bytes(32));
            $expiresToken  = date('Y-m-d H:i:s', time() + 900); // 15 minutes
            $ipAddress     = $this->request->getIPAddress();
            $userAgent     = $this->request->getUserAgent()->getAgentString();

            // Save token to DB
            $tokenModel = new MNCUserToken();
            $tokenModel->where('user_id', $user['id'])->delete(); // optional: clear old token
            $tokenModel->insert([
                'user_id'      => $user['id'],
                'access_token' => $accessToken,
                'user_agent'   => $userAgent,
                'ip_address'   => $ipAddress,
                'expires_at'   => $expiresToken,
            ]);

            // Set access_token cookie (adjust secure flag on production)
            setcookie('access_token', $accessToken, time() + 900, '/', '', false, true);

            // ✅ Redirect ke halaman yang akan inject JS (lihat view selanjutnya)
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid username or password');
    }


    public function logout()
    {
        $tokenModel = new MNCUserToken();

         // Get access token from cookie
        $accessToken = $this->request->getCookie('access_token');

         if ($accessToken) {
            // Delete token from DB
            $tokenModel->where('access_token', $accessToken)->delete();
        }

         // Clear cookies by setting them to expire in the past
        setcookie('access_token', '', time() - 900, '/');

        session()->destroy();
        
        return redirect()->to('/login');
    }
}
