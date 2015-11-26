<?php
class TSAutoModifiedBehavior extends CActiveRecordBehavior
{
  /**
   * The field that stores the creation time
   * @var string
   */
  public $Created = 'created';

  /**
   * The field that stores the modify time
   * @var string
   */
  public $Modified = 'modified';

  public $Unixtime = true;

  /**
   * The model where modified must be set.
   * Default: it's model where behavior is attached
   * @var @CModel
   */
  public $model = null;

  /**
   * The relations with attached model and $this->model if it not null
   * Format: 'field name of attached model' => 'field name of $this->model'
   * @var array
   */
  public $relations = null;

  protected function defineModified()
  {
    if ($this->relations)
    {
      $values = array();
      foreach($this->relations as $key=>$value)
        $values[$value] = $this->Owner->{$key};

      $result = $this->model->findByAttributes($values);
      if (($result) && ($result->hasAttribute($this->Modified)))
      {
        $result->{$this->Modified} = mktime();
        $result->save();
      }
    }
    else
      throw new CException('Attribute "relations" must be non empty array');
  }

  protected function getDatetime() {
    return $this->Unixtime ? new CDbExpression('UNIX_TIMESTAMP()') : new CDbExpression('NOW()');
  }

  // always returns false
  protected function processing()
  {
    if ($this->model === null)
    {
      if (($this->Owner->isNewRecord) && ($this->Owner->hasAttribute($this->Created))) {
        if ($this->Owner->{$this->Created} === null)
          $this->Owner->{$this->Created} = $this->getDatetime();
      }
      if ($this->Owner->hasAttribute($this->Modified))
        $this->Owner->{$this->Modified} = $this->getDatetime();
    }
    else
      $this->defineModified();
    return true;
  }

  public function beforeValidate($event)
  {
    if ($this->enabled)
      $this->processing();
    return true;
  }

  public function beforeSave($event)
  {
    if ($this->enabled)
      $this->processing();
    return true;
  }

  public function beforeDelete($event)
  {
    if ($this->enabled)
      $this->processing();
    return true;
  }
}

?>
