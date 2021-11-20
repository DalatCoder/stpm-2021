{% extends master.html.php %}

{% block title %}Trang này không tồn tại{% endblock %}

{% block content %}
<h3>Trang này không tồn tại, quay lại <a href="<?= \Ninja\NJConfiguration::get('home_path') ?>">trang chủ</a></h3>
{% endblock %}
