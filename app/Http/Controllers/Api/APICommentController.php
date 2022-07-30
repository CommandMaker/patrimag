<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class APICommentController extends Controller
{
    public function getComments (Request $request, int $id)
    {
        $page = $request->page;

        if (!$page) {
            $page = 1;
        }

        if (!Article::find($id)) {
            return response()->json([
                'status' => 404,
                'msg' => 'Aucun article ne correspond à cet id !'
            ], 404);
        }

        $comments = Comment::with('author')->whereArticleId($id)->paginate(30);

        return response()->json([
            'status' => 200,
            'total' => $comments->total(),
            'lastPage' => $comments->lastPage(),
            'page' => $comments->currentPage(),
            'data' => $comments->items()
        ]);
    }
}
