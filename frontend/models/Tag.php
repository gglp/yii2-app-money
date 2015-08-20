<?php

namespace frontend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 *
 * @property TransactionTag[] $transactionTags
 */
class Tag extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tree', 'lft', 'rgt', 'depth'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionTags() {
        return $this->hasMany(TransactionTag::className(), ['tag_id' => 'id']);
    }

    /**
     * Подключаем поведение NestedSets
     * @return type
     */
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    public function transactions() {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find() {
        return new TagQuery(get_called_class());
    }

    public function getRoots() {
        $roots = Tag::find()->roots()->asArray()->all();
        if (is_array($roots)) {
            $result = array();
            foreach ($roots as $item) {
                $result[] = ['id' => $item['id'], 'text' => $item['name'], 'children' => ($item['rgt'] - $item['lft'] > 1)];
            }
        }

        return $result;
    }

    public function getNodes() {
        $parent = Tag::findOne(['id' => $this->id]);

        $childs = $parent->children(1)->asArray()->all();
        if (is_array($childs)) {
            $result = array();
            foreach ($childs as $item) {
                $result[] = ['id' => $item['id'], 'text' => $item['name'], 'children' => ($item['rgt'] - $item['lft'] > 1)];
            }
        }

        return $result;
    }

}
