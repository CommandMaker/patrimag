<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class APICommentController extends Controller
{
    /**
     * Get paginated comments from the database
     *
     * @param  int  $id The id of the article
     * @param  Request  $request The app request
     */
    public function getComments(Request $request, int $id): JsonResponse
    {
        $page = $request->page;
        $orderby = $request->orderby;

        if (!$orderby || (strtolower($orderby) !== 'asc' && strtolower($orderby) !== 'desc')) {
            $orderby = 'desc';
        }

        if (!$page) {
            $page = 1;
        }

        if (!Article::find($id)) {
            return response()->json([
                'status' => 404,
                'msg' => 'Aucun article ne correspond à cet id !',
            ], 404);
        }

        $comments = Comment::orderBy('id', $orderby)
            ->whereNull('parent')
            ->whereArticleId($id)
            ->paginate(30);

        return response()->json([
            'status' => 200,
            'total' => $comments->total(),
            'lastPage' => $comments->lastPage(),
            'page' => $comments->currentPage(),
            'data' => $comments->items(),
        ]);
    }

    /**
     * Add a comment to the database
     *
     * @param  Request  $request
     * @param  int  $id The id of the article
     * @return JsonResponse
     */
    public function addComment(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'msg' => 'Vous devez être connecté pour publier un commentaire',
            ], 401);
        }

        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => 500,
                'msg' => 'Aucun article ne correspond à cet identifiant',
            ], 500);
        }

        $validator = Validator::make($request->only(['comment_content', 'parent']), [
            'comment_content' => 'required|string|min:1',
            'parent' => 'nullable|integer|exists:comments,id',
        ], [
            'parent.exists' => 'Aucun article ne correspond à cet identifiant parent',
            'comment_content.required' => 'Il est nécessaire de spécifier un contenu pour le commentaire',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 500,
                'msg' => 'Fields validation error',
                'errors' => $validator->errors()->all(),
            ], 500);
        }

        $parent = $request->post('parent');

        $comment = Comment::create([
            'content' => nl2br($request->comment_content),
            'article_id' => $id,
            'author_id' => $user->id,
            'parent' => $parent && Comment::find($parent) ? $parent : null,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $comment->load('author', 'replies'),
        ], 201);
    }

    /**
     * Delete a comment from the database (DELETE method)
     *
     * @param  Request  $request
     * @param  int  $id The id of the comment
     */
    public function deleteComment(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'msg' => 'Vous devez être connecté pour supprimer un commentaire',
            ], 401);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 500,
                'msg' => 'Aucun commentaire ne correspond à cet identifiant',
            ], 500);
        }

        if ($comment->author_id !== $user->id) {
            return response()->json([
                'status' => 403,
                'msg' => 'Vous n\'êtes pas l\'auteur de ce commentaire',
            ], 403);
        }

        $comment->replies()->delete();
        $comment->delete();

        return response()->json([
            'status' => 200,
            'msg' => 'Comment deleted',
            'data' => [
                'id' => $id,
            ],
        ]);
    }
}
