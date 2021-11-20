<?php

function load_template($template_file_name, $args = [], $template_directory = ROOT_DIR . '/src/Ninja/NJViews/')
{
    extract($args);

    ob_start();

    include $template_directory . $template_file_name;

    return ob_get_clean();
}
