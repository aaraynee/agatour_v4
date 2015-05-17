<!DOCTYPE html>
<html>
<head profile="http://www.w3.org/2005/10/profile">
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <title><?= $this->fetch('title') ?></title>
    <meta property="og:title" content="{% if(title is defined) %}{{ title }}{% else %}AGATOUR{% endif %}" />
    <meta property="og:image" content="{% if(image is defined) %}{{ image }}{% endif %}" />
    <meta property="og:description" content="{% if(description is defined) %}{{ description }}{% else %}Official Website of the AGA Tour.{% endif %}" />


    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('uikit.almost-flat.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->Html->script('http://code.jquery.com/jquery-2.1.1.min.js') ?>
    <?= $this->Html->script('uikit.min.js') ?>

    {{ partial("analytics") }}
    {{ partial("addthis") }}
</head>
<body>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">
    <div class="uk-grid uk-margin-top uk-margin-bottom uk-hidden-small">
        <div class="uk-width-2-10 logo">{{ image('img/logo.png',  "alt" : "AGA Tour") }}</div>
        <div class="uk-width-5-10">...</div>
        <div class="uk-width-3-10 social">
            <a target="_blank" href="http://facebook.com/agatourgolf" class="uk-icon-medium uk-icon-facebook"></a>
            <a target="_blank" href="http://twitter.com/agatour_golf" class="uk-icon-medium uk-icon-twitter"></a>
            <a target="_blank" href="http://youtube.com/agatour" class="uk-icon-medium uk-icon-youtube"></a>
            <a target="_blank" href="http://instagram.com/agatour" class="uk-icon-medium uk-icon-instagram"></a>
        </div>
    </div>
    <?= $this->element('Layout/nav') ?>
    <div class="page-content uk-margin-top uk-margin-large-bottom">
        <div class="uk-grid">
           <?php if(!isset($layout)) :
            $layout = 'full';
           endif; ?>
           <?php if($layout == 'sidebar'): ?>
                <div class="uk-width-large-7-10 uk-width-small-1-1">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
                <div class="uk-width-3-10 uk-hidden-small">{{ partial("theme/sidebar") }}</div>
            <?php else: ?>
                <div class="uk-width-large-1-1 uk-width-small-1-1">
                    <?= $this->Flash->render() ?>
                    <?= $this->fetch('content') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="uk-grid uk-margin-top uk-margin-bottom uk-text-center">
        <div class="uk-width-1-1 copyright">
            &copy; 2012-2014 AGA TOUR, Inc | All Rights Reserved.<br>
            AGA TOUR, Nandos Cup, and the AGA Ranking System are registered trademarks.<br>
        </div>
    </div>
</div>
</body>
</html>