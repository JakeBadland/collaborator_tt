<?php

namespace app\models;

use Yii;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public string $login;
    public string $password;
    public string $last_login;
    public string $login_attempts;

    public $id;
    public $authKey;
    public $accessToken;

    /**
     * Finds user by login name
     *
     * @param string $login
     * @return static|null
     */
    public static function findByUsername(string $login)
    {

        $file_name = Yii::getAlias('@data') . "/users.json";
        if (!is_file($file_name)){
            return null;
        }

        $users = json_decode(file_get_contents($file_name), true);

        if (!$users){
            return null;
        }

        if (!isset($users[$login])){
            return null;
        }

        return new static(array_merge(['login' => $login], $users[$login]));
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->login;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        //die('getAuthKey');
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findByUsername($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

}
