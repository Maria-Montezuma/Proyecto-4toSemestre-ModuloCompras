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
<body class="d-flex flex-column h-100">
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <nav id="sidebarMenu" class="sidebar">
                <div class="sidebar-header">
                    <img class="icono" src="{{ asset('icon/icono.ico') }}" alt="icono">
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proveedores') }}">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">Proveedores</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ordencompra') }}">
                            <i class="fas fa-file-invoice"></i>
                            <span class="nav-link-text">Orden de Compra</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recepcion') }}">
                            <i class="fas fa-truck-loading"></i>
                            <span class="nav-link-text">Recepción de Mercancía</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('devolucion') }}">
                            <i class="fas fa-undo-alt"></i>
                            <span class="nav-link-text">Devolución</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Contenido principal -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pb-5">
                @yield('content')
            </main>
        </div>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <span class="text-muted">© 2024 MDJM. Todos los derechos reservados.</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
