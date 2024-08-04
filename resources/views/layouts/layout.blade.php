<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo de Compras</title>
    <link rel="icon" href="{{ asset('icon/icon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('icon/icono.ico') }}" alt="icono">
                Módulo de Compras
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores') }}">
                            <i class="fas fa-users"></i> Proveedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ordencompra') }}">
                            <i class="fas fa-file-invoice"></i> Orden de Compra
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recepcion') }}">
                            <i class="fas fa-truck-loading"></i> Recepción de Mercancía
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('devolucion') }}">
                            <i class="fas fa-undo-alt"></i> Devolución
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

            <!-- Contenido principal -->
            <div class="container-fluid flex-grow-1 mt-5 pt-3">
            @yield('content')
    </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="text-muted">© 2024 MDJM. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
