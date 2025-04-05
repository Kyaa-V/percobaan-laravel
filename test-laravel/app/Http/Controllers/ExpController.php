<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpController extends Controller
{

    use AuthorizesRequests;

    public function postExperience(Request $request)
    {
        try {
            $request->validate([
                'position' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'status' => 'required|in:Internship,Pegawai Kontrak,Pegawai Tetap',
                'your_skills' => 'required|string',
                'users_id' => 'required|string|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $checkDataExists = Experience::where('company', $request->company)
                ->where('location', $request->location)
                ->where('status', $request->status)
                ->where('start_date', $request->start_date)->first();

            if ($checkDataExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim data yang sudah ada',
                ], 409);
            }

            $exp = Experience::create([
                'position' => $request->position,
                'company' => $request->company,
                'location' => $request->location,
                'status' => $request->status,
                'your_skills' => $request->your_skills,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'users_id' => $request->users_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil Memasukkan data experience',
                'data' => $exp
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form pengalaman gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Form Experience error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat melakukan post form experience',
                'error' => $e
            ], 500);
        }
    }
    public function getExperience($id)
    {
        try {
            $exp = Experience::where('users_id', $id)->get();

            return response()->json([
                "success" => true,
                "message" => "Berhasil mendaptkan data pengalaman",
                "data" => $exp
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "Data tidak di temukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage(), [
                'user_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan saat mendapatkan data",
                "error" => $e
            ], 500);
        }
    }
    public function deleteExpById($id)
    {
        try {
            $exp = Experience::findOrFail($id);

            $this->authorize('delete', $exp);

            $exp->delete();

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Data berhasil dihapus'
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data ini'
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus Data',
                'error' => $th->getMessage() //env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }

    public function editExpById(Request $request, $id)
    {
        try {
            $request->validate([
                'position' => 'string|max:255',
                'company' => 'string|max:255',
                'location' => 'string|max:255',
                'status' => 'in:Internship,Pegawai Kontrak,Pegawai Tetap',
                'your_skills' => 'string',
                'users_id' => 'string|exists:users,id',
                'start_date' => 'date',
                'end_date' => 'date|after_or_equal:start_date',
           ]);

            $exp = Experience::findOrFail($id);
            $this->authorize('update', $exp);

            $exp->update([
                'position' =>  $request->position ?? $exp->position,
                'company' =>  $request->company ?? $exp->company,
                'location' =>  $request->location ?? $exp->location,
                'status' =>  $request->status ?? $exp->status,
                'your_skills' =>  $request->your_skills ?? $exp->your_skills,
                'users_id' =>  $request->users_id ?? $exp->users_id,
                'start_date' =>  $request->start_date ?? $exp->start_date,
                'end_date' =>  $request->end_date ?? $exp->end_date,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Komentar berhasil diperbarui',
                    'data' => $exp
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit Data ini'
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui Data',
                'error' => $th->getMessage()//env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }
}
