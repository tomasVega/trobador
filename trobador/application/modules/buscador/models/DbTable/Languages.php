<?php

class Buscador_Model_DbTable_Languages extends Zend_Db_Table_Abstract {
    
    protected $_name = 'languages';
    
    // Devuelve la lista de idiomas
    public function getListaIdiomas($idioma) {

        $select = $this->select();
        $select->from($this->_name)
                ->where("language = ?", $idioma);

        $rows = $this->fetchAll($select);

        return $rows;

    }
    
}


?>
