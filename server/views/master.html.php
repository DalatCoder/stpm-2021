<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>{% yield title %}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">

    <!-- Feather icon -->
    <script src="/static/js/feather.min.js"></script>

    {% yield custom_styles %}
</head>

<body>

{% yield content %}

<script src="/static/js/bootstrap.bundle.min.js"></script>

<script>
    window.feather.replace();
</script>

{% yield custom_scrips %}

</body>

</html>
