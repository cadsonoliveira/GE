<!doctype html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foundation Docs: Clearing</title>
    <link rel="stylesheet" href="http://foundation.zurb.com/docs/assets/normalize.css" />
    <link rel="stylesheet" href="http://foundation.zurb.com/docs/assets/docs.css" />
    <script src="http://foundation.zurb.com/docs/assets/vendor/custom.modernizr.js"></script>
  </head>
  <body class="antialiased">

    <nav class="top-bar">
      <ul class="title-area">
        <!-- Title Area -->
        <li class="name">
          <h1><a href="/">Foundation</a></h1>
        </li>
        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
      </ul>

      <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
          <li class="divider"></li>
          <li><a href="/grid.php">Features</a></li>
          <li class="divider"></li>
          <li><a href="/templates.php">Add-ons</a></li>
          <li class="divider"></li>
          <li><a href="/case-jacquelinewest.php">Case Studies</a></li>
          <li class="divider"></li>
          <li><a href="/docs/">Docs</a></li>
          <li class="divider"></li>
          <li><a href="/training.php">Training</a></li>
          <li class="divider"></li>
          <li class="has-form">
            <a href="http://foundation.zurb.com/docs" class="button">Getting Started</a>
          </li>
        </ul>
      </section>
    </nav>


    <div class="row">
      <div class="large-12 columns">
        <h1 class="docs header"><a href="http://foundation.zurb.com/docs/">Foundation 4 Documentation</a></h1>
        <h6 class="docs subheader"><a href="http://foundation.zurb.com/old-docs/f3">Want F3 Docs?</a></h6>
        <hr>
      </div>
    </div>


<div class="row">
  <div class="large-9 push-3 columns">


    <div class="row">
      <div class="large-12 columns">
        <h2>Clearing</h2>
        <h4 class="subheader">Since Orbit isn't intended for variable-height content, we decided it would be a great idea to create a plugin that would help in that regard. Clearing makes it easy to create responsive lightboxes with any size image.</h4>
      </div>
    </div>

    <div class="row">
      <div class="large-12 columns">

        <ul class="clearing-thumbs" data-clearing>
          <li><a class="th" href="img/1.jpg"><img data-caption="Nulla vitae elit libero, a pharetra augue. Cras mattis consectetur purus sit amet fermentum." src="img/1.jpg"></a></li>
          <li><a class="th" href="img/1.jpg"><img src="../img/demos/demo2-th.jpg"></a></li>
          <li><a class="th" href="img/1.jpg"><img data-caption="Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus." src="img/1.jpg"></a></li>
          <li><a class="th" href="img/1.jpg"><img src="img/1.jpg"></a></li>
          <li><a class="th" href="img/1.jpg"><img data-caption="Integer posuere erat a ante venenatis dapibus posuere velit aliquet." src="img/1.jpg"></a></li>
        </ul>

      </div>
    </div>

  </div>
</div>

   <script>

      // Google Analytics
      var _gaq = _gaq || [];
      _gaq.push(
        ['_setAccount', 'UA-2195009-2'],
        ['_trackPageview'],
        ['b._setAccount', 'UA-2195009-27'],
        ['b._trackPageview']
      );

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

      document.write('<script src="http://foundation.zurb.com/docs/assets/vendor/'
        + ('__proto__' in {} ? 'zepto' : 'jquery')
        + '.js"><\/script>');
    </script>
    <script src="http://foundation.zurb.com/docs/assets/docs.js"></script>
    <script>
      $(document)

        .foundation();


      // For Kitchen Sink Page
      $('#start-jr').on('click', function() {
        $(document).foundation('joyride','start');
      });
    </script>
</body>
</html>