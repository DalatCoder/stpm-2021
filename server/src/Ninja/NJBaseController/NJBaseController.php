<?php

namespace Ninja\NJBaseController;

use Ninja\NJFlushMessage;
use Ninja\ViewHandler;

class NJBaseController
{
    public ViewHandler $view_handler;
    public NJFlushMessage $flush_handler;
    public array $entrypoint_arguments;
    
    public function __construct()
    {
        $this->view_handler = new ViewHandler();
        $this->flush_handler = new NJFlushMessage();
    }
    
    public function get_entrypoint_args(array $args)
    {
        $this->entrypoint_arguments = $args + ['flush_handler' => $this->flush_handler];
        $this->view_handler->set_general_args($this->entrypoint_arguments);
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
    
    public function route_redirect($url, $status_code = 301)
    {
        http_response_code($status_code);
        header('location: ' . $url);
        
        exit();
    }
}
