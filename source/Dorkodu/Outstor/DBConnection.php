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

    public function __get($name)
    {
      return $this->$name;
    }

    public function __set($name, $value) { }
  }