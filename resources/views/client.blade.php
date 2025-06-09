@extends('layouts.main')
@section('title', 'Rusky Vet - A saúde do seu cão em primeiro lugar')
@section('content')
<section class="py-6 border-bottom">
    <div class="container text-center">
        <h1>Olá {{ explode(' ', trim(auth()->User()->name))[0] }}!</h1>

        <div class="row mt-6 justify-content-center">
            <div class="col-md-3">
                <p>
                    <img src="{{ asset('images/dog.jpg') }}" class="round" width="100">
                </p>
                <p class="lead mt-4">Cadastrar cachorro</p>
                <p>
                    <a class="btn btn-primary" href="{{ route('client.edit-patient') }}" role="button">Cadastrar</a>
                </p>
            </div>
            <div class="col-md-3">
                <p>
                    <img src="{{ asset('images/appointment.jpg') }}" class="round" width="100">
                </p>
                <p class="lead mt-4">Agendar consulta</p>
                <p>
                    <a class="btn btn-primary" href="{{ route('client.create-appointment') }}" role="button">Agendar</a>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container text-center">
        <h3>Minhas consultas</h3>
        <div class="row mt-5 justify-content-center">
            <div class="col-12 col-lg-10">
                <table class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Nome do cachorro</th>
                            <th>Data da consulta</th>
                            <th>Horário</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (auth()->user()->Patient()->with('appointments')->get() as $patient)
                        @foreach ($patient->appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->status }}</td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $appointment->date_time->format('d/m/Y') }}</td>
                            <td>{{ $appointment->date_time->format('H:i') }}</td>
                            <td>
                                <a href="{{ route('client.view-appointment', $appointment->id) }}">Abrir</a>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="py-5 border-bottom">
    <div class="container text-center">
        <h3>Meus cachorros</h3>
        <div class="row mt-5 justify-content-center">
            <div class="col-12 col-lg-10">
                @if(auth()->User()->Patient()->count() === 0)
                Você não tem nenhum cachorro cadastrado.
                @else
                <table class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>Data de nascimento</th>
                            <th>Raça</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (auth()->User()->Patient()->where('name', '!=', null)->get() as $patient)
                        <tr>
                            <td><img src="" class="radius" width="40"></td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->getAge() }}</td>
                            <td>{{ $patient->birthdate->format('d/m/Y') }}</td>
                            <td>{{ $patient->breed }}</td>
                            <td>
                                <a href="{{ route('client.edit-patient', $patient->id) }}" class="mx-2"
                                    title="Editar">✏️</a>
                                <a href="javascript:if (confirm('Você tem certeza que deseja remover este cachorro?')) location.href='{{ route('client.remove-patient', $patient->id) }}'"
                                    class="mx-2" title="Remover">❌</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection