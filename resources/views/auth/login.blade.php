<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Authentification</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand img {
            height: 50px;
        }
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .auth-container h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .is-valid {
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img alt="MiniBank logo" src="{{ asset('images/Minibank.png') }}" />
                MiniBank
            </a>
            <a href="/" class="nav-link text-white">Accueil</a>
        </div>
    </nav>
    <div class="auth-container">
        <h2 class="text-center">Authentification</h2>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" onsubmit="return validatePhoneNumber()">
            @csrf

            <!-- Numéro de téléphone -->
            <div class="mb-3">
                <label class="form-label" for="telephone">{{ __('Numéro de téléphone') }}</label>
                <div class="input-group">
                    <span class="input-group-text">+221</span>
                    <input id="telephone" class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}" 
                           type="text" name="telephone" value="{{ old('telephone') }}" required autofocus 
                           placeholder="700000000" maxlength="9" />
                </div>
               
                @if ($errors->has('telephone'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('telephone') }}</strong>
                    </span>
                @endif
            </div>

            <!-- Mot de passe -->
            <div class="mb-3">
                <label class="form-label" for="password">{{ __('Mot de passe') }}</label>
                <div class="input-group">
                    <input id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                           type="password" name="password" required autocomplete="current-password" />
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye-slash"></i>
                    </span>
                </div>
                <div id="passwordFeedback" class="form-text">Le mot de passe doit comporter au moins 8 caractères.</div>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">{{ __('Se connecter') }}</button>
            </div>
        </form>
    </div>

    <script>
        // Toggle Mot de Passe Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const passwordFeedback = document.querySelector('#passwordFeedback');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Vérification en temps réel du mot de passe
        password.addEventListener('input', function () {
            const minLength = 8;
            if (password.value.length >= minLength) {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
                passwordFeedback.textContent = "Mot de passe valide.";
                passwordFeedback.style.color = "green";
            } else {
                password.classList.remove('is-valid');
                password.classList.add('is-invalid');
                passwordFeedback.textContent = `Le mot de passe doit comporter au moins ${minLength} caractères.`;
                passwordFeedback.style.color = "red";
            }
        });

        // Limitation de saisie pour le téléphone
        const telephoneInput = document.querySelector('#telephone');
        telephoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); // Restrict to numbers and max 9 digits
        });

        // Validation du numéro de téléphone avant l'envoi du formulaire
        function validatePhoneNumber() {
            const phoneNumber = parseInt(telephoneInput.value, 10);
            if (phoneNumber < 700000000 || phoneNumber > 789999999) {
                alert('Le numéro de téléphone doit être compris entre 700000000 et 789999999.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
