<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // ──────────────────────────────────────────
    //  LOGIN
    // ──────────────────────────────────────────
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->back()->with('error', 'Email atau password salah.')->withInput();
        }

        // Jika user login via Google, minta login via Google
        if ($user->google_id) {
            return redirect()->back()->with('error', 'Akun ini terdaftar via Google. Silakan login dengan Google.')->withInput();
        }

        if (!$user->is_active) {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif.')->withInput();
        }

        if (empty($user->email_verified_at) && $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Silakan verifikasi email Anda terlebih dahulu. Cek inbox atau spam folder.')->withInput();
        }

        $this->session->set([
            'user_id'   => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role,
            'avatar'    => $user->avatar,
            'logged_in' => true,
        ]);

        return $user->role === 'admin'
            ? redirect()->to('/admin/dashboard')
            : redirect()->to('/member/dashboard');
    }

    // ──────────────────────────────────────────
    //  REGISTER + EMAIL VERIFICATION
    // ──────────────────────────────────────────
    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|matches[password_confirm]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new UserModel();
        $token = bin2hex(random_bytes(32));

        $data = [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'       => 'member',
            'is_active'  => 0,
            'reset_token'=> $token,
        ];

        $model->save($data);

        $this->sendVerificationEmail($this->request->getPost('email'), $token);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }

    public function verifyEmail($token)
    {
        $model = new UserModel();
        $user  = $model->where('reset_token', $token)->first();

        if (!$user) {
            return view('auth/verify-email', ['status' => 'error', 'message' => 'Token verifikasi tidak valid atau sudah digunakan.']);
        }

        $model->update($user->id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_active'         => 1,
            'reset_token'       => null,
            'reset_expires'     => null,
        ]);

        return view('auth/verify-email', ['status' => 'success', 'message' => 'Email berhasil diverifikasi! Anda sekarang bisa login.']);
    }

    // ──────────────────────────────────────────
    //  FORGOT PASSWORD
    // ──────────────────────────────────────────
    public function forgotPassword()
    {
        return view('auth/forgot-password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $model = new UserModel();
        $user  = $model->findByEmail($email);

        // Selalu tampilkan pesan sukses untuk keamanan (mencegah email enumeration)
        if ($user) {
            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $model->update($user->id, [
                'reset_token'  => $token,
                'reset_expires'=> $expiresAt,
            ]);

            $this->sendResetPasswordEmail($user->email, $user->name, $token);
        }

        return view('auth/forgot-password-sent');
    }

    public function resetPassword($token)
    {
        $model = new UserModel();
        $user  = $model->where('reset_token', $token)
                        ->where('reset_expires >', date('Y-m-d H:i:s'))
                        ->first();

        if (!$user) {
            return view('auth/verify-email', [
                'status'  => 'error',
                'message' => 'Token reset password tidak valid atau sudah kedaluwarsa. Silakan minta link baru.',
            ]);
        }

        return view('auth/reset-password', ['token' => $token]);
    }

    public function doResetPassword()
    {
        $token  = $this->request->getPost('token');
        $model  = new UserModel();
        $user   = $model->where('reset_token', $token)
                         ->where('reset_expires >', date('Y-m-d H:i:s'))
                         ->first();

        if (!$user) {
            return view('auth/verify-email', [
                'status'  => 'error',
                'message' => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $rules = [
            'password' => 'required|min_length[6]|matches[password_confirm]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model->update($user->id, [
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token'  => null,
            'reset_expires'=> null,
        ]);

        return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }

    // ──────────────────────────────────────────
    //  LOGOUT
    // ──────────────────────────────────────────
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }

    // ──────────────────────────────────────────
    //  HELPER: SEND EMAIL
    // ──────────────────────────────────────────
    private function sendVerificationEmail(string $email, string $token): void
    {
        $config   = new \Config\Email();
        $emailObj = \Config\Services::email();
        $emailObj->initialize((array) $config);

        $verifyUrl = base_url("/verify-email/{$token}");
        $body      = view('auth/emails/verify', [
            'verifyUrl' => $verifyUrl,
            'year'      => date('Y'),
        ]);

        $emailObj->setFrom($config->fromEmail, $config->fromName);
        $emailObj->setTo($email);
        $emailObj->setSubject('Verifikasi Akun - Om Abonk');
        $emailObj->setMessage($body);
        $emailObj->send();
    }

    private function sendResetPasswordEmail(string $email, string $name, string $token): void
    {
        $config   = new \Config\Email();
        $emailObj = \Config\Services::email();
        $emailObj->initialize((array) $config);

        $resetUrl = base_url("/reset-password/{$token}");
        $body     = view('auth/emails/reset', [
            'resetUrl' => $resetUrl,
            'name'     => $name,
            'year'     => date('Y'),
        ]);

        $emailObj->setFrom($config->fromEmail, $config->fromName);
        $emailObj->setTo($email);
        $emailObj->setSubject('Reset Password - Om Abonk');
        $emailObj->setMessage($body);
        $emailObj->send();
    }
}
