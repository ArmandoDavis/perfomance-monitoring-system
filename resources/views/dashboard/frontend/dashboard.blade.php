@extends('layouts.frontend.app')
@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('My Task Analytics') }}</h4>
            <span class="text-muted">{{ now()->format('F Y') }}</span>
        </div>

        <div class="row">
            @php
                $cards = [
                    ['title' => 'Todo', 'count' => $todoTasks, 'color' => 'primary', 'icon' => 'fas fa-list'],
                    ['title' => 'In Progress', 'count' => $inProgressTasks ?? 0, 'color' => 'info', 'icon' => 'fas fa-spinner'],
                    ['title' => 'Completed', 'count' => $completedTasks, 'color' => 'success', 'icon' => 'fas fa-check-circle'],
                    ['title' => 'Backlog', 'count' => $backlogTasks ?? 0, 'color' => 'secondary', 'icon' => 'fas fa-archive'],
                    ['title' => 'Failed', 'count' => $failedTasks ?? 0, 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
                    ['title' => 'Submitted', 'count' => $submittedTasks ?? 0, 'color' => 'dark', 'icon' => 'fas fa-paper-plane'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-{{ $card['color'] }} shadow h-100 py-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                    {{ __($card['title']) }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $card['count'] }}
                                </div>
                            </div>
                            <i class="{{ $card['icon'] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- CHARTS --}}
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header fw-bold">
                        {{ __('Task Completion Trend (Jan - Dec)') }}
                    </div>
                    <div class="card-body">
                        <div id="monthlyTasksChart"></div>
                    </div>
                </div>
            </div>

            {{-- STATUS DISTRIBUTION --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header fw-bold">
                        {{ __('Task Status Distribution') }}
                    </div>
                    <div class="card-body">
                        <div id="taskStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PERFORMANCE --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header fw-bold">
                        {{ __('Weekly Performance Summary') }}
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">{{ __('Average Weekly Score') }}</h6>
                            <h3 class="fw-bold">{{ $avgWeeklyScore }}/100</h3>
                        </div>
                        <div>
                            <h6 class="text-muted">{{ __('Total Expenses') }}</h6>
                            <h3 class="fw-bold text-danger">{{ $totalExpenses }} TZS</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        .border-left-primary { border-left: 4px solid #4e73df !important; }
        .border-left-info { border-left: 4px solid #36b9cc !important; }
        .border-left-success { border-left: 4px solid #1cc88a !important; }
        .border-left-secondary { border-left: 4px solid #858796 !important; }
        .border-left-danger { border-left: 4px solid #e74a3b !important; }
        .border-left-dark { border-left: 4px solid #343a40 !important; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const monthlyData = @json($monthlyTasks);
        const statusData = @json($taskStatus);
        const months = monthlyData.map(item => item.month);
        const totals = monthlyData.map(item => item.total);

        const monthlyOptions = {
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false }
            },
            series: [{
                name: 'Completed Tasks',
                data: totals
            }],
            xaxis: {
                categories: months
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#4e73df'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1
                }
            }
        };

        new ApexCharts(
            document.querySelector("#monthlyTasksChart"),
            monthlyOptions
        ).render();

        // STATUS DONUT CHART
        const statusLabels = statusData.map(i => i.status);
        const statusTotals = statusData.map(i => i.total);

        const statusOptions = {
            chart: {
                type: 'donut',
                height: 300
            },
            series: statusTotals,
            labels: statusLabels,
            legend: {
                position: 'bottom'
            },
            colors: [
                '#4e73df', // Todo
                '#36b9cc', // In Progress
                '#f6c23e', // Pending
                '#1cc88a', // Completed
                '#858796', // Backlog
                '#e74a3b', // Failed
                '#343a40'  // Submitted
            ]
        };

        new ApexCharts(
            document.querySelector("#taskStatusChart"),
            statusOptions
        ).render();
    </script>
@endpush
