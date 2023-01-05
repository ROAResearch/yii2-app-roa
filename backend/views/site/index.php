<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$loremIpsum = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing
    elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
    enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
    voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>';

$section = function (string $linkText, string $url) use ($loremIpsum): string {
    $link = Html::a(
        $linkText,
        $url,
        ['class' => ['btn btn-outline-secondary']],
    );

    return <<<HTML
        <div class="col-lg-4">
            <h2>Heading</h2>

            $loremIpsum

            <p>$link</p>
        </div>
        HTML;
};

$this->title = 'My Yii Application';
?>
    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your
            Yii-powered application.</p>
        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <?= $section(
                'Yii Documentation &raquo;',
                'http://www.yiiframework.com/doc/',
            ),

            $section(
                'Yii Forum &raquo;',
                'http://www.yiiframework.com/forum/',
            ),

            $section(
                'Yii Extensions &raquo;',
                'http://www.yiiframework.com/extensions/',
            ) ?>
        </div>

    </div>
</div>
