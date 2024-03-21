<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthOtpController extends Controller
{
    public function login()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    // Generate OTP
    public function generate(Request $request)
    {
        # Validate Data
        $request->validate([
            'mobile' => 'required|exists:users,mobile'
        ]);

        # Generate An OTP
        $verificationCode = $this->generateOtp($request->mobile);

        # Return With OTP
        // @todo: Refactor this section to send SMS instead of sending OTP code to the FE
        return Inertia::render('Auth/LoginOtp', [
            'otp' => $verificationCode->otp,
            'user_id' => $verificationCode->user_id
        ]);
    }

    public function generateOtp($mobile)
    {
        $user = User::where('mobile', $mobile)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(1)
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        #Validation
        $request->validate([
            'mobile' => 'required|exists:users,mobile',
            'otp' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        #Validation Logic
        $verificationCode = VerificationCode::where('user_id', $user->id)->where('otp', $request->otp)->first();

        if ($verificationCode) {
            $user = User::where('mobile', $request->mobile)->first();

            if($user){
                // Expire The OTP
                $verificationCode->update([
                    'expire_at' => Carbon::now()
                ]);

                Auth::login($user);
                return redirect('/dashboard');
            }
        }
        return redirect('/login');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|exists:users,mobile',
            'otp' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }

        if (Carbon::now()->isAfter($verificationCode->expire_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Expired OTP',
            ], 422);
        }

        // Expire The OTP
        $verificationCode->update([
            'expire_at' => Carbon::now()
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'token' => $token
        ], 200);
    }

    public function verifyWebOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|exists:users,mobile',
            'otp' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }

        if (Carbon::now()->isAfter($verificationCode->expire_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Expired OTP',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Authentication Successful',
        ], 200);
    }

    public function verifyOtpInertia(Request $request)
    {
        $request->validate([
            'mobile' => 'required|exists:users,mobile',
            'otp' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->latest()
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }

        if (Carbon::now()->isAfter($verificationCode->expire_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Expired OTP',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
        ], 200);
    }

    //
    public function loginRequest(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'mobile' => 'required|exists:users,mobile'
            ]);
        } catch (ValidationException $e) {
            // If validation fails, return error response with validation errors
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        }

        $mobile = $validatedData['mobile'];

        $verificationCode = $this->generateOtp($mobile);


        return response()->json([
            'success' => true,
            'otp' => $verificationCode->otp,
        ]);
    }
}
