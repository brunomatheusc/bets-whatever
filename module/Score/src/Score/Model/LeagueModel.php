<?php

namespace Score\Model;

use Exception;
use Application\Classes\Funcoes;
use Zend\Session\Container;

class LeagueModel{
    private $funcao, $sessao, $config;

    public function __construct(Funcoes $funcao, $config = ''){
        $this->funcao = $funcao;
        $this->config = $config;
        $this->sessao = new Container('bets');
    }

    public function ligas(){
        try{
            $sql = "exec ligas_sp";
            return $this->funcao->executarSQL($sql);
        } catch(Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }
}