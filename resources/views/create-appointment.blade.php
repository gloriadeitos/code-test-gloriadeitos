@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Agendar consulta</h1>

        <div class="row mt-6 justify-content-center">
            <div class="col-md-5 text-left">
                <form action="{{ route('client.create-appointment') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="patient">Paciente</label>
                        <select name="patient" class="form-control @error('patient') is-invalid @enderror" id="patient">
                            <option value="">Selecione</option>
                            @foreach(auth()->User()->Patient()->where('name', '!=', null)->get() as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                        @error('patient')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date">Data da consulta</label>
                        <input type="text" name="date" autocomplete="off"
                            class="form-control @error('date') is-invalid @enderror" id="date"
                            value="{{ old('date', now()->format('d/m/Y')) }}">
                        @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="time">Horário da consulta</label>
                        <select name="time" class="form-control @error('time') is-invalid @enderror" id="time">
                            <option value="">Selecione</option>
                        </select>
                        @error('time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <p>Por favor, certifique-se de que poderá comparecer no horário marcado, pois não será possível
                        desfazer o agendamento.</p>

                    <button type="submit" class="btn btn-primary btn-block mt-4">Agendar</button>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(() => {
        // Inicializa o calendário para o campo de data
        $('#date').datepicker({
            language: 'pt-BR',
            autoHide: true,
            format: 'dd/mm/yyyy',
            filter: function(date) {
                return date.getDay() !== 0 && date.getDay() !== 6; // Exclui finais de semana
            }
        });

        // Define a data mínima como hoje
        $('#date').datepicker('setStartDate', new Date());

        // Quando a data for alterada, carrega os horários disponíveis
        $('#date').change(() => {
            loadAppointmentTimes();
        });

        function loadAppointmentTimes() {
            const date = $('#date').val(); // Obtém a data selecionada
            if (!date) return;

            $.ajax({
                url: "{{ route('appointments.available-times') }}", // Rota para buscar horários
                method: 'POST',
                data: {
                    date: date,
                    _token: "{{ csrf_token() }}" // Token CSRF para segurança
                },
                success: (times) => {
                    const timeSelect = $('#time');
                    timeSelect.empty(); // Limpa os horários anteriores
                    timeSelect.append('<option value="">Selecione</option>');

                    if (times.length === 0) {
                        timeSelect.append('<option disabled>Nenhum horário disponível</option>');
                    } else {
                        times.forEach(time => {
                            timeSelect.append(`<option value="${time}">${time}</option>`);
                        });
                    }
                },
                error: () => {
                    alert('Erro ao carregar os horários disponíveis.');
                }
            });
        }
    });
</script>
@endpush