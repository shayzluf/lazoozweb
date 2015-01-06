var blueimp;
var FormValidator;
var spinner;
var throttle;
var Aparallax = true;
var invert = false;
function invertLayout() {
	function s4() {
		return Math.floor( ( 1 + Math.random() ) * 0x10000 )
			.toString( 16 )
			.substring( 1 );
	}
	function guid() {
		return 'class-' + s4() + s4() + '-' + s4() + '-' + s4() + '-' +
			s4() + '-' + s4() + s4() + s4();
	}
	var uuid = guid();
	$( '.theme-white' )
		.removeClass( 'theme-white' )
		.addClass( uuid );
	$( '.theme-color' )
		.removeClass( 'theme-color' )
		.addClass( 'theme-white' );
	$( '.' + uuid )
		.removeClass( uuid )
		.addClass( 'theme-color' );
	return ! invert;
}
var handler = {
	'transition' : {
		call : function( parameter ) {
			if( parameter )
				$( 'body' ).removeClass( 'disable-transition' );
			else
				$( 'body' ).addClass( 'disable-transition' );
		},
		last : false
	},
	'transform' : {
		call : function( parameter ) {
			if( parameter )
				$( 'body' ).removeClass( 'disable-transform' );
			else
				$( 'body' ).addClass( 'disable-transform' );
		},
		last : false
	},
	'animation' : {
		call : function( parameter ) {
			if( parameter )
				$( 'body' ).removeClass( 'disable-animation' );
			else
				$( 'body' ).addClass( 'disable-animation' );
		},
		last : false
	},
	'parallax' : {
		call : function( parameter ) {
			Aparallax = parameter;
		},
		last : false
	},
	'layout-normal' : {
		call : function( parameter ) {
			if( parameter && invert )
				invert = invertLayout();
		},
		last : false
	},
	'layout-inverted' : {
		call : function( parameter ) {
			if( parameter && ! invert )
				invert = invertLayout();
		},
		last : false
	}
};
// All scripts
( function( $ ) {
    'use strict';
    $( handler );
	/**
		Global ready handler
	*/
	//	Caroufredsel
	function caroufredsel( customCollection ) {
		var collection = ( customCollection ) ? customCollection : $( '.caroufredsel' );
		collection.each( function() {
			var enableSwipe = $( this ).hasClass( 'enable-swipe' );
			$( this ).carouFredSel( {
				auto : false,
				swipe : {
					onTouch : enableSwipe,
					onMouse : enableSwipe
				},
				items : {
					height : 'variable',
					visible : 1,
					filter : 'div'
				},
				scroll: {
					items : 1,
					duration : throttle * 2,
					easing : 'linear',
					onAfter : function() {
						$( this ).closest( '.caroufredsel_wrapper' ).trigger( 'updateSizes' );
						$( this ).children().first().find( '.caroufredsel' ).trigger( 'updateSizes' );
						//var children = $( this ).children();
						//if( children.first().hasClass( 'disabled' ) )
							//$( this ).trigger( 'slideTo', children.first().next() );
					}
				},
				responsive : true,
				circular : true,
				infinite : false,
				pagination : {
					container : function() {
						if( $( this ).hasClass( 'enable-pagination' ) ) {
							var pagination = $( this ).closest( '.caroufredsel_wrapper' ).parent().find( '.pagination' );
							return ( pagination.length > 0 ) ? pagination : $( this ).closest( '.row' ).parent().find( '.pagination' );
						}
						else
							return false;
					},
					anchorBuilder : function( nr ) {
						if( $( this ).parent().hasClass( 'image-pagination' ) )
							return '<li><img src="' + $( this ).attr( 'data-image' ) + '" /></li>';
						else
							return '<a href="#"><span>' + nr + '</span></a>';
					}
				}/*,
				prev : {
					button : function() {
						return ( $( this ).hasClass( 'enable-controls' ) ) ? $( this ).closest( '.row' ).parent().find( '.controls > .previous' ) : undefined;
					},
					key : 'left',
				},
				next : {
					button : function() {
						return ( $( this ).hasClass( 'enable-controls' ) ) ? $( this ).closest( '.row' ).parent().find( '.controls > .next' ) : undefined;
					},
					key : 'right'
				}*/
			} );
			if( $( this ).hasClass( 'enable-controls' ) ) {
				var controls = $( this ).closest( '.row' ).parent().find( '.controls' );
				var collectionItem = $( this );
				controls.find( '.previous' ).click( function() {
					collectionItem.trigger( 'slideTo', collectionItem.children( ':not(.disabled)' ).last() );
				} );
				controls.find( '.next' ).click( function() {
					collectionItem.trigger( 'slideTo', collectionItem.children( ':not(.disabled)' )[ 1 ] );
				} );
			}
		} );
		/*
		$( '.caroufredsel .caroufredsel' ).each( function() {
			var minHeight = 0;
			$( this ).children().each( function() {
				var height = $( this ).children().first().outerHeight();
				minHeight = ( minHeight == 0 ) ? height : Math.min( height, minHeight );
				$( this ).height( height );
			} );
			$( this ).height( minHeight ).parent().height( minHeight );
		} );
		*/
		//$( '.caroufredsel .pagination' ).css( 'display', 'block' );
	}
	//--//
	$( document ).ready( function() {
        /*------------------------------------------------------------*/
		/*
		 * Additional handlers
		 */
		var path = window.location.pathname, parts = path.split('/');                
                
		$('.nav li:first').addClass('active');
		$('.nav li:first, .nav-footer li:first').find('a').attr('href', '#slider');
		if (parts !== '') {
			$('.nav li:first').removeClass('active');
			//$('.nav #blog').parent().addClass('active');
		}
		if(typeof window.user !== 'undefined') {
			$('.nav-footer li a').each(function() {
				var href = $(this).attr('href');
				if(href.indexOf("#") != -1) {
					$(this).attr('href', (typeof window.icl_home !== 'undefined' ? window.icl_home : window.user.home_url) + href);
				}
			});
			$('.nav li a').each(function() {
				var href = $(this).attr('href');
				if(href.indexOf("#") != -1) {
					$(this).attr('href', (typeof window.icl_home !== 'undefined' ? window.icl_home : window.user.home_url) + href);
				}
			});
		}
		if ($('body').hasClass('logged-in')) {
			for (var i in window.user.exclude) {
				if( window.user.exclude[i] )
					$('.nav, .nav-footer').find('#' + window.user.exclude[i]).parent().remove();
			}
			$('.nav li:last-child .dropdown-slide').append($('<li>').append($('<a>').addClass('scroll').attr('href', window.user.logout_url).text(window.user.logout_title)));
			$('.nav-footer').append($('<li>').append($('<a>').attr('href', window.user.logout_url).text(window.user.logout_title)));
		}
		$('p:empty').remove();
		$('.commentlist').find('ol').toggleClass('commentlist');
                $('.extra').find('img').toggleClass('img-responsive');
                
                $(window).on('resize load', function() {
                    var frame = $('.entry').find('iframe');
                    var frameWidth = frame.attr('width');
                    var frameHeight = frame.attr('height');
                    var proportion = frameHeight/frameWidth;
                    frame.attr({
                        'width': $('.entry').width(),
                        'height': Math.round($('.entry').width() * proportion)
                    });
                });
                
                if ($('.blog').length > 0) {
                    $('body > .overlay').first().remove();
                }
                /*
		$('#slider').on('click', function() {
			var video = $(this).find('video').get(0);
			if (typeof video !== 'undefined') {
				if (video.paused) {
					video.play();
				} else {
					video.pause();
				}
			}
		});
                */
		$('.entry').find('.more-link').toggleClass('more').append('<div class="arrow"><span class="tip"></span></div>').parent('p').css({'font-size': '100%'});
		$('.video-container').find('p').css({'height': '100%'});
                
        /*------------------------------------------------------------*/
		$( 'img' ).load(function() {
			//this tweak is a fix on chrome and safari browser
			$( '.masonry' ).masonry();
		});
		$( '.masonry' ).masonry( {
			itemSelector : 'a',
			isResizable : 'true',
			columnWidth : 1
		} );
		//	Form validation
		function validatorHighlight( form, errors, event ) {
			$( 'form[name=' + form + '] *' ).removeClass( 'has-error has-warning' );
			if( errors.length > 0 )
				for( var i = 0, errorLength = errors.length; i < errorLength; i++)
					$( '*[name=' + errors[ i ].name + ']' ).addClass( 'has-error' );
					//errorString += errors[ i ].message + '<br />';
			else
				if( form === 'feedbackForm' ) {
					event.preventDefault();
					$( 'form[name=feedbackForm]' ).ajaxSubmit( function() {
						$( 'form[name=feedbackForm]' ).resetForm();
						alert( 'Thank you for your comment!' );
					} ); 
				}
		}
		$( 'form.validator' ).each( function() {
			var fields = [];
			$( this ).find( '*[data-validator]' ).each( function() {
				fields.push( {
					name : $( this ).attr( 'name' ),
					rules : $( this ).attr( 'data-validator' )
				} );
			} );
			var validator = new FormValidator( $( this ).attr( 'name' ), fields, function( errors, event ) {
				validatorHighlight( $( this )[ 0 ].form.name, errors, event );
			} );
			$( validator );
		} );
		//--//
		//	Enable parallax effect
		if( $( window ).width() > 1024 )
			$( '.aparallax' ).Aparallax();
		//--//
		//	Search box layout status
		var layout = false;
		//--//
		//	Progressbars
		var canvas = document.createElement( 'canvas' );
		var canvasSupported = canvas.getContext;
		function drawSkills( resize ) {
			if( canvasSupported )
				$( '.skills canvas' ).each( function() {
					if( !$( this ).hasClass( 'animated' ) ) {
						var width = Math.min( $( window ).height() - $( '.topper' ).outerHeight() , $( this ).parent().width() );
						$( this ).attr( 'width', ( width * 2 ) + 'px' );
						$( this ).attr( 'height', ( width * 2 ) + 'px' );
						$( this ).css( {
							'width' : width + 'px',
							'height' : width + 'px'
						} );
						var context;
						if( $( window ).width() <= 1024 )
							$( this ).addClass( 'animate-once' );
						if( $( this ).hasClass( 'animate-once' ) || resize ) {
							context = $( this ).get( 0 ).getContext( '2d' );
							context.clearRect( 0, 0, ( width * 2 ), ( width * 2 ) );
							context.beginPath();
							context.lineWidth = 5.5;
							context.strokeStyle = $( this ).attr( 'data-color' );
							context.arc( width, width, width - context.lineWidth, 1.5 * Math.PI, 1.5 * Math.PI + $( this ).attr( 'data-progress' ) * 2 * Math.PI / 100, false );
							context.stroke();
						}
						else {
							$( this ).addClass( 'animate-once' );
							var progressCurrent = 0;
							var progressTotal = $( this ).attr( 'data-progress' );
							var color = $( this ).attr( 'data-color' );
							context = $( this ).get( 0 ).getContext( '2d' );
							$( this ).addClass( 'animated' );
							var jQuerythis = $( this );
							var interval = setInterval( function() {
								context.clearRect( 0, 0, ( width * 2 ), ( width * 2 ) );
								context.beginPath();
								context.lineWidth = 5.5;
								context.strokeStyle = color;
								context.arc( width, width, width - context.lineWidth, 1.5 * Math.PI, 1.5 * Math.PI + progressCurrent * 2 * Math.PI / 100, false );
								context.stroke();
								if( progressCurrent < progressTotal )
									//progressCurrent = Math.min( progressCurrent + Math.round( progressTotal / 30 ), progressTotal );
									progressCurrent += 5;
								else {
									jQuerythis.removeClass( 'animated' );
									clearInterval( interval );
								}
							}, throttle / 4 );
						}
					}
				} );
		}
		$( '.progress' ).each( function() {
			var progressBar = $( this ).find( '.progress-bar' );
			if( canvasSupported ) {
				var color = progressBar.attr( 'data-color' );
				var progress = Math.round( progressBar.attr( 'aria-valuenow' ) / ( progressBar.attr( 'aria-valuemax' ) - progressBar.attr( 'aria-valuemin' ) ) * 100 );

				var parent = $( this ).parent();
				var width = Math.min( $( window ).height() - $( '.topper' ).outerHeight() , parent.innerWidth() );
				var html = $( '<canvas class="progress-circle" width="' + ( width * 2 ) + 'px" height="' + ( width * 2 ) + 'px" data-progress="' + progress + '" data-color="' + color + '"></canvas><div style="color : ' + color + ';" class="caption overlay"><span>' + progressBar.find( '.sr-only' ).text() + '</span><span class="stretch-height collapse-width"></span></div>' );

				$( this ).parent().append( html );

				$( this ).remove();
			}
			else {
				if( progressBar.attr( 'data-color' ) )
					progressBar.css( 'background-color', progressBar.attr( 'data-color' ) );
				$( this ).parent().prepend( '<div class="caption text-center" style="color : ' + progressBar.attr( 'data-color' ) + ';">' + $( this ).find( '.sr-only' ).text() + '</div>' );
			}
		} );
		//--//
		//	Topper
		$( '.topper .menu-toggle' ).click( function() {
			var topperMenu = $( '.topper .menu' );
			topperMenu.toggleClass( 'open' );
			if( topperMenu.hasClass( 'open' ) )
				topperMenu.slideDown( throttle );
			else
				topperMenu.slideUp( throttle );
		} );
		$( document ).click( function( event ) {
			if( ! $( event.target ).hasClass( 'selected' ) && $( event.target ).closest( '.language-menu' ).length === 0 ) {
				var languageMenu = $( '.topper .language-menu' );
				languageMenu.find( '.nav' ).removeClass( 'open' );
				languageMenu.find( 'li:not( .selected )' ).slideUp( throttle );
			}
			if( ! $( event.target ).hasClass( 'menu-toggle' ) && $( event.target ).closest( '.menu' ).length === 0 ) {
				var topperMenu = $( '.topper .menu' );
				if( topperMenu.hasClass( 'menu-dropdown' ) && topperMenu.hasClass( 'open' ) ) {
					topperMenu.removeClass( 'open' );
					topperMenu.slideUp( throttle );
				}
			}
			if(
				! $( event.target ).hasClass( 'dropdown' ) &&
				! $( event.target ).hasClass( 'dropdown-slide' ) &&
				$( event.target ).closest( '.menu-dropdown' ).length === 0
			) {
				var dropdownMenu = $( '.dropdown-slide' );
				if( ! dropdownMenu.closest( '.menu' ).hasClass( 'menu-dropdown' ) && dropdownMenu.parent().hasClass( 'open' ) ) {
					dropdownMenu.parent().removeClass( 'open' );
					dropdownMenu.slideUp( throttle );
				}
			}
		} );
		$( '.topper .nav .dropdown' ).click( function( event ) {
			event.preventDefault();
			$( this ).parent().toggleClass( 'open' ).siblings().removeClass( 'open' );
			if( $( this ).closest( '.menu' ).hasClass( 'menu-dropdown' ) ) {
				var dropdownSlide = $( this ).siblings( '.dropdown-slide' );
				var right = dropdownSlide.outerWidth();
				if( $( this ).parent().hasClass( 'open' ) ) {
					dropdownSlide.css( 'margin-right', ( - right ) + 'px' ).show();
					$( this ).closest( '.nav' ).stop().animate( {
						'margin-right' : right + 'px'
					}, {
						duration : throttle,
						step : function( now ) {
							dropdownSlide.css( 'margin-right', ( - right + now ) + 'px' );
						}
					} );
				}
				else
					$( this ).closest( '.nav' ).stop().animate( {
						'margin-right' : 0
					}, {
						duration : throttle,
						step : function( now ) {
							dropdownSlide.css( 'margin-right', ( - right + now ) + 'px' );
						}
					} );
			}
			else {
				if( $( this ).parent().hasClass( 'open' ) )
					$( this ).siblings( '.dropdown-slide' ).slideDown( throttle );
				else
					$( this ).siblings( '.dropdown-slide' ).slideUp( throttle );
			}
		} );
		//	Modified bootstrap scrollspy
		$( window ).ascrollspy( { target : '#navigation' } );
		//--//
		var topperMenu = $( '.topper .menu' );
		var bodyOverlay = $( 'body > div.overlay' ).children().first();
		if( navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod' )
			bodyOverlay.css( 'position', 'device-fixed' );
		
		function windowResize( windowWidth ) {
			//	Resize language header menu
			$( '.topper .language-menu' ).width( $( '.topper .language-menu li' ).first().outerWidth() );
			//	Realign navigation header menu
			topperMenu.removeClass( 'menu-dropdown clip open' );
			function restoreTopperMenuState() {
				topperMenu.find( '.nav' ).css( 'margin-right', 0 ).children().removeClass( 'open' ).find( '.dropdown-slide' ).css( 'margin-right', '' ).hide();
			}
			if(	$( window ).width() < 768 || topperMenu.outerWidth() > $( window ).width() - $( '.logo' ).outerWidth() ) {
				$( '.topper .menu-toggle' ).show();
				$( '.topper .language-menu' ).addClass( 'button-enabled' );
				topperMenu.addClass( 'menu-dropdown clip' ).hide();
				restoreTopperMenuState();
			}
			else {
				$( '.topper .menu-toggle' ).hide();
				$( '.topper .language-menu' ).removeClass( 'button-enabled' );
				topperMenu.removeClass( 'menu-dropdown clip' ).show();
				restoreTopperMenuState();
			}
			if( topperMenu.hasClass( 'menu-dropdown' ) )
				topperMenu.width( $( window ).width() );
			else
				topperMenu.width( 'auto' );
			//--//
			//	Realign search box
			if( ( layout && ( windowWidth > 767 ) ) || ( !layout && ( windowWidth < 768 ) ) ) {
				$( '.blog > .row' ).prepend( $( '.blog > .row' ).children().last() );
				var col = $( '.blog > .row' ).children().first();
				if( col.find( '.widget_search' ).length !== 0 )
					col.find( '.widget_search' ).prepend( $( '.blog > .row .search' ) );
				else
					col.prepend( $( '.blog > .row .search' ) );
				layout = !layout;
			}
			//--//
			if( windowWidth < 992 && $( '.works .controls' ).index() !== 0 )
				$( '.works .controls' ).each( function() {
					$( this ).parent().prepend( $( this ) );
				} );
			if( windowWidth > 991 && $( '.works .controls' ).index() === 0 )
				$( '.works .controls' ).each( function() {
					$( this ).parent().append( $( this ) );
				} );
			drawSkills( true );
			var videoContainer = $( '.video-container' );
			var videoContainerSiblings = videoContainer.siblings();
			if( videoContainerSiblings.length > 0 )
				$( '.video-container' ).height( Math.max( Math.floor( videoContainer.outerWidth() * 0.5625 ), Math.min( videoContainerSiblings.first().outerHeight(), videoContainer.outerWidth() ) ) );
			//--//
			$( '.pricing .caption, .slider .circle' ).unbind( 'mouseover' ).unbind( 'mouseout' )
				.unbind( 'mouseenter' ).unbind( 'mouseleave' );
			if( windowWidth > 1024 ) {
				$( '.pricing .package' ).hover( function() {
					var color = $( this ).attr( 'data-color' );
					$( this ).children( '.caption' ).css( {
						'color' : '',
						'background-color' : color
					} );
					$( this ).find( 'fa' ).css( {
						'color' : color,
						'background-color' : ''
					} );
				}, function() {
					pricingPackagesUnhover( $( this ) );
				} ).trigger( 'mouseout' ).trigger( 'mouseleave' );
				$( '.slider .circle' ).hover( function() {
					$( this ).toggleClass( 'hover' );
				} );
			}
			//	Caroufredsel
			if( $( '.works .grid' ).hasClass( 'collapse-height' ) ) {
				$( '.works .caroufredsel' ).first().trigger( 'updateSizes' );
				$( '.works .caroufredsel .caroufredsel' ).first().trigger( 'updateSizes' );
			}
			//caroufredsel( $( '.posts .caroufredsel' ) );
			$( '.posts .caroufredsel' ).trigger( 'updateSizes' );
			//caroufredsel( $( '.stories .caroufredsel' ) );
			$( '.stories .caroufredsel' ).trigger( 'updateSizes' );
			//--//
			$( window ).ascrollspy( 'refresh' );
		}
		var rowWidth;
		var lastWidth = 0;
		var windowWidth = $( window ).width();
		var windowHeight = $( window ).height();
		var windowRatio = windowWidth / windowHeight;
		$( window ).resize( function() {
			windowWidth = $( window ).width();
			windowHeight = $( window ).height();
			windowRatio = windowWidth / windowHeight;
			if( bodyOverlay.length !== 0 ) {
				bodyOverlay.css( {
					'width' : windowWidth + 'px',
					'height' : 'auto'
				} );
				if( bodyOverlay.height() < windowHeight ) {
					bodyOverlay.css( {
						'width' : 'auto',
						'height' : windowHeight + 'px'
					} );
					delay( function() {
						var bodyOverlayWidth = bodyOverlay.width();
						bodyOverlay.css( 'left', ( ( bodyOverlayWidth > windowWidth ) ? - ( bodyOverlayWidth - windowWidth ) / 2 : 0 ) + 'px' );
					}, 'bodyOverlay', throttle );
				}
			}

			$('.entry').find('img').addClass('img-responsive');
			//	Prevent resize flicker on tablet browsers
			if( lastWidth != windowWidth ) {
				lastWidth = windowWidth;
				if( lastWidth > 1024 )
					delay( function() {
						windowResize( lastWidth );
					}, 'windowResize' );
				else
					windowResize( lastWidth );
				if( $( '.posts .controls' ).length > 0 )
					$( '.posts .controls' ).css( {
						'top' : $( '.posts .text-right-md' ).first().position().top + 'px'
					} );
				//	Searchbox focus/blur
				rowWidth = Math.min( windowWidth - 30, ( ( windowWidth > 1072 ) ? 975 : 873 ) );
				//--//
			}
		} );
		$( '.language-menu li' ).click( function( event ) {
			if( $( this ).hasClass( 'selected' ) ) {
				event.preventDefault();
				var parent = $( this ).parent();
				if( parent.hasClass( 'open' ) ) {
					$( this ).siblings().slideUp( throttle );
					parent.removeClass( 'open' );
				}
				else {
					$( this ).siblings().slideDown( throttle );
					parent.addClass( 'open' );
				}
			}
		} );
		//	Smooth scroll
		$( '.scroll' ).click( function( event ) {
			var offset = ( $( this.hash ).hasClass( 'theme-color' ) ) ? 0 : 150;
			if( $( this.hash ).offset() ) {
				var scrollTo = $( this.hash ).offset().top - Math.max( $( '.topper' ).outerHeight() , 0 ) + offset;
				scrollTo = ( scrollTo <= offset ) ? 0 : scrollTo;
				$( 'html, body' ).stop().animate( {
					scrollTop : scrollTo
				}, throttle );
			}
			var href = this.href.replace( this.hash, '' );
			if( href === '' || href == location.protocol + '//' + location.hostname + ( location.port ? ':' + location.port : '' ) + location.pathname + ( location.search ? location.search : '' ) )
				event.preventDefault();
		} );
		//--//
		if( $( window ).width() <= 1024  ) {
			var fallback = bodyOverlay.find( '.fallback' );
			if( fallback.length > 0 ) {
				bodyOverlay.parent().append( fallback );
				bodyOverlay.remove();
			}
			bodyOverlay = $( 'body > div.overlay' ).first().children().first();
			$( '.slider' ).find( 'video' ).each( function() {
				var fallback = $( this ).find( '.fallback' );
				if( $( this ).find( '.fallback' ).length > 0 ) {
					$( this ).parent().append( fallback.addClass( 'slide' ) );
					$( this ).remove();
				}
			} );
		}
		//	Slider
		$( '.slider > div#slider' ).Aslider();
		//--//
		//	Accordion
		$( '.accordion.open' ).each( function() {
			$( this ).find( '.contents' ).toggle();
		} );
		$( '.accordion .caption-wrapper' ).click( function() {
			$( this ).parent().toggleClass( 'open' ).find( '.contents' ).toggle( throttle );
		} );
		//--//
		//	Enable lightbox for some images
		$( '.blueimp' ).each( function() {
			$( this ).get( 0 ).onclick = function( event ) {
				event = event || window.event;
				var target = event.target || event.srcElement;
				var link = $( target.src ? target.parentNode : target ).parent().get( 0 );
				var options = {
					index : link,
					event : event
				};
				var links = this.getElementsByTagName( 'a' );
				var carousel = $( target ).closest( '.caroufredsel' );
				if( carousel.length === 0 || ( carousel.length !== 0 && parseInt( carousel.css( 'left' ), 10 ) === 0 ) ) {
					blueimp.Gallery( links, options );
					$( 'body' ).css( 'overflow', '' );
				}
				else
					return false;
			};
		} );
		//--//
		caroufredsel();
		//	Force realign events
		$( window ).trigger( 'resize' );
		//--//
		//	Search box focus & blur
		var searchWidth = $( '.blog .search' ).innerWidth();
		var searchFormControlFocus = false;
		$( '.blog .search .form-control' ).bind( 'focus', function() {
			searchFormControlFocus = true;
			$( this ).parent().addClass( 'active' );
			$( '.blog .search' ).width( rowWidth );
			$( this ).width( rowWidth - $( '.blog .search.active button' ).width() - 76 );
		} );
		$( '.blog .search .overlay' ).click( function() {
			$( '.blog .search' ).width( searchWidth );
			$( '.blog .search .form-control' ).removeAttr( 'style' );
			$( '.blog .search' ).removeClass( 'active' );
		} );
		//--//
		//	Pricing styles
		var pricingPackages = $( '.pricing .package' );
		function pricingPackagesUnhover( element ) {
			var color = element.attr( 'data-color' );
			element.children( '.caption' ).css( {
				'color' : color,
				'background-color' : ''
			} );
			element.find( 'fa' ).css( {
				'color' : '',
				'background-color' : color
			} );
		}
		pricingPackages.each( function() {
			$( this ).find( '.caption' ).addClass( 'highlight' );
			pricingPackagesUnhover( $( this ) );
		} );
		$( '.pricing .features' ).each( function() {
			$( this ).find( 'li:odd' ).addClass( 'highlight' );
		} );
		//--//
		//	Facts styling
		$( '.facts .fa' ).each( function() {
			$( this ).css( 'color', $( this ).attr( 'data-color' ) );
		} );
		var factsValues = [];
		$( '.facts .value' ).each( function( index ) {
			factsValues[ index ] = parseInt( $( this ).text(), 10 );
		} );
		var max = Math.max.apply( 0, factsValues );
		var increment = Math.max( 1, Math.round( max / 50 ) );
		var counter = 0;
		//--//
		//	Blur
		var blurCount = 0;
		$( '*[blur], .blur' ).each( function() {
			if( ! $( this ).hasClass( 'aparallax' ) ) {
				var canvas = document.createElement( 'canvas' );
				canvas = typeof canvas.getContext === 'function';
				if( canvas && typeof window.stackBlurCanvasRGB === 'function' ) {
					if( $( this ).parent().get( 0 ).tagName !== 'BODY' ) {
						var backgroundImage = $( this ).closest( 'article' ).css( 'backgroundImage' ).replace( 'url(', '' ).replace( ')', '' );
						blurCount++;
						var blurRadius = ( $( this ).attr( 'blur' ) ) ? $( this ).attr( 'blur' ) : 10;
						backgroundImage = $( '<img src=' + backgroundImage + ' />' );
						var parent = $( this ).parent();
						$( 'body' ).append( backgroundImage );
						$( this ).remove();
						var counter = 0;
						backgroundImage.load( function() {
							var backgroundImageWidth = backgroundImage.width();
							var backgroundImageHeight = backgroundImage.height();
							canvas = $( '<canvas width="' + backgroundImageWidth + '" height="' + backgroundImageHeight + '"></canvas>' );
							canvas.addClass( 'overlay' );
							canvas.css( {
								'width' : 'auto',
								'height' : 'auto',
								'min-width' : '100%',
								'min-height' : '100%'
							} );
							$( window ).resize( function() {
								delay( function() {
									canvas.css( 'left', ( parent.width() - canvas.width() ) / 2 );
								}, 'blur' + blurCount++ );
							} );
							parent.prepend( canvas );
							var canvasContext = canvas.get( 0 ).getContext( '2d' );
							canvasContext.clearRect( 0, 0, backgroundImageWidth, backgroundImageHeight );
							canvasContext.drawImage( backgroundImage.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight );
							window.stackBlurCanvasRGB( canvas.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight, blurRadius );
							backgroundImage.remove();
							if( ++counter >= blurCount )
								$( window ).trigger( 'resize' );
							if( $( window ).width() <= 1024 || $( 'body' ).hasClass( 'disable-animation' ) )
								canvas.show();
						} );
					}
					else {
						var blurRadius = ( $( this ).attr( 'blur' ) ) ? $( this ).attr( 'blur' ) : 10;
						var backgroundImage = $( this ).children( 'img' );
						backgroundImage.css( {
							'width' : 'auto',
							'height' : 'auto'
						} );
						var backgroundImageWidth = backgroundImage.width();
						var backgroundImageHeight = backgroundImage.height();
						canvas = $( '<canvas width="' + backgroundImageWidth + '" height="' + backgroundImageHeight + '"></canvas>' );
						$( this ).prepend( canvas );
						var canvasContext = canvas.get( 0 ).getContext( '2d' );
						canvasContext.clearRect( 0, 0, backgroundImageWidth, backgroundImageHeight );
						canvasContext.drawImage( backgroundImage.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight );
						window.stackBlurCanvasRGB( canvas.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight, blurRadius );
						backgroundImage.remove();
						bodyOverlay = $( 'body > div.overlay' ).children().first();
					}
				}
			}
		} );
		//--//
		//	Animation
		$( window ).scroll( function() {
			$( 'body' ).trigger( 'click' );
			//	Search box blur
			if( searchFormControlFocus )
				searchFormControlFocus = false;
			else
				$( '.aside.search .form-control' ).blur();
				//$( '.aside.search .overlay' ).trigger( 'click' );
			//--//
			var scrollVisible = $( window ).scrollTop() + $( window ).height();
			var sleep = throttle;
			if( canvasSupported )
				$( '.progress-circle' ).each( function() {
					if( !$( this ).hasClass( 'animate-once' ) )
						if( scrollVisible > $( this ).offset().top )
							drawSkills();
				} );
			else
				$( '.progress-bar' ).each( function() {
					if( !$( this ).hasClass( 'animate-once' ) )
						if( scrollVisible > $( this ).offset().top + $( this ).height() ) {
							$( this ).addClass( 'animate-once' );
							$( this ).stop().animate( {
								'width' : Math.round( $( this ).attr( 'aria-valuenow' ) / ( $( this ).attr( 'aria-valuemax' ) - $( this ).attr( 'aria-valuemin' ) ) * 100 ) + '%'
							}, throttle * 2 + sleep );
							sleep += throttle * 2;
						}
				} );
			if( bodyOverlay.length !== 0 && bodyOverlay.parent().css( 'position' ) !== 'fixed' && bodyOverlay.parent().css( 'position' ) !== 'device-fixed' )
				bodyOverlay.parent().css( 'top', $( window ).scrollTop() );
		} );
		if( $( window ).width() > 1024 && ! $( 'body' ).hasClass( 'disable-animation' ) )
			$( window ).scroll( function() {
				var scrollVisible = $( window ).scrollTop() + $( window ).height();
				$( '.zoomIn' ).each( function () {
					if( scrollVisible > $( this ).offset().top && !$( this ).hasClass( 'animate-once' ) )
						$( this ).addClass( 'animate-once' );
				} );
				//	Services fade-in-blur tweak
				$( '.services .overlay' ).each( function() {
					if( ! $( this ).hasClass( 'animate-once' ) )
						if( scrollVisible > $( this ).parent().offset().top + $( this ).height() / 2 ) {
							$( this ).addClass( 'animate-once' );
							$( this ).fadeIn( throttle * 4, function() {
								$( this ).closest( 'article' ).removeClass( 'aparallax' );
								var sleep = throttle;
								$( this ).parent().find( '.row > div' ).each( function() {
									$( this ).stop().animate( {
										'left' : '0'
									}, throttle * 2 + sleep );
									sleep += throttle * 2;
								} );
							} );
						}
				} );
				//--//
				//	Facts
				if( $( '.facts' ).length !== 0 && ! $( '.facts' ).hasClass( 'animate-once' ) )
					if( scrollVisible > $( '.facts' ).offset().top + $( '.facts' ).height() / 4 ) {
						$( '.facts' ).addClass( 'animate-once' );
						var factsInterval = setInterval( function() {
							$( '.facts .value' ).each( function( index ) {
								$( this ).text( Math.min( counter, factsValues[ index ] ) );
								if( counter > max )
									clearInterval( factsInterval );
							} );
							counter += increment;
						}, throttle / 2 );
					}
				//--//
			} );
		else {
			$( '.services' ).removeClass( 'aparallax' );
			$( '.services .overlay' ).show();
			$( '.services' ).css( {
				'background-image' : 'none'
			} );
			$( '.services .row > div' ).css( {
				'left' : '0'
			} );
		}
		//--//
		//	Form styling
		$( 'input[type=radio] + label, input[type=checkbox] + label' ).each( function() {
			var disabled = $( '#' + $( this ).attr( 'for' ) ).attr( 'disabled' );
			disabled = ( typeof disabled !== 'undefined' && disabled !== false ) ? ' disabled' : '';
			$( this ).add( '#' + $( this ).attr( 'for' ) ).wrapAll( '<span class="' + $( '#' + $( this ).attr( 'for' ) ).attr( 'type' ) + disabled + '"></span>' );
		} );
		$( 'select' ).customSelect();
		$( '.grid.reverse' ).each( function() {
			var length = $( this ).children().length;
			$( this ).children().each( function() {
				$( this ).css( 'zIndex', length-- );
			} );
		} );
		//--//
		var caroufredselContainer = $( '.works .caroufredsel' ).first();
		var gridItems = $( '.works .grid > li' );
		if( caroufredselContainer.children().length == gridItems.length ) {
			gridItems.each( function() {
				caroufredselContainer.children().eq( $( this ).index() ).attr( 'data-index', $( this ).index() );
			} );
			var gridFilterItems = $( '.grid-filter a' );
			if( gridFilterItems.length !== 0 )
				gridFilterItems.click( function( event ) {
					event.preventDefault();
					var itemsVisible = ! gridItems.parent().hasClass( 'collapse-height' );
					var filter = $( this ).attr( 'data-filter' );
					function filterGrid( width, height ) {
						var gridItemsFiltered = gridItems.filter( filter );
						function finalize() {
							gridItemsFiltered.each( function() {
								caroufredselContainer.children( '*[data-index=' + $( this ).index() + ']' ).removeClass( 'disabled' );
							} );
							caroufredselContainer.trigger( 'slideTo', caroufredselContainer.children( ':not(.disabled)' ).first() );
						}
						if( itemsVisible ) {
							if( $( window ).width() > 1024 ) {
								var counter = 0;
								gridItemsFiltered.each( function() {
									$( this ).stop().css( {
										'padding-top' : height / 2,
										'padding-right' : width / 2,
										'padding-bottom' : height / 2,
										'padding-left' : width / 2
									} ).show().animate( {
										'padding-top' : 0,
										'padding-right' : 0,
										'padding-bottom' : 0,
										'padding-left' : 0
									}, throttle, function() {
										if( ++counter == gridItemsFiltered.length )
											finalize();
									} );
								} );
							}
							else {
								gridItemsFiltered.css( 'padding', 0 ).show();
								finalize();
							}
						}
						else {
							gridItemsFiltered.show();
							finalize();
						}
					}
					if( ! $( this ).hasClass( 'active' ) ) {
						$( this ).addClass( 'active' ).siblings().removeClass( 'active' );
						caroufredselContainer.children().addClass( 'disabled' );
						if( itemsVisible ) {
							var width = gridItems.first().outerWidth();
							var height = gridItems.first().outerHeight();
							if( $( window ).width() > 1024 ) {
								var counter = 0;
								gridItems.each( function() {
									$( this ).stop().animate( {
										'padding-top' : height / 2,
										'padding-right' : width / 2,
										'padding-bottom' : height / 2,
										'padding-left' : width / 2
									}, throttle, function() {
										$( this ).css( 'padding', 0 ).hide();
										if( ++counter == gridItems.length )
											filterGrid( width, height );
									} );
								} );
							}
							else {
								gridItems.css( 'padding', 0 ).hide();
								filterGrid( width, height );
							}
						}
						else {
							gridItems.css( 'padding', 0 ).hide();
							filterGrid();
						}
					}
				} );
		}
		function switchToItem( index ) {
			var section = $( '.works section.page-light-dose' );
			if( section.length > 0 ) {
				$( '.works .grid' ).addClass( 'collapse-height' );
				section.removeClass( 'collapse-height' );
				caroufredselContainer.trigger( 'updateSizes' );
				var selected = caroufredselContainer.children( '*[data-index=' + index + ']' );
				selected.find( '.caroufredsel' ).trigger( 'updateSizes' );
				caroufredselContainer.trigger( 'slideTo', selected );
				$( 'html, body' ).stop().animate( {
					scrollTop : ( ( $( '.works' ).hasClass( 'scroll-to-filter' ) ) ? $( '.works .grid-filter' ).offset().top : section.offset().top ) - $( 'body > header' ).height()
				}, throttle );
			}
		}
		$( '.works .grid > li' ).click( function() {
			switchToItem( $( this ).index() );
		} );
		$( '.works section.page-light-dose .controls .close' ).click( function() {
			$( '.works .grid' ).removeClass( 'collapse-height' );
			$( '.works section.page-light-dose' ).addClass( 'collapse-height' );
			$( 'html, body' ).stop().animate( {
				scrollTop : ( ( $( '.works' ).hasClass( 'scroll-to-filter' ) ) ? $( '.works .grid-filter' ).offset().top : $( '.works .grid' ).offset().top ) - $( 'body > header' ).height()
			}, throttle );
		} );
		//--//
		$( '.video iframe' ).addClass( 'stretch-both' ).attr( 'frameborder', 0 ).attr( 'webkitallowfullscreen', 'webkitallowfullscreen' ).attr( 'mozallowfullscreen', 'mozallowfullscreen' ).attr( 'allowfullscreen', 'allowfullscreen' );
		/*
		if( navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod' ) {
			$( 'textarea, input:not([type=submit]), input:not([type=reset]), input:not([type=button]), input:not([type=button])' ).focus( function() {
				$( '.topper' ).hide();
			} );
			$( window ).resize( function() {
				$( '.topper' ).show();
			} );
		}
		*/
	} );
} )( jQuery );
//	Remove spinner
$( window ).load( function() {
	'use strict';
	if( $( '#spin' ).length !== 0 ) {
		spinner.stop();
		delay( function() {
			if( $( window ).width() > 1024 )
				$( '#spin' ).fadeOut( function() {
					$( '#spin' ).remove();
				} );
			else
				$( '#spin' ).remove();
		}, 'spin', throttle );
	}
} );
//--//
/*	*/
/***********************************************************************************
 * jQuery Adaptive parralax plugin v.1.4                                           *
 * Created by wfoojjaec                                                            *
 * For any copyright issues one should contact author directly via gmail or skype. *
 ***********************************************************************************/
( function( jQuery ) {
	'use strict';
	jQuery.fn.Aparallax = function() {
		if( jQuery( this ).length > 0 )
			jQuery( this ).each( function() {
				var jQuerythis = jQuery( this );

				var windowWidth = jQuery( window ).width();
				var windowHeight = jQuery( window ).height();

				function update( Aparallax ) {
					var windowScrollTop = $( window ).scrollTop();
					var elementOffsetTop = jQuerythis.offset().top;
					var elementWidth = jQuerythis.outerWidth();
					var elementHeight = jQuerythis.outerHeight();
					var ratio = elementWidth / backgroundImageWidth;
					if( windowScrollTop < elementOffsetTop + elementHeight && windowScrollTop + windowHeight > elementOffsetTop && jQuerythis.hasClass( 'aparallax' ) ) {
						var offset;
						if( Aparallax === true || Aparallax === undefined ) {
							if( windowScrollTop > elementOffsetTop + elementHeight || windowScrollTop + windowHeight < elementOffsetTop || backgroundImageHeight * ratio <= elementHeight )
								offset = 0;
							else {
								var after = Math.max( windowScrollTop + windowHeight - elementOffsetTop - elementHeight, 0 ) / windowHeight;
								var before = Math.min( ( windowScrollTop + windowHeight - elementOffsetTop ) / windowHeight, 1 );
								offset = - Math.floor( ( after + before ) / 2 * ( backgroundImageHeight * ratio - elementHeight ) );
							}
						}
						else
							offset = 0;
						var width = backgroundImageWidth * ratio;
						backgroundImage.css( {
							'top' : offset + 'px',
							'left' : ( - width / 2 ) + 'px',
							'width' : width + 'px',
							'height' : backgroundImageHeight * ratio + 'px'
						} );
					}
				}

				var backgroundImage = jQuerythis.css( 'backgroundImage' ).replace( 'url(', '' ).replace( ')', '' );
				if( backgroundImage !== 'none' ) {

					$( window ).resize( function() {
						windowWidth = jQuery( window ).width();
						windowHeight = jQuery( window ).height();
						update( Aparallax );
					} );

					backgroundImage = $( '<img src=' + backgroundImage + ' />' );
					jQuerythis.css( 'backgroundImage', 'none' );
					jQuerythis.prepend( backgroundImage );
					var backgroundImageWidth = 0;
					var backgroundImageHeight = 0;
					backgroundImage.load( function() {
						backgroundImageWidth = backgroundImage.width();
						backgroundImageHeight = backgroundImage.height();
						if( typeof window.stackBlurCanvasRGB === 'function' && ( jQuerythis.hasClass( 'blur' ) || jQuerythis.attr( 'blur' ) ) ) {
							var canvas = document.createElement( 'canvas' );
							canvas = typeof canvas.getContext === 'function';
							if( canvas ) {
								var blurRadius = ( jQuerythis.attr( 'blur' ) ) ? jQuerythis.attr( 'blur' ) : 10;
								canvas = $( '<canvas width="' + backgroundImageWidth + '" height="' + backgroundImageHeight + '"></canvas>' );
								jQuerythis.prepend( canvas );
								var canvasContext = canvas.get( 0 ).getContext( '2d' );
								canvasContext.clearRect( 0, 0, backgroundImageWidth, backgroundImageHeight );
								canvasContext.drawImage( backgroundImage.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight );
								window.stackBlurCanvasRGB( canvas.get( 0 ), 0, 0, backgroundImageWidth, backgroundImageHeight, blurRadius );
								backgroundImage.remove();
								backgroundImage = canvas;
							}
						}
						backgroundImage.css( {
							'position' : 'absolute',
							'marginLeft' : '50%'
						} );
					} );

					$( window ).scroll( function() {
						update( Aparallax );
					} );

					$( window ).load( function() {
						update( Aparallax );
					} );

					update();
				}
			} );
	};
} )( jQuery );
/**/
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Hel Ohm Um Lo Cham                                                *
 * Created by wfoojjaec                                              *
 * For any copyright issues one should contact author directly       *
 *  via gmail or skype.                                              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
var delay = ( function() {
	'use strict';
	var timeout = { };
		//	callback	- function()
		//	id			- timeout identifier
		//	time		- call suppression delay
	return function( callback, id, time ) {
		if( 'function' === typeof callback ) {
			id = ( id ) ? id : 'delay';
			time = ( time !== null ) ? time : 100;
			clearTimeout( timeout[ id ] );
			timeout[ id ] = setTimeout( callback, time );
		}
	};
} )();
/**
	Aslider jQuery plugin
	by default_string@i.ua
	Uses <img ... /> tags in a passed container
*/
( function( $, f ) {
	'use strict';
	//  If there's no jQuery, Aslider can't work, so kill the operation.
	if( ! $ ) return f;
	var Aslider = function() {
		//	Test for canvas support
		var canvas = document.createElement( 'canvas' );
		this.fallback = !canvas.getContext;
		//--//
		/**	Properties
		*/
		this.interval = f;
		this.options = {
			//	Delay between each fade
			delay : throttle * 20,
			//	Fading speed
			speed : throttle * 4
		};
		//  Set up our elements
		this.counter = 0;
		this.element = f;
		this.items = f;
		//	Index of current image in collection, collection indexes are based on template markup
		this.current = 0;
		this.last = 0;
		this.color = false;
		this.moving = false;
		//  Create a deep clone for methods where context changes
		var _ = this;
		/*	*/
		/**	Methods
		*/
		this.init = function( element, options ) {
			this.element = element;
			this.items = element.children( '.slide' );
			this.current = this.items.length - 1;
			this.last = this.items.length - 1;
			//  Check whether we're passing any options
			this.options = $.extend( this.options, options );
			//  Create the HTML
			var html = '<div class="slider-controls">';
			if( this.items.length > 1 ) {
				$.each( this.items, function( index ) {
					if( _.fallback )
						html += '<div class="circle' + ( index < 1 ? ' active' : '' ) + '"></div>';
					else
						html += '<canvas width="48" height="48" class="circle' + ( index < 1 ? ' active' : '' ) + '">' + ( index + 1 ) + '</canvas>';
				} );
				html += '</div>';
				//  Add it to the Aslider
				this.element.append( html ).find( '.circle' ).click( function() {
					_.move( $( this ).index() );
				} ).hover( function() {
					if( !_.fallback )
						_.dots();
				} ).css( {
					'width' : '24px',
					'height' : '24px'
				} );
			}
			this.element.swipe( {
				swipeLeft : function() {
					_.move( _.current - 1 );
				},
				swipeRight : function() {
					_.move( _.current + 1 );
				},
				threshold : 50
			} );
			//  Set up
			this.setup();
			return this;
		};
		//  Work out what methods need calling
		this.setup = function() {
			//  Set the main element
			this.element.css( {
				'position' : 'relative'
			} );
			if( !this.options.disableControls )
				this.element.addClass( 'enable-controls' );
			//	Set slider items
			this.items.css( {
				'position' : 'absolute',
				'top' : '0',
				'left' : '0',
				'z-index' : '0',
				'display' : 'none'
			} );
			//	Call autoresize
			function asliderResize() {
				var maxHeight = ( $( window ).height() / 3 < $( window ).width() ) ? $( window ).height() : $( window ).width();
				_.element.css( {
					'height' : Math.min( Math.max( 360, maxHeight ) ) + 'px'
				} );
				var elementRatio = _.element.outerWidth() / _.element.outerHeight();
				_.items.each( function() {
					var itemRatio = $( this ).outerWidth() / $( this ).outerHeight();
					if( itemRatio > elementRatio ) {
						$( this ).css( {
							'width' : 'auto',
							'height' : '100%'
						} );
						$( this ).css( {
							'right' : Math.round( ( _.element.outerWidth() - $( this ).outerWidth() ) / 2 ) + 'px',
							'bottom' : '0'
						} );
					}
					else
						if( itemRatio < elementRatio ) {
							$( this ).css( {
								'width' : '100%',
								'height' : 'auto'
							} );
							$( this ).css( {
								'right' : '0',
								'bottom' : Math.round( ( _.element.outerHeight() - $( this ).outerHeight() ) / 2 ) + 'px'
							} );
						}
						else
							$( this ).css( {
								'right' : '0',
								'bottom' : '0',
								'width' : '100%',
								'height' : '100%'
							} );
				} );
			}
			var windowWidth = 0;
			$( window ).resize( function() {
				if( $( window ).width() != windowWidth ) {
					if( windowWidth > 1024 )
						delay( function() {
							asliderResize();
						}, 'Aslider', throttle );
					else
						asliderResize();
				}
			} );
			//	Play
			if( this.options.delay !== f ) {
				this.move( 0 );
				this.move( 1 );
				this.start();
			}
		};

		function setText() {
			if( $( '.data-short' ).html() != _.items.eq( _.current ).attr( 'data-short' ) )
				$( '.data-short' ).fadeOut( _.options.speed / 2, function() {
					$( '.data-short' ).html( _.items.eq( _.current ).attr( 'data-short' ) );
					$( '.data-short' ).fadeIn( _.options.speed / 2 );
				} );
			if( $( '.data-full' ).html() != _.items.eq( _.current ).attr( 'data-full' ) )
				$( '.data-full' ).fadeOut( _.options.speed / 2, function() {
					$( '.data-full' ).html( _.items.eq( _.current ).attr( 'data-full' ) );
					$( '.data-full' ).fadeIn( _.options.speed / 2 );
				} );
		}
		//  Move Aslider to a slide index
		this.move = function( index ) {
			if( this.current != index && !this.moving ) {
				this.moving = true;
				this.counter = 0;
				if( !this.items.eq( index ).length  )
					index = 0;
				if( index < 0 )
					index = ( this.items.length - 1 );
				if( this.items.eq( index ).get( 0 ).tagName !== 'DIV' ) {
					this.items.eq( index ).css( { 'z-index' : 1 } );
					this.items.eq( _.last ).css( { 'z-index' : 0 } );

					this.items.eq( index ).fadeIn( _.options.speed, function() {
						//if( _.items.eq( _.current ).get( 0 ).tagName !== 'DIV' ) {
							if( _.last !== index )
								_.items.eq( _.last ).hide();
							//_.items.eq( _.current ).hide();
						//};
						_.current = index;
						_.last = index;
						setText();
						_.moving = false;
					} );
				}
				else {
					_.current = index;
					setText();
					_.moving = false;
				}
				if( !this.element.is( ':animated' ) ) {
					//  Handle those pesky dots
					this.element.find( '.circle:eq(' + index + ')' ).addClass( 'active' ).siblings().removeClass( 'active' );
					if( !_.fallback )
						_.dots();
				}
			}
		};
		//  Autoplay functionality
		this.start = function() {
			_.interval = setInterval( function() {
				if( _.counter >= _.options.delay ) {
					_.move( _.current + 1 );
					_.counter = 0;
				}
				else
					_.counter += throttle / 2;
				_.dots();
			}, throttle / 2 );
		};
		//  Stop autoplay
		this.stop = function() {
			_.interval = clearInterval( _.interval );
			return _;
		};
		this.dots = function() {
			this.element.find( '.circle' ).each( function( undefined, element ) {
				var context = element.getContext( '2d' );
				var color = _.element.parent().hasClass( 'theme-color' );
				context.clearRect( 0, 0, 48, 48 );
				context.beginPath();
				context.lineWidth = 3.5;
				if( $( this ).hasClass( 'hover' ) )
					if( color )
						context.strokeStyle = '#FFF';
					else
						context.strokeStyle = '#000';
				else {
					if( color )
						context.strokeStyle = 'rgba( 255, 255, 255, 0.25 )';
					else
						context.strokeStyle = 'rgba( 0, 0, 0, 0.25 )';
				}
				context.arc( 24, 24, 21.5, 0, 2 * Math.PI, false );
				context.stroke();
				if( $( this ).hasClass( 'active' ) ) {
					context.beginPath();
					context.lineWidth = 3.5;
					if( color )
						context.strokeStyle = '#FFF';
					else
						context.strokeStyle = '#000';
					context.arc( 24, 24, 21.5, - Math.PI / 2, _.counter / _.options.delay * 2 * Math.PI - Math.PI / 2, false );
					context.stroke();
				}
			} );
		};
		/*	*/
	};
	//  Create a jQuery plugin
	$.fn.Aslider = function( o ) {
		var len = this.length;
		//  Enable multiple-Aslider support
		return this.each( function( index ) {
			//  Cache a copy of $(this)
			var me = $( this );
			var instance = ( new Aslider() ).init( me, o );
			//  Invoke Aslider instance
			me.data( 'Aslider' + ( len > 1 ? '-' + ( index + 1 ) : '' ), instance );
		} );
	};
	/*	*/
} )( window.jQuery, false );
/*	*/

/* ========================================================================
 * This is a MODIFIED version of scrollspy script with a custom hash fix
 * ========================================================================
 * Copyleft 2013
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */

( function( $ ) {
	'use strict';

	// SCROLLSPY CLASS DEFINITION
	// ==========================

	function AScrollSpy(element, options) {
		var href;
		var process = $.proxy( this.process, this );

		this.$element = $( element ).is( 'body' ) ? $( window ) : $( element );
		this.$body = $( 'body' );
		this.$scrollElement = this.$element.on( 'scroll.bs.scroll-spy.data-api', process );
		this.options = $.extend( {}, AScrollSpy.DEFAULTS, options );
		this.selector = ( this.options.target || ( ( href = $( element ).attr( 'href' ) ) && href.replace( /.*(?=#[^\s]+$)/, '' ) ) || '' ) + ' .nav li > a';
		this.offsets = $( [] );
		this.targets = $( [] );
		this.activeTarget = null;

		this.refresh();
		this.process();
	}

	AScrollSpy.DEFAULTS = {
		offset : 10
	};

	AScrollSpy.prototype.refresh = function () {
		var offsetMethod = this.$element[ 0 ] == window ? 'offset' : 'position';
		this.offsets = $( [] );
		this.targets = $( [] );
		var self = this;
		this.$body
			.find( this.selector )
			.map( function () {
				var $el = $( this );
				var href = $el.data( 'target' ) || $el.attr( 'href' );
				//	MODIFIED
				var ahref = '#' + href.split( '#' )[ 1 ];
				//var $href = /^#\w/.test(href) && $(href)
				var $href = /^#\w/.test( ahref ) && $( ahref );
				//--//
				return ( $href && $href.length && [ [ $href[ offsetMethod ]().top + ( ! $.isWindow( self.$scrollElement.get( 0 ) ) && self.$scrollElement.scrollTop() ), href ] ] ) || null;
			} )
		.sort( function( a, b ) {
			return a[ 0 ] - b[ 0 ];
		} )
		.each( function () {
			self.offsets.push( this[ 0 ] );
			self.targets.push( this[ 1 ] );
		} );
	};

	AScrollSpy.prototype.process = function () {
		//var scrollTop = this.$scrollElement.scrollTop() + this.options.offset;
    var scrollTop = this.$scrollElement.scrollTop() + this.options.offset + $( '.topper' ).outerHeight();
    //
		var scrollHeight = this.$scrollElement[ 0 ].scrollHeight || this.$body[ 0 ].scrollHeight;
		var maxScroll = scrollHeight - this.$scrollElement.height();
		var offsets = this.offsets;
		var targets = this.targets;
		var activeTarget = this.activeTarget;
		var i;

		if( scrollTop >= maxScroll ) {
			return activeTarget != ( i = targets.last()[ 0 ] ) && this.activate( i );
		}

		var jsHint;
		for( i = offsets.length; i--; )
			jsHint = activeTarget != targets[ i ] && scrollTop >= offsets[ i ] && ( ! offsets[ i + 1 ] || scrollTop <= offsets[ i + 1 ] ) && this.activate( targets[ i ] );
	};

	AScrollSpy.prototype.activate = function( target ) {
		this.activeTarget = target;

		$( this.selector )
			.parents( '.active' )
			.removeClass( 'active' );

		var selector = this.selector + '[data-target="' + target + '"],' + this.selector + '[href="' + target + '"]';

		var active = $( selector )
			.parents( 'li' )
			.addClass( 'active' );

		if( active.parent( '.dropdown-menu' ).length )  {
			active = active
				.closest('li.dropdown')
				.addClass('active');
		}

		active.trigger( 'activate' );
	};


	// SCROLLSPY PLUGIN DEFINITION
	// ===========================

	var old = $.fn.ascrollspy;

	$.fn.ascrollspy = function (option) {
		return this.each( function () {
			var $this = $( this );
			var data = $this.data( 'bs.ascrollspy' );
			var options = typeof option == 'object' && option;

			if( ! data )
				$this.data( 'bs.ascrollspy', ( data = new AScrollSpy( this, options ) ) );
			if( typeof option == 'string' )
				data[ option ]();
		} );
	};

	$.fn.ascrollspy.Constructor = AScrollSpy;


	// SCROLLSPY NO CONFLICT
	// =====================

	$.fn.ascrollspy.noConflict = function () {
		$.fn.ascrollspy = old;
		return this;
	};


	// SCROLLSPY DATA-API
	// ==================

	$( window ).on( 'load', function() {
		$( '[data-spy="scroll"]' ).each( function() {
			var $spy = $( this );
			$spy.ascrollspy( $spy.data() );
		} );
	} );

} )( window.jQuery, false );
