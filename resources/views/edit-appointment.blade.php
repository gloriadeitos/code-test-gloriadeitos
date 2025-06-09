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
                            <th>Data e hora</th>
                            <td>{{ \Carbon\Carbon::parse($appointment->date_time)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Nome do paciente</th>
                            <td>{{ $appointment->patient->name }}</td>
                        </tr>
                        <tr>
                            <th>Raça</th>
                            <td>{{ $appointment->patient->breed }}</td>
                        </tr>
                        <tr>
                            <th>Idade</th>
                            <td>{{ $appointment->patient->getAge() }}</td>
                        </tr>
                        <tr>
                            <th>Dono</th>
                            <td>{{ $appointment->patient->owner->name }}</td>
                        </tr>
                        <tr>
                            <th>Atendimento realizado por</th>
                            <td>{{ $appointment->finalizedBy->name ?? 'Não registrado' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-6 justify-content-center">
            <div class="col-md-6 text-center">
                <form method="POST" action="{{ route('vet.edit-appointment', $appointment->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="notes"><strong>Observações</strong></label>
                        @if ($appointment->status === 'FINALIZADA')
                        <div class="form-control-plaintext">{{ $appointment->notes }}</div>
                        @else
                        <textarea id="notes" name="notes" class="form-control" rows="5"
                            required>{{ old('notes', $appointment->notes) }}</textarea>
                        @endif
                    </div>
                    @if ($appointment->status !== 'FINALIZADA')
                    <button type="submit" class="btn btn-primary mt-3">Salvar e finalizar consulta</button>
                    @endif
                </form>
            </div>
        </div>

    </div>
</section>
@endsection