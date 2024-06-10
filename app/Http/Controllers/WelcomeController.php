<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class WelcomeController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8', // Thêm quy tắc validate cho password
            ]);

            // Kiểm tra lỗi validate
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Tạo user mới
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')), // Mã hóa mật khẩu
            ]);

            // Dispatch job SendWelcomeEmail
            SendWelcomeEmail::dispatch($user->email);

            return response()->json(['message' => 'User created successfully!']);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
