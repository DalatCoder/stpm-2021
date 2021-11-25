{% extends master.html.php %}

{% block title %}<?= $shop_name ?? 'SSF' ?> - Tạo tài khoản{% endblock %}

{% block content %}

<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="h4 my-5">Tạo tài khoản ngay</h1>

        <?php if ($flush_handler->get('auth_error')): ?>
            <div class="alert alert-danger">
                <?= $flush_handler->get('auth_error') ?>
                <?php $flush_handler->clean_at('auth_error') ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" id="username" class="form-control" name="username" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="display_name" class="form-label">Tên hiển thị</label>
                <input type="text" id="display_name" class="form-control" name="display_name" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" class="form-control" name="password" autocomplete="off">
            </div>
            <hr class="mt-5">
            <div class="mb 3 d-flex">
                <input type="submit" value="Tạo tài khoản" class="btn btn-primary">
                <a href="/auth/login" class="btn btn-secondary ms-auto">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

{% endblock %}
