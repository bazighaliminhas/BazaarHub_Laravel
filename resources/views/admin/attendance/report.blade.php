@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Daily Attendance Report</h2>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
            ← Back to Logs
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">📅 Check-In / Check-Out Summary</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Date</th>
                        <th>Check IN</th>
                        <th>Check OUT</th>
                        <th>Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $checkIn  = $log->check_in  ? \Carbon\Carbon::parse($log->check_in)  : null;
                            $checkOut = $log->check_out ? \Carbon\Carbon::parse($log->check_out) : null;
                            $hours    = ($checkIn && $checkOut)
                                        ? $checkIn->diffInMinutes($checkOut) / 60
                                        : null;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $log->device_user_id }}</code></td>
                            <td>{{ $log->employee_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</td>
                            <td>
                                @if($checkIn)
                                    <span class="badge bg-success">
                                        {{ $checkIn->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($checkOut)
                                    <span class="badge bg-danger">
                                        {{ $checkOut->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($hours !== null)
                                    <strong>{{ number_format($hours, 1) }} hrs</strong>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No report data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection