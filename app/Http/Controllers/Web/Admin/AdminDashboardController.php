<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\CartoonEpisode;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $cartoons = Cartoon::count();
        $episodes = CartoonEpisode::count();
        $published = Cartoon::where('status', 'published')->count();
        $users = User::count();
        $content = $cartoons + $episodes;
        $databaseOnline = true;
        try { DB::connection()->getPdo(); } catch (\Throwable) { $databaseOnline = false; }

        $cartoonStats = [
            ['label' => 'Cartoons', 'value' => $cartoons],
            ['label' => 'Episodes', 'value' => $episodes],
            ['label' => 'Collections', 'value' => Collection::count()],
        ];

        return view('admin.dashboard', [
            'adminName' => auth()->user()?->name ?? 'Super Admin',
            'metrics' => [
                ['label' => 'Total Applications', 'value' => '5', 'note' => 'Active modules', 'icon' => 'layers', 'tone' => 'purple'],
                ['label' => 'Total Users', 'value' => number_format($users), 'note' => 'Across all apps', 'icon' => 'users', 'tone' => 'green'],
                ['label' => 'Total Content', 'value' => number_format($content), 'note' => 'Items in platform', 'icon' => 'file', 'tone' => 'blue'],
                ['label' => 'Total Orders', 'value' => '—', 'note' => 'Commerce module pending', 'icon' => 'cart', 'tone' => 'orange'],
            ],
            'apps' => [
                ['name' => 'Cartoon Archive', 'description' => 'Manage cartoons, categories and collections.', 'icon' => 'film', 'status' => 'active', 'accent' => 'purple', 'stats' => $cartoonStats, 'stat_labels' => [], 'route' => route('admin.cartoon.dashboard'), 'action' => 'Open Studio'],
                ['name' => 'Kipanya Wear', 'description' => 'Manage products, inventory, orders and customers.', 'icon' => 'bag', 'status' => 'planned', 'accent' => 'green', 'stats' => [], 'stat_labels' => ['Products','Orders','Customers'], 'route' => null, 'action' => 'Open Admin'],
                ['name' => 'Kipanya Book', 'description' => 'Manage books, authors, inventory and orders.', 'icon' => 'book', 'status' => 'planned', 'accent' => 'blue', 'stats' => [], 'stat_labels' => ['Books','Orders','Customers'], 'route' => null, 'action' => 'Open Admin'],
                ['name' => 'Kaypee Motors', 'description' => 'Manage vehicles, listings, inquiries and leads.', 'icon' => 'car', 'status' => 'planned', 'accent' => 'orange', 'stats' => [], 'stat_labels' => ['Vehicles','Leads','Inquiries'], 'route' => null, 'action' => 'Open Admin'],
                ['name' => 'Kipanya TV', 'description' => 'Manage TV shows, videos, channels and schedules.', 'icon' => 'tv', 'status' => 'planned', 'accent' => 'pink', 'stats' => [], 'stat_labels' => ['Videos','Channels','Shows'], 'route' => null, 'action' => 'Open Admin'],
            ],
            'recent' => ActivityLog::with('user')->latest()->take(6)->get(),
            'systemStatus' => [
                ['label' => 'Web Server', 'value' => 'Online', 'tone' => 'good'],
                ['label' => 'Database', 'value' => $databaseOnline ? 'Online' : 'Offline', 'tone' => $databaseOnline ? 'good' : 'bad'],
                ['label' => 'Storage', 'value' => Storage::disk('public')->exists('.gitignore') || is_writable(storage_path('app')) ? 'Healthy' : 'Check', 'tone' => 'good'],
                ['label' => 'Queue', 'value' => 'Configured', 'tone' => 'good'],
                ['label' => 'Backup', 'value' => 'Not connected', 'tone' => 'neutral'],
            ],
        ]);
    }
}
