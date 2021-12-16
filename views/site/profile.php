<?php

/* @var $this yii\web\View */

$this->title = 'Профиль';
?>
<div class="site-login">
    <h3>Добрый день, <?=$login?></h3>

    <a href="/site/logout">
        <button type="submit" class="btn btn-primary">Выход</button>
    </a>
</div>
