<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /** Category definitions — single source of truth */
    private function categoryMeta(): array
    {
        return [
            ['slug' => 'web',       'label' => 'Web',        'icon' => 'bi-globe2',           'color' => '#2563eb', 'bg' => '#eff6ff'],
            ['slug' => 'mobile',    'label' => 'Mobile',     'icon' => 'bi-phone-fill',        'color' => '#16a34a', 'bg' => '#f0fdf4'],
            ['slug' => 'design',    'label' => 'Design',     'icon' => 'bi-palette-fill',      'color' => '#db2777', 'bg' => '#fdf2f8'],
            ['slug' => 'ecommerce', 'label' => 'E-Commerce', 'icon' => 'bi-cart-fill',         'color' => '#d97706', 'bg' => '#fffbeb'],
            ['slug' => 'saas',      'label' => 'SaaS',       'icon' => 'bi-cloud-fill',        'color' => '#7c3aed', 'bg' => '#f5f3ff'],
            ['slug' => 'other',     'label' => 'Other',      'icon' => 'bi-three-dots-circle', 'color' => '#475569', 'bg' => '#f8fafc'],
        ];
    }

    public function index(Request $request)
    {
        $query = Project::orderBy('sort_order')->orderBy('created_at', 'desc');

        match($request->filter) {
            'featured'  => $query->where('featured', true),
            'published' => $query->where('status', 'published'),
            'draft'     => $query->where('status', 'draft'),
            default     => null,
        };

        $projects = $query->paginate(15)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /** Dedicated categories browse page */
    public function categoriesPage(Request $request)
    {
        $activeCategory = $request->category;
        $categories     = $this->categoryMeta();

        $query = Project::orderBy('sort_order')->orderBy('created_at', 'desc');
        if ($activeCategory) {
            $query->where('category', $activeCategory);
        }
        $projects = $query->paginate(12)->withQueryString();

        $categoryCounts = Project::selectRaw('category, COUNT(*) as count, SUM(featured) as featured_count')
            ->groupBy('category')
            ->get();

        return view('admin.projects.categories', compact(
            'projects', 'categories', 'activeCategory', 'categoryCounts'
        ));
    }

    /** Dedicated featured projects management page */
    public function featuredPage()
    {
        $featured  = Project::where('featured', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        $available = Project::where('featured', false)->orderBy('status')->orderBy('title')->get();

        return view('admin.projects.featured', [
            'featured'       => $featured,
            'available'      => $available,
            'maxFeatured'    => 6,
            'totalPublished' => Project::where('status', 'published')->count(),
            'totalAll'       => Project::count(),
        ]);
    }

    /** Toggle featured on/off with a single PATCH */
    public function toggleFeatured(Project $project)
    {
        $project->update(['featured' => !$project->featured]);

        $msg = $project->fresh()->featured
            ? "⭐ \"{$project->title}\" is now featured!"
            : "\"{$project->title}\" removed from featured.";

        return back()->with('success', $msg);
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'tech_stack'  => 'nullable|string',
            'demo_url'    => 'nullable|url',
            'github_url'  => 'nullable|url',
            'client_name' => 'nullable|string|max:150',
            'category'    => 'required|in:web,mobile,design,ecommerce,saas,other',
            'status'      => 'required|in:draft,published',
            'featured'    => 'boolean',
            'thumbnail'   => 'nullable|image|max:4096',
            'images.*'    => 'nullable|image|max:4096',
        ]);

        if (!empty($data['tech_stack'])) {
            $data['tech_stack'] = array_map('trim', explode(',', $data['tech_stack']));
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('projects/images', 'public');
            }
            $data['images'] = $paths;
        }

        $data['slug']     = Str::slug($data['title']);
        $data['featured'] = $request->boolean('featured');

        $project = Project::create($data);

        return redirect()->route('admin.projects.index')
            ->with('success', "Project \"{$project->title}\" created successfully!");
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'tech_stack'  => 'nullable|string',
            'demo_url'    => 'nullable|url',
            'github_url'  => 'nullable|url',
            'client_name' => 'nullable|string|max:150',
            'category'    => 'required|in:web,mobile,design,ecommerce,saas,other',
            'status'      => 'required|in:draft,published',
            'featured'    => 'boolean',
            'thumbnail'   => 'nullable|image|max:4096',
            'images.*'    => 'nullable|image|max:4096',
        ]);

        if (!empty($data['tech_stack'])) {
            $data['tech_stack'] = array_map('trim', explode(',', $data['tech_stack']));
        }

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) Storage::disk('public')->delete($project->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            foreach ($project->images ?? [] as $old) {
                Storage::disk('public')->delete($old);
            }
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('projects/images', 'public');
            }
            $data['images'] = $paths;
        }

        $data['slug']     = Str::slug($data['title']);
        $data['featured'] = $request->boolean('featured');

        $project->update($data);

        return redirect()->route('admin.projects.index')
            ->with('success', "Project updated successfully!");
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) Storage::disk('public')->delete($project->thumbnail);
        foreach ($project->images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted.');
    }
}