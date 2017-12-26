<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "numbers".
 *
 * @property int $id
 * @property string $number_val
 * @property int $contact_id
 *
 * @property Contacts $contact
 */
class Numbers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'numbers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id'], 'integer'],
            [['number_val'], 'required', 'message' => 'Номер не может быть пустым!'],
            [['number_val'], 'integer', 'message' => 'введите только числа!'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contacts::className(), 'targetAttribute' => ['contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number_val' => '',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contacts::className(), ['id' => 'contact_id']);
    }
}
