<?php

namespace Bets\Model;

use Exception;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ProdutoTable extends AbstractTableGateway{
    protected $table = 'tbl_produtos';
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Produto());
        $this->initialize();
    }
    
    public function fetchAll($pageNumber = 1, $countPerPage = 5){
        $select = new Select();
        $select->from($this->table)->order('produto_id');
        
        $adapter = new DbSelect($select, $this->adapter, $this->resultSetPrototype);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($countPerPage);
        
        return $paginator;
    }
    
    public function getProduto($idProduto){
        $idProduto = (int)$idProduto;
        $rowSet = $this->select(array(
            'produto_id' => $idProduto
        ));
        $row = $rowSet->current();
        
        if (!$row){
            throw nex \Exception("Registro id $idProduto não encontrado");
        }
        
        return $row;
    }
    
    public function saveProduto(Produto $produto){
        $data = array(
            'produto_nome' => $produto->produto_nome,
            'produto_preco' => $produto->produto_preco,
            'produto_foto' => $produto->produto_foto,
            'produto_descricao' => $produto->produto_descricao,
            'produto_status' => $produto->produto_status
        );
        
        $idProduto = (int) $produto->produto_id;
        
        if ($idProduto == 0){
            try {
                $this->insert($data);
            } catch (Exception $ex) {
                $pdoException = $ex->getPrevious();
                var_dump($pdoException);
                exit;
            }
        } else {
            if ($this->getProduto($idProduto)){
                $this->update($data, array(
                    'produto_id' => $idProduto
                ));
            } else {
                throw new \Exception ("Produto $produto->produto_nome não encontrado no banco.");
            }
        }
    }
    
    public function removeProduto($idProduto){
        $idProduto = (int) $idProduto;
        
        if ($this->getProduto($idProduto)){
            $this->delete(array(
               'produto_id' => $idProduto 
            ));
        } else {
            throw new \Exception("Produto não encontrado");
        }
    }
}
