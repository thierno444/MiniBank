<!-- resources/views/clients/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un Client</h1>
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="numCarteIdentite">Numéro de Carte d'Identité</label>
            <input type="text" class="form-control" id="numCarteIdentite" name="numCarteIdentite" required>
        </div>
        <!-- Ajoute d'autres champs ici -->
        <button type="submit" class="btn btn-success">Créer Client</button>
    </form>
</div>
@endsection
