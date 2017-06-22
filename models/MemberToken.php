<?php

namespace app\models;

use app\traits\CommonModel;
use app\traits\TrackerModel;
use Yii;

/**
 * This is the model class for table "{{%member_token}}".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 */
class MemberToken extends \yii\db\ActiveRecord
{
    use CommonModel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'token'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['member_id', 'token'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
