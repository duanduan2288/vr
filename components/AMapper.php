<?php
/**
 * Abstract Class --  Mapper
 * 
 * 
 * @author Sam Xiao
 * @since 1.0
 *
 */
    namespace app\components;
    use yii\db\ActiveRecord;

abstract class AMapper extends ActiveRecord
{
    /**
     * @return array customized fields (name=>label)
     * type : textField|passwordField|radioButton|radioButtonList|textArea|textField|checkBox|checkBoxList|dropDownList|fileField|hiddenField|dateField
     *
     * @todo radioButton|radioButtonList|checkBoxList|fileField|hiddenField
     * @author Sam Xiao
     * @since 1.0
     */
    public abstract  function customFields();
    /**
     *
     * @param unknown_type $name
     * @return array|boolean
     */
    public function getCustomedField ($name)
    {

        $fields = $this->customFields();
        if (isset($fields[$name])) {
            return $fields[$name];
        } else {
            return FALSE;
        }
    }

    /**
     * Sets the named attribute value.
     * You may also use $this->AttributeName to set the attribute value.
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     * @return boolean whether the attribute exists and the assignment is conducted successfully
     * @see hasAttribute
     */
    public function setAttribute ($name, $value)
    {

        $field = $this->getCustomedField($name);
        if ($field) {
            switch ($field['type']) {
                case 'dateField':
                    $value = strtotime($value);
                    break;
                default:
            }
        }
        return parent::setAttribute($name, $value);
    }
}