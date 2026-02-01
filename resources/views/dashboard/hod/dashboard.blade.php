@extends('layouts.hod.app')
@section('title', 'HOD Dashboard - ' . auth()->user()->department->name)

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        {{-- Todo Tasks --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Pending Tasks') }}</p>
                            <h4 class="my-1 text-info">{{ $todoTasks }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Completed Tasks --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Completed Tasks') }}</p>
                            <h4 class="my-1 text-success">{{ $completedTasks }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class='bx bxs-check-circle'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Expenses --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Dept. Expenses') }}</p>
                            <h4 class="my-1 text-danger">TSh {{ number_format($totalExpenses) }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Performance --}}
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">{{ __('Avg. Performance') }}</p>
                            <h4 class="my-1 text-warning">{{ $avgWeeklyScore }}%</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Header with Filter --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h5 class="mb-0 text-uppercase">{{ auth()->user()->department->name }} - Statistics</h5>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex justify-content-md-end">
                <select name="year" class="form-select w-auto me-2" onchange="this.form.submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-white shadow-sm"><i class="fas fa-filter"></i></button>
            </form>
        </div>
    </div>

    {{-- Widgets Section --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        {{-- ... Widgets remain same as before, they use $todoTasks, $completedTasks, etc. ... --}}
    </div>

    <div class="row">
        {{-- Mixed Chart: Budget (Bar) vs Spent (Line) --}}
        <div class="col-12 col-lg-8">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">{{ $year }} Financial Analysis</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mixedBudgetChart"></div>
                </div>
            </div>
        </div>

        {{-- Staff Availability --}}
        <div class="col-12 col-lg-4">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">Staff Availability (Count)</h6>
                </div>
                <div class="card-body text-center">
                    <div id="staffStatusChart" style="min-height: 250px;"></div>
                    <div class="row mt-3">
                        <div class="col border-end">
                            <h4 class="mb-0 text-success">{{ $staffStatus->active ?? 0 }}</h4>
                            <small class="text-muted">Active</small>
                        </div>
                        <div class="col">
                            <h4 class="mb-0 text-secondary">{{ $staffStatus->inactive ?? 0 }}</h4>
                            <small class="text-muted">Inactive</small>
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
            const options = {
                series: [{
                    name: 'Allocated Budget',
                    type: 'column', // Bar
                    data: @json($budgetChartData->pluck('allocated'))
                }, {
                    name: 'Actual Spent',
                    type: 'line', // Line
                    data: @json($budgetChartData->pluck('spent'))
                }],
                chart: {height: 350, type: 'line', toolbar: {show: false}},
                stroke: {width: [0, 4], curve: 'smooth'},
                colors: ['#00d2ff', '#ff1212'],
                labels: @json($budgetChartData->pluck('month')),
                yaxis: [{title: {text: 'Budget Amount'}}],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {formatter: (val) => "TSh " + val.toLocaleString()}
                }
            };
            new ApexCharts(document.querySelector("#mixedBudgetChart"), options).render();

            // Staff Status Donut
            const staffOptions = {
                series: [{{ $staffStatus->active ?? 0 }}, {{ $staffStatus->inactive ?? 0 }}],
                chart: {type: 'donut', height: 250},
                labels: ['Active', 'Inactive'],
                colors: ['#28a745', '#adb5bd'],
                legend: {position: 'bottom'},
                plotOptions: {
                    pie: {donut: {labels: {show: true, total: {show: true, label: 'Total Staff'}}}}
                }
            };
            new ApexCharts(document.querySelector("#staffStatusChart"), staffOptions).render();
        });
    </script>
@endpush
