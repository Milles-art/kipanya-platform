<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\CartoonComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartoonSocialController extends Controller
{
    public function like(Request $request, Cartoon $cartoon): JsonResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);
        $like = $request->user()->cartoonLikes()->where('cartoon_id', $cartoon->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $request->user()->cartoonLikes()->create(['cartoon_id' => $cartoon->id]);
            $liked = true;
        }
        return response()->json(['ok' => true, 'liked' => $liked, 'likes_count' => $cartoon->likes()->count()]);
    }

    public function comment(Request $request, Cartoon $cartoon): JsonResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);
        $data = $request->validate(['body' => ['required', 'string', 'min:1', 'max:2000'], 'parent_id' => ['nullable', 'integer']]);
        if (!empty($data['parent_id'])) {
            abort_unless(CartoonComment::whereKey($data['parent_id'])->where('cartoon_id', $cartoon->id)->exists(), 422);
        }
        $comment = $request->user()->cartoonComments()->create(['cartoon_id' => $cartoon->id, 'parent_id' => $data['parent_id'] ?? null, 'body' => trim($data['body'])]);
        $comment->load('user');
        return response()->json(['ok' => true, 'comment_count' => $cartoon->comments()->count(), 'comment' => ['id' => $comment->id, 'body' => e($comment->body), 'user' => $comment->user->name, 'created_at' => $comment->created_at->diffForHumans(), 'initials' => collect(preg_split('/\s+/', trim($comment->user->name)))->filter()->map(fn($v)=>mb_strtoupper(mb_substr($v,0,1)))->take(2)->implode('')]]);
    }

    public function share(Request $request, Cartoon $cartoon): JsonResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);
        $cartoon->increment('shares_count');
        return response()->json(['ok' => true, 'shares_count' => $cartoon->fresh()->shares_count]);
    }
}
