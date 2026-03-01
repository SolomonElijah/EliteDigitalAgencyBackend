<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    /**
     * GET /api/v1/projects/featured
     */
    public function featured()
    {
        $projects = Project::published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => $this->formatProject($p));

        return response()->json([
            'success' => true,
            'data'    => $projects,
            'meta'    => ['total' => $projects->count()],
        ]);
    }

    /**
     * GET /api/v1/projects
     */
    public function index(Request $request)
    {
        $query = Project::published()->orderBy('sort_order')->orderBy('created_at', 'desc');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->featured) {
            $query->featured();
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('client_name', 'like', "%{$request->search}%");
            });
        }

        $projects = $query->paginate($request->per_page ?? 12);

        return response()->json([
            'success' => true,
            'data'    => $projects->map(fn($p) => $this->formatProject($p)),
            'meta'    => [
                'current_page' => $projects->currentPage(),
                'last_page'    => $projects->lastPage(),
                'total'        => $projects->total(),
                'per_page'     => $projects->perPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/projects/{slug}
     */
    public function show($slug)
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatProject($project, true),
        ]);
    }

    /**
     * GET /api/v1/projects/categories
     */
    public function categories()
    {
        $cats = Project::published()
            ->select('category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->get();

        return response()->json(['success' => true, 'data' => $cats]);
    }

    private function formatProject(Project $p, bool $full = false): array
    {
        $data = [
            'id'          => $p->id,
            'title'       => $p->title,
            'slug'        => $p->slug,
            'description' => $full ? $p->description : substr($p->description, 0, 200) . '...',
            'thumbnail'   => $p->thumbnail_url,
            'tech_stack'  => $p->tech_stack ?? [],
            'demo_url'    => $p->demo_url,
            'github_url'  => $p->github_url,
            'client_name' => $p->client_name,
            'category'    => $p->category,
            'featured'    => $p->featured,
            'created_at'  => $p->created_at->toDateString(),
        ];

        if ($full) {
            $data['images'] = $p->images_url;
        }

        return $data;
    }
}