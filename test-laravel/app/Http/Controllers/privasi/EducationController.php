<?php

namespace App\Http\Controllers\privasi;

use Illuminate\Http\Request;
use App\Models\privasi\Education;
use App\Trait\MonitoringLong;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationController
{
    use AuthorizesRequests, MonitoringLong;

    public function postEducation(Request $request)
    {
        $start = microtime(true);
        try {
            $validationRequest = $request->validate([
                "graduation_years" => "required|string",
                "name_schools" => "required|string",
                "schools" => "required|in:SMA/SMK,PERGURUAN TINGGI,SMP,SD",
                "type_schools" => "required|in:SWASTA,NEGERI",
                "average_value" => "required|string",
                "diploma_date" => "required|string",
                "major" => "required|string",
                "users_id" => "required|string|max:36",
            ]);


            $checkDataExists = Education::where('users_id', $request->users_id)
                ->where('graduation_years', $request->graduation_years)
                ->where('name_schools', $request->name_schools)
                ->where('major', $request->major)
                ->where('diploma_date', $request->diploma_date)->first();

            if ($checkDataExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim data yang sudah ada',
                ], 409);
            }

            $educations = Education::create($validationRequest);
            $this->logLongProcess("get data education", $start);
            return response()->json([
                'success' => true,
                'message' => 'Data personal berhasil disimpan',
                'data' => $educations
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form pengalaman gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Form Education error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat melakukan post form experience',
                'error' => $e
            ], 500);
        }
    }
    public function getEducationById($id)
    {
        $start = microtime(true);
        try {
            $ducations = Education::where('diploma_date', $id)->get();
            $this->logLongProcess("get data education by id", $start);
            return response()->json([
                "success" => true,
                "message" => "Berhasil mendaptkan data pengalaman",
                "data" => $ducations
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                "success" => false,
                "message" => "Data tidak di temukan"
            ], 404);
        } catch (\Exception $e) {
            Log::error('get education by id error: ' . $e->getMessage(), [
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
    public function editEducationById(Request $request, $id)
    {
        $start = microtime(true);
        try {
            $request->validate([
                "graduation_years" => "required|string",
                "name_schools" => "required|string",
                "schools" => "required|in:SMA/SMK,PERGURUAN TINGGI,SMP,SD",
                "type_schools" => "required|in:SWASTA,NEGERI'",
                "average_value" => "required|string",
                "diploma_date" => "required|string",
                "major" => "required|string",
                "users_id" => "required|string|max:36",
            ]);

            $education = Education::findOrFail($id);
            $this->authorize('update', $education);

            $education->update([
                'graduation_years' =>  $request->graduation_years ?? $education->graduation_years,
                'name_schools' =>  $request->name_schools ?? $education->name_schools,
                'schools' =>  $request->schools ?? $education->schools,
                'type_schools' =>  $request->type_schools ?? $education->type_schools,
                'average_value' =>  $request->average_value ?? $education->average_value,
                'diploma_date' =>  $request->diploma_date ?? $education->diploma_date,
                'major' =>  $request->major ?? $education->major,
                'users_id' =>  $request->users_id ?? $education->users_id,
            ]);
            $this->logLongProcess("edit data education", $start);
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Komentar berhasil diperbarui',
                    'data' => $education
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
                'error' => $th->getMessage() //env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }
    public function deleteEducationById($id)
    {
        $start = microtime(true);
        try {
            $education = Education::findOrFail($id);

            $this->authorize('delete', $education);

            $education->delete();
            $this->logLongProcess("delete data education", $start);
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
}
