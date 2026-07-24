<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ResetCodeController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.request-code');
    }

    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $throttleKey = 'reset-send|' . Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'email' => 'Demasiados intentos. Inténtalo de nuevo en ' . ceil(RateLimiter::availableIn($throttleKey) / 60) . ' minuto(s).',
            ]);
        }
        RateLimiter::hit($throttleKey, 3600);

        $code = random_int(100000, 999999); // Código de 6 dígitos, generador criptográficamente seguro

        PasswordResetCode::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => Hash::make((string) $code),
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Mail::raw("Tu código para restablecer la contraseña es: $code", function ($message) use ($request) {
            $message->to($request->email)->subject('Código de recuperación');
        });

        return redirect()->route('password.verify-code-form')->with('email', $request->email);
    }

    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        $throttleKey = 'reset-verify|' . Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors(['code' => 'Demasiados intentos. Solicita un nuevo código en unos minutos.']);
        }

        $record = PasswordResetCode::where('email', $request->email)->first();

        if (!$record || $record->isExpired() || !Hash::check($request->code, $record->code)) {
            RateLimiter::hit($throttleKey, 600);
            return back()->withErrors(['code' => 'Código inválido o expirado']);
        }

        RateLimiter::clear($throttleKey);

        return view('auth.reset-with-code', ['email' => $request->email, 'code' => $request->code]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = PasswordResetCode::where('email', $request->email)->first();

        if (!$record || $record->isExpired() || !Hash::check($request->code, $record->code)) {
            return back()->withErrors(['code' => 'Código inválido o expirado. Solicita uno nuevo.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        PasswordResetCode::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Contraseña restablecida correctamente.');
    }
}
