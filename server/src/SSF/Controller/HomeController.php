<?php

namespace SSF\Controller;

class HomeController extends SSFBaseController
{
    public function home()
    {
        $this->view_handler->render('home.html.php');
    }
}
