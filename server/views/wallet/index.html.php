{% extends master.html.php %}

{% block title %}<?= $shop_name ?? 'SSF' ?> - Ví tiền{% endblock %}

{% block content %}

<div class="row justify-content-center">
    <h1 class="h4 my-5">Quản lý ví tiền</h1>
    <div class="col-lg-8">
        <?php if ($flush_handler->get('auth_error')): ?>
            <div class="alert alert-danger">
                <?= $flush_handler->get('auth_error') ?>
                <?php $flush_handler->clean_at('auth_error') ?>
            </div>
        <?php endif; ?>

        <a href="/wallets/create" class="btn btn-success mb-4">Thêm ví mới</a>
        <table class="table table-striped table-hover table-borderless shadow shadow-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ngày BĐ</th>
                <th scope="col">Ngày KT</th>
                <th scope="col">Còn lại</th>
                <th scope="col">Lượng/Ngày</th>
                <th scope="col">Tổng thu</th>
                <th scope="col">Tổng chi</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($wallets as $key => $wallet): ?>
                <tr>
                    <th scope="row"><?= $key + 1 ?></th>
                    <td><?= $wallet->get_begin_date('d-m-Y') ?></td>
                    <td><?= $wallet->get_end_date('d-m-Y') ?></td>
                    <td>0</td>
                    <td>0</td>
                    <td><?= $wallet->get_total_incomes() ?></td>
                    <td><?= $wallet->get_total_outcomes() ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($wallets as $wallet): ?>
            <?php if (count($wallet->get_logs()) > 0): ?>
                <h2 class="h5 mt-5">Danh sách hoạt động trong tuần <?= (new DateTime())->format('W') ?></h2>
                <p>Ví: <?= $wallet->get_begin_date('d/m/Y') . ' - ' . $wallet->get_end_date('d/m/Y') ?></p>
                <table class="table table-striped table-hover table-borderless">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tiêu đề</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Lượng</th>
                        <th scope="col">Ngày</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($wallet->get_logs() as $key => $log): ?>
                        <?php if ($log->type === 'in'): ?>
                            <tr class="table-success">
                                <th scope="row"><?= $key + 1 ?></th>
                                <td>Bổ sung</td>
                                <td><?= $log->get_category()->name ?></td>
                                <td>+ <?= $log->amount ?></td>
                                <td><?= $log->get_log_date('d-m-Y') ?></td>
                                <td></td>
                            </tr>
                        <?php else: ?>
                            <tr class="table-danger">
                                <th scope="row"><?= $key + 1 ?></th>
                                <td><?= $log->title ?></td>
                                <td><?= $log->get_category()->name ?></td>
                                <td>- <?= $log->amount ?></td>
                                <td><?= $log->get_log_date('d-m-Y') ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="col-lg-4">
        <div class="card shadow shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Thêm chi tiêu mới</h5>
                <form action="/wallet-logs/outcomes" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input class="form-control form-control-sm" type="text" id="title" autocomplete="off" name="log[title]"
                               placeholder="VD: Đi chợ">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Lượng</label>
                        <input class="form-control form-control-sm" type="number" id="amount" autocomplete="off" name="log[amount]"
                               placeholder="50000">
                    </div>
                    <div class="mb-3">
                        <label for="category_id">Danh mục</label>
                        <select id="category_id" class="form-control form-select-sm" name="log[category_id]">
                            <?php foreach ($outcome_categories as $category): ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="wallet_id" id="wallet_id">Ví</label>
                        <select id="wallet_id" class="form-control form-select-sm" name="log[wallet_id]">
                            <?php foreach ($wallets as $wallet): ?>
                                <option
                                    value="<?= $wallet->id ?>"><?= $wallet->get_begin_date('d/m/Y') . ' - ' . $wallet->get_end_date('d/m/Y') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Ngày</label>
                        <input class="form-control form-control-sm" type="date" id="date" autocomplete="off" name="log[log_date]"
                               value="<?= (new DateTime())->format('Y-m-d') ?>">
                    </div>
                    <hr>
                    <div class="mb-3">
                        <input type="submit" value="Ghi lại" class="btn btn-primary btn-sm">
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-3 shadow shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Thêm thu thập mới</h5>
                <form action="/wallet-logs/incomes" method="POST">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Lượng</label>
                        <input class="form-control form-control-sm" type="number" id="amount" autocomplete="off" name="income_log[amount]"
                               placeholder="50000">
                    </div>
                    <div class="mb-3">
                        <label for="category_id">Danh mục</label>
                        <select id="category_id" class="form-select form-control-sm" name="income_log[category_id]">
                            <?php foreach ($income_categories as $category): ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="wallet_id" id="wallet_id">Ví</label>
                        <select id="wallet_id" class="form-select form-control-sm" name="income_log[wallet_id]">
                            <?php foreach ($wallets as $wallet): ?>
                                <option
                                    value="<?= $wallet->id ?>"><?= $wallet->get_begin_date('d/m/Y') . ' - ' . $wallet->get_end_date('d/m/Y') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <input type="submit" value="Ghi lại" class="btn btn-primary btn-sm">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {% endblock %}
