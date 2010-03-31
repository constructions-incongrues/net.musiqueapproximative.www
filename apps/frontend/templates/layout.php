<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/images/favico.png" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <link type="application/rss+xml" title="Musique Approximative" rel="alternate" href="<?php echo url_for('@post_feed') ?>"/>
  </head>
  <body>
    <div id="browse-all">
      <p>
        <?php echo link_to('Browse all tracks', '@post_list', array('id' => 'index-toggle')) ?> <span id="loading" style="display: none;">(loading...)</span>
      </p>
    </div>

    <div id="browse"> 
      <form method="get" action="<?php echo url_for('@post_list') ?>">
        <input type="text" class="search" name="q" value="<?php echo $sf_request->getParameter('q') ?>"/>
        <input type="submit" class="submit" value="Search !" />
      </form>
    </div>

    <div id="index"></div>

    <?php echo $sf_content ?>

    <div id="maintitle">Musique Approximative</div>

    <div id="about">
      <p>Ceci est un <em>micropodcast</em>. Vous y trouverez un flux de morceaux glanés au fil du web. </p>
      <p>
        <strong>Contributeurs : </strong>
         <a href="http://bozoo.pastis-hosting.net">Bozoo</a> -
         <a href="http://myspace.com/renardocrew">Chaton Pute</a> -
         <a href="http://myspace.com/renardocrew">Super Promotion</a> -
         <a href="http://myspace.com/deehowyou">Deehowyou</a> -
         <a href="http://www.ouiedire.net">Valkiri</a> -
         <a href="http://www.sidabitball.com">Jambonbill</a> - 
         <a href="http://myspace.com/eatrabbit">Eat Rabbit</a> - 
         <a href="http://www.glafouk.com">Glafouk</a> -
         <a href="http://www.cochise.canalblog.com">Jacques Cochise</a> - 
         <a href="http://www.myspace.com/plancton9">Plancton9</a> - 
         <a href="http://www.myspace.com/schling">Schling</a> -
         <a href="http://puyopuyo.lautre.net">Puyo Puyo</a> -
         <a href="http://www.myspace.com/rachitikdata">Bibi Mati</a> -
         <a href="http://www.mazemod.org">Otrox</a> - 
         <a href="http://www.egotwister.com">Edmond Leprince</a>
      </p>
      <p>Ce projet est hébergé par <a href="http://www.pastis-hosting.net">Pastis Hosting</a>.</p>
      <p>Contact : bertier at musiqueapproximative point net</p>
    </div>
  </body>
</html>
