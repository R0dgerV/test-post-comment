<?php

/**
 * This is the model class for table "Posts".
 *
 * The followings are the available columns in table 'Posts':
 * @property string $id
 * @property string $text
 * @property string $authorId
 * @property string $pageId
 * @property string $lft
 * @property string $rgt
 * @property string $root
 * @property integer $level
 * @property string $created
 * @property string $modified
 * @property string $flags
 *
 * The followings are the available model relations:
 * @property Authors $author
 * @property Pages $page
 */
class BasePosts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Posts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text, authorId, pageId, created, modified', 'required'),
			array('level', 'numerical', 'integerOnly'=>true),
			array('authorId, pageId, lft, rgt, root, flags', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, text, authorId, pageId, lft, rgt, root, level, created, modified, flags', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'author' => array(self::BELONGS_TO, 'Authors', 'authorId'),
			'page' => array(self::BELONGS_TO, 'Pages', 'pageId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'authorId' => 'Author',
			'pageId' => 'Page',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'root' => 'Root',
			'level' => 'Level',
			'created' => 'Created',
			'modified' => 'Modified',
			'flags' => 'Flags',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('authorId',$this->authorId,true);
		$criteria->compare('pageId',$this->pageId,true);
		$criteria->compare('lft',$this->lft,true);
		$criteria->compare('rgt',$this->rgt,true);
		$criteria->compare('root',$this->root,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('flags',$this->flags,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BasePosts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
