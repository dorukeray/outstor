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
    public const DRIVER = 'sqlite';
    
    /**
     * Database Connection Constructor.
     *
     * @param array $config
     */
    public function __construct(string $database, $charset = 'utf8', $collation = 'utf8_general_ci' )
    {
      $this->database = $database;
      $this->charset = $charset;
      $this->collation = $collation;
    }

    public function getDSN()
    {
      return sprintf('sqlite:%s', $this->database);
    }
  }