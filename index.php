<?php
    // Load config
    $config = json_decode(file_get_contents('compo.json'));
    $compos = $config->compos;
    $history = $config->history;
    $current = $compos->{$history[0]->compo};
    $current->id = $history[0]->compo;

    // Calculate (and sort) the standings
    $leaderboard = [];
    foreach ($history as $season) {
        $results = $season->results;
        for ($i = 0; $i < 3; $i++) {
            if (isset($results[$i])) {
                $leaderboard[$results[$i]] += 3-$i;
            }
        }
    }
    arsort($leaderboard);
?>
<!doctype html>
<html lang="en">
<head>
    <title>CodeCup @ CodeDay</title>
    <link rel="stylesheet" href="/assets/css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="index">
<div class="content">
    <header>
        <h1><a href="/">CodeCup @ CodeDay</a></h1>
        <nav>
            <ul>
                <?php if ($current->id != "unannounced"): ?>
                    <li class="challenge"><a href="<?=$current->link?>">Start the Current Challenge</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </header>
    <section class="details">
        <article class="general">
            <h2>About CodeCup</h2>
            <p>CodeCup is a nationwide challenge which happens at each CodeDay, starting at 6:30pm Pacific Time,
               organized by <a href="https://srnd.org/">srnd.org</a> and presented by
               <a href="https://www.splunk.com/en_us/about-us/splunk4good.html">Splunk.</a></p>
            <h3>Earning Points</h3>
            <p>All participants at each city compete together: it's city-vs-city.</p>
            <p>Each season, the first-place city will win 3 leaderboard points, the second-place city will win 2, and
               the third-place will win 1. The leaderboard never resets.</p>
            <h3>Prizes</h3>
            <p>Because you're competing as a city, there are no individual prizes, but each season's first-place city
               will get a CodeCup victory cup to display at future CodeDays!</p>
        </article>
        <article class="current">
            <h2>Current Challenge</h2>
            <?php if ($current->id != "unannounced"): ?>
                <p>This season's challenge is <a href="<?=$current->link?>"><?=$current->name?>.</a></p>
                <p><?=$current->long?></p>
                <p><?=$current->scoring?></p>
                <p class="cta"><a href="<?=$current->link?>">Start Now</a></p>
            <?php else: ?>
                <p>This season's challenge will be announced shortly before CodeDay.</p>
            <?php endif ?>
        </article>
    </section>
    <section class="leaderboard">
        <h2>Leaderboard</h2>
        <ol>
            <?php foreach ($leaderboard as $city => $points): ?>
                <li><?= $city ?> (<?= $points ?> points)</li>
            <?php endforeach ?>
        </ol>
        <br />
        <h2>Presented by</h2>
        <p><a href="https://www.splunk.com/en_us/about-us/splunk4good.html" target="_blank" style="text-decoration:none;border:none"><img src="https://codeday.org/assets/img/sponsors/splunk.png" style="max-width:200px;margin-top:-1.5rem" /></a></p>
    </section>
    <section class="history">
        <h2>History</h2>
        <ul>
            <?php foreach ($history as $season): ?>
                <?php if (isset($season->results) && count($season->results) > 0): ?>
                <li>
                    <h3><?=substr($season->year, 2)?>-<?=strtoupper($season->season)?>: <?=$compos->{$season->compo}->name?></h3>
                        <ol class="results">
                            <?php foreach ($season->results as $city): ?>
                                <li><?=$city?></li>
                            <?php endforeach ?>
                        </ol>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </section>
</div>
</body>
</html>
