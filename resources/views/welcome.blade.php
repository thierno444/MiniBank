<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MiniBank - Page d'Accueil</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Couleur de fond de la page */
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
            position: relative; /* Position relative pour l'affichage du contenu */
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        footer {
            background-color: #3b82f6;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            z-index: 0; /* S'assurer que le footer est derrière le contenu */
        }

        /* Styles supplémentaires pour le contenu principal */
        .content {
            position: relative;
            z-index: 1; /* Assure que le contenu est au-dessus de la navbar */
            text-align: center;
            padding: 20px;
        }

        .lead {
            font-size: 1.25rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="chemin/vers/ton/logo.png" alt="Logo MiniBank" style="width: 150px; margin-bottom: 20px;">
        <h1>Bienvenue sur MiniBank</h1>
        <nav class="nav">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link text-white">Connexion</a>
                    <a href="{{ route('register') }}" class="nav-link text-white">Inscription</a>
                @endauth
            @endif
        </nav>
    </div>

    <div class="content">
        <h2>Gérez vos transactions et paiements en toute sécurité</h2>
        <p class="lead">Fonctionnalités :</p>
        <ul class="list-unstyled">
            <li>🔹 Transferts d'argent rapides et sécurisés</li>
            <li>🔹 Suivi de vos transactions en temps réel</li>
            <li>🔹 Gestion facile des comptes clients</li>
            <li>🔹 Accessibilité 24/7 sur toutes vos plateformes</li>
        </ul>
        <p>Rejoignez-nous dès aujourd'hui pour profiter de tous nos services !</p>
    </div>

    <footer>
        <p class="mb-0">© {{ date('Y') }} MiniBank. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
