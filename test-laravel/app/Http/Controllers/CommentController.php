<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CommentResource;
use App\Trait\MonitoringLong;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests, MonitoringLong;

    public function getCommentsByAuthor($authorId)
    {
        $start = microtime(true);
        try {
            $comments = Comment::with(['author', 'replies.author'])
                ->where('authors_id', $authorId)
                ->whereNull('parent_id')
                ->orderBy('created_at', 'desc')
                ->get();
            $this->logLongProcess("get comment error", $start);

            return response()->json([
                'success' => true,
                'data' => new CommentResource($comments)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data komentar'
            ], 500);
        }
    }
    public function postComment(Request $request)
    {
        $start = microtime(true);
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
                'authors_id' => 'required|string|exists:users,id',
                'users_id' => 'required|string|exists:users,id',
                'parent_id' => 'nullable|string'
            ]);

            $comment = Comment::create([
                'content' => $request->content,
                'authors_id' => $request->authors_id,
                'users_id' => $request->users_id,
                'parent_id' => $request->input('parent_id') ?? null,
            ]);
            $this->logLongProcess("post comment error", $start);

            return response()->json([
                'success' => true,
                'message' => 'Terkirim',
                'data' => $comment,
                'status_code' => 200
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
                'status_code' => 422
            ], 422);
        } catch (\Exception $e) {
            Log::error('Gagal memposting komentar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memposting komentar',
                'error' => $e->getMessage(),
                'status_code' => 500
            ], 500);
        }
    }

    public function deleteCommentById($id)
    {
        $start = microtime(true);
        try {
            $comment = Comment::findOrFail($id);

            $this->authorize('delete', $comment);

            $comment->delete();
            $this->logLongProcess("delete comment error", $start);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Komentar berhasil dihapus'
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus komentar',
                'error' => env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }

    public function editCommentById(Request $request, $id)
    {
        $start = microtime(true);
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment = Comment::findOrFail($id);
            $this->authorize('update', $comment);

            $comment->update([
                'content' => $validated['content'],
            ]);
            $this->logLongProcess("edit comment error", $start);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => 'Komentar berhasil diperbarui',
                    'comment' => $comment
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
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit komentar ini'
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui komentar',
                'error' => env('APP_DEBUG') ? $th->getMessage() : null
            ], 500);
        }
    }
}
