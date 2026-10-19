<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'age', 'is_active', 'created_at', 'updated_at'])
            ->latest()
            ->paginate(min(max((int) request('per_page', 10), 1), 100));

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'age' => ['required', 'integer', 'min:0', 'max:130'],
            'is_active' => ['sometimes', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator->errors()->toArray());
        }

        $data = $validator->validated();
        $data['is_active'] = $data['is_active'] ?? true;
        $data['password'] = Hash::make($data['password']);

        try {
            $user = User::create($data);
        } catch (QueryException $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                return response()->json([
                    'message' => 'A user with this email address already exists.',
                    'errors' => ['email' => ['This email address is already in use.']],
                ], 409);
            }

            throw $exception;
        }

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $this->userData($user),
        ], 201);
    }

    public function show(string $id)
    {
        return response()->json($this->userData(User::findOrFail($id)));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'age' => ['sometimes', 'required', 'integer', 'min:0', 'max:130'],
            'is_active' => ['sometimes', 'required', 'boolean'],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator->errors()->toArray());
        }

        $data = $validator->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        try {
            $user->update($data);
        } catch (QueryException $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                return response()->json([
                    'message' => 'A user with this email address already exists.',
                    'errors' => ['email' => ['This email address is already in use.']],
                ], 409);
            }

            throw $exception;
        }

        return response()->json($this->userData($user->refresh()));
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();

        return response()->noContent();
    }

    private function userData(User $user): array
    {
        return $user->only(['id', 'name', 'email', 'age', 'is_active', 'created_at', 'updated_at']);
    }

    private function validationResponse(array $errors)
    {
        $isDuplicateEmail = isset($errors['email']) && collect($errors['email'])
            ->contains(fn (string $message) => str_contains($message, 'already been taken'));

        return response()->json([
            'message' => $isDuplicateEmail ? 'A user with this email address already exists.' : 'The given data was invalid.',
            'errors' => $errors,
        ], $isDuplicateEmail ? 409 : 422);
    }

    private function isUniqueConstraintViolation(QueryException $exception): bool
    {
        return in_array($exception->getCode(), ['23000', '23505'], true);
    }
}
