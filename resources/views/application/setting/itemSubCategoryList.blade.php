@extends('layouts/contentNavbarLayout')

@section('title', 'Items Sub Category')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Items Sub Category') }}</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> New
                    </button>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="GET" action="{{ route('setting-item-sub-category') }}" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <select name="category_id" class="form-select">
                                <option value="">-- All Items Categories --</option>
                                @foreach($categories as $catId => $catName)
                                    <option value="{{ $catId }}" {{ (string)($categoryId ?? '') === (string)$catId ? 'selected' : '' }}>
                                        {{ $catName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search sub category name..." value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Filter</button>
                            @if(!empty($categoryId) || !empty($search))
                                <a href="{{ route('setting-item-sub-category') }}" class="btn btn-outline-secondary">Reset</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">SL</th>
                                    <th>Items Category</th>
                                    <th>Sub Category Name</th>
                                    <th style="width: 120px;">Status</th>
                                    <th style="width: 120px;">Stock Items</th>
                                    <th style="width: 180px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($subCategories as $key => $item)
                                    <tr>
                                        <td>{{ $subCategories->firstItem() + $key }}</td>
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge bg-label-success">Active</span>
                                            @else
                                                <span class="badge bg-label-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->stock_items_count }}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-primary editBtn"
                                                    data-id="{{ $item->id }}"
                                                    data-category-id="{{ $item->category_id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-is-active="{{ $item->is_active }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal">
                                                Edit
                                            </button>
                                            <form action="{{ route('setting-item-sub-category-delete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sub category?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No Items Sub Category found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $subCategories->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Items Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm">
                    @csrf
                    <div class="modal-body">
                        <div id="addFormErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label for="add_category_id" class="form-label">Items Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="add_category_id" class="form-select" required>
                                <option value="">-- Select Items Category --</option>
                                @foreach($categories as $catId => $catName)
                                    <option value="{{ $catId }}" {{ (string)($categoryId ?? '') === (string)$catId ? 'selected' : '' }}>
                                        {{ $catName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add_name" class="form-control" placeholder="Enter Sub Category Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_is_active" class="form-label">Status</label>
                            <select name="is_active" id="add_is_active" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Items Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm">
                    @csrf
                    <div class="modal-body">
                        <div id="editFormErrors" class="alert alert-danger d-none"></div>
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Items Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="edit_category_id" class="form-select" required>
                                <option value="">-- Select Items Category --</option>
                                @foreach($categories as $catId => $catName)
                                    <option value="{{ $catId }}">{{ $catName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter Sub Category Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select name="is_active" id="edit_is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
    function renderValidationErrors(containerSelector, xhr) {
        const $container = $(containerSelector);
        $container.empty().addClass('d-none');

        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            let html = '<ul class="mb-0">';
            $.each(xhr.responseJSON.errors, function (key, messages) {
                html += '<li>' + messages[0] + '</li>';
                toastr.error(messages[0]);
            });
            html += '</ul>';
            $container.html(html).removeClass('d-none');
        } else {
            toastr.error('Something went wrong.');
        }
    }

    $('#addModal').on('show.bs.modal', function () {
        $('#add_name').val('');
        $('#add_is_active').val('1');
        $('#addFormErrors').empty().addClass('d-none');
    });

    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('setting-item-sub-category-create') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                $('#addModal').modal('hide');
                location.reload();
            },
            error: function (xhr) {
                renderValidationErrors('#addFormErrors', xhr);
            }
        });
    });

    $(document).on('click', '.editBtn', function () {
        $('#editFormErrors').empty().addClass('d-none');
        $('#edit_id').val($(this).data('id'));
        $('#edit_category_id').val($(this).data('category-id'));
        $('#edit_name').val($(this).data('name'));
        $('#edit_is_active').val($(this).data('is-active'));
    });

    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('setting-item-sub-category-update') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                $('#editModal').modal('hide');
                location.reload();
            },
            error: function (xhr) {
                renderValidationErrors('#editFormErrors', xhr);
            }
        });
    });
</script>
@endsection
