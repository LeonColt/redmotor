
<?php
/*
 * Copyright 2015-2017 Leon Colt/Lionman Bloodsucker
 * This program/script/library is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Created by PhpStorm.
 * User: LC
 * Date: 14/04/2017
 * Time: 15:06
 */

const ascending = "ASC";
const descending = "DESC";

const db_operator_equal = "=";
const db_operator_not_equal = "!=";
const db_operator_greater_than = ">";
const db_operator_less_than = "<";
const db_operator_greater_than_or_equal = ">=";
const db_operator_less_than_or_equal = "<=";
const db_operator_between = "BETWEEN";
const db_operator_like = "LIKE";
const db_operator_in = "IN";

const join = "JOIN";
const join_inner = "INNER JOIN";
const join_left = "LEFT JOIN";
const join_right = "RIGHT JOIN";
const join_full = "FULL JOIN";
const join_full_outer = "FULL OUTER JOIN";

abstract class BasicCollection implements ArrayAccess, Countable, Iterator {
    private $list;
    public function __construct() {$this->list = array();}
    public function next(){return next($this->list);}
    public function previous() {return prev($this->list);}
    public function key(){return key($this->list);}
    public function valid(){return $this->key() !== NULL;}
    public function rewind(){reset($this->list);}
    public function clear(){$this->list = array();}
    public function removeByItem($item) {
        if(array_search($item, $this->list, true)) {
        }
    }
    public function removeByIndex($index) {
        if($this->offsetExists($index)) unset($this->list[$index]);
        else throw new Exception("Index out of Bounds");
    }
    public function count() : int{return count($this->list);}
    public function isEmpty() : bool {return $this->count() === 0;}
    public function offsetExists($offset) : bool {return array_key_exists($offset, $this->list);}
    public abstract function current();
    public abstract function offsetGet($offset);
    public abstract function offsetSet($offset, $value);
    public function offsetUnset($offset) {$this->removeByIndex($offset);}
    public function append($item) {array_push($this->list, $item);}
    public function get($index) {
        if($this->offsetExists($index)) return $this->list[$index];
        else throw new Exception("Index out of Bounds");
    }
    public function set($index, $item) {$this->list[$index] = $item;}
    public function begin() {return reset($this->list);}
    public function end() {return FALSE;}
    public function rbegin() {return end($this->list);}
    public function rend(){return FALSE;}
}

class Collection extends BasicCollection {
    public function current(){ return $this->get($this->key());}
    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)) return $this->get($offset);
        else throw new Exception("Index out of Bounds");
    }
    public function offsetSet($offset, $value){ $this->set($offset, $value);}
}
class Parameter {
    const BIND_BY_PARAM = 1;
    const BIND_BY_VALUE = 2;
    const BIND_BY_COLUMN = 3;
    private $variable, $type, $length, $bind_by;
    protected $value;
    public function __construct($variable, $value, int $type = PDO::PARAM_STR, int $length = null, int $bind_by = Parameter::BIND_BY_VALUE)
    {
        $this->variable = $variable;
        $this->value = $value;
        $this->type = $type;
        $this->length = $length;
        $this->bind_by = $bind_by;
    }
    public function setVariable($variable){$this->variable = $variable;}
    public function getVariable() {return $this->variable;}
    public function setValue($value) {$this->value = $value;}
    public function setValueRef(&$value){$this->value =& $value;}
    public function setType(int $type){$this->type = $type;}
    public function getType() : int {return $this->type;}
    public function setLength(int $length) {$this->length = $length;}
    public function getLength() : int {return $this->length;}
    public function &getValueRef() {return $this->value;}
    public function getValue() {return $this->value;}
    public function getBindBy() : int {return $this->bind_by;}
    public function setBindBy(int $bind_by) {$this->bind_by = $bind_by;}
}
class ParameterArray extends BasicCollection {
    public function current() : Parameter{return $this->get($this->key());}
    public function get($index) : Parameter{return parent::get($index);}
    public function append($parameter)
    {
        if($parameter instanceof Parameter) parent::append($parameter);
        else throw new Exception("Invalid Parameter");
    }

    public function offsetGet($offset) : Parameter
    {
        if($this->offsetExists($offset)) return $this->get($this->key());
        else throw new Exception("Index out of Bounds");
    }
    public function offsetSet($offset, $value){
        if($value instanceof Parameter) $this->set($offset, $value);
        else throw new Exception("Invalid Value");
    }
}
class Column {
    private $column, $table, $as;
    public function __construct($column = null, $table = null, $as = null)
    {
        $this->column = $column;
        $this->table = $table;
        $this->as = $as;
    }
    /**
     * @return string|Select
     */
    public function getColumn(){return $this->column;}
    /**
     * @param string|Select $column
     */
    public function setColumn($column){$this->column = $column;}
    /**
     * @return string
     */
    public function getTable(){return $this->table;}
    /**
     * @param string $table
     */
    public function setTable($table){$this->table = $table;}
    /**
     * @return string
     */
    public function getAs(){return $this->as;}
    /**
     * @param string $as
     */
    public function setAs($as){$this->as = $as;}
    public function __toString() : string
    {
        if($this->column === null || $this->column === NULL) {
            debug_print_backtrace();
            trigger_error("unable to cast to string if column is null");
            exit();
        }
        $str = "";
        if($this->table !== null) $str.=($this->table.".");
        $str.=$this->column;
        if($this->as !== null) $str.=(" as ".$this->as);
        return $str;
    }
}
class ColumnArray extends BasicCollection {
    public function current() : Column{return $this->get($this->key());}
    public function offsetGet($offset) : Column{return $this->get($offset);}
    public function offsetSet($offset, $value)
    {
        if($value instanceof Column) parent::offsetSet($offset, $value);
        else throw new Exception("Invalid Value, Value must be Instance Of DatabaseColumn Class");
    }
}
class Query {
    protected $query;
    private $parameter;
    private $success_executed;
    public function __construct() {
        $this->query = "";
        $this->parameter = new ParameterArray();
        $this->result = null;
        $this->success_executed = false;
    }
    public function __toString() : string{$this->processQuery();return $this->query;}
    public function clear(){$this->query = "";}
    public function set(string $query){$this->query = $query;}
    public function append(string $query) { $this->query .= $query; }
    public function getParameterArray() : ParameterArray{return $this->parameter;}
    public function appendParameter(Parameter $parameter) {$this->getParameterArray()->append($parameter);}
    public function clearParameter() {$this->getParameterArray()->clear();}
    protected function processQuery() {}
    public final function getParameterVariableIntegerOrder() {return $this->getParameterArray()->count() + 1;}
    public function setSuccessExecuted(bool $success_executed) {$this->success_executed = $success_executed;}
    public function isSuccessExecuted() : bool {return $this->success_executed;}
}
class JoinTableOn {
    private $table_left, $column_left, $operator, $table_right, $column_right, $operator_separator;
    public function __construct(string $table_left = null, string $column_left = null, string $operator = null, string $table_right = null, string $column_right, string $operator_separator = null) {
        $this->table_left = $table_left;
        $this->column_left = $column_left;
        $this->operator = $operator;
        $this->table_right = $table_right;
        $this->column_right = $column_right;
        $this->operator_separator = $operator_separator;
    }
    /**
     * @return string
     */
    public function getTableLeft() : string{return $this->table_left;}
    /**
     * @param string $table_left
     */
    public function setTableLeft(string $table_left){$this->table_left = $table_left;}
    /**
     * @return string
     */
    public function getColumnLeft() : string{return $this->column_left;}
    /**
     * @param string $column_left
     */
    public function setColumnLeft(string $column_left){$this->column_left = $column_left;}
    /**
     * @return string
     */
    public function getOperator() : string{return $this->operator;}
    /**
     * @param string $operator
     */
    public function setOperator(string $operator){$this->operator = $operator;}
    /**
     * @return string
     */
    public function getTableRight() : string{return $this->table_right;}
    /**
     * @param string $table_right
     */
    public function setTableRight(string $table_right){$this->table_right = $table_right;}
    /**
     * @return string
     */
    public function getColumnRight() : string{return $this->column_right;}
    /**
     * @param string $column_right
     */
    public function setColumnRight(string $column_right){$this->column_right = $column_right;}
    /**
     * @return string
     */
    public function getOperatorSeparator() : string{return $this->operator_separator;}
    /**
     * @param string $operator_seperator
     */
    public function setOperatorSeparator(string $operator_seperator){$this->operator_seperator = $operator_seperator;}
    public function __toString() : string
    {
        $str = (" ON ".$this->table_left.".".$this->column_left." ".$this->operator." ".$this->table_right.".".$this->column_right);
        if($this->operator_separator !== null) $str.=(" ".$this->operator_separator);
        return $str;
    }
}
class JoinTableOnArray extends BasicCollection {
    public function current() : JoinTableOn{return $this->get($this->key());}
    public function offsetGet($offset) : JoinTableOn{return $this->get($offset);}
    /**
     * @param mixed $offset
     * @param JoinTableOn $value
     * @throws Exception
     */
    public function offsetSet($offset, $value){
        if($value instanceof JoinTableOn) parent::offsetSet($offset, $value);
        else throw new Exception("Invalid Parameter");
    }
}
class JoinTable {
    const INNER_JOIN = "INNER JOIN";
    const LEFT_JOIN = "LEFT JOIN";
    const RIGHT_JOIN = "RIGHT JOIN";
    const FULL_JOIN = "FULL JOIN";
    const LEFT_OUTER_JOIN = "LEFT OUTER JOIN";
    const RIGHT_OUTER_JOIN = "LEFT OUTER JOIN";
    const FULL_OUTER_JOIN = "LEFT OUTER JOIN";
    private $type;
    private $table;
    private $ons;
    public function __construct($table, $type = JoinTable::INNER_JOIN, JoinTableOnArray $ons = null) {
        $this->table = $table;
        $this->type = $type;
        $this->ons = ($ons === null) ? new JoinTableOnArray() : $ons;
    }
    public function setJoinType(string $type) {$this->type = $type;}
    public function getTable() : string {return $this->table;}
    public function setTable(string $table) {$this->table = $table;}
    public function appendOn(JoinTableOn $on) {$this->ons->append($on);}
    public function getOnArray() : JoinTableOnArray {return $this->ons;}
    public function __toString() : string
    {
        if($this->type === null) {
            debug_print_backtrace();
            trigger_error("Type of JOIN TABLE cannot be null, and must be string");
            exit();
        }
        if($this->table === null) {
            debug_print_backtrace();
            trigger_error("Table Name for JOIN TABLE cannot be null, and must be string");
            exit();
        }
        $str = $this->type." ".$this->table;
        if($this->ons->isEmpty()) return $str;
        foreach ($this->ons as $on) $str.=(" ".$on);
        return $str;
    }
}
class JoinTableArray extends BasicCollection {
    public function current() : JoinTable{return $this->get($this->key());}
    public function offsetGet($offset) : JoinTable{return parent::offsetGet($offset);}
    /**
     * @param mixed $offset
     * @param JoinTable $value
     * @throws Exception
     */
    public function offsetSet($offset, $value){ if ($value instanceof JoinTable ) parent::offsetSet($offset, $value); else throw new Exception("Invalid Parameter");}
    public function begin() : JoinTable{return parent::begin();}
    public function rbegin() : JoinTable{return parent::rbegin();}
}
class Select extends Query
{
    const FETCH_ALL = 0;
    const FETCH_BOTH = PDO::FETCH_BOTH;
    const FETCH_ASSOC = PDO::FETCH_ASSOC;
    const FETCH_BOUND = PDO::FETCH_BOUND;
    private $main_table, $columns, $joins, $wheres, $order_by, $group_by, $havings, $distinct, $limit, $offset, $as;
    private $with_rollup;
    private $fetch_mode;
    private $statement;
    public function __construct(string $table)
    {
        parent::__construct();
        $this->main_table = $table;
        $this->columns = new ColumnArray();
        $this->joins = new JoinTableArray();
        $this->wheres = array();
        $this->group_by = array();
        $this->havings = array();
        $this->order_by = array();

        $this->distinct = false;
        $this->limit = null;
        $this->offset = null;
        $this->as = null;

        $this->with_rollup = false;

        $this->fetch_mode = Select::FETCH_ALL;
    }
    public function clear_limit(){$this->limit = null;}
    public function clear_offset(){$this->offset = null;}
    public function appendColumn(Column $column){$this->columns->append($column);}
    public function appendJoin(JoinTable $join_table){$this->joins->append($join_table);}
    public function appendJoinOn(JoinTableOn $join_table_on){$this->joins->rbegin()->appendOn($join_table_on);}
    public function appendJoinOnEx($left_table, $left_column, $operator, $right_table, $right_column, $operator_separator = null) {$this->joins->rbegin()->appendOn(new JoinTableOn($left_table, $left_column, $operator, $right_table, $right_column, $operator_separator));}
    public function append_where(string $expression, $seperator_operator = null){array_push($this->wheres, array($expression, $seperator_operator));}
    public function append_having(string $expression, $seperator_operator = null){array_push($this->havings, array($expression, $seperator_operator));}
    public function order_by(string $column, $asc_desc = "ASC"){array_push($this->order_by, array($column, $asc_desc));}
    public function group_by(string $expression){array_push($this->group_by, $expression);}
    public function with_rollup($with_rollup = true){$this->with_rollup = $with_rollup;}
    public function distinct($distinct = true){$this->distinct = $distinct;}
    public function limit(int $limit){$this->limit = $limit;}
    public function offset(int $offset){$this->offset = $offset;}
    public final function getFetchMode() : int {return $this->fetch_mode;}
    public final function setFetchMode(int $fetch_mode) {$this->fetch_mode = $fetch_mode;}
    public final function fetchAssoc() {$this->fetch_mode = PDO::FETCH_ASSOC;}
    public final function fetchBoth() {$this->fetch_mode = PDO::FETCH_BOTH;}
    public final function fetchBound() {$this->fetch_mode = PDO::FETCH_BOUND;}
    public final function setAs(string $as) {$this->as = $as;}
    public function setStatement(PDOStatement $statement) {$this->statement = $statement;}
    public function getStatement() : PDOStatement {return $this->statement;}
    public function fetch(int $style, int $cursor_orientation = PDO::FETCH_ORI_NEXT, int $cursor_offset = 0 ) {return $this->getStatement()->fetch($style, $cursor_orientation, $cursor_offset );}
    public function fetchAll(int $style, mixed $argument = null, array $args = array()) {
        if($argument === null) return $this->getStatement()->fetchAll($style);
        else return $this->getStatement()->fetchAll($style, $argument, $args);
    }
    public function rowCount() {return $this->getStatement()->rowCount();}
    protected function processQuery()
    {
        $this->clear();
        $this->append("SELECT ");
        if($this->distinct) $this->append("DISTINCT ");
        if($this->columns->isEmpty()) $this->append("* ");
        else {
            foreach($this->columns as $index => $column) {
                $this->append($column);
                ($index === $this->columns->count() - 1) ? $this->append(" ") : $this->append(", ");
            }
        }
        $this->query .= "FROM ";
        if($this->main_table instanceof Select)
            $this->query .= ("(".$this->main_table.") "."AS ".$this->main_table->as." ");
        else $this->query .= $this->main_table.' ';
        foreach($this->joins as $join) $this->append($join." ");
        if(count($this->wheres) > 0)
        {
            $this->query .= "WHERE ";
            foreach($this->wheres as $where)
            {
                if($where[1] !== null) $this->query .= $where[1].' ';
                $this->query .= $where[0].' ';
            }
        }
        if(count($this->group_by) > 0)
        {
            $this->query .= "GROUP BY ";
            for($i = 0; $i<count($this->group_by); $i++)
            {
                $this->query .= $this->group_by[0].'';
                if($i < count($this->group_by) - 1) $this->query .= ', '; else $this->query .= ' ';
            }
            if($this->with_rollup) $this->query .= 'WITH ROLLUP ';
        }
        if(count($this->havings) > 0)
        {
            $this->query .= "HAVING ";
            foreach($this->havings as $having)
            {
                if($having[1] !== null) $this->query .= $having[1].' ';
                $this->query .= $having[0].' ';
            }
        }
        if(count($this->order_by) > 0)
        {
            $this->query .= 'ORDER BY ';
            for($i = 0; $i < count($this->order_by); $i++)
            {
                $this->query .= $this->order_by[$i][0].' '.$this->order_by[$i][1].' ';
                if($i < count($this->order_by) - 1) $this->query .= ',';
            }
        }
        if($this->limit !== null) $this->query .= 'LIMIT '.$this->limit.' ';
        if($this->offset !== null) $this->query .= 'OFFSET '.$this->offset.' ';
    }
}
class Insert extends Query
{
    private $main_table, $columns, $update_on_duplicate, $primary_key;
    public function __construct($table, $update_on_duplicate = false)
    {
        parent::__construct();
        $this->main_table = $table;
        $this->columns = new ColumnArray();
        $this->update_on_duplicate = $update_on_duplicate;
        $this->primary_key = new ColumnArray();
    }
    public function clearColumn(){$this->columns->clear();}
    public function appendColumn($column){
        if($column instanceof Column) $this->columns->append($column);
        else if($column instanceof Select) {
            $this->columns->append(new Column("( ".$column." )"));
            foreach ($column->getParameterArray() as $parameter)
                $this->appendParameter($parameter);
        }
    }
    public function appendColumnValue( Column $column, Parameter $value) {
        $this->columns->append($column);
        $this->appendParameter($value);
    }
    public function appendPrimaryKey( Column $column) {$this->primary_key->append($column);}
    protected function processQuery()
    {
        $this->clear();
        $this->append("INSERT"." INTO ".$this->main_table." ");
        if($this->columns->count() > 0) {
            $this->append(" ( ");
            $this->columns->begin();
            $first_index = $this->columns->key();
            $inserted_column = array();
            $number_of_columns = 0;
            foreach ($this->columns as $index => $column) {
                if($index === $first_index) {
                    $this->append($column);
                    array_push($inserted_column, (string)$column);
                    $number_of_columns++;
                }
                else {
                    $column_same = false;
                    foreach ($inserted_column as $item) {
                        if(strcmp($item, $column) === 0) {
                            $column_same = true;
                            break;
                        }
                    }
                    if(!$column_same) {
                        $this->append(", ".$column);
                        array_push($inserted_column, (string)$column);
                        $number_of_columns++;
                    }
                }
            }
            $this->append(" ) ");
            $this->append(" VALUES ");
            $counter = 0;
            $this->getParameterArray()->begin();
            $first_index = $this->getParameterArray()->key();
            $this->getParameterArray()->rbegin();
            foreach ($this->getParameterArray() as $index => $parameter) {
                if($counter === 0) {
                    if($index !== $first_index) $this->append(", ");
                    $this->append("( ");
                }
                if($index !== $first_index) $this->append(", ");
                if(is_numeric($parameter->getVariable())) $this->append("?");
                else $this->append($parameter->getVariable());
                if( $counter === $number_of_columns - 1) {
                    $this->append(" )");
                    $counter = 0;
                }
                else $counter++;
            }
        }
        if($this->update_on_duplicate)
        {
            $this->columns->rbegin();
            $last_index = $this->columns->key();
            $this->append(" ON DUPLICATE KEY UPDATE ");
            foreach ($this->columns as $index => $column) {
                $is_primary_key = false;
                foreach ($this->primary_key as $pk) {
                    if(strcmp($column->getColumn(), $pk->getColumn()) === 0) {
                        $is_primary_key = true; break;
                    }
                }
                if(!$is_primary_key) {
                    $this->append($column." = VALUES ( ");
                    $this->append($column." ) ");
                    if($index !== $last_index) $this->append(",");
                }
            }
            $this->query .= ';';
        }
        else $this->query .= ';';
    }
}
class Update extends Query
{
    const SET_COLUMN = 0;
    const SET_EXPRESSION = 1;
    private $table, $joins, $sets, $wheres, $order_by, $limit, $offset;
    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;
        $this->joins = array();
        $this->sets = array();
        $this->wheres = array();
        $this->order_by = array();
        $this->limit = null;
        $this->offset = null;
    }
    public function set_table($table){$this->table = $table;}
    public function clear_join(){$this->joins = array();}
    public function clear_where(){$this->wheres = array();}
    public function clear_order_by(){$this->order_by = array();}
    public function clear_limit(){$this->limit = null;}
    public function clear_offset(){$this->offset = null;}
    public function append_join($type, $table)
    {
        array_push($this->joins, array($type, $table, null, null, null, null));
    }
    public function append_on($table_left, $left, $operator, $table_right, $right, $seperator_operator = null)
    {
        if($this->joins[count($this->joins)-1][2] === null) $this->joins[count($this->joins)-1][2] = array();
        array_push($this->joins[count($this->joins)-1][2], array(array($left, $table_left), $operator, array($right, $table_right), $seperator_operator));
    }
    public function appendSet(Column $column, $expression) {
        array_push($this->sets, array($column, $expression));
    }
    public function append_where($expression, $seperator_operator = null)
    {
        array_push($this->wheres, array($expression, $seperator_operator));
    }
    protected function processQuery()
    {
        $this->append("UPDATE ");
        $this->append($this->table.' ');
        foreach($this->joins as $join)
        {
            $this->append($join[0]." ".$join[1].' ');
            if(isset($join[2]))
            {
                $this->query .= "ON ";
                foreach($join[2] as $on)
                {
                    if($on[3] !== null) $this->query .= $on[3]." ";
                    $this->query .= $on[0][1].'.'.$on[0][0].' ';
                    $this->query .= $on[1].' ';
                    $this->query .= $on[2][1].'.'.$on[2][0].' ';
                }
            }
        }
        if(count($this->sets) > 0)
        {
            $this->query .= 'SET ';
            for($i = 0; $i<count($this->sets); $i++)
            {
                $this->query .= $this->sets[$i][0].' ';
                $this->query .= '= ';
                $this->query .= $this->sets[$i][1].' ';
                if( $i < count($this->sets) - 1) $this->query .= ",";
            }
        }
        if(count($this->wheres) > 0)
            $this->query .= "WHERE ";
        {
            foreach($this->wheres as $where)
            {
                if($where[1] !== null) $this->query .= $where[1].' ';
                $this->query .= $where[0].' ';
            }
        }
        if(true)//if(count($this->tables) < 2)
        {
            if(count($this->order_by) > 0)
            {
                $this->query .= 'ORDER BY ';
                for($i = 0; $i < count($this->order_by); $i++)
                {
                    $this->query .= $this->order_by[$i][0].' '.$this->order_by[$i][1].' ';
                    if($i < count($this->order_by) - 1) $this->query .= ',';
                }
            }
        }
        if(true)//if(count($this->tables) < 2)
        {
            if($this->limit !== null) $this->query .= 'LIMIT '.$this->limit.' ';
        }
        if(true)//if(count($this->tables) < 2)
        {
            if($this->offset !== null) $this->query .= 'OFFSET '.$this->offset.' ';
        }
    }
}
class Delete extends Query
{
    private $main_table;
    private $tables, $joins, $wheres, $order_by, $limit, $offset;
    public function __construct($table)
    {
        parent::__construct();
        $this->main_table = $table;
        $this->tables = array();
        $this->joins = array();
        $this->wheres = array();
        $this->order_by = array();
        $this->limit = null;
        $this->offset = null;
    }
    public function clear_table(){$this->tables = array();}
    public function clear_join(){$this->joins = array();}
    public function clear_where(){$this->wheres = array();}
    public function clear_order_by(){$this->order_by = array();}
    public function clear_limit(){$this->limit = null;}
    public function clear_offset(){$this->offset = null;}
    public function clear_parameter(){$this->getParameterArray()->clear();}
    public function append_on($table_left, $left, $operator, $table_right, $right, $seperator_operator = null)
    {
        if($left instanceof Select)
        {
            $temp = $left;
            $left = "(";
            $left .= $temp;
            $left .= ") ";
            foreach($this->getParameterArray() as $param) $this->getParameterArray()->append($param);
        }
        if($right instanceof Select)
        {
            $temp = $right;
            $right = "(";
            $right .= $temp;
            $right .= ") ";
            foreach($this->getParameterArray() as $param) $this->getParameterArray()->append($param);
        }
        if($this->joins[count($this->joins)-1][2] === null) $this->joins[count($this->joins)-1][2] = array();
        array_push($this->joins[count($this->joins)-1][2], array(array($left, $table_left), $operator, array($right, $table_right), $seperator_operator));
    }
    public function append_where($expression, $seperator_operator = null)
    {
        array_push($this->wheres, array($expression, $seperator_operator));
    }
    public function append_order_by($column, $asc_desc = ascending)
    {
        array_push($this->order_by, array($column, $asc_desc));
    }
    public function append_table($table)
    {
        if($table instanceof Select)
        {
            $temp = $table;
            $table = "(";
            $table .= $temp->query();
            $table .= ") ";
            foreach($this->getParameterArray() as $param) $this->appendParameter($param);
        }
        array_push($this->tables, $table);
    }
    public function append_join($type, $table)
    {
        if($table instanceof Select)
        {
            $temp = $table;
            $table = "(";
            $table .= $temp->query();
            $table .= ") ";
            foreach($this->getParameterArray() as $param) $this->appendParameter($param);
        }
        array_push($this->joins, array($type, $table, null, null, null, null));
    }
    protected function processQuery()
    {
        $this->query = "DELETE ";
        if(count($this->tables) > 1)
        {
            for($i = 0; $i<count($this->tables); $i++)
            {
                $this->query .= $this->tables[$i];
                if($i < count($this->tables) - 1) $this->query .= ',';
            }
            $this->query .= " FROM ";
            $this->query .= $this->main_table;
            $this->query .=" ";
            foreach($this->joins as $join)
            {
                $this->query .= $join[0]." ".$join[1].' ';
                if(isset($join[2]))
                {
                    $this->query .= "ON ";
                    foreach($join[2] as $on)
                    {
                        if($on[3] !== null) $this->query .= $on[3]." ";
                        $this->query .= $on[0][1].'.'.$on[0][0].' ';
                        $this->query .= $on[1].' ';
                        $this->query .= $on[2][1].'.'.$on[2][0].' ';
                    }
                }
            }
        }
        else
        {
            $this->query .= "FROM ".$this->main_table.' ';
        }
        if(count($this->wheres) > 0)
        {
            $this->query .= "WHERE ";
            foreach($this->wheres as $where)
            {
                if($where[1] !== null) $this->query .= $where[1].' ';
                $this->query .= $where[0].' ';
            }
        }
        if(true)//if(count($this->tables) < 2)
        {
            if(count($this->order_by) > 0)
            {
                $this->query .= 'ORDER BY ';
                for($i = 0; $i < count($this->order_by); $i++)
                {
                    $this->query .= $this->order_by[$i][0].' '.$this->order_by[$i][1].' ';
                    if($i < count($this->order_by) - 1) $this->query .= ',';
                }
            }
        }
        if(true)//if(count($this->tables) < 2)
        {
            if($this->limit !== null) $this->query .= 'LIMIT '.$this->limit.' ';
        }
        if(true)//if(count($this->tables) < 2)
        {
            if($this->offset !== null) $this->query .= 'OFFSET '.$this->offset.' ';
        }
    }
}
class QueryArray extends Collection {
    private $is_transaction;
    public function __construct() {
        parent::__construct();
        $this->is_transaction = false;
    }
    public function isExecuteAsTransaction() : bool {return $this->is_transaction;}
    public function setExecuteAsTransaction(bool $is_transaction = true) {$this->is_transaction = $is_transaction;}
    public function append($item)
    {
        parent::append($item);
    }
}
class QueryArrayArray extends BasicCollection {
    public function current() : QueryArray{return $this->get($this->key());}
    public function offsetGet($offset) : QueryArray{return $this->get($this->key());}

    public function offsetSet($offset, $value)
    {
        if($value instanceof QueryArray) parent::offsetSet($offset, $value);
    }
}
class Runner {
    private $connection = null, $query_array_array;
    public function __construct(PDO $connection = null) {
        if($connection !== null) {
            $this->connection = $connection;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $this->query_array_array = new QueryArrayArray();
    }
    public function setQueryArrayArray(QueryArrayArray $query_array_array) {$this->query_array_array = $query_array_array;}
    public function getQueryArrayArray() : QueryArrayArray {return $this->query_array_array;}
    public function clearQueryArrayArray() {$this->query_array_array = new QueryArrayArray();}
    public function appendQueryArray(QueryArray $query_array) {$this->query_array_array->append($query_array);}
    public function connect(string $host, int $port, string $database, string $username, string $password) {
        try {
            if((int)$port === 3306)
                $this->connection = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);
            else $this->connection = new PDO("mysql:host=".$host.":".$port.";dbname=".$database, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) { throw $ex;} catch (Exception $ex) { throw $ex; }
    }
    public function execute() {
        if($this->connection === null) throw new Exception("Invalid Connection");
        try {
            foreach ($this->query_array_array as $query_array) {
                $success = true;
                if($query_array->isExecuteAsTransaction())
                    $this->connection->beginTransaction();
                foreach ($query_array as $query) {
                    if($query instanceof Select) {
                        $stmt = $this->connection->prepare((string)$query);
                        foreach ($query->getParameterArray() as $param) {
                            switch ($param->getBindBy()) {
                                case Parameter::BIND_BY_PARAM : $stmt->bindParam($param->getVariable(), $param->getValueRef(), $param->getType(), $param->getLength());break;
                                case Parameter::BIND_BY_VALUE : $stmt->bindValue($param->getVariable(), $param->getValue(), $param->getType()); break;
                            }
                        }
                        if(!$stmt->execute()) {
                            $success = false;
                            $query->setSuccessExecuted(false);
                        } else {
                            $query->setSuccessExecuted(true);
                            $query->setStatement($stmt);
                        }
                    }
                    else if($query instanceof Insert) {
                        $stmt = $this->connection->prepare((string)$query);
                        foreach ($query->getParameterArray() as $param) {
                            switch ($param->getBindBy()) {
                                case Parameter::BIND_BY_PARAM : $stmt->bindParam($param->getVariable(), $param->getValueRef(), $param->getType(), $param->getLength());break;
                                case Parameter::BIND_BY_VALUE : $stmt->bindValue($param->getVariable(), $param->getValue(), $param->getType()); break;
                            }
                        }
                        if(!$stmt->execute()) {
                            $success = false;
                            $query->setSuccessExecuted(false);
                        } else $query->setSuccessExecuted(true);
                    }
                    else if($query instanceof Update) {
                        $stmt = $this->connection->prepare((string)$query);
                        foreach ($query->getParameterArray() as $param) {
                            switch ($param->getBindBy()) {
                                case Parameter::BIND_BY_PARAM : $stmt->bindParam($param->getVariable(), $param->getValueRef(), $param->getType(), $param->getLength());break;
                                case Parameter::BIND_BY_VALUE : $stmt->bindValue($param->getVariable(), $param->getValue(), $param->getType()); break;
                            }
                        }
                        if(!$stmt->execute()) {
                            $success = false;
                            $query->setSuccessExecuted(false);
                        } else $query->setSuccessExecuted(true);
                    }
                    else if($query instanceof Delete) {
                        $stmt = $this->connection->prepare((string)$query);
                        foreach ($query->getParameterArray() as $param) {
                            switch ($param->getBindBy()) {
                                case Parameter::BIND_BY_PARAM : $stmt->bindParam($param->getVariable(), $param->getValueRef(), $param->getType(), $param->getLength());break;
                                case Parameter::BIND_BY_VALUE : $stmt->bindValue($param->getVariable(), $param->getValue(), $param->getType()); break;
                            }
                        }
                        if(!$stmt->execute()) {
                            $success = false;
                            $query->setSuccessExecuted(false);
                        } else $query->setSuccessExecuted(true);
                    }
                    else if($query instanceof Query) {
                        $stmt = $this->connection->prepare((string)$query);
                        foreach ($query->getParameterArray() as $param) {
                            switch ($param->getBindBy()) {
                                case Parameter::BIND_BY_PARAM : $stmt->bindParam($param->getVariable(), $param->getValueRef(), $param->getType(), $param->getLength());break;
                                case Parameter::BIND_BY_VALUE : $stmt->bindValue($param->getVariable(), $param->getValue(), $param->getType()); break;
                            }
                        }
                        if(!$stmt->execute()) {
                            $success = false;
                            $query->setSuccessExecuted(false);
                        } else $query->setSuccessExecuted(true);
                    } else throw new Exception("Wrong Object");
                }
                if($query_array->isExecuteAsTransaction()) {
                    if ($success) $this->connection->commit(); else $this->connection->rollBack();
                }

            }
        }
        catch (PDOException $ex) { throw $ex;} catch (Exception $ex) { throw $ex; }
    }
}
class RunnerEx
{
    const QUERY_SELECT = 1;
    const QUERY_INSERT = 2;
    const QUERY_UPDATE = 3;
    const QUERY_DELETE = 4;
    private $connection, $queries, $result, $keys;
    public function __construct(PDO $connection = null, $default_key_length = 7)
    {
        $this->queries = array();
        $this->result = array();
        $this->keys = array(bin2hex(openssl_random_pseudo_bytes($default_key_length)));
        if($connection !== null)
        {
            $this->connection = $connection;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else $this->connection = null;
        $this->queries[$this->keys[0]] = array();
    }
    public function connect(string $host = 'localhost', int $port = 3306, string $database = null, string $username = 'root', string $password = '')
    {
        try{
            if((int)$port === 3306)
                $this->connection = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);
            else $this->connection = new PDO("mysql:host=".$host.":".$port.";dbname=".$database, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function execute()
    {
        if($this->connection === null) throw new Exception("there is no connection");
        if(!$this->connection instanceof PDO) throw new Exception("invalid connection object");
        if( $this->connection instanceof PDO) {
            try {
                $one_by_ones = $this->prepareQueryByKey($this->keys[0]);
                foreach($one_by_ones as $by_one) {
                    if( $by_one[1] instanceof Select) {
                        if( $by_one[0]->execute()) {
                            foreach($by_one[1]->binder() as $item) {
                                if($item instanceof Parameter)
                                    $by_one[0]->bindColumn($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                                else if($item instanceof ParameterRef)
                                    $by_one[0]->bindColumn($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                            }
                            if( count($by_one[1]->binder()) > 0 )
                                $by_one[1]->setResult($by_one[0]->fetch(PDO::FETCH_BOUND));
                            else {
                                if($by_one[1]->getFetchMode() === -1)
                                    $by_one[1]->setResult($by_one[0]->fetchAll());
                                else if($by_one[1]->getFetchMode() === PDO::FETCH_ASSOC) {
                                    $temp = array();
                                    while( $row = $by_one[0]->fetch($by_one[1]->getFetchMode()) )
                                        array_push($temp, $row);
                                    $by_one[1]->setResult($temp);
                                }

                            }
                        }
                    } else if($by_one[1] instanceof Insert) {
                        $by_one[1]->setResult($by_one[0]->execute());

                    } else if($by_one[1] instanceof Update ) {
                        $by_one[1]->setResult($by_one[0]->execute());

                    } else if($by_one[1] instanceof Delete ) {
                        $by_one[1]->setResult($by_one[0]->execute());
                    }
                    else {
                        if(!isset($this->result[$by_one[1]]))
                            $this->result[$by_one[1]] = array();
                        if( (int)$by_one[2] === Runner::QUERY_SELECT ) {
                            if ($by_one[0]->execute() )
                                array_push($this->result[$by_one[1]], $by_one[0]->fetchAll());
                            else array_push($this->result[$by_one[1]], null);
                        }
                        else {
                            if ($by_one[0]->execute() )
                                array_push($this->result[$by_one[1]], true);
                            else array_push($this->result[$by_one[1]], false);
                        }
                    }
                }
                $query_of_queries = array();
                for($i = 0; $i < count($this->keys); $i++) {
                    if( $i == 0) continue;
                    array_push($query_of_queries, $this->prepareQueryByKey($this->keys[$i]));
                }
                foreach($query_of_queries as $queries) {
                    $this->connection->beginTransaction();
                    $success = true;
                    foreach($queries as $query) {
                        if( $query[1] instanceof Select) {
                            if( $query[0]->execute()) {
                                $query[1]->setResult($query[0]->fetchAll());
                            } else {$success = false; break;}
                        } else if($query[1] instanceof Insert) {
                            if( $query[0]->execute() ) {
                                $query[1]->setResult(true);
                            }else {$success = false; break;}

                        } else if($query[1] instanceof Update ) {
                            if( $query[0]->execute() ) {
                                $query[1]->setResult(true);
                            }else {$success = false; break;}

                        } else if($query[1] instanceof Delete ) {
                            if( $query[0]->execute() ) {
                                $query[1]->setResult(true);
                            }else {$success = false; break;}
                        }
                        else {
                            if(!isset($this->result[$by_one[1]]))
                                $this->result[$by_one[1]] = array();
                            if( (int)$by_one[2] === Runner::QUERY_SELECT ) {
                                if ($by_one[0]->execute() )
                                    array_push($this->result[$by_one[1]], $by_one[0]->fetchAll());
                                else {array_push($this->result[$by_one[1]], null); $success=false; break;}
                            }
                            else {
                                if ($by_one[0]->execute() )
                                    array_push($this->result[$by_one[1]], true);
                                else {array_push($this->result[$by_one[1]], false);$success=false; break;}
                            }
                        }
                    }
                    if($success) $this->connection->commit(); else $this->connection->rollBack();
                }

            } catch(PDOException $ex) {
                throw $ex;
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }
    private final function prepareQueryByKey($key) {
        $temp = array();
        $queries = $this->queries[$key];
        if( count($queries) < 1) return $temp;
        if( $this->connection instanceof PDO ) {
            foreach($queries as $query) {
                if( $query[0] instanceof Select ) {
                    $stmt = $this->connection->prepare( $query[0]);
                    foreach( $query[0]->parameter() as $item ) {
                        if($item instanceof Parameter)
                            $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                        else if($item instanceof ParameterRef) {
                            $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                        }
                    }
                    array_push($temp, array($stmt, $query[0]));
                }
                else if( $query[0] instanceof Insert ) {
                    $stmt = $this->connection->prepare( $query[0]->query());
                    foreach( $query[0]->parameter() as $item ) {
                        if($item instanceof Parameter)
                            $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                    }
                    array_push($temp, array($stmt, $query[0]));
                }
                else if( $query[0] instanceof Update ) {
                    $stmt = $this->connection->prepare( $query[0]->query());
                    foreach( $query[0]->parameter() as $item ) {
                        if($item instanceof Parameter)
                            $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                    }
                    array_push($temp, array($stmt, $query[0]));
                }
                else if( $query[0] instanceof Delete ) {
                    $stmt = $this->connection->prepare( $query[0]->query());
                    foreach( $query[0]->parameter() as $item ) {
                        if($item instanceof Parameter) $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                    }
                    array_push($temp, array($stmt, $query[0]));
                } else {
                    $stmt = $this->connection->prepare($query[0]);
                    if( isset($query[1]) && !empty($query[1])) {
                        foreach( $query[1] as $item) {
                            if($item instanceof Parameter) $stmt->bindParam($item->getVariable(), $item->getValue(), $item->getType(), $item->getLength());
                        }
                    }
                    array_push($temp, array($stmt, $key, $query[2]));
                }
            }
        }
        else throw new Exception("No Connection to Database");
        return $temp;
    }
    public function append_query($query, $key = null, array $parameter = null, int $type = Runner::QUERY_SELECT)
    {
        if($key === null) array_push($this->queries[$this->keys[0]], array($query, $parameter, $type));
        else
        {
            if(in_array($key, $this->keys))
            {
                if(!isset($this->queries[$key]))
                    $this->queries[$key] = array(array($query, $parameter, $type));
                else array_push($this->queries[$key], array($query, $parameter, $type));
            }
            else
            {
                array_push($this->keys, $key);
                $this->queries[$key] = array(array($query, $parameter, $type));
            }
        }
    }
    public function clear_query()
    {
        $default_key = $this->keys[0];
        $this->queries = array();
        $this->keys = array();
        array_push($this->keys, $default_key);
        $this->queries[$this->keys[0]] = array();
    }
    public function result($key = null)
    {
        if($key !== null) {
            if (isset($this->result[$key])) return $this->result[$key];
            else return null;
        }
        else return $this->result;
    }
    public function get_connection(){return $this->connection;}
    public function get_default_key() : string{return $this->keys[0];}
    public final function isAllQuerySuccess($key = null) : bool {
        $queries = $this->queries[$key];
        if( count($queries) < 1) return true;
        foreach($queries as $query) {
            if( $query[0] instanceof Select ) {
                if(count($query[0]->result()) < 1 ) return false;
            }
            else if( $query[0] instanceof Insert ) {
                if($query[0]->getResult() == false) return false;
            }
            else if( $query[0] instanceof Update ) {
                if($query[0]->getResult() == false) return false;
            }
            else if( $query[0] instanceof Delete ) {
                if($query[0]->getResult() == false) return false;
            }
        }
        return true;
    }
}