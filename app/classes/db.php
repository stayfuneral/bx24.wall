<?php

class DB {

    public $connection;
    private $keys, $values, $result;

    public function __construct() {
        $this->connection = @mysqli_connect('localhost', 'wall', 'Funeral@123', 'user9580671_wall') or die('Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error());      
    }

    public function customSelect(string $sql) {
        if(!empty($this->result)) {
            unset($this->result);
        }

        $query = mysqli_query($this->connection, $sql);

        while($row = mysqli_fetch_assoc($query)) {
            $this->result[] = $row;
        }
        return $this->result;
    }

    /*
     * @param string $tableName;
     * @param bool $where;
     * @param array $whereParams = ['Field' => 'Value'];
     * @return array $result;
     */

    public function Select(string $tableName, bool $where = false, array $whereParams = []) : array {
        if(!empty($this->result)) {
            unset($this->result);
        }
        
//        $result = [];
        $SQL = "select * from $tableName";
        if ($where === true) {
            $SQL .= " where ";
            if(!is_array($whereParams)) {
                return false;
            } else {
                if(count($whereParams) > 1) {
                    $params = [];
                    foreach ($whereParams as $key => $value) {
                        $params[] = $key . ' = \'' . $value . '\'';
                    }
                    $SQL .= implode(' and ', $params);
                } else {
                    foreach ($whereParams as $key => $value) {
                        $SQL .= $key . ' = \'' . $value . '\'';
                    }
                }
            }
        }

        $Query = mysqli_query($this->connection, $SQL);
        while ($row = mysqli_fetch_assoc($Query)) {
            $result[] = $row;
        }
        return $this->result;
    }
    
    public function Desc($tableName) : array {
        if(!empty($this->result)) {
            unset($this->result);
        }
        $SQL = "DESC $tableName";
        $Query = mysqli_query($this->connection, $SQL);
        while ($row = mysqli_fetch_assoc($Query)) {
            $this->result[] = $row;
        }
        return $this->result;
    }

    /*
     * @param string $tableName;
     * @param array $Values = [
     *      'Field 1' => 'Value 1',
     *      'Field 2' => 'Value 2'
     * ];
     * @return true or null
     */
    public function Insert(string $tableName, array $Values) :array {
        if(!empty($this->result)) unset($this->result);
        $result = [];
        $keys = [];
        $values = [];
        foreach ($Values as $key => $value) {
            $keys[] = $key;
            $values[] = '\''.$value.'\'';
        }
        $SQL = "insert into $tableName (".implode(', ', $keys).") values (".implode(', ', $values).")";
        $Query = mysqli_query($this->connection, $SQL);
        if($Query !== false) {
            $this->result['result'] = 'success';
        } else {
            $this->result['error'] = 'error';
        }
        return $this->result;
    }
    
    /*
     * @param string $tableName - имя таблицы
     * @param array $updateParams - массив параметров в формате ['название поля' => 'новое значение']
     * @param bool $where
     * @param array $whereParams - массив параметров для оператора WHERE в формате ['название поля' => 'значение поля']
     */
    
    public function Update($tableName, $updateParams, $where = false, $whereParams = []) : bool {
        
    }
    
    public function Test() {
        if(!empty($this->result)) {
            unset($this->result);
        }
        $this->result = 'test';
        return $this->result;
    }

    public function __destruct() {
        
        mysqli_close($this->connection);
    }

}