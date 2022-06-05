@extends('layouts.dashboard')

@section('content')
    <div class="container main-content-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Malfunctioning sensors') }}</h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sensor ID</th>
                                <th>Face</th>
                                <th>Temperature</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($malfunctioning as $sensors)
                                <tr>
                                    <td>{{$sensors->id}}</td>
                                    <td>{{$sensors->sensor_id}}</td>
                                    <td>{{$sensors->face}}</td>
                                    <td>{{$sensors->temperature}}&#8451;</td>
                                    <td>{{$sensors->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$malfunctioning->links('pagination::bootstrap-4')}}

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection
