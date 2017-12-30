<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $rePassword;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => \Yii::t('common','This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            //正则验证
//            ['username','match','pattern' =>'正则表达式','message'=>'12312312312'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' =>\Yii::t('common','This email address has already been taken.')],

            [['password','rePassword'], 'required'],
            [['password','rePassword'], 'string', 'min' => 6],
            ['rePassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],

            ['verifyCode','captcha']
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }

    public function attributeLabels(){
        return [
            'username' => '用户名',
            'email' =>'邮箱',
            'password'=>'密码',
            'rePassword'=> '重复密码',
            'verifyCode'=>'验证码'
        ];
    }
}
