<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    public function dashboard()
    {
        $user = Auth::user();
        
        // Get real bookings with relationships
        $upcomingBookings = $user->bookings()
            ->with(['field', 'review'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(6)
            ->get();
        
        $recentBookings = $user->bookings()
            ->with(['field', 'review'])
            ->where('date', '<', now()->format('Y-m-d'))
            ->orWhere(function($query) {
                $query->where('date', '=', now()->format('Y-m-d'))
                      ->where('end_time', '<', now()->format('H:i'));
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
            
        // Calculate statistics for dashboard cards
        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();
        $pendingBookings = $user->bookings()->where('status', 'pending')->count();
        $totalReviews = $user->reviews()->count();
        
        return view('user.dashboard', compact(
            'user', 
            'upcomingBookings', 
            'recentBookings',
            'totalBookings',
            'completedBookings',
            'pendingBookings',
            'totalReviews'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }    public function updateProfile(Request $request)
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
              // Store this in the session for reference
            session()->flash('image_uploaded', true);
            
            $user->profile_image = $profileImageUrl;
        }
        
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        return view('user.change-password');
    }    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('user.change-password')->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'booking_reminders' => 'boolean',
            'promotional_emails' => 'boolean',
            'language' => 'string|in:id,en',
            'timezone' => 'string',
            'theme' => 'string|in:light,dark,auto',
            'currency' => 'string|in:IDR,USD',
            'favorite_sports' => 'array',
            'favorite_sports.*' => 'string',
        ]);
        
        // In a real application, we would store these preferences in the database
        // For now, we'll just redirect with success message
        
        return response()->json([
            'success' => true,
            'message' => 'Preferensi berhasil disimpan!'
        ]);
    }
}
