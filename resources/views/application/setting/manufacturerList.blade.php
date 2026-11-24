@extends('layouts/contentNavbarLayout')

@section('title', 'Manufacturer')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Manufacturer') }}</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> New
                    </button>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="GET" action="{{ route('setting-manufacturer') }}" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search manufacturer name..." value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                            @if(!empty($search))
                                <a href="{{ route('setting-manufacturer') }}" class="btn btn-outline-secondary">Reset</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">SL</th>
                                    <th>Manufacturer Name</th>
                                    <th style="width: 140px;">Stock Items</th>
                                    <th style="width: 180px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($manufacturers as $key => $item)
                                    <tr>
                                        <td>{{ $manufacturers->firstItem() + $key }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->stock_items_count }}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-primary editBtn"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal">
                                                Edit
                                            </button>
                                            <form action="{{ route('setting-manufacturer-delete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this manufacturer?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No Manufacturer found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $manufacturers->withQueryString()->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title">Add Manufacturer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addForm">
                    @csrf
                    <div class="modal-body">
                        <div id="addFormErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label for="add_name" class="form-label">Manufacturer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add_name" class="form-control" placeholder="Enter Manufacturer Name" required>
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
                    <h5 class="modal-title">Edit Manufacturer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm">
                    @csrf
                    <div class="modal-body">
                        <div id="editFormErrors" class="alert alert-danger d-none"></div>
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Manufacturer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter Manufacturer Name" required>
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
        $('#addForm')[0].reset();
        $('#addFormErrors').empty().addClass('d-none');
    });

    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('setting-manufacturer-create') }}",
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
        $('#edit_name').val($(this).data('name'));
    });

    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('setting-manufacturer-update') }}",
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
