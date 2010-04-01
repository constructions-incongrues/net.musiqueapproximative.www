<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/images/favico.png" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <!--
    <link type="application/rss+xml" title="Musique Approximative" rel="alternate" href="<?php echo url_for('@post_feed') ?>"/>
    -->
    <link type="application/rss+xml" title="Musique Approximative" rel="alternate" href="http://feeds.feedburner.com/musique-approximative"/>
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
        <?php include_component('post', 'contributors') ?>
      </p>
      <p>
        Ce projet a été développé par <a href="https://launchpad.net/~constructions-incongrues">Constructions Incongrues</a> 
        et est hébergé par <a href="http://www.pastis-hosting.net">Pastis Hosting</a>.
      </p>
      <p>
        Le code source du projet est <a href="https://launchpad.net/musique-approximative">distribué</a> sous licence 
        <a href="http://www.gnu.org/licenses/agpl-3.0.html">GNU AGPLv3</a>.
      </p>
      <p>Contact : bertier at musiqueapproximative point net</p>
    </div>
    <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
    try {
      var pageTracker = _gat._getTracker("UA-4958604-1");
      pageTracker._trackPageview();
    } catch(err) {}</script>
  </body>
</html>
