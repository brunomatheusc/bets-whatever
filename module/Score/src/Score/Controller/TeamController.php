<?php

namespace Score\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Score\Model\LeagueModel;
use Score\Model\TeamModel;
use Application\Classes\Funcoes;

class TeamController extends AbstractActionController{
    public function indexAction(){
        return new ViewModel();
    }

    public function adicionarAction(){
        $funcao = new Funcoes($this);

        $ligas = (new LeagueModel($funcao))->ligas();
        $times = (new TeamModel($funcao))->times();

        return new ViewModel(array("ligas" => $ligas, "times" => $times));
    }

    public function timesAction(){
        $request = $this->getRequest();

        if ($request->isPost()){
            $params = $this->params()->fromPost();
            $times = (new TeamModel(new Funcoes($this)))->timesByLiga($params['liga']);
        }

        return new JsonModel(array("retorno" => true, "times" => $times));
    }

    public function editarAction(){
        echo "<pre>";
        var_dump("Oi");
        echo "</pre>";
        exit;
        
        return new ViewModel();
    }

    public function listarTodosAction(){
        
        return new ViewModel();
    }

    public function addTimeAction(){
        
        return new JsonModel();
    }

    public function editTimeAction(){
        
        return new JsonModel();
    }

    public function statsAction(){
        
        return new ViewModel();
    }
}