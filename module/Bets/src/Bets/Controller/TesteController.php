<?php

namespace Bets\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TesteController extends AbstractActionController{
    public function indexAction(){
        return new ViewModel();
    }
}