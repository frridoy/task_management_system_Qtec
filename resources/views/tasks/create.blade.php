@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-4 fw-semibold">New Task</h5>

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-muted small">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" maxlength="100"
                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                            placeholder="Enter task title"
                            value="{{ old('title') }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Description</label>
                        <textarea name="description" rows="3"
                            class="form-control form-control-sm @error('description') is-invalid @enderror"
                            placeholder="Optional details...">{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label text-muted small">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending"     {{ old('status','pending') === 'pending'     ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') === 'in_progress'           ? 'selected' : '' }}>In Progress</option>
                                <option value="completed"   {{ old('status') === 'completed'             ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label text-muted small">Priority</label>
                            <select name="priority" class="form-select form-select-sm">
                                <option value="low"    {{ old('priority') === 'low'              ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority','medium') === 'medium'  ? 'selected' : '' }}>Medium</option>
                                <option value="high"   {{ old('priority') === 'high'             ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label text-muted small">Due Date</label>
                            <input type="date" name="due_date"
                                class="form-control form-control-sm @error('due_date') is-invalid @enderror"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('due_date') }}">
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Create Task</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
