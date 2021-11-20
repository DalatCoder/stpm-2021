{% extends master.html.php %}

{% block title %}Ninja Framework{% endblock %}

{% block custom_styles %}
<link rel="stylesheet" href="/static/css/main.css">
{% endblock %}

{% block content %}

<main>

    <div class="container">
        <h1 class="text-center mb-5 text-white">Ninja Framework</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center shadow-lg fs-5 text-secondary">

                        <?= $introduction ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <div class="lds-grid">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div class="mt-3 text-white text-center">
            <a href="https://github.com/DalatCoder/ninja-php-framework" class="text-white text-decoration-none me-3">
                <span data-feather="github"></span>
            </a>
            <a href="https://www.facebook.com/dalatcoder" class="text-white text-decoration-none me-3">
                <span data-feather="facebook"></span>
            </a>
            <a href="mailto:hieuntctk42@gmail.com" class="text-white text-decoration-none me-3">
                <span data-feather="mail"></span>
            </a>
        </div>
    </div>

</main>

{% endblock %}
