@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Consulta #{{ $appointment->id }}</h1>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-10 text-left">

                <div class="text-center mb-4">
                    @if ($appointment->patient->picture)
                    <img src="{{ asset('storage/' . $appointment->patient->picture) }}" class="radius" height="140">
                    @endif
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Consulta</th>
                            <td>{{ $appointment->id }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $appointment->status }}</td>
                        </tr>
                        <tr>
                            <th>Paciente</th>
                            <td>{{ $appointment->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>Data</th>
                            <td>{{ $appointment->date_time->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Hora</th>
                            <td>{{ $appointment->date_time->format('H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>
@endsection