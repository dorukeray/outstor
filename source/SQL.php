<?php
  namespace Dorkodu\Outstor;
  /**
   * SQL Query Builder
   *
   * @class    SQL
   * @author   Doruk Eray (@dorkodu) <doruk@dorkodu.com>
   * @web      <http://dorkodu.com>
   * @url      <https://github.com/dorukdorkodu/outstor>
   * @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
   */
  class SQL
  {
    /**
     * @var PDO|null
     */
    public $pdo = null;

    /**
     * @var mixed Query variables
     */
    protected $select = '*';
    protected $from = null;
    protected $where = null;
    protected $limit = null;
    protected $offset = null;
    protected $join = null;
    protected $orderBy = null;
    protected $groupBy = null;
    protected $having = null;
    protected $grouped = false;
    protected $numRows = 0;
    protected $insertId = null;
    protected $query = null;
    protected $error = null;
    protected $result = [];
    protected $prefix = null;

    /**
     * @var array SQL operators
     */
    protected $operators = ['=', '!=', '<', '>', '<=', '>=', '<>'];

    /**
     * @var int Total query count
     */
    protected $queryCount = 0;

    /**
     * @var bool
     */
    protected $debug = true;

    /**
     * @var int Total transaction count
     */
    protected $transactionCount = 0;
    
    /**
     * @param $table
     *
     * @return $this
     */
    public function table($table)
    {
      if (is_array($table)) {
        $from = '';
        foreach ($table as $key) {
          $from .= $this->prefix . $key . ', ';
        }
        $this->from = rtrim($from, ', ');
      } else {
        if (strpos($table, ',') > 0) {
          $tables = explode(',', $table);
          foreach ($tables as $key => &$value) {
            $value = $this->prefix . ltrim($value);
          }
          $this->from = implode(', ', $tables);
        } else {
          $this->from = $this->prefix . $table;
        }
      }
      
      return $this;
    }

    /**
     * @param array|string $fields
     *
     * @return $this
     */
    public function select($fields)
    {
      $select = is_array($fields) ? implode(', ', $fields) : $fields;
      $this->optimizeSelect($select);

      return $this;
    }

    /**
     * @param string      $field
     * @param string|null $name
     *
     * @return $this
     */
    public function max($field, $name = null)
    {
      $column = 'MAX(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
      $this->optimizeSelect($column);
      return $this;
    }

    /**
     * @param string      $field
     * @param string|null $name
     *
     * @return $this
     */
    public function min($field, $name = null)
    {
      $column = 'MIN(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
      $this->optimizeSelect($column);
      return $this;
    }

    /**
     * @param string      $field
     * @param string|null $name
     *
     * @return $this
     */
    public function sum($field, $name = null)
    {
      $column = 'SUM(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
      $this->optimizeSelect($column);
      return $this;
    }

    /**
     * @param string      $field
     * @param string|null $name
     *
     * @return $this
     */
    public function count($field, $name = null)
    {
      $column = 'COUNT(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
      $this->optimizeSelect($column);
      return $this;
    }

    /**
     * @param string      $field
     * @param string|null $name
     *
     * @return $this
     */
    public function avg($field, $name = null)
    {
      $column = 'AVG(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
      $this->optimizeSelect($column);
      return $this;
    }

    /**
     * @param string      $table
     * @param string|null $field1
     * @param string|null $operator
     * @param string|null $field2
     * @param string      $type
     *
     * @return $this
     */
    public function join($table, $field1 = null, $operator = null, $field2 = null, $type = '')
    {
      $on = $field1;
      $table = $this->prefix . $table;
      if (!is_null($operator)) {
        $on = !in_array($operator, $this->operators)
          ? $field1 . ' = ' . $operator
          : $field1 . ' ' . $operator . ' ' . $field2;
      }
      $this->join = (is_null($this->join))
        ? ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on
        : $this->join . ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on;
      return $this;
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function innerJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'INNER ');
    }


    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function innerJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'INNER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function leftJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'LEFT ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function rightJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'RIGHT ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function fullOuterJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'FULL OUTER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function leftOuterJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'LEFT OUTER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function rightOuterJoin($table, $field1, $operator = '', $field2 = '')
    {
      return $this->join($table, $field1, $operator, $field2, 'RIGHT OUTER ');
    }

 /**
     * @param array|string $where
     * @param string       $operator
     * @param string       $val
     * @param string       $type
     * @param string       $andOr
     *
     * @return $this
     */
    public function where($where, $operator = null, $val = null, $type = '', $andOr = 'AND')
    {
      if (is_array($where) && !empty($where)) {
        $_where = [];
        foreach ($where as $column => $data) {
          $_where[] = $type . $column . '=' . $this->escape($data);
        }
        $where = implode(' ' . $andOr . ' ', $_where);
      } else {
        if (is_null($where) || empty($where)) {
          return $this;
        }

        if (is_array($operator)) {
          $params = explode('?', $where);
          $_where = '';
          foreach ($params as $key => $value) {
            if (!empty($value)) {
              $_where .= $type . $value . (isset($operator[$key]) ? $this->escape($operator[$key]) : '');
            }
          }
          $where = $_where;
        } elseif (!in_array($operator, $this->operators) || $operator == false) {
          $where = $type . $where . ' = ' . $this->escape($operator);
        } else {
          $where = $type . $where . ' ' . $operator . ' ' . $this->escape($val);
        }
      }

      if ($this->grouped) {
        $where = '(' . $where;
        $this->grouped = false;
      }

      $this->where = is_null($this->where)
        ? $where
        : $this->where . ' ' . $andOr . ' ' . $where;

      return $this;
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function orWhere($where, $operator = null, $val = null)
    {
      return $this->where($where, $operator, $val, '', 'OR');
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function notWhere($where, $operator = null, $val = null)
    {
      return $this->where($where, $operator, $val, 'NOT ', 'AND');
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function orNotWhere($where, $operator = null, $val = null)
    {
      return $this->where($where, $operator, $val, 'NOT ', 'OR');
    }

    /**
     * @param string $where
     * @param bool   $not
     *
     * @return $this
     */
    public function whereNull($where, $not = false)
    {
      $where = $where . ' IS ' . ($not ? 'NOT' : '') . ' NULL';
      $this->where = is_null($this->where) ? $where : $this->where . ' ' . 'AND ' . $where;

      return $this;
    }

    /**
     * @param string $where
     *
     * @return $this
     */
    public function whereNotNull($where)
    {
      return $this->whereNull($where, true);
    }


    /**
     * @param string $field
     * @param array  $keys
     * @param string $type
     * @param string $andOr
     *
     * @return $this
     */
    public function in($field, array $keys, $type = '', $andOr = 'AND')
    {
      if (is_array($keys)) {
        $_keys = [];
        foreach ($keys as $k => $v) {
          $_keys[] = is_numeric($v) ? $v : $this->escape($v);
        }
        $where = $field . ' ' . $type . 'IN (' . implode(', ', $_keys) . ')';

        if ($this->grouped) {
          $where = '(' . $where;
          $this->grouped = false;
        }

        $this->where = is_null($this->where)
          ? $where
          : $this->where . ' ' . $andOr . ' ' . $where;
      }

      return $this;
    }

     /**
     * @param string $field
     * @param array  $keys
     *
     * @return $this
     */
    public function notIn($field, array $keys)
    {
      return $this->in($field, $keys, 'NOT ', 'AND');
    }

    /**
     * @param string $field
     * @param array  $keys
     *
     * @return $this
     */
    public function orIn($field, array $keys)
    {
      return $this->in($field, $keys, '', 'OR');
    }

    /**
     * @param string $field
     * @param array  $keys
     *
     * @return $this
     */
    public function orNotIn($field, array $keys)
    {
      return $this->in($field, $keys, 'NOT ', 'OR');
    }

     /**
     * @param Closure $obj
     *
     * @return $this
     */
    public function grouped(Closure $obj)
    {
      $this->grouped = true;
      call_user_func_array($obj, [$this]);
      $this->where .= ')';

      return $this;
    }


    /**
     * @param string     $field
     * @param string|int $value1
     * @param string|int $value2
     * @param string     $type
     * @param string     $andOr
     *
     * @return $this
     */
    public function between($field, $value1, $value2, $type = '', $andOr = 'AND')
    {
      $where = '(' . $field . ' ' . $type . 'BETWEEN ' . ($this->escape($value1) . ' AND ' . $this->escape($value2)) . ')';
      if ($this->grouped) {
        $where = '(' . $where;
        $this->grouped = false;
      }

      $this->where = is_null($this->where)
        ? $where
        : $this->where . ' ' . $andOr . ' ' . $where;

      return $this;
    }

    /**
     * @param string     $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function notBetween($field, $value1, $value2)
    {
      return $this->between($field, $value1, $value2, 'NOT ', 'AND');
    }

    /**
     * @param string     $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function orBetween($field, $value1, $value2)
    {
      return $this->between($field, $value1, $value2, '', 'OR');
    }

    /**
     * @param string     $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function orNotBetween($field, $value1, $value2)
    {
      return $this->between($field, $value1, $value2, 'NOT ', 'OR');
    }

  
  }