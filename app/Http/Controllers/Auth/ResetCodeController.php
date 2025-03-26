<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResetCodeController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.request-code');
    }

    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $code = mt_rand(100000, 999999); // Código de 6 dígitos

        PasswordResetCode::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => $code,
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

        $record = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record || $record->isExpired()) {
            return back()->withErrors(['code' => 'Código inválido o expirado']);
        }

        return view('auth.reset-with-code', ['email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        PasswordResetCode::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Contraseña restablecida correctamente.');
    }
}
