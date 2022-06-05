@extends('layouts.dashboard')

@section('content')
    <div class="container main-content-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Sides Temperature') }}</h3>

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
                                <th>Face</th>
                                <th>Summarize aggregated hourly temperatures</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sidesTemperature as $temperature)
                                <tr>
                                    <td>{{$temperature->id}}</td>
                                    <td>{{$temperature->face}}</td>
                                    <td>{{$temperature->temperature}}&#8451;</td>
                                    <td>{{$temperature->created_at->format("Y-m-d H:00:00")}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection
