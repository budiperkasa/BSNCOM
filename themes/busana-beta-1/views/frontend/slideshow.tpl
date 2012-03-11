<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>Minimalistic Slideshow Gallery with jQuery</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Minimalistic Slideshow Gallery with jQuery" />
        <meta name="keywords" content="minimalistic, slideshow, jquery, gallery, thumbnails, css3"/>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        
    </head>

    <body>	
		<div class="content">
		<div id="msg_slideshow" class="msg_slideshow">
			<div id="msg_wrapper" class="msg_wrapper">
				</div>
			<div id="msg_controls" class="msg_controls"><!-- right has to animate to 15px, default -110px -->
				<a href="#" id="msg_grid" class="msg_grid"></a>
				<a href="#" id="msg_prev" class="msg_prev"></a>
				<a href="#" id="msg_pause_play" class="msg_pause"></a><!-- has to change to msg_play if paused-->
				<a href="#" id="msg_next" class="msg_next"></a>
			</div>
			<div id="msg_thumbs" class="msg_thumbs"><!-- top has to animate to 0px, default -230px -->
					<div class="msg_thumb_wrapper">
					<a href="#"><img src="images/thumbs/1.jpg" alt="images/1.jpg"/></a>
					<a href="#"><img src="images/thumbs/2.jpg" alt="images/2.jpg"/></a>
					<a href="#"><img src="images/thumbs/3.jpg" alt="images/3.jpg"/></a>
					<a href="#"><img src="images/thumbs/4.jpg" alt="images/4.jpg"/></a>
					<a href="#"><img src="images/thumbs/5.jpg" alt="images/5.jpg"/></a>
					<a href="#"><img src="images/thumbs/6.jpg" alt="images/6.jpg"/></a>
					</div>
					<div class="msg_thumb_wrapper" style="display:none;">
					<a href="#"><img src="images/thumbs/7.jpg" alt="images/7.jpg"/></a>
					<a href="#"><img src="images/thumbs/8.jpg" alt="images/8.jpg"/></a>
					<a href="#"><img src="images/thumbs/9.jpg" alt="images/9.jpg"/></a>
					<a href="#"><img src="images/thumbs/10.jpg" alt="images/10.jpg"/></a>
					<a href="#"><img src="images/thumbs/11.jpg" alt="images/11.jpg"/></a>
					<a href="#"><img src="images/thumbs/12.jpg" alt="images/12.jpg"/></a>
				</div>
				<a href="#" id="msg_thumb_next" class="msg_thumb_next"></a>
				<a href="#" id="msg_thumb_prev" class="msg_thumb_prev"></a>
				<a href="#" id="msg_thumb_close" class="msg_thumb_close"></a>
				<span class="msg_loading"></span><!-- show when next thumb wrapper loading -->
				</div>
			</div>


			
			<div>
		</div>
<script type="text/javascript">
            $(function() {ldelim}
				/**
				* interval : time between the display of images
				* playtime : the timeout for the setInterval function
				* current  : number to control the current image
				* current_thumb : the index of the current thumbs wrapper
				* nmb_thumb_wrappers : total number	of thumbs wrappers
				* nmb_images_wrapper : the number of images inside of each wrapper
				*/
				var interval			= 4000;
				var playtime;
				var current 			= 0;
				var current_thumb 		= 0;
				var nmb_thumb_wrappers	= $('#msg_thumbs .msg_thumb_wrapper').length;
				var nmb_images_wrapper  = 6;
				/**
				* start the slideshow
				*/
				play();
				
				/**
				* show the controls when 
				* mouseover the main container
				*/
				slideshowMouseEvent();
				function slideshowMouseEvent(){ldelim}
					$('#msg_slideshow').unbind('mouseenter')
									   .bind('mouseenter',showControls)
									   .andSelf()
									   .unbind('mouseleave')
									   .bind('mouseleave',hideControls);
					{rdelim}
				
				/**
				* clicking the grid icon,
				* shows the thumbs view, pauses the slideshow, and hides the controls
				*/
				$('#msg_grid').bind('click',function(e){ldelim}
					hideControls();
					$('#msg_slideshow').unbind('mouseenter').unbind('mouseleave');
					pause();
					$('#msg_thumbs').stop().animate({ldelim}'top':'0px'{rdelim},500);
					e.preventDefault();
				{rdelim});
				
				/**
				* closing the thumbs view,
				* shows the controls
				*/
				$('#msg_thumb_close').bind('click',function(e){ldelim}
					showControls();
					slideshowMouseEvent();
					$('#msg_thumbs').stop().animate({ldelim}'top':'-230px'{rdelim},500);
					e.preventDefault();
				{rdelim});
				
				/**
				* pause or play icons
				*/
				$('#msg_pause_play').bind('click',function(e){ldelim}
					var $this = $(this);
					if($this.hasClass('msg_play'))
						play();
					else
						pause();
					e.preventDefault();	
				{rdelim});
				
				/**
				* click controls next or prev,
				* pauses the slideshow, 
				* and displays the next or prevoius image
				*/
				$('#msg_next').bind('click',function(e){ldelim}
					pause();
					next();
					e.preventDefault();
				{rdelim});
				$('#msg_prev').bind('click',function(e){ldelim}
					pause();
					prev();
					e.preventDefault();
				{rdelim});
				
				/**
				* show and hide controls functions
				*/
				function showControls(){ldelim}
					$('#msg_controls').stop().animate({ldelim}'right':'15px'{rdelim},500);
				{rdelim}
				function hideControls(){ldelim}
					$('#msg_controls').stop().animate({ldelim}'right':'-110px'{rdelim},500);
				{rdelim}
				
				/**
				* start the slideshow
				*/
				function play(){ldelim}
					next();
					$('#msg_pause_play').addClass('msg_pause').removeClass('msg_play');
					playtime = setInterval(next,interval)
				{rdelim}
				
				/**
				* stops the slideshow
				*/
				function pause(){ldelim}
					next();
					$('#msg_pause_play').addClass('msg_play').removeClass('msg_pause');
					clearTimeout(playtime);
				{rdelim}
				
				/**
				* show the next image
				*/
				function next(){ldelim}					++current;
					showImage('r');
				{rdelim}
				
				/**
				* shows the previous image
				*/
				function prev(){ldelim}
					--current;
					showImage('l');
				{rdelim}
				
				/**
				* shows an image
				* dir : right or left
				*/
				function showImage(dir){ldelim}
					/**
					* the thumbs wrapper being shown, is always 
					* the one containing the current image
					*/
					alternateThumbs();
					
					/**
					* the thumb that will be displayed in full mode
					*/
					var $thumb = $('#msg_thumbs .msg_thumb_wrapper:nth-child('+current_thumb+')')
								.find('a:nth-child('+ parseInt(current - nmb_images_wrapper*(current_thumb -1)) +')')
								.find('img');
					if($thumb.length){ldelim}
						var source = $thumb.attr('alt');
						var $currentImage = $('#msg_wrapper').find('img');
						if($currentImage.length){ldelim}
							$currentImage.fadeOut(function(){ldelim}
								$(this).remove();
								$('<img />').load(function(){ldelim}
									var $image = $(this);
									resize($image);
									$image.hide();
									$('#msg_wrapper').empty().append($image.fadeIn());
								{rdelim}).attr('src',source);
							{rdelim});
						{rdelim}
						else{ldelim}
							$('<img />').load(function(){ldelim}
									var $image = $(this);
									resize($image);
									$image.hide();
									$('#msg_wrapper').empty().append($image.fadeIn());
							{rdelim}).attr('src',source);
						{rdelim}
								
					{rdelim}
					else{ldelim} //this is actually not necessary since we have a circular slideshow
						if(dir == 'r')
							--current;
						else if(dir == 'l')
							++current;	
						alternateThumbs();
						return;
					{rdelim}
				{rdelim}
				
				/**
				* the thumbs wrapper being shown, is always 
				* the one containing the current image
				*/
				function alternateThumbs(){ldelim}
					$('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
									.hide();
					current_thumb = Math.ceil(current/nmb_images_wrapper);
					/**
					* if we reach the end, start from the beggining
					*/
					if(current_thumb > nmb_thumb_wrappers){ldelim}
						current_thumb 	= 1;
						current 		= 1;
					{rdelim}	
					/**
					* if we are at the beggining, go to the end
					*/					
					else if(current_thumb == 0){ldelim}
						current_thumb 	= nmb_thumb_wrappers;
						current 		= current_thumb*nmb_images_wrapper;
					{rdelim}
					
					$('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
									.show();	
				{rdelim}
				
				/**
				* click next or previous on the thumbs wrapper
				*/
				$('#msg_thumb_next').bind('click',function(e){ldelim}
					next_thumb();
					e.preventDefault();
				{rdelim});
				$('#msg_thumb_prev').bind('click',function(e){ldelim}
					prev_thumb();
					e.preventDefault();
				{rdelim});
				function next_thumb(){ldelim}
					var $next_wrapper = $('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+parseInt(current_thumb+1)+')');
					if($next_wrapper.length){ldelim}
						$('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
										.fadeOut(function(){ldelim}
											++current_thumb;
											$next_wrapper.fadeIn();									
										{rdelim});
					{rdelim}
				{rdelim}
				function prev_thumb(){ldelim}
					var $prev_wrapper = $('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+parseInt(current_thumb-1)+')');
					if($prev_wrapper.length){ldelim}
						$('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
										.fadeOut(function(){ldelim}
											--current_thumb;
											$prev_wrapper.fadeIn();									
										{rdelim});
					{rdelim}				
				{rdelim}
				
				/**
				* clicking on a thumb, displays the image (alt attribute of the thumb)
				*/
				$('#msg_thumbs .msg_thumb_wrapper > a').bind('click',function(e){ldelim}
					var $this 		= $(this);
					$('#msg_thumb_close').trigger('click');
					var idx			= $this.index();
					var p_idx		= $this.parent().index();
					current			= parseInt(p_idx*nmb_images_wrapper + idx + 1);
					showImage();
					e.preventDefault();
				{rdelim}).bind('mouseenter',function(){ldelim}
					var $this 		= $(this);
					$this.stop().animate({ldelim}'opacity':1{rdelim});
				{rdelim}).bind('mouseleave',function(){ldelim}
					var $this 		= $(this);	
					$this.stop().animate({ldelim}'opacity':0.5{rdelim});
				{rdelim});
				
				/**
				* resize the image to fit in the container (400 x 400)
				*/
				function resize($image){ldelim}
					var theImage 	= new Image();
					theImage.src 	= $image.attr("src");
					var imgwidth 	= theImage.width;
					var imgheight 	= theImage.height;
					
					var containerwidth  = 400;
					var containerheight = 400;
                
					if(imgwidth	> containerwidth){ldelim}
						var newwidth = containerwidth;
						var ratio = imgwidth / containerwidth;
						var newheight = imgheight / ratio;
						if(newheight > containerheight){ldelim}
							var newnewheight = containerheight;
							var newratio = newheight/containerheight;
							var newnewwidth =newwidth/newratio;
							theImage.width = newnewwidth;
							theImage.height= newnewheight;
						{rdelim}
						else{ldelim}
							theImage.width = newwidth;
							theImage.height= newheight;
						{rdelim}
					{rdelim}
					else if(imgheight > containerheight){ldelim}
						var newheight = containerheight;
						var ratio = imgheight / containerheight;
						var newwidth = imgwidth / ratio;
						if(newwidth > containerwidth){ldelim}
							var newnewwidth = containerwidth;
							var newratio = newwidth/containerwidth;
							var newnewheight =newheight/newratio;
							theImage.height = newnewheight;
							theImage.width= newnewwidth;
						{rdelim}
						else{ldelim}
							theImage.width = newwidth;
							theImage.height= newheight;
						{rdelim}
					{rdelim}
					$image.css({ldelim}
						'width'	:theImage.width,
						'height':theImage.height
					{rdelim});
				{rdelim}
            {rdelim});
        </script>
        
    </body>
</html>