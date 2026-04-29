@extends('layouts.admin')
@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')

{{-- Welcome Banner --}}
<div class="rounded-4 p-4 mb-4 text-white"
     style="background:linear-gradient(135deg,#0f1623,#1a2332);
            box-shadow:0 8px 30px rgba(0,0,0,0.2);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                Attendance Management 👆
            </h4>
            <p class="mb-0 opacity-60" style="font-size:14px;">
                BazaarHub Admin Panel — UFace800 Biometric Attendance
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.attendance.sync') }}"
               class="btn btn-warning rounded-pill px-3 fw-bold" style="font-size:13px;">
                <i class="bi bi-arrow-repeat me-1"></i> Sync Device
            </a>
            <a href="{{ route('admin.attendance.report') }}"
               class="btn btn-light rounded-pill px-3 fw-bold" style="font-size:13px;">
                <i class="bi bi-bar-chart-line me-1"></i> View Report
            </a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    @foreach([
        ['label'=>'Today\'s Punches', 'value'=> $todayCount,   'icon'=>'👆', 'bg'=>'#fff3ee', 'trend'=>'Punched in today',     'class'=>'trend-up'],
        ['label'=>'Present Today',    'value'=> $presentCount,  'icon'=>'✅', 'bg'=>'#e8f8f0', 'trend'=>'On time arrivals',     'class'=>'trend-up'],
        ['label'=>'Absent Today',     'value'=> $absentCount,   'icon'=>'❌', 'bg'=>'#fdecea', 'trend'=>'Not punched yet',      'class'=>'trend-up'],
        ['label'=>'Late Arrivals',    'value'=> $lateCount,     'icon'=>'⏰', 'bg'=>'#fef9e7', 'trend'=>'Arrived after 9 AM',   'class'=>'trend-info'],
        ['label'=>'Total Records',    'value'=> $totalRecords,  'icon'=>'📋', 'bg'=>'#eef2ff', 'trend'=>'All time logs',        'class'=>'trend-info'],
        ['label'=>'This Month',       'value'=> $monthCount,    'icon'=>'📅', 'bg'=>'#f0f9f4', 'trend'=>'Monthly punches',      'class'=>'trend-up'],
    ] as $stat)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:{{ $stat['bg'] }}">
                {{ $stat['icon'] }}
            </div>
            <div class="stat-num">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
            <div class="stat-trend {{ $stat['class'] }}">{{ $stat['trend'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter Bar --}}
<div class="content-card mb-4">
    <div class="card-head">
        <h5><i class="bi bi-funnel me-2" style="color:#FF6B35;"></i>Filter Logs</h5>
    </div>
    <div class="p-3">
        <form method="GET" action="{{ route('admin.attendance.index') }}"
              class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px;">Employee ID</label>
                <input type="text" name="emp_id" class="form-control"
                       placeholder="e.g. 001" value="{{ request('emp_id') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px;">Date From</label>
                <input type="date" name="date_from" class="form-control"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold" style="font-size:12px;">Date To</label>
                <input type="date" name="date_to" class="form-control"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-primary-a w-100 justify-content-center">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('admin.attendance.index') }}"
                   class="btn btn-light rounded-3 px-3 fw-semibold" style="font-size:13px;">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Attendance Logs Table --}}
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-fingerprint me-2" style="color:#FF6B35;"></i>
            Attendance Logs
            <span style="font-size:12px;color:#bbb;font-weight:400;" class="ms-2">
                {{ $logs->total() }} total records
            </span>
        </h5>
        <a href="{{ route('admin.attendance.sync') }}" class="btn-sm-view">
            <i class="bi bi-arrow-repeat me-1"></i> Sync Now
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Punch Time</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    {{-- # --}}
                    <td style="color:#bbb;font-size:12px;">{{ $loop->iteration }}</td>

                    {{-- Employee --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;
                                        background:linear-gradient(135deg,#FF6B35,#FF8C42);
                                        border-radius:10px;display:flex;align-items:center;
                                        justify-content:center;color:white;font-weight:700;
                                        font-size:14px;flex-shrink:0;">
                                {{ $log->uid ?? '?' }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px;">
                                    Employee #{{ $log->uid }}
                                </div>
                                <div style="font-size:11px;color:#bbb;">ID: {{ $log->id }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Punch Time --}}
                    <td>
                        <span class="fw-bold" style="color:#1a1f2e;font-size:14px;">
                            {{ \Carbon\Carbon::parse($log->punch_time)->format('h:i A') }}
                        </span>
                    </td>

                    {{-- Date --}}
                    <td style="font-size:12px;color:#888;">
                        {{ \Carbon\Carbon::parse($log->punch_time)->format('d M Y') }}
                    </td>

                    {{-- Day --}}
                    <td style="font-size:12px;color:#888;">
                        {{ \Carbon\Carbon::parse($log->punch_time)->format('l') }}
                    </td>

                    {{-- Type --}}
                    <td>
                        @php $hour = \Carbon\Carbon::parse($log->punch_time)->hour; @endphp
                        @if($hour < 12)
                            <span class="b-active">Check In</span>
                        @else
                            <span class="b-pending">Check Out</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @php
                            $punchHour   = \Carbon\Carbon::parse($log->punch_time)->hour;
                            $punchMinute = \Carbon\Carbon::parse($log->punch_time)->minute;
                        @endphp
                        @if($punchHour < 9 || ($punchHour == 9 && $punchMinute == 0))
                            <span class="b-active">On Time</span>
                        @elseif($punchHour < 12)
                            <span class="b-inactive">Late</span>
                        @else
                            <span class="b-pending">Checkout</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="font-size:48px;">👆</div>
                        <div class="fw-bold mt-2" style="color:#1a1f2e;">No attendance records found</div>
                        <div style="font-size:13px;color:#bbb;" class="mt-1">
                            Sync your UFace800 device to load records
                        </div>
                        <a href="{{ route('admin.attendance.sync') }}" class="btn-primary-a mt-3 d-inline-flex">
                            <i class="bi bi-arrow-repeat"></i> Sync Device Now
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="border-top:1px solid #f5f5f5;">
        <div style="font-size:13px;color:#888;">
            Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }}
            of {{ $logs->total() }} records
        </div>
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection