
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Création de compte</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
        
                /* Styles pour la barre de navigation */
                .navbar {
                    background-color: #3b82f6;
                    height: 50vh; /* Occupe la moitié de la hauteur de la page */
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    color: white;
                    position: relative; /* Position relative pour l'affichage du formulaire */
                }
        
                .container {
                    background-color: #fff;
                    border-radius: 15px;
                    padding: 20px;
                    max-width: 800px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    position: absolute; /* Positionnement absolu pour superposer le formulaire */
                    top: 50%; /* Placer le conteneur à 50% de la hauteur de la barre de navigation */
                    left: 50%;
                    transform: translate(-50%, -50%); /* Centrer le conteneur */
                    z-index: 1; /* S'assurer que le formulaire est au-dessus de la navbar */
                    width: 80%; /* Largeur du formulaire */
                    max-width: 700px; /* Limite la largeur maximale */
                }
        
                h1 {
                    font-size: 1.5rem;
                    margin-bottom: 20px;
                }
        
                label {
                    font-weight: bold;
                }
        
                .form-control {
                    border: 1px solid #e3e3e3;
                    border-radius: 5px;
                }
        
                .form-group input,
                .form-group select {
                    background-color: #f7f7f7;
                }
        
                .profile-image {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 20px;
                    position: relative;
                }
        
                .profile-image img {
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                    object-fit: cover;
                }
        
                .profile-image .default-image {
                    background-color: #e3e3e3;
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                }
        
                .profile-image input[type="file"] {
                    display: none;
                }
        
                .icon-upload {
                    position: absolute;
                    bottom: 0;
                    right: 0;
                    background-color: #007bff;
                    color: white;
                    padding: 5px;
                    border-radius: 50%;
                    cursor: pointer;
                }
        
                .btn-primary {
                    background-color: #3b82f6;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 8px;
                    width: auto;
                }
        
                .btn-primary:hover {
                    background-color: #2563eb;
                }
                
                .btn-secondary {
                    margin-left: 10px; /* Ajoute un espace entre les boutons */
                }
            </style>
        </head>
        <body>
            <!-- Barre de navigation -->
            <nav class="navbar"></nav>
        
            <div class="container mt-5">
                <h1 class="text text-primary">Création de compte </h1>
        
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
        
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
        
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Image de profil -->
                            <div class="profile-image">
                                <!-- L'image du profil téléchargée ou par défaut -->
                                <img src="{{ asset('storage/' . ($user->photo ?? 'images/default.png')) }}" alt="Profile Image" id="profileDisplay">
        
                                <!-- Input pour uploader l'image -->
                                <label for="profile_image" class="icon-upload">
                                    <input type="file" id="profile_image" name="photo" accept="image/*" onchange="displayImage(this)" style="display: none;">
                                    <i class="fa fa-upload"></i> P
                                </label>
                            </div>
                        </div>
        
                        <div class="col-md-9">
                            <!-- Nom et prénom sur la même ligne -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrer votre nom" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrer votre prénom" required>
                                </div>
                            </div>
        
                            <!-- Email et NIN (Numéro d'identification) -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="telephone">Numéro de téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+221</span>
                                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Ex: 77 290 45 45" required 
                                               oninput="validatePhoneNumber(this)">
                                    </div>
                                    <small class="form-text text-danger" id="phoneError" style="display: none;">Veuillez entrer un numéro valide entre 700000000 et 789999999.</small>
                                </div>
        
                                <div class="form-group col-md-6">
                                    <label for="carte_identite">Carte D'identité</label>
                                    <input type="text" class="form-control" id="carte_identite" name="carte_identite" placeholder="Ex: 1447456800856" pattern="^[0-9]+$" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
        
                            <!-- Date de naissance et Adresse -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" required min="1900-01-01" max="2019-12-31" onchange="validateDate(this)">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Dakar, Yoff" required>
                                </div>
                            </div>
        
                            <!-- Mot de passe et rôle -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrer un mot de passe" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">Confirmer le MDP</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmer votre mot de passe" required>
                                    <div class="valid-feedback">Les mots de passe correspondent.</div>
                                    <div class="invalid-feedback">Les mots de passe ne correspondent pas.</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="role">Rôle</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="client">Client</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('login') }}" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        
            <script>


function validatePhoneNumber(input) {
            // Enlever tous les caractères non numériques
            input.value = input.value.replace(/[^0-9]/g, '');

            // Vérifier si le numéro est compris entre 700000000 et 789999999
            const phoneNumber = parseInt(input.value, 10);
            const errorMessage = document.getElementById('phoneError');
            if (phoneNumber < 700000000 || phoneNumber > 789999999) {
                errorMessage.style.display = 'block'; // Afficher le message d'erreur
            } else {
                errorMessage.style.display = 'none'; // Cacher le message d'erreur
            }
        }


                function displayImage(input) {
                    var file = input.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('profileDisplay').setAttribute('src', e.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                }
        
                // Vérification en temps réel du mot de passe
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');
        
                password.addEventListener('input', function() {
                    const minLength = 8;
                    if (password.value.length >= minLength) {
                        password.classList.remove('is-invalid');
                        password.classList.add('is-valid');
                    } else {
                        password.classList.remove('is-valid');
                        password.classList.add('is-invalid');
                    }
                    validatePasswordConfirmation(); // Vérifier également la confirmation
                });
        
                passwordConfirmation.addEventListener('input', validatePasswordConfirmation);
        
                function validatePasswordConfirmation() {
                    if (password.value === passwordConfirmation.value && password.value.length >= 8) {
                        passwordConfirmation.classList.remove('is-invalid');
                        passwordConfirmation.classList.add('is-valid');
                    } else {
                        passwordConfirmation.classList.remove('is-valid');
                        passwordConfirmation.classList.add('is-invalid');
                    }
                }
        
                function validateDate(input) {
                    const date = new Date(input.value);
                    const currentYear = new Date().getFullYear();
                    if (date.getFullYear() >= 2020) {
                        alert("La date de naissance ne peut pas être à partir de 2020.");
                        input.value = ''; // Réinitialise le champ
                    }
                }
            </script>
        </body>
        
        
        </html>
