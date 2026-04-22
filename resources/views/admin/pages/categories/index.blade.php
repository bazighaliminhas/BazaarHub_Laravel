@extends('layouts.admin')
@section('title','Categories')
@section('page-title','Manage Categories')
@section('content')

<div class="row g-4">

    {{-- Add Category Form --}}
    <div class="col-lg-4">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-plus-circle me-2" style="color:#FF6B35;"></i>Add Category</h5>
            </div>
            <div class="p-4">
                @if(session('success'))
                <div class="alert border-0 rounded-3 mb-3"
                     style="background:#e8f8f0;color:#27ae60;font-size:13px;">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert border-0 rounded-3 mb-3"
                     style="background:#fdecea;color:#e74c3c;font-size:13px;">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:14px;">
                            Category Name *
                        </label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name') }}"
                               placeholder="e.g. Dairy Products" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:14px;">
                            Emoji Icon
                        </label>
                        <input type="text" name="icon" class="form-control"
                               value="{{ old('icon') }}"
                               placeholder="e.g. 🥛" maxlength="10">
                        <div class="form-text">Paste any emoji as the category icon</div>
                    </div>
                    <button type="submit" class="btn-primary-a w-100 justify-content-center">
                        <i class="bi bi-plus-lg"></i> Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Categories List --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-tags me-2" style="color:#FF6B35;"></i>
                    All Categories ({{ $categories->count() }})
                </h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $i => $cat)
                        <tr>
                            <td style="color:#bbb;">{{ $i + 1 }}</td>
                            <td style="font-size:26px;">{{ $cat->icon ?? '🏷️' }}</td>
                            <td class="fw-semibold">{{ $cat->name }}</td>
                            <td>
                                <code style="background:#f8f9fa;padding:3px 8px;
                                             border-radius:6px;font-size:12px;color:#888;">
                                    {{ $cat->slug }}
                                </code>
                            </td>
                            <td>
                                <span class="fw-bold" style="color:#FF6B35;">
                                    {{ $cat->products_count }}
                                </span>
                                <span style="font-size:12px;color:#bbb;"> products</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete category \'{{ $cat->name }}\'?')">
                                    @csrf @method('DELETE')
                                    <button class="btn-sm-del">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
