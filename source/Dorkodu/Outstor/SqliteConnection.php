<?php
  namespace Dorkodu\Outstor;

  use Dorkodu\Outstor\DBConnection;

  /**
   * SqliteConnection - SQLite Database Connection Value Object
   *
   * @author Doruk Eray (@dorkodu) <doruk@dorkodu.com>
   * @url <https://github.com/dorukdorkodu/outstor>
   * @license The MIT License (MIT) - <http://opensource.org/licenses/MIT>
   */
  class SqliteConnection implements DBConnection
  {
    private const DRIVER = 'sqlite';
    public $database;

    public $charset;
    public $collation;

    /**
     * Database Connection Constructor.
     *
     * @param array $config
     */
    public function __construct($database, $charset = 'utf8', $collation = 'utf8_general_ci' )
    {
      $this->database = $database;

      $this->charset = $charset;
      $this->collation = $collation;
    }

    public function getDSN()
    {
      $dsn = sprintf('sqlite:%s', $this->database);
      return 
      $dsn = '';
      if ($this->driver === 'mysql' || $this->driver === 'pgsql') {
        $dsn = sprintf('%s:host=%s;%sdbname=%s',
                $this->driver,
                str_replace(':' . $this->port, '', $this->host),
                ($this->port !== '' ? 'port=' . $this->port . ';' : ''),
                $this->database
              );
      } elseif ($this->driver === 'sqlite') {
      } elseif ($this->driver === 'oracle') {
        $dsn = sprintf('oci:dbname=%s/%s',
                        $this->host,
                        $this->database
                      );
      }
      return $dsn;
    }

    public function __get($name)
    {
      return $this->$name;
    }

    public function __set($name, $value) { }
  }