<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OwnerWelcome;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (auth()->user()->isFieldOwner()) {
            return route('owner.dashboard');
        }
        
        return '/';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $role = request()->query('type');
        return view('auth.register', ['preselected_role' => $role]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        
        if (isset($data['role']) && ($data['role'] === 'owner' || $data['role'] === 'field_owner')) {
            $rules['phone'] = ['required', 'string', 'max:15'];
            $rules['address'] = ['required', 'string'];
            $rules['terms'] = ['required', 'accepted'];
        }
        
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Log registration data for debugging (excluding password)
        $logData = $data;
        unset($logData['password']);
        unset($logData['password_confirmation']);
        \Log::info('Registration attempt with data:', $logData);
        
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
        
        // Add role and additional fields for field owners
        if (isset($data['role']) && ($data['role'] === 'owner' || $data['role'] === 'field_owner')) {
            $userData['role'] = 'field_owner';  // Selalu gunakan field_owner sebagai role
            $userData['phone'] = $data['phone'] ?? null;
            $userData['address'] = $data['address'] ?? null;
            \Log::info('Creating field owner account with data:', $userData);
        } else {
            // Set default role for regular users
            $userData['role'] = 'user';
            \Log::info('Creating regular user account');
        }
        
        try {
            // Debugging database connection
            try {
                \DB::connection()->getPdo();
                \Log::info('Database connected successfully: ' . \DB::connection()->getDatabaseName());
            } catch (\Exception $e) {
                \Log::error('Database connection error: ' . $e->getMessage());
            }
            
            // Create user with detailed error reporting
            $user = User::create($userData);
            \Log::info('User created successfully with ID: ' . $user->id);
            
            // Send welcome email to field owners
            if ($user->isFieldOwner()) {
                try {
                    Mail::to($user->email)->send(new OwnerWelcome($user));
                    \Log::info('Welcome email sent to owner: ' . $user->email);
                } catch (\Exception $e) {
                    // Log the error but continue with registration
                    \Log::error('Failed to send owner welcome email: ' . $e->getMessage());
                }
            }
            
            return $user;
        } catch (\Exception $e) {
            \Log::error('Failed to create user: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
