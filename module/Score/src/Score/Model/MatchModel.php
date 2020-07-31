<?php

namespace Score\Model;

use Exception;
use Application\Classes\Funcoes;
use Zend\Session\Container;

class MatchModel{
    private $funcao, $sessao, $config;

    public function __construct(Funcoes $funcao, $config = ''){
        $this->funcao = $funcao;
        $this->config = $config;
        $this->sessao = new Container('bets');
    }

    public function home(){
        try{
            $sql = "exec home_sp";
            return array("retorno" => true, "dados" => $this->funcao->executarSQL($sql));
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}