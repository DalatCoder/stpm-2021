<?php

namespace Sample\Controller;

use Ninja\NJBaseController\NJBaseController;

class SampleController extends NJBaseController
{
    public function show_home_page()
    {
        $introduction = 'Chào mừng đến với PHP Ninja Framework';
        
        $this->view_handler->render('index.html.php', [
            'introduction' => $introduction
        ]);
    }
}
