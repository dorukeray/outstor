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
  }
