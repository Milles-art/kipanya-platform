<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TshirtDesignController extends Controller
{
    public function create(Cartoon $cartoon): View
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);

        return view('pages.public.tshirt', [
            'cartoon' => $cartoon->load('category'),
            'design' => session('wear_design'),
        ]);
    }

    public function save(Request $request, Cartoon $cartoon): RedirectResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);

        $data = $request->validate([
            'color' => ['required', 'in:ink,white,sand'],
            'size' => ['required', 'in:S,M,L,XL,XXL'],
            'fit' => ['required', 'in:classic,oversized'],
            'placement' => ['required', 'in:center,left'],
        ]);

        session(['wear_design' => $data + [
            'cartoon_id' => $cartoon->id,
            'cartoon_title' => $cartoon->title,
        ]]);

        return redirect()->route('wear.design', $cartoon)->with('status', 'Your T-shirt design is ready for Kipanya Wear.');
    }
}
