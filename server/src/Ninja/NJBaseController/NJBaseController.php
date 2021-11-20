<?php

namespace Ninja\NJBaseController;

use Ninja\ViewHandler;

class NJBaseController
{
    protected ViewHandler $view_handler;
    protected array $entrypoint_arguments;
    
    public function __construct()
    {
        $this->view_handler = new ViewHandler();
    }
    
    public function get_entrypoint_args(array $args)
    {
        $this->entrypoint_arguments = $args;
    }
    
    public function handle_on_invalid_authentication(array $args)
    {
        $this->view_handler
            ->set_view_directory(ROOT_DIR . '/src/Ninja/NJViews/')
            ->render('401.html.php');
    }
    
    public function handle_on_invalid_permission($args)
    {
        $this->view_handler
            ->set_view_directory(ROOT_DIR . '/src/Ninja/NJViews/')
            ->render('403.html.php');
    }
    
    public function handle_on_page_not_found($args)
    {
        $this->view_handler
            ->set_view_directory(ROOT_DIR . '/src/Ninja/NJViews/')
            ->render('404.html.php');
    }
}
