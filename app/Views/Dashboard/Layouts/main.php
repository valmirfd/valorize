<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="<?php echo csrf_token() ?>" content="<?php echo csrf_hash(); ?>" class="csrf" />
    <title><?= $this->renderSection('title'); ?> <?= APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <!-- favicon -->
    <link rel="shortcut icon" href="<?= base_url('recursos/images/logo.png') ?>" type="image/png">
    <!-- bootstrap -->
    <link rel="stylesheet" href="<?= base_url('recursos/libs/bootstrap/bootstrap.min.css') ?>">
    <!-- fontawesome -->
    <link rel="stylesheet" href="<?= base_url('recursos/libs/fontawesome/all.min.css') ?>">
    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css" integrity="sha512-UiKdzM5DL+I+2YFxK+7TDedVyVm7HMp/bN85NeWMJNYortoll+Nd6PU9ZDrZiaOsdarOyk9egQm6LOJZi36L2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="<?= base_url('recursos/css/styles.css') ?>" rel="stylesheet" />
    <?php echo $this->renderSection('styles'); ?>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?= route_to('dashboard.home') ?>"><?= APP_NAME ?></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?= route_to('dashboard.home') ?>">Settings</a></li>
                    <li><a class="dropdown-item" href="<?= route_to('dashboard.home') ?>">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?= site_url('logout'); ?>">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link <?php echo url_is("/dashboard") ? 'active' : ''; ?>" href="<?= route_to('dashboard.home'); ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house-lock"></i></div>
                            Dashboard
                        </a>

                        <a class="nav-link <?php echo url_is("/dashboard/igrejas") ? 'active' : ''; ?>" href="<?= route_to('dashboard.igrejas'); ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-church"></i></div>
                            Igrejas
                        </a>



                    </div>
                </div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h3 class="mt-4"><?= $title ?? ""; ?></h3>



                    <?php echo $this->renderSection('content'); ?>


                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Sistema de Gestão de Membros <?= date('Y'); ?></div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- boostrap -->
    <script src="<?= base_url('recursos/libs/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('recursos/js/scripts.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js" integrity="sha512-79j1YQOJuI8mLseq9icSQKT6bLlLtWknKwj1OpJZMdPt2pFBry3vQTt+NZuJw7NSd1pHhZlu0s12Ngqfa371EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?= base_url('recursos/js/formatters.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyCurrencyFormat();
        });
    </script>

    <?php echo $this->renderSection('scripts'); ?>
</body>

</html>