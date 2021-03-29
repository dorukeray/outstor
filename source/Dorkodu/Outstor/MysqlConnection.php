<?php
  namespace Dorkodu\Outstor;

  use Dorkodu\Outstor\DBConnection;

  /**
   * MysqlConnection - MySQL Database Connection Value Object
   *
   * @author Doruk Eray (@dorkodu) <doruk@dorkodu.com>
   * @url <https://github.com/dorukdorkodu/outstor>
   * @license The MIT License (MIT) - <http://opensource.org/licenses/MIT>
   */
  class MysqlConnection implements DBConnection
  {
    public const DRIVER = 'mysql';
    
    /**
     * Database Connection Constructor.
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