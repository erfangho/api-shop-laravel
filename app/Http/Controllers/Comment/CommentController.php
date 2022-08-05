<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $comments = Comment::all();

        return response()->json($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request, $id)
    {
        try {
            $current_user = Auth::user();

            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->product_id = (int)$id;
            $comment->user_id = $current_user['id'];
            $comment->save();

            return response()->json($comment, ResponseAlias::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return JsonResponse
     */
    public function show($id)
    {
        $comments = Comment::where('product_id', $id)->get();

        return response()->json($comments, ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return JsonResponse
     */
    public function destroy($product_id, $comment_id)
    {
        try {
            $current_user = Auth::user();
            if ($current_user['id'] == Comment::find($comment_id)['user_id']) {
                $comment = Comment::find($comment_id);
                $comment->delete();

                return response()->json(['message' => 'Comment deleted'], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'You are not authorized to delete this comment'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
