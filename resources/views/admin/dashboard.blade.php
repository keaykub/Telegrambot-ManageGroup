@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="mt-4">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <text class="text-Dashboard">Dashboard</text>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" style="margin-right: 10px;">Export PDF</button>
                        <button type="button" class="btn btn-primary"><i class="bi bi-plus-lg"></i>   Create new report</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-chartmain mt-3 w-100">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let ctx = document.getElementById('myChart').getContext('2d');
            let monthName = {!! json_encode($monthName) !!};

            let chartData = {
                labels: {!! json_encode($dataChart->pluck('date')) !!},
                datasets: [{
                    label: 'ยอดผู้ใช้เดือน {{ $monthName }}',
                    data: {!! json_encode($dataChart->pluck('total')) !!},
                    backgroundColor: 'white',
                    borderColor: 'white',
                    borderWidth: 2
                }]
            };

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                                stepSize: 10
                            },
                            suggestedMin: 0,
                            suggestedMax: 100,
                        },
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
