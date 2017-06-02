<?php

namespace app\models;

use app\traits\TrackerModel;
use Yii;

/**
 * This is the model class for table "{{%track_time}}".
 *
 * @property integer $id
 * @property string $board_id
 * @property string $list_id
 * @property string $card_id
 * @property string $time
 * @property string $created_at
 * @property string $updated_at
 */
class TrackTime extends \yii\db\ActiveRecord
{
    use TrackerModel;

    const REGEXP_SPLIT_ESTIMATION = '([0-9]+h)( ){0,1}([0-9]+m)';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%track_time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['board_id', 'list_id', 'card_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['board_id', 'list_id', 'card_id', 'time'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'board_id' => Yii::t('app', 'Board ID'),
            'list_id' => Yii::t('app', 'List ID'),
            'card_id' => Yii::t('app', 'Card ID'),
            'time' => Yii::t('app', 'Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
