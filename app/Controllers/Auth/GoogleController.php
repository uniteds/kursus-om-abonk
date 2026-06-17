<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use League\OAuth2\Client\Provider\GenericProvider;

class GoogleController extends BaseController
{
    protected $provider;

    protected function getProvider(): GenericProvider
    {
        if ($this->provider === null) {
            $config = new \Config\GoogleOAuth();

            $this->provider = new GenericProvider([
                'clientId'                => $config->clientId,
                'clientSecret'            => $config->clientSecret,
                'redirectUri'             => $config->redirectUri,
                'urlAuthorize'            => 'https://accounts.google.com/o/oauth2/v2/auth',
                'urlAccessToken'          => 'https://oauth2.googleapis.com/token',
                'urlResourceOwnerDetails' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            ]);
        }

        return $this->provider;
    }

    /**
     * Redirect ke Google untuk login
     */
    public function redirect()
    {
        $authUrl = $this->getProvider()->getAuthorizationUrl([
            'scope'      => ['openid', 'email', 'profile'],
            'accessType' => 'offline',
        ]);

        // Simpan state di session untuk CSRF protection
        $this->session->set('oauth2state', $this->getProvider()->getState());

        return redirect()->to($authUrl);
    }

    /**
     * Callback dari Google setelah user authorize
     */
    public function callback()
    {
        // Cek state untuk mencegah CSRF
        $sessionState = $this->session->get('oauth2state');
        $returnedState = $this->request->getVar('state');

        if (empty($returnedState) || $returnedState !== $sessionState) {
            $this->session->remove('oauth2state');
            return redirect()->to('/login')->with('error', 'Invalid state parameter. Silakan coba lagi.');
        }

        $this->session->remove('oauth2state');

        // Cek ada error dari Google
        $error = $this->request->getVar('error');
        if ($error) {
            return redirect()->to('/login')->with('error', 'Login dengan Google dibatalkan.');
        }

        // Ambil token
        try {
            $token = $this->getProvider()->getAccessToken('authorization_code', [
                'code' => $this->request->getVar('code'),
            ]);

            // Ambil data user dari Google
            $googleUser = $this->getProvider()->getResourceOwner($token);
            $userInfo   = $googleUser->getArray();

            $googleId   = $userInfo['sub'] ?? '';
            $name       = $userInfo['name'] ?? '';
            $email      = $userInfo['email'] ?? '';
            $avatarUrl  = $userInfo['picture'] ?? null;

            // Cari user berdasarkan google_id atau email
            $model = new UserModel();
            $user  = $model->where('google_id', $googleId)->first()
                     ?? $model->findByEmail($email);

            if ($user) {
                // Update data Google jika user sudah ada
                $updateData = [
                    'id'         => $user->id,
                    'google_id'  => $googleId,
                    'avatar_url' => $avatarUrl,
                ];

                // Jika password kosong (user register manual), set placeholder
                // agar user bisa login via Google tanpa password
                if (empty($user->password)) {
                    $updateData['password'] = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                }

                // Jika email belum verified, set verified
                if (empty($user->email_verified_at)) {
                    $updateData['email_verified_at'] = date('Y-m-d H:i:s');
                    $updateData['is_active'] = 1;
                }

                $model->save($updateData);
                $user = $model->find($user->id);
            } else {
                // Buat user baru
                $data = [
                    'name'              => $name,
                    'email'             => $email,
                    'password'          => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                    'role'              => 'member',
                    'is_active'         => 1,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'google_id'         => $googleId,
                    'avatar_url'        => $avatarUrl,
                ];

                $model->save($data);
                $user = $model->findByEmail($email);
            }

            // Set session
            $this->session->set([
                'user_id'   => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'avatar'    => $user->avatar_url ?? $user->avatar,
                'logged_in' => true,
            ]);

            return $user->role === 'admin'
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/member/dashboard');

        } catch (\Exception $e) {
            log_message('error', 'Google OAuth Error: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
