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

    /**
     * DB constructor.
     *
     * @param array $config
     */
    public function __construct($isDevEnvironment = false, $prefix = '')
    {
      $this->debug = $isDevEnvironment;
      $this->prefix = $prefix;
    }

    public function connect(IConnection $connection)
    {
      try {
        
        # create a PDO instance
        if ($connection instanceof SqliteConnection) {
          $this->pdo = new PDO($connection->getDSN());
        } else {
          $this->pdo = new PDO($connection->getDSN(), $connection->user, $connection->password);
        }

        # if using mysql, then use buffered query
        if ($connection instanceof MysqlConnection) {
          $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);          
        }

        # setup the connection
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        # assign the connection
        $this->connection = $connection;
        return true;
      } catch (Exception $e) {
        throw $e;
        return false;
      }
    }

    /**
     * Disconnects from the database.
     *
     * @return void
     */
    public function disconnect()
    {
      $this->connection = null;
      $this->pdo = null;
    }

    /**
     * @return int
     */
    public function numRows()
    {
      return $this->numRows;
    }

    /**
     * @return int|null
     */
    public function insertId()
    {
      return $this->insertId;
    }

    /**
     * @throw PDOException
     */
    public function error()
    {
      if ($this->debug === true) {
        if (php_sapi_name() === 'cli') {
          die(sprintf("\n.::Database Error::.\nQuery: %s\nError: %s\n", $this->query, $this->error));
        } else {
          die(<<<HTML
          <h1>Database Error</h1>
          <h4>Query: <em style="font-weight:normal;">{$this->query}</em></h4>
          <h4>Error: <em style="font-weight:normal;">{$this->error}</em></h4>
          HTML);
        }
      }
      throw new PDOException($this->error . '. (' . $this->query . ')');
    }

  }
