<?php
class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/home');
        }
        $this->view('auth/login', ['title' => 'Login'], 'auth');
    }

    public function login(): void
    {
        $data = $this->only(['username', 'password']);
        $back = '/login';

        if (Auth::lockedOut()) {
            flash('danger', 'Too many failed attempts. Please wait a few minutes and try again.');
            $this->redirect($back);
        }

        $clean = $this->validate($data, [
            'username' => 'required|max:50',
            'password' => 'required',
        ], $back);

        if (!Auth::attempt($clean['username'], (string) $data['password'])) {
            flash('danger', 'Invalid username or password.');
            flash_old(['username' => $clean['username']]);
            $this->redirect($back);
        }

        $this->redirect('/home');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('success', 'You have been logged out.');
        $this->redirect('/login');
    }
}
