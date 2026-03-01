@extends('admin.layout')
@section('title', 'Featured Projects')

@push('styles')
<style>
.featured-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    height: 100%;
}
.featured-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.featured-card .badge-featured {
    position: absolute;
    top: 12px; left: 12px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 8px rgba(245,158,11,0.4);
}
.featured-card .card-thumb {
    width: 100%; height: 200px;
    object-fit: cover; display: block;
}
.featured-card .no-thumb {
    width: 100%; height: 200px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 8px; color: #9ca3af;
}
.featured-card .card-body { padding: 1rem 1.1rem 1.1rem; }
.featured-card .tech-tag {
    display: inline-block;
    background: #ede9fe; color: #6d28d9;
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 8px; border-radius: 20px;
    margin: 2px 2px 2px 0;
}
.featured-card .unfeature-btn {
    background: none;
    border: 1.5px solid #fbbf24; color: #d97706;
    font-size: 0.78rem; font-weight: 600;
    padding: 5px 12px; border-radius: 8px;
    cursor: pointer; transition: all 0.2s;
    display: flex; align-items: center; gap: 5px;
}
.featured-card .unfeature-btn:hover { background: #fef3c7; border-color: #d97706; }
.available-card {
    background: #fff; border-radius: 12px;
    padding: 0.85rem 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    display: flex; align-items: center; gap: 0.75rem;
    margin-bottom: 0.6rem; transition: box-shadow 0.15s;
}
.available-card:hover { box-shadow: 0 3px 12px rgba(0,0,0,0.1); }
.available-card img { width: 52px; height: 44px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
.available-card .no-img {
    width: 52px; height: 44px; background: #f3f4f6;
    border-radius: 8px; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0; color: #9ca3af;
}
.feature-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none; color: #fff;
    font-size: 0.75rem; font-weight: 600;
    padding: 5px 12px; border-radius: 8px;
    cursor: pointer; white-space: nowrap;
    transition: opacity 0.2s;
    display: flex; align-items: center; gap: 4px;
}
.feature-btn:hover { opacity: 0.88; }
.empty-state { text-align: center; padding: 3rem 2rem; color: #9ca3af; }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #fbbf24; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0 small">Manage which projects are highlighted on your portfolio. Click ⭐ to feature or unfeature.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-collection me-1"></i> All Projects
        </a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Project
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- LEFT: Currently Featured --}}
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-700">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Featured Projects
                <span class="badge rounded-pill ms-2" style="background:#fef3c7;color:#92400e;font-size:0.72rem">
                    {{ $featured->count() }} / {{ $maxFeatured }} shown
                </span>
            </h6>
        </div>

        @if($featured->count() > 0)
        <div class="row g-3">
            @foreach($featured as $project)
            <div class="col-sm-6 col-md-4">
                <div class="featured-card">
                    <span class="badge-featured"><i class="bi bi-star-fill"></i> Featured</span>
                    @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" class="card-thumb" alt="{{ $project->title }}">
                    @else
                        <div class="no-thumb">
                            <i class="bi bi-image fs-2"></i>
                            <span class="small">No image</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="fw-600 mb-1" style="font-size:0.9rem;line-height:1.3">{{ $project->title }}</div>
                        <div class="text-muted mb-2" style="font-size:0.75rem">{{ $project->client_name ?? '—' }} · {{ ucfirst($project->category) }}</div>
                        <div class="mb-3">
                            @foreach(array_slice($project->tech_stack ?? [], 0, 4) as $tech)
                                <span class="tech-tag">{{ $tech }}</span>
                            @endforeach
                            @if(count($project->tech_stack ?? []) > 4)
                                <span class="tech-tag" style="background:#f3f4f6;color:#6b7280">+{{ count($project->tech_stack) - 4 }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <form method="POST" action="{{ route('admin.projects.toggleFeatured', $project) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="unfeature-btn">
                                    <i class="bi bi-star"></i> Unfeature
                                </button>
                            </form>
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem;padding:5px 10px">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-auto" style="font-size:0.75rem;padding:5px 10px">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        <div class="empty-state card">
            <i class="bi bi-star"></i>
            <h6 class="text-muted mb-2">No featured projects yet</h6>
            <p class="small text-muted mb-0">Click <strong>"Feature"</strong> on any project from the right panel.</p>
        </div>
        @endif

        <div class="mt-4 p-3 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-code-slash text-success mt-1"></i>
                <div>
                    <div class="fw-600 small text-success mb-1">Frontend API Endpoint</div>
                    <code class="small" style="background:#dcfce7;padding:3px 8px;border-radius:6px;color:#166534">
                        GET /api/v1/projects/featured
                    </code>
                    <div class="text-muted mt-1" style="font-size:0.75rem">Use this to fetch featured projects in your frontend.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Available to Feature --}}
    <div class="col-lg-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-700">
                <i class="bi bi-collection text-muted me-2"></i>
                Available Projects
            </h6>
            <span class="badge bg-light text-dark">{{ $available->count() }}</span>
        </div>

        @if($available->count() > 0)
        <div style="max-height:600px;overflow-y:auto;padding-right:2px">
            @foreach($available as $project)
            <div class="available-card">
                @if($project->thumbnail_url)
                    <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}">
                @else
                    <div class="no-img"><i class="bi bi-image"></i></div>
                @endif
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-600 text-truncate" style="font-size:0.85rem">{{ $project->title }}</div>
                    <div class="text-muted" style="font-size:0.72rem">{{ ucfirst($project->category) }}
                        @if($project->status === 'draft')
                            · <span class="text-warning">Draft</span>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.projects.toggleFeatured', $project) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="feature-btn">
                        <i class="bi bi-star-fill"></i> Feature
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-check-circle fs-2 text-success mb-2 d-block"></i>
            <div class="small">All projects are already featured!</div>
        </div>
        @endif

        <div class="mt-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a">
            <div class="fw-600 small mb-2" style="color:#92400e">
                <i class="bi bi-info-circle me-1"></i> Stats
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Featured</span>
                <span class="fw-600">{{ $featured->count() }}</span>
            </div>
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Published</span>
                <span class="fw-600">{{ $totalPublished }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span class="text-muted">Total Projects</span>
                <span class="fw-600">{{ $totalAll }}</span>
            </div>
        </div>
    </div>

</div>
@endsection