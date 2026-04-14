@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Tasks</h5>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">+ New Task</a>
</div>

<form method="GET" action="{{ route('tasks.index') }}" class="card border-0 shadow-sm p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">Title</label>
            <input type="text" name="title" class="form-control form-control-sm" placeholder="Search title..."
                value="{{ request('title') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status')==='in_progress' ? 'selected' : '' }}>In Progress
                </option>
                <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">Priority</label>
            <select name="priority" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="low" {{ request('priority')==='low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority')==='medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority')==='high' ? 'selected' : '' }}>High</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">From Date</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small mb-1">To Date</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td class="text-muted small">{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>
                        @php
                        $badge = ['pending' => 'warning', 'in_progress' => 'info', 'completed' =>
                        'success'][$task->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </td>
                    <td>
                        @php
                        $pbadge = ['low' => 'secondary', 'medium' => 'primary', 'high' => 'danger'][$task->priority] ??
                        'secondary';
                        @endphp
                        <span class="badge bg-{{ $pbadge }}">{{ ucfirst($task->priority) }}</span>
                    </td>
                    <td>{{ $task->due_date?->format('d M Y') ?? '—' }}</td>
                    <td class="text-muted small">{{ $task->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                             <a href="" class="btn btn-sm btn-outline-info" title="Show">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="" method="POST" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No tasks found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($tasks->hasPages())
<div class="mt-3 d-flex justify-content-end">
    {{ $tasks->links() }}
</div>
@endif
@endsection
