<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\WearDesign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TshirtDesignController extends Controller
{
    public const COLORS = [
        'white' => ['label' => 'White', 'hex' => '#f8fafc', 'text' => '#0f172a'],
        'black' => ['label' => 'Black', 'hex' => '#1e293b', 'text' => '#f1f5f9'],
        'teal' => ['label' => 'Teal', 'hex' => '#0d9488', 'text' => '#f0fdfa'],
        'navy' => ['label' => 'Navy', 'hex' => '#1e3a5f', 'text' => '#f1f5f9'],
        'sand' => ['label' => 'Sand', 'hex' => '#d4b896', 'text' => '#422006'],
        'slate' => ['label' => 'Slate', 'hex' => '#475569', 'text' => '#f1f5f9'],
        'olive' => ['label' => 'Olive', 'hex' => '#4d5b3a', 'text' => '#f0fdf4'],
        'rust' => ['label' => 'Rust', 'hex' => '#9a3412', 'text' => '#fff7ed'],
    ];

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public const PLACEMENTS = [
        'front-center' => ['label' => 'Front Center', 'description' => 'Classic full-chest placement'],
        'front-pocket' => ['label' => 'Front Pocket', 'description' => 'Small, top-left placement'],
    ];

    public function create(Request $request, Cartoon $cartoon): View
    {
        abort_unless($cartoon->status === ContentStatus::Published && $cartoon->resolved_thumbnail_url, 404);

        $design = session('wear_designs.'.$cartoon->id) ?? session('wear_design');
        if ($request->user()) {
            $saved = WearDesign::query()->where('user_id', $request->user()->id)->where('cartoon_id', $cartoon->id)->latest()->first();
            if ($saved) {
                $design = [
                    'color' => $saved->color,
                    'size' => $saved->size,
                    'placement' => $saved->placement,
                    'configuration' => $saved->configuration,
                ];
            }
        }
        $queryDesign = array_filter([
            'color' => $request->query('color'),
            'size' => $request->query('size'),
            'placement' => $request->query('placement'),
        ], static fn ($value) => is_string($value) && $value !== '');
        if ($queryDesign) {
            $design = array_merge($design ?? [], $queryDesign);
        }

        return view('pages.public.tshirt', [
            'cartoon' => $cartoon->load('category'),
            'design' => $design,
            'colors' => self::COLORS,
            'sizes' => self::SIZES,
            'placements' => self::PLACEMENTS,
        ]);
    }

    public function state(Request $request, Cartoon $cartoon): JsonResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published && $cartoon->resolved_thumbnail_url, 404);

        $data = $request->validate([
            'color' => ['required', 'in:'.implode(',', array_keys(self::COLORS))],
            'size' => ['required', 'in:'.implode(',', self::SIZES)],
            'placement' => ['required', 'in:'.implode(',', array_keys(self::PLACEMENTS))],
            'scale' => ['nullable', 'numeric', 'min:.25', 'max:2'],
            'offset_x' => ['nullable', 'numeric', 'min:-100', 'max:100'],
            'offset_y' => ['nullable', 'numeric', 'min:-100', 'max:100'],
            'rotation' => ['nullable', 'numeric', 'min:-20', 'max:20'],
            'artwork_enabled' => ['required', 'boolean'],
        ]);

        $configuration = [
            'scale' => (float) ($data['scale'] ?? 1),
            'offset_x' => (float) ($data['offset_x'] ?? 0),
            'offset_y' => (float) ($data['offset_y'] ?? 0),
            'rotation' => (float) ($data['rotation'] ?? 0),
            'artwork_enabled' => (bool) $data['artwork_enabled'],
        ];

        $payload = [
            'cartoon_id' => $cartoon->id,
            'cartoon_title' => $cartoon->title,
            'cartoon_image' => $cartoon->resolved_thumbnail_url,
            'color' => $data['color'],
            'size' => $data['size'],
            'placement' => $data['placement'],
            'configuration' => $configuration,
        ];

        // Keep the legacy session key for compatibility, but also scope state by Cartoon.
        $request->session()->put('wear_design', $payload);
        $request->session()->put('wear_designs.'.$cartoon->id, $payload);

        if ($request->user()) {
            WearDesign::updateOrCreate(
                ['user_id' => $request->user()->id, 'cartoon_id' => $cartoon->id, 'status' => 'draft'],
                ['color' => $data['color'], 'size' => $data['size'], 'placement' => $data['placement'], 'configuration' => $configuration]
            );
        }

        return response()->json(['saved' => true, 'artwork_enabled' => $configuration['artwork_enabled']]);
    }

    public function save(Request $request, Cartoon $cartoon): RedirectResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published && $cartoon->resolved_thumbnail_url, 404);

        $data = $request->validate([
            'color' => ['required', 'in:'.implode(',', array_keys(self::COLORS))],
            'size' => ['required', 'in:'.implode(',', self::SIZES)],
            'placement' => ['required', 'in:'.implode(',', array_keys(self::PLACEMENTS))],
            'scale' => ['nullable', 'numeric', 'min:.25', 'max:2'],
            'offset_x' => ['nullable', 'numeric', 'min:-100', 'max:100'],
            'offset_y' => ['nullable', 'numeric', 'min:-100', 'max:100'],
            'rotation' => ['nullable', 'numeric', 'min:-20', 'max:20'],
            'artwork_enabled' => ['nullable', 'boolean'],
        ]);

        $configuration = [
            'scale' => (float) ($data['scale'] ?? 1),
            'offset_x' => (float) ($data['offset_x'] ?? 0),
            'offset_y' => (float) ($data['offset_y'] ?? 0),
            'rotation' => (float) ($data['rotation'] ?? 0),
            'artwork_enabled' => (bool) ($data['artwork_enabled'] ?? true),
        ];

        $payload = [
            'cartoon_id' => $cartoon->id,
            'cartoon_title' => $cartoon->title,
            'cartoon_image' => $cartoon->resolved_thumbnail_url,
            'color' => $data['color'],
            'size' => $data['size'],
            'placement' => $data['placement'],
            'configuration' => $configuration,
        ];

        session(['wear_design' => $payload, 'wear_designs.'.$cartoon->id => $payload]);

        if ($request->user()) {
            WearDesign::updateOrCreate(
                ['user_id' => $request->user()->id, 'cartoon_id' => $cartoon->id, 'status' => 'draft'],
                ['color' => $data['color'], 'size' => $data['size'], 'placement' => $data['placement'], 'configuration' => $configuration]
            );
        }

        return redirect()->route('wear.design', $cartoon)->with('design_saved', true)->with('status', 'Design saved. The selected Cartoon artwork is connected to this Wear design.');
    }
}
