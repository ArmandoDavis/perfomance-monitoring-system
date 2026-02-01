@extends('layouts.admin.app')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card radius-10 shadow-none border">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <h5 class="mb-0 text-dark fw-bold">{{ __('Dashboard Overview') }} - {{ $selectedYear }}</h5>
                    <form action="" method="GET" id="yearFilterForm" class="d-flex align-items-center">
                        <label class="me-2 text-muted small fw-bold text-nowrap">{{ __('Select Year') }}:</label>
                        <select name="year" class="form-select form-select-sm border-primary" onchange="this.form.submit()">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                            @if(!$availableYears->contains(now()->year))
                                <option value="{{ now()->year }}" {{ $selectedYear == now()->year ? 'selected' : '' }}>{{ now()->year }}</option>
                            @endif
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Completed Tasks') }}</p>
                            <h4 class="my-1 text-success">{{ number_format($completedTasks) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light-success text-success ms-auto">
                            <i class="material-icons-outlined">task_alt</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Total Expenses') }}</p>
                            <h4 class="my-1 text-danger">TZS {{ number_2_format($totalExpenses) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light-danger text-danger ms-auto">
                            <i class="material-icons-outlined">payments</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Weekly Performance') }}</p>
                            <h4 class="my-1 text-info">{{ $avgWeeklyScore }}%</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light-info text-info ms-auto">
                            <i class="material-icons-outlined">trending_up</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Pending Review') }}</p>
                            <h4 class="my-1 text-warning">{{ $pendingApprovals }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-light-warning text-warning ms-auto">
                            <i class="material-icons-outlined">pending_actions</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <div><h6 class="mb-0">{{ __('Financial Overview (Budget vs Spent)') }}</h6></div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="financial-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">{{ __('Task Distribution by Status') }}</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalTasks = $taskStatus->sum('total');
                        $colors = ['bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-info', 'bg-secondary'];
                    @endphp

                    <div class="task-status-list">
                        @forelse($taskStatus as $index => $status)
                            @php
                                $percentage = $totalTasks > 0 ? round(($status->total / $totalTasks) * 100) : 0;
                                $currentColor = $colors[$index % count($colors)];
                            @endphp
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="fw-bold">{{ $status->name }}</span>
                                    <span class="text-muted small">{{ $status->total }} Tasks ({{ $percentage }}%)</span>
                                </div>
                                <div class="progress" style="height: 7px;">
                                    <div class="progress-bar {{ $currentColor }}" role="progressbar"
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted py-5">{{ __('No data available') }}</p>
                        @endforelse
                    </div>

                    {{-- Summary footer ndogo --}}
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-secondary">{{ __('Total Volume') }}</span>
                            <h5 class="mb-0">{{ number_format($totalTasks) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script src="{{ asset('/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script>
        $(function () {
            // Financial Overview Chart
            const options = {
                series: [{
                    name: 'Allocated Budget',
                    data: @json($budgetExpense->pluck('allocated'))
                }, {
                    name: 'Spent Amount',
                    data: @json($budgetExpense->pluck('spent'))
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {show: false}
                },
                colors: ["#0dcaf0", "#f41127"],
                dataLabels: {enabled: false},
                xaxis: {
                    categories: @json($budgetExpense->map(fn($m) => Carbon\Carbon::create()->month($m->month)->format('M'))),
                }
            };
            new ApexCharts(document.querySelector("#financial-chart"), options).render();

            // Task Status Pie Chart
            const statusOptions = {
                series: @json($taskStatus->pluck('total')),
                chart: {type: 'donut', height: 350},
                labels: @json($taskStatus->pluck('name')),
                colors: ["#0d6efd", "#212529", "#198754", "#ffc107"],
                responsive: [{
                    breakpoint: 480,
                    options: {chart: {width: 200}, legend: {position: 'bottom'}}
                }]
            };
            new ApexCharts(document.querySelector("#status-chart"), statusOptions).render();
        });
    </script>
@endpush
