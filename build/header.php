<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=1190" />
	<meta name="MobileOptimized" content="1190" />
	<title>BoichFamilyCellar</title>
	<link media="all" rel="stylesheet" type="text/css" href="css/all.css" />

	<script type="text/javascript" src="https://use.typekit.net/dsx3ufk.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

	<!-- Google CDN jQuery with fallback to local -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/jquery-1.9.1.min.js"%3E%3C/script%3E'))</script>

	<script type="text/javascript" src="js/jquery.main.js"></script>
	<!--[if IE]><script type="text/javascript" src="js/ie.js"></script><![endif]-->
	<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"/><![endif]-->

	<!-- Custom scrollbars -->
	<link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" />
	<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>

	<script>

		var scroller;

		$(function()
		{
			$(".scrollable-area-wrapper").mCustomScrollbar(
			{
				scrollButtons:{
					enable:true
				}, 
				scrollInertia: 250,
				autoDraggerLength: true,
				advanced:{
				    updateOnContentResize: true
				}
			});

			scroller = $('div.carousel').data('ScrollAbsoluteGallery');

			// smooth switching between pages
			$(window).on('hashchange', function() 
			{
				var hash = window.location.hash.substr(1);

				if( hash == '' ) {
					return;
				}

				scroller.numSlide( parseInt(hash) );
			});

			// initial position
			var hash = window.location.hash.substr(1);
			var startpos = parseInt(hash);

			if( hash != '' ) {
				scroller.numSlide( startpos, true )
			}

			// hide scrollers if they're not necessary
			if( $('.slide').length == 1 )
			{
				$('.btn-next, .btn-prev').hide();
			}
			
		});
	</script>

</head>