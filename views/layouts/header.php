<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lokanesia</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .icon-shape {
            width: 48px;
            height: 48px;
            background-position: center;
            border-radius: 0.75rem;
        }
        .icon i {
            color: #fff;
            opacity: 0.8;
            top: 11px;
            position: relative;
        }
        .bg-gradient-primary {
            background: linear-gradient(310deg,#7928ca,#ff0080);
        }
        .bg-gradient-success {
            background: linear-gradient(310deg,#17ad37,#98ec2d);
        }
        .bg-gradient-warning {
            background: linear-gradient(310deg,#f53939,#fbcf33);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Lokanesia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tourist-spots">Tempat Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard/itineraries">Rencana Perjalanan</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($user->name ?? 'User') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/dashboard/profile"><i class="fas fa-user-circle"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="/dashboard/settings"><i class="fas fa-cog"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container"> 