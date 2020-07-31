<?php

namespace Application\Controller;

use Application\Classes\Funcoes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Score\Model\MatchModel;

class IndexController extends AbstractActionController{
    public function indexAction(){
        $dados = (new MatchModel(new Funcoes($this)))->home();
        return new ViewModel(array("dados" => $dados['dados']));
    }
}
