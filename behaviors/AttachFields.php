<?php
namespace pistol88\field\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use pistol88\field\models\Field;
use pistol88\field\models\FieldValue;

class AttachFields extends Behavior
{
    private $fieldVariants = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'deleteValues',
        ];
    }
    
    public function getField($code)
    {
        return $this->getFieldValue($code);
    }
    
    public function getFieldValue($code)
    {
        if($field = Field::findOne(['slug' => $code])) {
            if($field->type == 'checkbox') {
                return $this->getFieldValues($code);
            }
            if($value = FieldValue::findOne(['field_id' => $field->id, 'item_id' => $this->owner->id])) {
                return $value->value;
            }
        }

        return false;
    }
    
    public function getFieldValues($code)
    {
        if($field = Field::findOne(['slug' => $code])) {
            if($value = FieldValue::findAll(['field_id' => $field->id, 'item_id' => $this->owner->id])) {
                return ArrayHelper::map($value, 'value', 'value');
            }
        }

        return false;
    }
    
    public function getFieldVariantId($code)
    {
        if($field = Field::findOne(['slug' => $code])) {
            if($value = FieldValue::findOne(['field_id' => $field->id, 'item_id' => $this->owner->id])) {
                return $value->variant_id;
            }
        }

        return false;
    }
    
    public function getFieldVariantIds($code)
    {
        if($field = Field::findOne(['slug' => $code])) {
            if($value = FieldValue::findAll(['field_id' => $field->id, 'item_id' => $this->owner->id])) {
                return ArrayHelper::map($value, 'variant_id', 'variant_id');
            }
        }

        return false;
    }
    
    public function fieldVariants()
    {
        if(!$this->owner->isNewRecord) {
            if(is_array($this->fieldVariants)) {
                return $this->fieldVariants;
            }

            $values = FieldValue::findAll(['item_id' => $this->owner->id]);

            $this->fieldVariants = [];

            foreach($values as $value) {
                $this->fieldVariants[$value->variant_id] = $value->variant_id;
            }

            return $this->fieldVariants;
        } else {
            return [];
        }
    }

    public function getFields()
    {
        $model = $this->owner;

        $fields = Field::find()->where(['relation_model' => $model::className()])->all();

        return $fields;
    }
    
    public function deleteValues()
    {
        foreach(FieldValue::find()->where(['item_id' => $this->owner->id])->all() as $value) {
            $value->delete();
        }
        
        return true;
    }
}
