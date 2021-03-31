<?php
  namespace Dorkodu\Outstor;

  use Dorkodu\Outstor\{
                        IConnection,
                        SqliteConnection, 
                        MysqlConnection,
                      };

  use PDO, PDOException, Exception, PDOStatement;

  /**
   * DB - the Database class for Outstor library
   *
   * @author  Doruk Eray (@dorkodu) <doruk@dorkodu.com>
   * @url      <https://github.com/dorukdorkodu/outstor>
   * @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
   */
  class DB
  {
    /**
     * @var PDO|null
     */
    public $pdo = null;
    
    /**
     * @var IConnection. 
     */
    protected $connection = null;

    /**
     * @var mixed Connection variables
     */
    protected $numRows = 0;
    protected $insertId = null;
    protected $query = null;
    protected $error = null;
    protected $result = [];

    /** 
     * @var PDOStatement $statement The prepared statement after a query is set. 
     */
    protected PDOStatement $statement;

    /**
     * @var array SQL operators
     */
    protected $operators = ['=', '!=', '<', '>', '<=', '>=', '<>'];

    /**
     * @var int Total query count
     */
    protected $queryCount = 0;

    /**
     * @var bool Is it in the development environment?
     */
    protected $debug = true;

    /**
     * @var int Total transaction count
     */
    protected $transactionCount = 0;

  }
