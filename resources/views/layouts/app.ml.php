<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | MonkeysLegion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/luxury-dark.css') ?>">
    <style>
        header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--dark) 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            background-color: #14161f;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--gray);
        }
    </style>
</head>

<body class="dark-theme">
    @include('partials.header')

    <header>
        <div class="container">
            @yield('header')
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    &copy; {{ date('Y') }} MonkeysLegion ORM Framework
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Terms</a>
                    <a href="#" class="text-decoration-none text-muted">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>

</html>