<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>{% yield title %}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/dashboard.css">

    <!-- Feather icon -->
    <script src="/static/js/feather.min.js"></script>

    {% yield custom_styles %}
</head>

<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/">SSF - Save For Future</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
        <?php if ($is_logged_in): ?>
            <div class="d-sm-flex">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="">Xin chào <?= $logged_in_user->display_name ?></a>
                </div>
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="/auth/logout">Đăng xuất</a>
                </div>
            </div>
        <?php else: ?>
            <div class="d-sm-flex">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="/auth/register">Tạo tài khoản</a>
                </div>
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="/auth/login">Đăng nhập</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">
                    <span data-feather="home"></span>
                    Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file"></span>
                    Ví tiền
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="shopping-cart"></span>
                    Tiết kiệm
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="users"></span>
                    Cá nhân
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="bar-chart-2"></span>
                    Báo cáo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="layers"></span>
                    Giới thiệu
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Báo cáo nhanh</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
                <span data-feather="plus-circle"></span>
            </a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Ngày - tuần - quý
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Tổng nhập
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Tổng xuất
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Tiết kiệm
                </a>
            </li>
        </ul>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            {% yield content %}
        </main>
    </div>
</div>

<script src="/static/js/bootstrap.bundle.min.js"></script>

<script>
    window.feather.replace();
</script>

{% yield custom_scrips %}

</body>

</html>
