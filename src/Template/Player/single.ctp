<style>
    div.player {
        padding: 20px;
        background: #d8d8d8; }

    div.image img {
        width: 100%; }

    div.player dl {
        float: left; }

    div.player dl dt.name {
        padding: 10px 0;
        font-size: 30px;
        color: #005391; }

    div.player dl dt.country {
        font-weight: 300;
        text-transform: uppercase;
        width: 100%;
        margin-bottom: 20px;
        border-bottom: 3px #005391 solid;
        font-size: 18px;
        color: #4e4e4e; }

    div.player dl dt.country img {
        vertical-align: middle;
        width: 25px; }

    div.player dl.details dl {
        padding-right: 30px; }

    div.player dl.details dl dd {
        text-transform: uppercase;
        color: #005391;
        font-weight: 400;
        font-size: 14px; }

    div.player dl.details dl dt {
        font-weight 400;
        font-size: 10px;
        color: #4e4e4e;
        text-transform: uppercase;
        padding-bottom: 15px; }

    div.bio {
        border-top: 2px solid #005391;
        padding: 20px;
        background: #d8d8d8;
        color: #000; }

    div.bio p {
        font-weight: 300; }

    ul.nav-tabs li a {
        font-weight: 400;
        font-size: 15px;
        border: 0;
        border-top: 4px #d8d8d8 solid;
        font-family: 'Roboto Condensed', sans-serif;
        text-transform: uppercase;
        border-radius: 0;
        color: #005391;
        background: #d8d8d8;
        margin: 0; }

    ul.nav-tabs {
        border: 0; }

    ul.nav-tabs li.active a, ul.nav-tabs li:hover a, ul.nav-tabs li.active:hover a {
        border: 0;
        border-top: 4px #005391 solid;
        border-radius: 0;
        color: #005391;
        background: #ffffff;;
        margin: 0; }

    div.tab-content {
        padding: 20px 0; }
</style>

<div class="player">
    <div class="uk-grid">
        <div class="uk-width-1-3 image">
            <?= $player->photo; ?>
        </div>
        <div class="uk-width-2-3">
            <dl class="details">
                <dt class="name"><?= $player->name; ?></dt>
                <dt class="country"><?= $this->Html->image($player->flag, ['alt' => $player->country]) ?> <?= $player->country; ?></dt>
                <dt>Handicap: <?= $player->handicap(); ?></dt>
                <dl>
                    <dd><?= $player->height; ?> cms</dd>
                    <dt>Height</dt>
                    <dd><?= $player->weight; ?> kgs</dd>
                    <dt>Weight</dt>
                    <dd><?= $player->age; ?></dd>
                    <dt>Age</dt>
                </dl>
                <dl>
                    <dd><?= $player->college; ?></dd>
                    <dt>College</dt>
                    <dd><?= $player->pro; ?></dd>
                    <dt>AGA Debut</dt>
                    <dd><?= $player->pob; ?></dd>
                    <dt>Place of Birth</dt>
                </dl>
                <dl>
                    <dd><?= $player->clubs; ?></dd>
                    <dt>Clubs</dt>
                    <dd><?= $player->glove; ?></dd>
                    <dt>Glove</dt>
                </dl>
            </dl>
        </div>
    </div>
</div>
<div class="uk-grid uk-margin-bottom">
    <div class="uk-width-1-1">
        <div class="bio">
            <?= $player->bio; ?>
        </div>
    </div>
</div>

<!-- This is the tabbed navigation containing the toggling elements -->
<ul class="uk-tab" data-uk-tab="{connect:'#player_tabs'}">
    <li><a href="">Season Summary</a></li>
    <li><a href="">Scorecards</a></li>
    <li><a href="">Career</a></li>
    <li><a href="">AGA Cup</a></li>
    <li><a href="">Stats</a></li>
    <li><a href="">Handicap Tracker</a></li>
</ul>

<!-- This is the container of the content items -->
<ul id="player_tabs" class="uk-switcher uk-margin">
    <li>

        <script>
            $(function(){
                Dashboard.init();
            });

            var Dashboard = {
                el: {
                },
                init: function(){
                    var time = new Date();
                    year = time.getFullYear();
                    this.el.yearFilter = $('.year');
                    this.el.seasonFilter = $('.season');
                    this.el.tournamentFilter = $('.tournament');
                    this.el.roundFilter = $('.round');


                    this.el.yearFilter.find('li a').click(this.switchYear);

                    this.el.seasonFilter.change(this.switchSeason);
                    var active_season = this.el.seasonFilter.val();
                    this.el.tournamentFilter.filter('[data-id="'+active_season+'"]').removeClass('uk-hidden');


                    this.el.tournamentFilter.change(this.switchTournament);
                    var active_tournament = this.el.roundFilter.val();
                    this.el.roundFilter.filter('[data-id="'+active_tournament+'"]').removeClass('uk-hidden');


                },
                switchYear: function(){
                    year = $(this).data('tab');
                    $('.uk-table').find('tbody tr').fadeOut().addClass('uk-hidden');
                    $('.uk-table').find('tbody tr.year'+year).fadeIn().removeClass('uk-hidden');
                    $('.year').children('li').removeClass('uk-active');
                    $(this).parents('li').addClass('uk-active');
                },
                switchSeason: function(){
                    var current_season = $(this).val();
                    $('.tournament').addClass('uk-hidden');
                    $('.tournament[data-id="'+current_season+'"]').removeClass('uk-hidden');
                },
                switchTournament: function(){
                    console.log("wfwef");
                    var current_tournament = $(this).val();
                    $('.round').addClass('uk-hidden');
                    $('.round[data-id="'+current_tournament+'"]').removeClass('uk-hidden');
                }
            }
        </script>

        <ul class="uk-subnav uk-subnav-pill year">
            <li class="uk-active"><a data-tab="2015">2015</a></li>
            <li><a data-tab="2014">2014</a></li>
            <li><a data-tab="2013">2013</a></li>
        </ul>

        <table class="uk-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Tournament</th>
                    <th>Strokes</th>
                    <th>Adjusted</th>
                    <th>Pos.</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($player->round as $round): ?>
                <tr class="year<?= $round->tournament->season->year ?> <?= (($round->tournament->type == 18 || date('Y', strtotime($round->tournament->date)) != date('Y')) ? 'uk-hidden' : '') ?>">
                        <td><?= $round->tournament->display_date ?></td>
                        <td><?= $round->tournament->name ?></td>
                        <td><?= $round->strokes ?></td>
                        <td><?= $round->score ?></td>
                        <td><?= $round->position ?></td>
                        <td><?= $round->points ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </li>
    <li>

        <?= $this->Form->select('season', $seasons, ['class' => 'season']) ?>

        <?php foreach($seasons as $id => $season) : ?>
            <?= $this->Form->select('tournament', $rounds[$id], ['class' => 'tournament uk-hidden', 'data-id' => $id]) ?>
        <?php endforeach; ?>

        <?php foreach($player->round as $round): ?>

            <table class="uk-table uk-hidden round" data-id="<?= $round->id ?>">
                <thead>
                    <tr>
                        <th colspan="22"><?= $round->tournament->name ?></th>
                    </tr>
                    <tr>
                        <th>Par</th>
                        <?php for($hole = 1; $hole <= $round->holes_played; $hole++) :?>
                            <th><?= $hole ?></th>
                            <?php if($hole == 9) : ?>
                                <th>OUT</th>
                            <?php elseif($hole == 18) : ?>
                                <th>IN</th>
                                <th>TOTAL</th>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Par</th>
                        <?php $scorecard = $round->tournament->course->scorecard('all'); ?>
                        <?php for($hole = 1; $hole <= $round->holes_played; $hole++) :?>
                            <td><?= $scorecard[$hole] ?></td>
                            <?php if($hole == 9) : ?>
                                <th><?= $round->tournament->course->scorecard('front9') ?></th>
                            <?php elseif($hole == 18) : ?>
                                <th><?= $round->tournament->course->scorecard('back9') ?></th>
                                <th><?= $round->tournament->course->scorecard('par') ?></th>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th>Score</th>
                        <?php $scorecard = $round->score('all'); ?>
                        <?php for($hole = 1; $hole <= $round->holes_played; $hole++) :?>
                            <td><?= $scorecard[$hole] ?></td>
                            <?php if($hole == 9) : ?>
                                <th><?= $round->score('front9') ?></th>
                            <?php elseif($hole == 18) : ?>
                                <th><?= $round->score('back9') ?></th>
                                <th><?= $round->strokes ?></th>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <?php $scorecard = $round->score('status'); ?>
                        <?php for($hole = 1; $hole <= $round->holes_played; $hole++) :?>
                            <td><?= $scorecard[$hole] ?></td>
                            <?php if($hole == 9) : ?>
                                <th>--</th>
                            <?php elseif($hole == 18) : ?>
                                <th>--</th>
                                <th><?= sprintf('%+d', $round->total) ?></th>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </tr>
                </tbody>
            </table>

        <?php endforeach; ?>

    </li>
    <li>
        Career
    </li>
    <li></li>
    <li></li>
    <li>
    
 		<script src="http://mbostock.github.com/d3/d3.v2.js"></script>

 
	<div class="handicap"></div>
        
 
<style>

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}

.x.axis path {
  display: none;
}

.line {
  fill: none;
  stroke: steelblue;
  stroke-width: 2px;
}
    
    .adjusted {
  fill: none;
}

</style>
    
                        <?php 
                $handicaps = array();
                foreach($player->round as $round){
                    if($round->holes_played == 18) {
                    $handicaps[$round->tournament->date] = $player->handicap($round->tournament->date);
                    $handicap_array[$round->tournament->date] = $round->handicap;
                    $adjusted_array[$round->tournament->date] = $round->adjusted;
                    }
                }
                ksort($handicaps);
                ksort($handicap_array);
                ksort($adjusted_array);
        ?>
        <script>

    var margin = {top: 20, right: 20, bottom: 30, left: 50},
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;
            
            var parseDate = d3.time.format("%d-%b-%y").parse;


    var x = d3.time.scale()
        .range([0, width]);

    var y = d3.scale.linear()
        .range([height, 0]);

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var line = d3.svg.line()
        .x(function(d) { return x(d.date); })
        .y(function(d) { return y(d.close); }).interpolate("basis");

    var svg = d3.select(".handicap").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            
    var data = [
            <?php foreach($handicaps as $date => $handicap) : ?>
               { date: '<?= date('d-M-y', strtotime($date)) ?>' , value: <?= $handicap ?>},
            <?php endforeach; ?>
    ];  
            
                        
    var data2 = [
            <?php foreach($handicap_array as $date => $handicap) : ?>
               { date: '<?= date('d-M-y', strtotime($date)) ?>' , value: <?= $handicap ?> },
            <?php endforeach; ?>
    ];  
            
    var data3 = [
            <?php foreach($adjusted_array as $date => $adjusted) : ?>
               { date: '<?= date('d-M-y', strtotime($date)) ?>' , value: <?= $adjusted ?> },
            <?php endforeach; ?>
    ];  
                
            
    data.forEach(function(d) {
        d.date = parseDate(d.date);
        d.close = d.value;
  });
            
                data2.forEach(function(d) {
        d.date = parseDate(d.date);
        d.close = d.value;
  });
        
                        data3.forEach(function(d) {
        d.date = parseDate(d.date);
        d.close = d.value;
  });
            
  x.domain(d3.extent(data, function(d) { return d.date; }));
  y.domain(d3.extent(data, function(d) { return d.close; }));
        
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Handicap");

  svg.append("path")
      .datum(data)
      .attr("class", "line")
      .attr("d", line);
</script>
 </li>
</ul>