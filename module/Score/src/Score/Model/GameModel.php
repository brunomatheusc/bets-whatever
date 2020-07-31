<?php

namespace Score\Model;

use Exception;
use Application\Classes\Funcoes;
use Zend\Session\Container;

class GameModel{
    private $funcao, $sessao, $config;

    public function __construct(Funcoes $funcao, $config = ''){
        $this->funcao = $funcao;
        $this->config = $config;
        $this->sessao = new Container('bets');
    }

    public function adicionar($params){
        try{
            $sql = "exec today_games :idliga, :game, :data, :idhome, :idaway, :casa, :visitante, :liga";
            $this->funcao->executarSQL($sql, $params, '');

            // foreach($params as $p){
            //     $p['data'] = date('Y-m-d H:i', strtotime(date('Y-m-d') . $p['hora']));
            //     $this->funcao->executarSQL($sql, $p);
            // }
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }

        return array("retorno" => true, "msg" => "Cadastrado com sucesso!");
    }

    public function atualizar($params){
        try{
            $sql = "exec update_games_sp :idliga, :game, :data, :idhome, :idaway, :casa, :visitante, :liga, 
                    :placar, :ht, :ft, :attht, :attft, :chutes_ft";
            // $this->funcao->executarSQL($sql, $params, '');

            foreach($params as $p){
                $this->funcao->executarSQL($sql, $p, '');
                // $p['data'] = strftime("%Y-%m-%d %H:%M", strtotime($data . $p['hora']));
            }
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }

        return array("retorno" => true);
    }

    public function editar(){
        try{
    
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function listar(){
        try{
    
        } catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}