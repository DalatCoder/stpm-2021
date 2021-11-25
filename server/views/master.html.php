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
                <a class="nav-link <?= $route == '/' ? 'active' : '' ?>" aria-current="page" href="/">
                    <span data-feather="home"></span>
                    Tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $route == '/wallets' ? 'active' : '' ?>" href="/wallets">
                    <span data-feather="file"></span>
                    Ví tiền
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $route == '/savings' ? 'active' : '' ?>" href="/savings">
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
    
    <div class="row">
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <footer class="bd-footer py-5 mt-5 bg-light">
                <div class="container py-5">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <a class="d-inline-flex align-items-center mb-2 link-dark text-decoration-none" href="/" aria-label="Bootstrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"/></svg>
                                <span class="fs-5">Bootstrap</span>
                            </a>
                            <ul class="list-unstyled small text-muted">
                                <li class="mb-2">Designed and built with all the love in the world by the <a href="/docs/5.0/about/team/">Bootstrap team</a> with the help of <a href="https://github.com/twbs/bootstrap/graphs/contributors">our contributors</a>.</li>
                                <li class="mb-2">Code licensed <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE" target="_blank" rel="license noopener">MIT</a>, docs <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank" rel="license noopener">CC BY 3.0</a>.</li>
                                <li class="mb-2">Currently v5.0.2.</li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-2 offset-lg-1 mb-3">
                            <h5>Links</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="/">Home</a></li>
                                <li class="mb-2"><a href="/docs/5.0/">Docs</a></li>
                                <li class="mb-2"><a href="/docs/5.0/examples/">Examples</a></li>
                                <li class="mb-2"><a href="https://themes.getbootstrap.com/">Themes</a></li>
                                <li class="mb-2"><a href="https://blog.getbootstrap.com/">Blog</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-2 mb-3">
                            <h5>Guides</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="/docs/5.0/getting-started/">Getting started</a></li>
                                <li class="mb-2"><a href="/docs/5.0/examples/starter-template/">Starter template</a></li>
                                <li class="mb-2"><a href="/docs/5.0/getting-started/webpack/">Webpack</a></li>
                                <li class="mb-2"><a href="/docs/5.0/getting-started/parcel/">Parcel</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-2 mb-3">
                            <h5>Projects</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="https://github.com/twbs/bootstrap">Bootstrap 5</a></li>
                                <li class="mb-2"><a href="https://github.com/twbs/bootstrap/tree/v4-dev">Bootstrap 4</a></li>
                                <li class="mb-2"><a href="https://github.com/twbs/icons">Icons</a></li>
                                <li class="mb-2"><a href="https://github.com/twbs/rfs">RFS</a></li>
                                <li class="mb-2"><a href="https://github.com/twbs/bootstrap-npm-starter">npm starter</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-lg-2 mb-3">
                            <h5>Community</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="https://github.com/twbs/bootstrap/issues">Issues</a></li>
                                <li class="mb-2"><a href="https://github.com/twbs/bootstrap/discussions">Discussions</a></li>
                                <li class="mb-2"><a href="https://github.com/sponsors/twbs">Corporate sponsors</a></li>
                                <li class="mb-2"><a href="https://opencollective.com/bootstrap">Open Collective</a></li>
                                <li class="mb-2"><a href="https://bootstrap-slack.herokuapp.com/">Slack</a></li>
                                <li class="mb-2"><a href="https://stackoverflow.com/questions/tagged/bootstrap-5">Stack Overflow</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="/static/js/bootstrap.bundle.min.js"></script>

<script>
    window.feather.replace();
</script>

{% yield custom_scrips %}

</body>

</html>
