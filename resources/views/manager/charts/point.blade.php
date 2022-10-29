@extends('layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header " >
{{--                    <form id="form-filter">--}}
{{--                        <div class="form-group d-flex">--}}
{{--                            <div class="input-group mb-3 w-25 mr-3">--}}
{{--                                <label for="select-teacher">Tên giáo viên</label>--}}
{{--                                <select class="custom-select select-filter-role" id="select-teacher" name="teacher" >--}}
{{--                                    <option selected>All...</option>--}}
{{--                                    @foreach($data as $each)--}}
{{--                                        <option value="{{ $each->id }}"--}}
{{--                                                @if ((string)$each->id === $selectedTeacher) selected @endif>--}}
{{--                                            {{$each->first_name}} {{$each->last_name}}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
                </div>
                <div class="card-body" style="width:600px">
                    <canvas id="ChartPoint"></canvas>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
                integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
                crossorigin="anonymous" referrerpolicy="no-referrer">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script>
            let ctx = document.getElementById('ChartPoint');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['1 - 4 (điểm)', '4 - 6 (điểm)', '6-8 (điểm)', '8-10 (điểm)'],
                    datasets: [{
                        label: 'Thống kê điểm số',
                        data: [12, 19, 3, 5],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection()
