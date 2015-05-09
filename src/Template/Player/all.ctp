<style>
    div.player {
        padding: 10px 20px; }

    span.name a {
        text-decoration: none;
        text-transform: uppercase;
        display: block;
        color: #005391;
        font-weight: 400; }

    span.country {
        font-size: 12px;
        text-transform: uppercase;
        display: block;
        color: #a0a0a0; }

    span.country img {
        vertical-align: middle;
        width: 20px; }

</style>

<h3>Players</h3>
<div class="uk-grid">
    <?php foreach ($players as $player) { ?>
    <div class="uk-width-1-4 player">
        <span class="name">
            <?= $this->Html->link($player->fullname,['controller' => 'Player', 'action' => 'single', $player->slug]) ?><br>
        </span>
        <span class="country"><?= $this->Html->image($player->flag, ['alt' => $player->country]) ?> <?= $player->country ?></span>
    </div>
    <?php } ?>
</div>