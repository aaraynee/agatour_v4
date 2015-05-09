<style>
    table.tournament {
        border-collapse: collapse;
        width: 100%; }

    table.tournament thead tr td {
        text-transform: uppercase;
        vertical-align: middle;
        border: none;
        font-weight: 700;
        font-size: 12px;
        background:linear-gradient(to bottom,#005391 0,#004987  100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#005391',endColorstr='#004987 ',GradientType=0);
        color: #fff;
        padding: 5px 10px; }

    table.tournament tr td {
        text-align: center;
        vertical-align: middle;
        border: 1px #d8d8d8 solid;
        font-size: 14px;
        padding: 10px 5px; }

    table.tournament tr td.name {
        text-align: left; }

    table.tournament tr td img {
        width: 25px; }

    table.tournament tr td.team {
        width: 40% }

    table.tournament tr td.score {
        width: 20% }

    table.scorecard {
        margin: 0;
        border-collapse: collapse;
        background: #F6F6F6;
        color: #303030; }

    table.scorecard tr td {
        padding: 0;
        font-size: 10px;
        border: 1px solid #e2e2e2; }

    table.scorecard tr td.hole {
        width: 25px; }

    table.scorecard tr td.eagle {
        color: #767676;
        background: #26A3E4; }

    table.scorecard tr td.birdie {
        color: #767676;
        background: #9EC9F5; }

    table.scorecard tr td.par {
        background: #F6F6F6; }

    table.scorecard tr td.bogey {
        color: #767676;
        background: #F5AD28 ; }

    table.scorecard tr td.double-bogey {
        color: #d0d0d0;
        background: #ea4500; }

    table.scorecard tr td.threeplus {
        color: #d0d0d0;
        background: #8e4600; }

</style>

<h3><?= $tournament->name; ?></h3>
<p><?= $tournament->date; ?></p>

<table class="uk-table tournament">
    <thead>
    <tr>
        <td rowspan="2">Pos</td>
        <td rowspan="2" class="flag">Country</td>
        <td rowspan="2" class="name">Player Name</td>
        <td rowspan="2">Front 9</td>
        <td rowspan="2">Back 9</td>
        <td rowspan="2">Strokes</td>
        <td rowspan="2">Total</td>
        <td rowspan="2">Adjusted</td>
        <?php if ($tournament->type != 'practice') { ?>
        <td rowspan="2">Points</td>
        <?php } ?>
        <td rowspan="2"></td>
    </tr>
    </thead>

    <?php foreach ($tournament->round as $round): ?>
    <tr>
        <td><?= $round->position; ?></td>
        <td><?= $round->player->flag; ?></td>
        <td class="name"><?= $round->player->name; ?></td>
        <td><?= $round->score('front_9'); ?></td>
        <td><?= $round->score('back_9'); ?></td>
        <td><?= $round->strokes; ?></td>
        <td><?= $round->total; ?></td>
        <td><?= $round->adjusted; ?></td>

        <?php if ($tournament->type != 'practice'): ?>
            <td><?= $round->points; ?></td>
        <?php endif; ?>
        <td><span class="uk-icon-dot-circle-o" data-uk-toggle="{target:'#round<?= $round->id; ?>'}"></span></td>
    </tr>

    <?php endforeach; ?>
</table>