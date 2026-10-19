<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $users = User::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })->latest()->paginate($perPage)->withQueryString();

        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        $activeUsers = User::where('is_active', true)->count();

        return view('users.index', compact('users', 'totalUsers', 'newUsers', 'activeUsers', 'search'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
       $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'age' => 'required|integer|min:0|max:130',
        'is_active' => 'required|boolean',
        'password' => 'required|string|min:8|confirmed',
       ]);
       $data['password'] = Hash::make($data['password']);
       User::create($data);
       return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
      $user = User::findOrFail($id);
      return view('users.edit',compact('user'));
    }

    public function update(Request $request, User $user)
    {
       $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($user->id)],
        'age' => 'required|integer|min:0|max:130',
        'is_active' => 'required|boolean',
        'password' => 'nullable|string|min:8|confirmed',
       ]);
       if(!empty($data['password'])){
        $data['password'] = Hash::make($data['password']);
       }else{
        unset($data['password']);
       }
       $user->update($data);
       return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
