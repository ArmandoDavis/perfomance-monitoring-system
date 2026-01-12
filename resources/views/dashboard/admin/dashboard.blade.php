@extends('layouts.admin.app')
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        {{-- Average Weekly Performance --}}
        <div class="col-12 col-xl-3 d-flex">
            <div class="card rounded-4 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <h2 class="mb-0">{{ number_format($avgWeeklyScore ?? 0, 1) }}%</h2>
                    </div>
                    <p class="mb-0">Average Weekly Performance Score</p>
                    <div id="chart1"></div>
                </div>
            </div>
        </div>

        {{-- Task / Finance Summary --}}
        <div class="col-12 col-xl-9 d-flex">
            <div class="card rounded-4 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-around flex-wrap gap-4 p-4">

                        {{-- Todo Tasks --}}
                        <div class="text-center">
                            <div class="mb-2 wh-48 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined">assignment</i>
                            </div>
                            <h3 class="mb-0">{{ $todoTasks }}</h3>
                            <p class="mb-0">Todo Tasks</p>
                        </div>

                        <div class="vr"></div>

                        {{-- Completed Tasks --}}
                        <div class="text-center">
                            <div class="mb-2 wh-48 bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined">check_circle</i>
                            </div>
                            <h3 class="mb-0">{{ $completedTasks }}</h3>
                            <p class="mb-0">Completed Tasks</p>
                        </div>

                        <div class="vr"></div>

                        {{-- Pending Reviews --}}
                        <div class="text-center">
                            <div class="mb-2 wh-48 bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined">info</i>
                            </div>
                            <h3 class="mb-0">{{ $pendingNotifications }}</h3>
                            <p class="mb-0">Pending Reviews</p>
                        </div>

                        <div class="vr"></div>

                        {{-- Total Expenses --}}
                        <div class="text-center">
                            <div class="mb-2 wh-48 bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center">
                                <i class="material-icons-outlined">payments</i>
                            </div>
                            <h3 class="mb-0">{{ number_format($totalExpenses) }} TZS</h3>
                            <p class="mb-0">Total Expenses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Side --}}
        <div class="col-12 col-xl-5 col-xxl-4 d-flex">
            <div class="card rounded-4 w-100 shadow-none bg-transparent border-0">
                <div class="card-body p-0">
                    <div class="row g-4">

                        {{-- Task Status --}}
                        <div class="col-12 col-xl-6">
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $taskStatus->sum('total') }}</h4>
                                    <p class="mb-2">Total Tasks</p>
                                    <div id="chart3"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Staff Activity --}}
                        <div class="col-12 col-xl-6">
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $staffStatus->active }}</h4>
                                    <p class="mb-2">Active Staff</p>
                                    <div id="chart2"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Budget Summary --}}
                        <div class="col-12">
                            <div class="card rounded-4">
                                <div class="card-body">
                                    <h2 class="mb-0">{{ number_format($budgetExpense->sum('allocated')) }} TZS</h2>
                                    <p class="mb-0">Total Allocated Budget</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="col-12 col-xl-7 col-xxl-8 d-flex">
            <div class="card w-100 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Budget vs Expense Analysis</h5>
                    <div id="chart4"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/assets/plugins/apexchart/apexcharts.min.js') }}"></script>

    <script>
        $(function () {
            "use strict";

            /* ============================
               Chart 1: Weekly Completed Tasks
            ============================ */
            new ApexCharts(document.querySelector('#chart1'), {
                chart: {
                    type: 'area',
                    height: 105,
                    sparkline: { enabled: true }
                },
                series: [{
                    name: 'Completed Tasks',
                    data: @json($weeklyTasks->pluck('total'))
                }],
                xaxis: {
                    categories: @json($weeklyTasks->pluck('date'))
                },
                stroke: { curve: 'smooth', width: 1.7 },
                colors: ['#02c27a'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.5,
                        opacityTo: 0
                    }
                },
                tooltip: { theme: 'dark' }
            }).render();

            /* ============================
               Chart 2: Staff Activity
            ============================ */
            new ApexCharts(document.querySelector('#chart2'), {
                chart: { type: 'radialBar', height: 180 },
                series: [
                    {{ $staffStatus->active }},
                    {{ $staffStatus->inactive }}
                ],
                labels: ['Active', 'Inactive'],
                colors: ['#0866ff', '#fc185a'],
                stroke: { lineCap: 'round' }
            }).render();

            /* ============================
               Chart 3: Task Status Distribution
            ============================ */
            new ApexCharts(document.querySelector('#chart3'), {
                chart: {
                    type: 'donut',
                    height: 180,
                },
                series: @json($taskStatus->pluck('total')),
                labels: @json($taskStatus->pluck('status_cv_id')),
                colors: ['#0d6efd', '#fc6718', '#02c27a']
            }).render();

            /* ============================
               Chart 4: Sales vs Views
            ============================ */
            new ApexCharts(document.querySelector('#chart4'), {
                chart: {
                    type: 'bar',
                    height: 235,
                    foreColor: '#9ba7b2',
                    toolbar: { show: false }
                },
                series: [
                    { name: 'Sales', data: @json($salesData) },
                    { name: 'Views', data: @json($viewsData) }
                ],
                xaxis: {
                    categories: @json($months)
                },
                colors: ['#0d6efd', '#6f42c1'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '55%'
                    }
                },
                tooltip: { theme: 'dark' }
            }).render();

        });
    </script>
@endpush

