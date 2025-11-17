<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Lakukan upaya login seperti biasa
        // Kita ambil email & password secara manual menggunakan $this->only()
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // =================================================================
        // BLOK PERBAIKAN LOGIKA (TAMBAHAN KITA)
        // =================================================================
        // 2. Ambil data user yang baru saja berhasil login
        $user = Auth::user();

        // 3. Periksa apakah user tersebut sudah di-soft delete
        if ($user->deleted_at !== null) {
            // 4. Jika ya, segera logout paksa user tersebut
            Auth::logout();
            $this->session()->invalidate();
            $this->session()->regenerateToken();

            // 5. Kirim pesan error yang jelas
            throw ValidationException::withMessages([
                'email' => 'Akun ini sudah tidak aktif atau telah dihapus.',
            ]);
        }
        // =================================================================
        // AKHIR DARI BLOK PERBAIKAN
        // =================================================================

        // 6. Jika lolos pengecekan, bersihkan rate limiter dan lanjutkan
        RateLimiter::clear($this->throttleKey());

        $this->session()->regenerate();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
