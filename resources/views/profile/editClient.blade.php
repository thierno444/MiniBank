<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    @extends('layouts.sidebar-navbarC')

    @section('content')
    <div class="container mt-5">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">Modifié Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab">Securité</a>
            </li>
        </ul>

        <div class="tab-content py-4" id="myTabContent">
            <!-- Section Modifier le Profil -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="text-center mb-4">
                                <img id="currentPhoto" src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" alt="Profile" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <input type="file" class="form-control-file d-none" id="photo" name="photo" accept="image/*" onchange="previewImage(event)">
                                <label for="photo" class="btn btn-primary">Changer la photo</label>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nom">Votre Nom</label>
                                        <input type="text" class="form-control rounded" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prenom">Votre Prénom</label>
                                        <input type="text" class="form-control rounded" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_naissance">Votre Date de naissance</label>
                                        <input type="date" class="form-control rounded" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adresse">Votre Adresse</label>
                                        <input type="text" class="form-control rounded" id="adresse" name="adresse" value="{{ old('adresse', $user->adresse) }}" required>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary rounded px-4">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section Sécurité -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="d-block mb-3"></label>
                            <h6>Modification du Mot de Passe 
                            </h6>
                        </div>

                        <hr class="my-4">

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')  <!-- Assurez-vous que cela correspond à la méthode de votre route -->
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_password">Mot de passe actuel</label>
                                        <input type="password" class="form-control rounded {{ $errors->has('current_password') ? 'is-invalid' : '' }}" id="current_password" name="current_password" required minlength="8">
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe</label>
                                        <input type="password" class="form-control rounded {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" required minlength="8">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control rounded {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" required minlength="8">
                                        <div id="passwordMatchFeedback" class="form-text"></div>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary rounded px-4">Enregistrer</button>
                            </div>
                        </form>
                        
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const img = document.querySelector('img[alt="Profile"]'); // Image actuelle
            const preview = document.getElementById('preview'); // Image de prévisualisation
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Met à jour la source de l'image actuelle et de prévisualisation
                    img.src = e.target.result; // Met à jour la source de l'image actuelle
                    preview.src = e.target.result; // Met à jour l'aperçu
                    preview.style.display = 'block'; // Affiche l'image de prévisualisation
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    

    <script>
        const password = document.querySelector('#password');
        const passwordConfirmation = document.querySelector('#password_confirmation');
        const passwordMatchFeedback = document.querySelector('#passwordMatchFeedback');
    
        // Vérification en temps réel de la correspondance des mots de passe
        passwordConfirmation.addEventListener('input', function () {
            if (password.value === passwordConfirmation.value && password.value.length >= 8) {
                passwordConfirmation.classList.remove('is-invalid');
                passwordConfirmation.classList.add('is-valid');
                passwordMatchFeedback.textContent = "Les mots de passe correspondent.";
                passwordMatchFeedback.style.color = "green";
            } else {
                passwordConfirmation.classList.remove('is-valid');
                passwordConfirmation.classList.add('is-invalid');
                passwordMatchFeedback.textContent = "Les mots de passe ne correspondent pas.";
                passwordMatchFeedback.style.color = "red";
            }
        });
    
        // Vérification du mot de passe pour la longueur minimale
        password.addEventListener('input', function () {
            const minLength = 8;
            if (password.value.length >= minLength) {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
            } else {
                password.classList.remove('is-valid');
                password.classList.add('is-invalid');
            }
        });
    </script>
    
    @endsection

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    

</body>
</html>