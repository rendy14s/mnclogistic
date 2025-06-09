<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\MNCUserToken;
use CodeIgniter\I18n\Time;

class TokenAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $accessToken = $request->getCookie('access_token');

        // Optional: support token from Authorization header (for APIs)
        if (!$accessToken && $request->hasHeader('Authorization')) {
            $header = $request->getHeaderLine('Authorization');
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $accessToken = $matches[1];
            }
        }

        if (!$accessToken) {
            return response()->setStatusCode(401)->setJSON(['error' => 'Access token missing']);
        }

        $tokenModel = new MNCUserToken();
        $token = $tokenModel->GetAccessToken($accessToken);

        if (!$token) {
            return response()->setStatusCode(401)->setJSON(['error' => 'Invalid token']);
        }

        if (Time::now()->isAfter(Time::parse($token['access_expires']))) {
            return response()->setStatusCode(401)->setJSON(['error' => 'Token expired']);
        }

        // Optionally set user data into the request or session
        // session()->set('user_id', $token['user_id']);

        // Let request continue
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
