<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's bookings (in a real app, these would come from the database)
        $bookings = Booking::where('user_id', $user->id)
                        ->orderBy('date', 'desc')
                        ->take(5)
                        ->get();
        
        // Get upcoming bookings
        $upcomingBookings = $bookings->where('status', 'confirmed')
                        ->where('date', '>=', date('Y-m-d'));
        
        return view('user.dashboard', compact('user', 'bookings', 'upcomingBookings'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->bio;

        // Handle profile image upload
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            // Get the upload service
            $imageUploadService = app(\App\Services\ImageUploadService::class);
            
            // Save the image and get its URL
            $profileImageUrl = $imageUploadService->uploadImage(
                $request->file('profile_image'),
                'profiles',
                $user->profile_image
            );
            
            $user->profile_image = $profileImageUrl;
        }
        
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
    
    public function changePassword()
    {
        return view('user.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Password berhasil diperbarui!');
    }
}
