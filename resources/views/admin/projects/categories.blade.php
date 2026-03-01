@extends('admin.layout')
@section('title', 'Project Categories')

@push('styles')
<style>
.cat-tab {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.7rem 1.1rem;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid transparent;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    transition: all 0.18s;
    text-decoration: none;
    color: #374151;
    width: 100%;
}
.cat-tab:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    color: #111;
    transform: translateX(3px);
}
.cat-tab.active {
    border-color: currentColor;
    background: #fafafa;
    font-weight: 600;
}
.cat-tab .cat-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.cat-tab .cat-count {
    margin-left: auto;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    background: rgba(0,0,0,0.06);
}
.proj-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    transition: transform 0.18s, box-shadow 0.18s;
    height: 100%;
    position: relative;
}
.proj-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
.proj-card .thumb {
    width: 100%; height: 160px;
    object-fit: cover; display: block;
}
.proj-card .no-thumb {
    width: 100%; height: 160px;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 6px;
    font-size: 0.8rem; color: #9ca3af;
}
.proj-card .body { padding: 0.9rem 1rem 1rem; }
.proj-card .tech-tag {
    display: inline-block;
    background: #ede9fe; color: #6d28d9;
    font-size: 0.65rem; font-weight: 600;
    padding: 2px 7px; border-radius: 20px;
    margin: 1px;
}
.featured-star {
    position: absolute; top: 10px; right: 10px;
    background: rgba(0,0,0,0.45);
    border-radius: 50%;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
}
.cat-header-bar {
    border-radius: 12px;
    padding: 1.1rem 1.3rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.empty-cat {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0 small">Browse and manage all projects grouped by category.</p>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Project
    </a>
</div>

<div class="row g-4">

    {{-- LEFT: Category Sidebar --}}
    <div class="col-lg-3">
        <div class="mb-2 px-1" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;font-weight:600">Categories</div>

        {{-- All --}}
        <a href="{{ route('admin.projects.categories') }}"
           class="cat-tab mb-2 {{ !$activeCategory ? 'active' : '' }}"
           style="{{ !$activeCategory ? 'color:#302b63;border-color:#302b63' : '' }}">
            <div class="cat-icon" style="background:#ede9fe;color:#6d28d9">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <span>All Projects</span>
            <span class="cat-count">{{ $categoryCounts->sum('count') }}</span>
        </a>

        @foreach($categories as $cat)
        <a href="{{ route('admin.projects.categories', ['category' => $cat['slug']]) }}"
           class="cat-tab mb-2 {{ $activeCategory === $cat['slug'] ? 'active' : '' }}"
           style="{{ $activeCategory === $cat['slug'] ? "color:{$cat['color']};border-color:{$cat['color']}" : '' }}">
            <div class="cat-icon" style="background:{{ $cat['bg'] }};color:{{ $cat['color'] }}">
                <i class="bi {{ $cat['icon'] }}"></i>
            </div>
            <span>{{ $cat['label'] }}</span>
            <span class="cat-count">
                {{ $categoryCounts->where('category', $cat['slug'])->first()->count ?? 0 }}
            </span>
        </a>
        @endforeach

        <div class="mt-3 pt-3" style="border-top:1px solid #f0f0f0">
            <a href="{{ route('admin.projects.featured') }}" class="cat-tab" style="color:#d97706">
                <div class="cat-icon" style="background:#fef3c7;color:#d97706">
                    <i class="bi bi-star-fill"></i>
                </div>
                <span>Featured</span>
                <span class="cat-count">{{ $categoryCounts->sum('featured_count') }}</span>
            </a>
        </div>
    </div>

    {{-- RIGHT: Projects Grid --}}
    <div class="col-lg-9">

        {{-- Category header bar --}}
        @if($activeCategory)
            @php $activeMeta = collect($categories)->firstWhere('slug', $activeCategory); @endphp
            <div class="cat-header-bar" style="background:{{ $activeMeta['bg'] }};border:1px solid {{ $activeMeta['color'] }}22">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:{{ $activeMeta['color'] }}22;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:{{ $activeMeta['color'] }}">
                        <i class="bi {{ $activeMeta['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="color:{{ $activeMeta['color'] }};font-size:1.05rem">{{ $activeMeta['label'] }}</div>
                        <div class="small text-muted">{{ $projects->total() }} project{{ $projects->total() !== 1 ? 's' : '' }} in this category</div>
                    </div>
                </div>
                <a href="{{ route('admin.projects.create') }}" class="btn btn-sm" style="background:{{ $activeMeta['color'] }};color:#fff;border:none">
                    <i class="bi bi-plus-lg me-1"></i> Add {{ $activeMeta['label'] }}
                </a>
            </div>
        @else
            <div class="cat-header-bar" style="background:#f8f7ff;border:1px solid #e9d5ff">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#ede9fe;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#7c3aed">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="color:#302b63;font-size:1.05rem">All Projects</div>
                        <div class="small text-muted">{{ $projects->total() }} total project{{ $projects->total() !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($categories as $cat)
                    <a href="{{ route('admin.projects.categories', ['category' => $cat['slug']]) }}"
                       class="btn btn-sm"
                       style="background:{{ $cat['bg'] }};color:{{ $cat['color'] }};border:none;font-size:0.72rem">
                        <i class="bi {{ $cat['icon'] }} me-1"></i>{{ $cat['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Projects grid --}}
        @if($projects->count() > 0)
        <div class="row g-3">
            @foreach($projects as $project)
            <div class="col-sm-6 col-md-4">
                <div class="proj-card">
                    @if($project->featured)
                    <div class="featured-star"><i class="bi bi-star-fill text-warning"></i></div>
                    @endif

                    @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" class="thumb" alt="{{ $project->title }}">
                    @else
                        @php $meta = collect($categories)->firstWhere('slug', $project->category); @endphp
                        <div class="no-thumb" style="background:{{ $meta['bg'] ?? '#f3f4f6' }}">
                            <i class="bi {{ $meta['icon'] ?? 'bi-image' }}" style="font-size:2rem;color:{{ $meta['color'] ?? '#9ca3af' }}"></i>
                            <span>No image</span>
                        </div>
                    @endif

                    <div class="body">
                        <div class="d-flex align-items-start justify-content-between gap-1 mb-1">
                            <div class="fw-600" style="font-size:0.88rem;line-height:1.35">{{ $project->title }}</div>
                            <span class="badge rounded-pill flex-shrink-0 {{ $project->status === 'published' ? 'badge-success' : 'badge-warning' }}" style="font-size:0.62rem">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>

                        @if($project->client_name)
                        <div class="text-muted mb-2" style="font-size:0.73rem">
                            <i class="bi bi-building me-1"></i>{{ $project->client_name }}
                        </div>
                        @endif

                        <div class="mb-3">
                            @foreach(array_slice($project->tech_stack ?? [], 0, 3) as $tech)
                                <span class="tech-tag">{{ $tech }}</span>
                            @endforeach
                            @if(count($project->tech_stack ?? []) > 3)
                                <span class="tech-tag" style="background:#f3f4f6;color:#6b7280">+{{ count($project->tech_stack) - 3 }}</span>
                            @endif
                        </div>

                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary flex-grow-1" style="font-size:0.75rem">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.projects.toggleFeatured', $project) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn btn-sm {{ $project->featured ? 'btn-warning' : 'btn-outline-secondary' }}"
                                    style="font-size:0.75rem"
                                    title="{{ $project->featured ? 'Unfeature' : 'Feature' }}">
                                    <i class="bi bi-star{{ $project->featured ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                            @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($projects->hasPages())
        <div class="mt-3">{{ $projects->appends(request()->query())->links() }}</div>
        @endif

        @else
        <div class="empty-cat">
            @php $meta = collect($categories)->firstWhere('slug', $activeCategory); @endphp
            <i class="bi {{ $meta['icon'] ?? 'bi-folder' }}" style="font-size:3rem;color:{{ $meta['color'] ?? '#9ca3af' }};display:block;margin-bottom:1rem"></i>
            <h6 class="text-muted mb-2">No {{ $meta['label'] ?? '' }} projects yet</h6>
            <p class="small text-muted mb-3">Start adding projects to this category.</p>
            <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Add First Project
            </a>
        </div>
        @endif

    </div>
</div>
@endsection