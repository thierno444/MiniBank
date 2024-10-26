<!-- resources/views/clients/index.blade.php -->

@extends('layouts.app')
@include('layouts.sidebar-navbar')

@section('content')
<div class="container">
    <h1>Liste des Clients</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Ajouter un Client</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numéro de Carte d'Identité</th>
                <th>Solde</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->numCarteIdentite }}</td>
                <td>{{ $client->solde }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-info">Voir</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
