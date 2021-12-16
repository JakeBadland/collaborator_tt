<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'Вход';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <form method="POST" action="/site/login">
        <div class="form-group">
            <label for="login">Имя пользователя</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Введите имя пользователя">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Введите пароль">
        </div>
        <?php if(isset($this->params['error'])) : ?>
            <div class="login-error"><?=$this->params['error']?></div>
        <?php endif?>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>
