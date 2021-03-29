<?php
  namespace Dorkodu\Outstor;

  /**
   * DBConnection - SQL Database Connection Value Object
   *
   * @author Doruk Eray (@dorkodu) <doruk@dorkodu.com>
   * @url <https://github.com/dorukdorkodu/outstor>
   * @license The MIT License (MIT) - <http://opensource.org/licenses/MIT>
   */
  class DBConnection
  {
    public $driver;
    public $host;
    public $database;
    public $user;
    public $password;
    public $port;

    public $charset;
    public $collation;

    /**
     * Database Connection Constructor.
     *
     * @param array $config
     */
    public function __construct( string $driver = 'mysql',
                                 string $host = 'localhost',
                                 string $database,
                                 string $user,
                                 string $password,
                                 string $port = '',
                                 string $charset = 'utf8',
                                 string $collation = 'utf8_general_ci' )
    {
      $this->driver = $driver;
      $this->host = $host;
      $this->user = $user;
      $this->password = $password;
      $this->database = $database;

      $this->port = ($this->port !== '')
                    ? $port
                    : (strstr($host, ':') ? explode(':', $host)[1] : '');

      $this->charset = $charset;
      $this->collation = $collation;
    }

    public function __get($name)
    {
      return $this->$name;
    }

    public function __set($name, $value) { }
  }