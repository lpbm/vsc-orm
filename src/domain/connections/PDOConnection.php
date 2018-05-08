<?php
namespace orm\domain\connections;


class PDOConnection extends ConnectionA implements SqlConnectionI
{
	/**
	 * @var \PDO
	 */
	protected $conn;
	private $options = [\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION];

	/**
	 * @var string
	 */
	private $dbType;

	/**
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param null $dbType
	 */
	public function __construct( $dbHost = null, $dbUser = null, $dbPass = null, $dbType = null) {
		parent::__construct($dbHost, $dbUser, $dbPass);
		if (ConnectionType::isValid($dbType)) {
			$this->dbType = $dbType;
		}
	}

	protected function connect()
	{
		$dsn = sprintf('%s:host=%s;dbname=%s', $this->dbType, $this->getDatabaseHost(), $this->getDatabaseName());

		try {
			$this->conn = new \PDO($dsn, $this->getDatabaseUser(), $this->getDatabasePassword(), $this->options);
		} catch (\PDOException $e) {
			throw new ExceptionConnection($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function getType()
	{
		return ConnectionType::getType($this->dbType);
	}

	public function escape($incData)
	{
		return $this->conn->quote($incData);
	}

	public function query($query)
	{
		return $this->conn->query($query);
	}

	public function getRow()
	{
		// TODO: Implement getRow() method.
	}

	public function getArray()
	{
		// TODO: Implement getArray() method.
	}

	public function getFirst()
	{
		// TODO: Implement getFirst() method.
	}

	public function close()
	{
		// TODO: Implement close() method.
	}

	public function startTransaction($bAutoCommit = false)
	{
		return $this->conn->beginTransaction();
	}

	public function rollBackTransaction()
	{
		return $this->conn->rollBack();
	}

	public function commitTransaction()
	{
		$this->conn->commit();
	}

	public function getScalar()
	{
		// TODO: Implement getScalar() method.
	}
}
