@extends('admin.layout')
@section('title', 'All Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.projects.index') }}"
           class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
        <a href="{{ route('admin.projects.index', ['filter' => 'featured']) }}"
           class="btn btn-sm {{ request('filter') === 'featured' ? 'btn-warning' : 'btn-outline-warning' }}">
            <i class="bi bi-star-fill me-1"></i>Featured
        </a>
        <a href="{{ route('admin.projects.index', ['filter' => 'published']) }}"
           class="btn btn-sm {{ request('filter') === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published</a>
        <a href="{{ route('admin.projects.index', ['filter' => 'draft']) }}"
           class="btn btn-sm {{ request('filter') === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' }}">Draft</a>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Project
    </a>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Projects <span class="text-muted fw-400 small">({{ $projects->total() }})</span></span>
        <a href="{{ route('admin.projects.featured') }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-star-fill me-1"></i> Manage Featured
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-3" style="width:60px"></th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Stack</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($projects as $project)
            <tr>
                <td class="ps-3">
                    @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" style="width:48px;height:40px;object-fit:cover;border-radius:6px">
                    @else
                        <div style="width:48px;height:40px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div class="fw-500">{{ $project->title }}</div>
                    <div class="text-muted small">{{ $project->client_name }}</div>
                </td>
                <td><span class="badge bg-light text-dark">{{ ucfirst($project->category) }}</span></td>
                <td>
                    @foreach(array_slice($project->tech_stack ?? [], 0, 3) as $tech)
                        <span class="badge" style="background:#ede9fe;color:#6d28d9;font-size:0.7rem">{{ $tech }}</span>
                    @endforeach
                    @if(count($project->tech_stack ?? []) > 3)
                        <span class="text-muted small">+{{ count($project->tech_stack) - 3 }}</span>
                    @endif
                </td>
                <td>
                    <span class="badge rounded-pill {{ $project->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.projects.toggleFeatured', $project) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm border-0 p-0 bg-transparent"
                            title="{{ $project->featured ? 'Click to unfeature' : 'Click to feature' }}">
                            @if($project->featured)
                                <i class="bi bi-star-fill text-warning fs-5"></i>
                            @else
                                <i class="bi bi-star text-muted fs-5"></i>
                            @endif
                        </button>
                    </form>
                </td>
                <td class="text-muted small">{{ $project->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @if($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No projects found. <a href="{{ route('admin.projects.create') }}">Add one</a></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($projects->hasPages())
    <div class="card-footer">{{ $projects->links() }}</div>
    @endif
</div>
@endsection