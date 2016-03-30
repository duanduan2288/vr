<?php
    namespace app\models\_base;
    use app\components\AMapper;
    use yii\data\ActiveDataProvider;
    use Yii;
/**
 * This is the model base class for the table "issue_operation_history".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "IssueOperationHistory".
 *
 * Columns in table "issue_operation_history" available as properties of the model,
 * followed by relations of table "issue_operation_history" available as properties of the model.
 *
 * @property integer $id
 * @property string $issue_id
 * @property string $operator_id
 * @property string $state
 * @property string $operation_date
 * @property string $operator_ip
 * @property string $operation
 * @property string $detail
 *
 * @property Issue $issue
 * @property User $operator
 */
abstract class BaseIssueOperation extends AMapper {

    public static function tableName()
    {
        return 'issue_operation';
    }

	public static function label($n = 1) {
		return Yii::t('app', 'IssueOperation|IssueOperationHistories', $n);
	}

	public static function representingColumn() {
		return 'status';
	}

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('issue_id, operator_id, operation, attached_data, operator_ip, created', 'required'),
            array('issue_id, operator_id', 'length', 'max'=>10),
            array('status', 'length', 'max'=>12),
            array('operation, operator_ip', 'length', 'max'=>56),
            array('operation_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, issue_id, operator_id, status, operation_date, operation, attached_data, operator_ip, created', 'safe', 'on'=>'search'),
        );
    }

    public function getIssue(){
        return $this->hasOne('Issue',['id'=>'issue_id']);
    }

    public function getOperator(){
        return $this->hasMany('RegistrarUser',['id'=>'operator_id']);
    }

	public function pivotModels() {
		return array(
		);
	}

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'issue_id' => 'Issue',
            'operator_id' => 'Operator',
            'status' => 'Status',
            'operation_date' => 'Operation Date',
            'operation' => 'Operation',
            'attached_data' => 'Attached Data',
            'operator_ip' => 'Operator Ip',
            'created' => 'Created',
        );
    }

    public function search(){
        $query = $this::find()->where(
            [
                'id'=>$this->id,
                'issue_id'=>$this->issue_id,
                'operator_id'=>$this->operator_id,
                'status'=>$this->status,
                'operation_date'=>$this->operation_date,
                'operation'=>$this->operation,
                'attached_data'=>$this->attached_data,
                'operator_ip'=>$this->operator_ip,
                'created'=>$this->created
            ]);
        return new ActiveDataProvider([
            'query'=>$query,
        ]);
    }
	/**
	 * @return array customized fields (name=>label)
	 */
	public function customFields()
	{
		return array(
    		'id' => array( 'type' => 'textField', 'htmlOptions'=>array( ),  ),
    		'issue_id' => array( 'type' => 'dropDownList', 'htmlOptions'=>array( ), 'modelClass'=>'Issue' ),
    		'operator_id' => array( 'type' => 'dropDownList', 'htmlOptions'=>array( ), 'modelClass'=>'User' ),
    		'state' => array( 'type' => 'textField', 'htmlOptions'=>array(  'size'=>12,'maxlength'=>12 ),  ),
    		'operation_date' => array( 'type' => 'textField', 'htmlOptions'=>array( ),  ),
    		'operator_ip' => array( 'type' => 'textField', 'htmlOptions'=>array(  'size'=>56,'maxlength'=>56 ),  ),
		);
	}	
}