<?php

class TSDbConnection extends CDbConnection
{
  protected $_level = 0;
  protected $_transaction = null;

  /**
   * Returns the currently active transaction.
   * @return BmDbTransaction the currently active transaction. Null if no active transaction.
   */
  public function getCurrentTransaction()
  {
    if($this->_transaction !== null)
    {
      if ($this->_transaction->getActive())
        return $this->_transaction;
    }
    return null;
  }

  /**
   * Starts a transaction.
   * @return CDbTransaction the transaction initiated
   * @throws CException if the connection is not active
   */
  public function beginTransaction()
  {
    if($this->getActive())
    {
      if ($this->incTransactionLevel() == 1)
      {
        $this->getPdoInstance()->beginTransaction();
        $this->_transaction = new TSDbTransaction($this);
      }
      return $this->getCurrentTransaction();
    }
    else
      throw new CDbException(Yii::t('yii','CDbConnection is inactive and cannot perform any DB operations.'));
  }

  /**
   * Returns current transaction nesting level
   * @return int transaction nesting level
   */
  public function getTransactionLevel()
  {
    return $this->_level;
  }

  /**
   * Increments transaction nesting level
   * @return int transaction nesting level after incrementing
   */
  public function incTransactionLevel()
  {
    return ++$this->_level;
  }

  /**
   * Decrements transaction nesting level
   * @return int transaction nesting level after decrementing
   */
  public function decTransactionLevel()
  {
    return (($this->_level == 0) ? 0 : --$this->_level);
  }
}

class TSDbTransaction extends CDbTransaction
{
  public function commit()
  {
    if ($this->connection->decTransactionLevel() == 0)
      parent::commit();
  }

  public function rollBack()
  {
    if ($this->connection->decTransactionLevel() == 0)
      parent::rollBack();
  }
}
