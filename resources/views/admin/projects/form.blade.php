@extends('admin.layout')
@section('title', $project->exists ? 'Edit Project' : 'Add Project')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data">
    @csrf
    @if($project->exists) @method('PUT') @endif

    <div class="card mb-3">
        <div class="card-header">Project Details</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-500">Project Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $project->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500">Category *</label>
                    <select name="category" class="form-select">
                        @foreach(['web','mobile','design','ecommerce','saas','other'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $project->category) == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Description *</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                        required>{{ old('description', $project->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Tech Stack <small class="text-muted">(comma-separated)</small></label>
                    <input type="text" name="tech_stack" class="form-control"
                        value="{{ old('tech_stack', is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '') }}"
                        placeholder="Laravel, Vue.js, MySQL, Tailwind">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Client Name</label>
                    <input type="text" name="client_name" class="form-control"
                        value="{{ old('client_name', $project->client_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Demo URL</label>
                    <input type="url" name="demo_url" class="form-control"
                        value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">GitHub URL</label>
                    <input type="url" name="github_url" class="form-control"
                        value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/...">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Images</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Thumbnail Image</label>
                    @if($project->thumbnail_url)
                        <div class="mb-2">
                            <img src="{{ $project->thumbnail_url }}" style="height:100px;border-radius:8px;object-fit:cover">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <div class="form-text">Max 4MB. JPG, PNG, WebP.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Additional Images</label>
                    @if($project->images_url && count($project->images_url))
                        <div class="d-flex gap-1 mb-2 flex-wrap">
                            @foreach($project->images_url as $img)
                                <img src="{{ $img }}" style="height:60px;width:80px;object-fit:cover;border-radius:6px">
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <div class="form-text">Select multiple files. Replaces existing images.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Settings</div>
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-500">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" {{ old('status', $project->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $project->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured"
                            {{ old('featured', $project->featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-500" for="featured">
                            <i class="bi bi-star-fill text-warning me-1"></i> Featured Project
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-lg me-1"></i>
            {{ $project->exists ? 'Update Project' : 'Create Project' }}
        </button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
</div>
</div>
@endsection
