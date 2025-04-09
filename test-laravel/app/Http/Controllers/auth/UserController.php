<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{

    use AuthorizesRequests;
    
    public function updatePaswordUser(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|min:6|max:100',
            ]);

            $user = User::findOrFail($id);

            $user->update([
                "password" => Hash::make($request->password),
            ]);

            return response()->json([
                "success" => true,
                "message" => "Password berhasil diupdate",
                "data" => new UserResource($user)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => "Validasi gagal",
                "errors" => $e->errors()
            ], 422);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "User tidak ditemukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Update user error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengupdate user"
            ], 500);
        }
    }

    public function get()
    {
        try {
            $users = User::with('role')->paginate(15);

            return response()->json([
                "success" => true,
                "amount_users" => $users->total(),
                "data" => [
                    "message" => "Berhasil get data user",
                    "users" => new UserResourceCollection($users)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get users error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data user",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $this->authorize('delete',$user);
            $user->delete();

            return response()->json([
                "success" => true,
                "message" => "Berhasil menghapus user"
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "User tidak ditemukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat menghapus user"
            ], 500);
        }
    }

    /**
     * Get user by ID
     */
    public function getById($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                "success" => true,
                "data" => new UserResource($user, "User dengan nama: {$user->name} ditemukan")
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "User tidak ditemukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Get user by ID error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mengambil data user"
            ], 500);
        }
    }
}
