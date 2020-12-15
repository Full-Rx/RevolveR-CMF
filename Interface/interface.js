
 /* 
  * RevolveR Front-end :: main interface
  *
  * v.2.0.0.5
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( !self.run ) {

	const R = new R_CMF_i[ 0 ]( 'R' );

	self.run = true;

	R.abuseObserver = null;

	R.morning = null;

	R.evening = null;

	R.night = null;

}

self.searchAction = null;

R.talkUpdate = () => {

	clearInterval( R.talk );

	let talkArea = R.sel('.revolver-talk');

	let talkAbuse = () => {

		if( R.abuseObserver ) {

			R.detachEvent(R.abuseObserver);

		}

		R.abuseObserver = R.event('.talk-abuse', 'click', function(e) {

			e.preventDefault();

			let abuseId = this.dataset.abuse;

			R.fetch('/talker-s/?abuse='+ abuseId, 'get', 'json', null, function() {

				console.log('RevolveR Talk service abuse applyed for message '+ abuseId);

			});

		});

	};

	let talkPlay = () => {

		R.event('.talk-play', 'click', function(e) {

			let uaudio = new Audio('/public/talk/'+ this.dataset.audio);

			uaudio.play();

		});

	};

	if( talkArea ) {

		console.log('RevolveR Talk service enabled.');

		talkArea[ 0 ].scrollTop = talkArea[ 0 ].offsetHeight + 1000;

		talkAbuse();

		talkPlay();

		R.talk = setInterval(() => {

			R.fetch('/talker-s/', 'post', 'json', null, function() {

				let output = '';

				for( m of this ) {

					output += '<div class="revolver__talk-message">';

					output += '<b>'+ m.name +':</b> <p>'+ m.message;

					if( m.id ) {

						output += '<a class="talk-abuse" data-abuse="'+ m.id +'">[ X ]</a>';

					}

					if( m.audio ) {

						output += '<a data-audio='+ m.audio +' class="talk-play">[ ► ]</a>';

					}

					output +='</p>';
					output += '</div>';

				}

				talkArea[ 0 ].innerHTML = output;

				talkArea[ 0 ].scrollTop = R.sel('.revolver-talk')[ 0 ].offsetHeight + 1000;

				talkPlay();

				talkAbuse();

			});

		}, 8000);

	} 
	else {

		console.log('RevolveR Talk service unload.');

		clearInterval( R.talk );

	}

};

R.search = (q) => {

	R.fetch('/search/?query='+ q, 'get', 'html', true, function() {

		if( this.length ) {

			R.insert('.revolver__main-contents', '<article class="revolver__article published"><div class="revolver__article-contents">'+ this +'<div></article>');

			R.logging(this, 'div');

			void setTimeout(() => {

				self.searchAction = null;

				clearInterval( R.notificationsInterval );

				R.notificationsInterval = null;

				R.fetchRoute( null );

			}, 500);

		}

	});

};

R.logging = ( x, e = 'html' ) => {

	let lines = x.split('</meter>')[0].split('<meter value="0">')[1].replace('<!--', '').replace('-->', '').trim().split("\n");

	let s = [];

	let l = 0;

	for ( let i of lines ) {

		if( l > 1 && l < lines.length - 2 ) {

			if( i.length ) {

				s.push( i );

			}

		}

		l++;

	}

	s.push( new Date().toString() );

	s.push( 'Route: '+ self.route );	

	let tpause = 1000;

	let tshift = 0;

	for ( let line of s ) {

		void setTimeout(() => {

			console.log( line );

		}, tpause + ( tshift * 500 ) );	

		tshift++;

	}

};

R.switchAttendanceDate = ( x ) => {

	for( let d in x ) {

		if( x[ d ][ 0 ] === 'choosen' ) {

			R.loadURI(

				document.location.origin + document.location.pathname + '?date='+ x[ d ][ 1 ], 'attendance'

			);

		}

	}

};

R.useCaptcha = ( p ) => {

	self.overprint = atob( p.split('*')[2] );
	self.oprintvar = atob( p.split('*')[0] );

	self.flag = null;

	let pixels = R.sel('#drawpane div');

	if( pixels ) {

		for( let i of R.sel('#drawpane div') ) {

			i.addEventListener('click', function(e) {

				e.preventDefault();

				let choosen = this.dataset.selected;

				if( e.isTrusted ) {

					self.flag = true;

					if( choosen === 'null' || choosen === 'false' ) {

						this.style = 'background: #7e333d;';
						this.dataset.selected = 'true';
						this.className = 'active';

						R.tick('hint', .03);

					}
					else {

						this.style = 'background: #a2a2a2; transform: scale(1);';
						this.dataset.selected = 'null';
						this.className = 'inactive';

					}

				}

			});

		}

	}

	const finger = R.sel('#overprint');

	if( finger ) {

		function oPrint( m ) {

			let c = finger[0].getContext('2d');

			function walk( e, i ) {

				let s = e.split(':');

				let style = '#' + ( s[ 0 ] == 1 ? '888888' : 'D99FAF' );

				c.fillStyle = style;

				c.fillRect( s[ 1 ], s[ 2 ], 24, 24 );

				c.stroke();

			}

			m.forEach( walk );

		}

		let d = atob( p.split('*')[ 1 ] ).split('|').sort();

		let xy = [];

		for( let i of d ) {

			xy.push( i.split('-')[ 1 ] );

		}

		oPrint( xy );

	}

};

R.cleanNotifications = (t) => {

	let timeShift = 0;

	for( let e of t ) {

		void setTimeout(() => {

			RR.tick('expand', .05);

			R.styleApply(e.querySelectorAll('.revolver__statuses-heading') , ['display:none'], () => {

				R.animate(e.children, ['height:0px:800:elastic']);

				R.animate([e], ['height:0px:1500:wobble', 'color:rgba(255,255,255,.1):700:elastic', 'opacity:0:1200:harmony'], () => {

					R.rem([e]);

				});

			});

		}, 10000 / ++timeShift);

	}

};

// Make interface
R.fetchRoute = ( intro ) => {

	const setupScreen = R.sel('.setup-screen')[0];

	if( document.location.protocol.includes('http:') ) {

		setupScreen.style.display = 'none';

	}

	if( 'caches' in window ) {

		if( 'serviceWorker' in navigator ) {

			window.addEventListener('load', function () {

				navigator.serviceWorker.register('/pwa.es7?s', {scope: '/'}).then(function(reg) {

					console.log('Registered Service Worker for Cache');

				});

			});

			let deferred;

			const setup = R.sel('.setup-home')[0];

			let installedTest = R.cookie('installed','get');

			if( installedTest.length > 0 ) {

				setupScreen.style.display = 'none';

			}

			window.addEventListener('beforeinstallprompt', ( e ) => {

				// Prevent Chrome 67 and earlier from automatically showing the prompt
				e.preventDefault();

				// Stash the event so it can be triggered later.
				deferred = e;

				// Update UI to notify the user they can add to home screen
				setupScreen.style.display = 'block';

				setup.addEventListener('click', ( e ) => {

					// hide our user interface that shows our A2HS button
					setupScreen.style.display = 'none';

					// Show the prompt
					deferred.prompt();

					// Wait for the user to respond to the prompt
					deferred.userChoice.then((c) => {

						if( c.outcome === 'accepted' ) {

							console.log('User accepted the application add promt');

							R.cookie('installed=1','set');

						}
						else {

							console.log('User dismissed the the application add promt');

							R.cookie('installed','rem');
						}

						deferred = null;

					});

				});

			});

		}

	}

	R.talkUpdate();

	R.timeFutures();

	const share = R.sel('.socialize'); 

	if( share ) {

		R.event('.fb, .vk, .tw', 'mouseenter', function(e) {

			R.tick('hint', .03);

		});

		R.event('.fb, .vk, .tw', 'click', function(e) {

			R.tick('expand', .05);

			window.open(this.dataset.share, 'example', 'width=600,height=400');


		});

	}

	/* Recorder */
	let recordHandler = R.sel('.revolver__record-handler');

	if( recordHandler ) {

		function rec( e ) {

			e.innerHTML = '<span style="color:#b00000bf">[ • recording ]</span>';

			R.recorder();

			setTimeout(() => {

				R.recordStart();

				if( R.isM ) {

					setTimeout(() => {

						send( e );

					}, 5500);

				}

			}, 500);

		};

		function send( e ) {

			e.innerHTML = '<span style="color:#b00092bf">[ ➥ sending ]</span>';

			setTimeout(() => {

				R.recordStop();

				setTimeout(() => {

					let adata = new FormData();

					adata.append( btoa('revolver_user_id'), R.utoa( R.sel('#revolver__user_id')[ 0 ].innerText +'~:::~text~:::~'+ -1) );
					adata.append( btoa('revolver_user_nickname'), R.utoa( R.sel('#revolver__user_name')[ 0 ].innerText +'~:::~text~:::~'+ -1) );
					adata.append( btoa('revolver_message'), R.utoa( 'Audio' +'~:::~text~:::~'+ -1) );
					adata.append( 'revolver_audio', R.recordStore );

					R.FormData = adata;

					// Perform parameterized fetch request
					R.fetch('/talker-s/?audio=send', 'POST', 'text', null, () => {

						R.FormData = null;

						e.innerHTML = '<span>[ • record ]</span>';

					});

				}, 500);

			}, 500);

		}

		if( R.isM ) {

			R.event(recordHandler, 'touchstart', function( e ) {

				if( e.isTrusted ) {

					rec( this );

				}

			});

		} 
		else {

			R.event(recordHandler, 'mousedown', function( e ) {

				if( e.isTrusted ) {

					rec( this );

				}

			});

			R.event(recordHandler, 'mouseup', function( e ) {

				if( e.isTrusted ) {

					send( this );

				}

			});

		}

	}

	// Privacy policy
	R.fetch('/secure/?policy=get', 'get', 'json', null, function() {

		const key = atob( this.privacy ).split('::');

		if( key[0] !== 'accepted' ) {

			const nPolicy = {

				html: '<div class="revolver__statuses-heading">... Privacy policy notification <i>+</i></div><div class="privacy-policy-notification"><p>This domain use cookies only to improve privacy and make possible correct work our services.</p><p>You can <a href="/privacy/">read domain cookie policy</a> and <a href="'+ document.location.pathname +'?notification=accept-privacy-policy">accept</a> it.</p></div>',

				attr: {

					class : 'revolver__status-notifications revolver__notice'

				}

			};

			R.new('div', '.revolver__main-contents', 'before', nPolicy);

			let forms = R.sel('form');

			let c = 0;

			for( let f of forms ) {

				if( c >= 1 ) {

					R.event([f.parentElement], 'click', (e) => {

						if( e.isTrusted ) {

							R.new('div', '.revolver__form-wrapper', 'before', nPolicy);

							R.tick('notification', .2);

							if( !R.isM ){

								R.hint();

							}

						}

					});

					for(let i of f.querySelectorAll('input, textarea')) {

						i.disabled = 'disabled';

					}

				}

				c++;

			}

			R.styleApply('.revolver__captcha-wrapper', ['display:none']);

			R.setAllow( null );

		}
		else {

			if( intro ) {

				let cform = R.sel('#comment-add-form');

				let route = cform ? cform[0].action.replace( document.location.origin, '' ) : document.location.pathname;

				if( route !== '/' && route !== '/logout/' ) {

					R.fetch('/secure/?route='+ route, 'get', 'json', null, function() {

						R.useCaptcha( this.key );

					});

				}

			}

			R.setAllow( key[1] );

		}

		// Lazy load
		R.lazyLoad();

		// Stop preview
		clearInterval( R.preview );

		// Hide status messages
		clearInterval( R.notificationsInterval );

		R.notificationsInterval = null;

		R.notificationsInterval = setInterval(() => {

			let notifications = R.sel('.revolver__status-notifications');

			if( notifications ) {

				R.cleanNotifications( notifications );

			}

		}, 20000);

		R.event('.revolver__status-notifications .revolver__statuses-heading i', 'click', function(e) {

			e.preventDefault();

			if( e.isTrusted ) {

				RR.tick('expand', .05);

				R.styleApply([this.parentElement], ['display:none'], () => {

					R.animate(this.parentElement.parentElement.children, ['height:0px:500:elastic']);

					R.animate([this.parentElement.parentElement], ['height:0px:1500:wobble', 'color:rgba(255,255,255,.1):700:elastic', 'opacity:0:1000:harmony']);

					void setTimeout(() => {

						R.rem([this.parentElement.parentElement]);

					}, 1300);

				});

			}

		});

		for( let i of R.sel('a') ) {

			if( i.target === '_blank') {

				R.addClass( [ i ], ['external'] );

			}

		}

		R.event('a:not(.talk-abuse):not(.metahash)', 'click', function(e) {

			e.preventDefault();

			if( e.isTrusted ) {

				if( R.hasClass( [ this ], 'external' ) ) {

					self.open( e.target.href );

					return;

				}

				if( !this.href.includes( 'webp', 'svg' ,'png', 'jpg', 'jpeg', 'gif', 'zip' ) ) {

					R.loadURI(

						this.href, this.innerText

					);

				}

			}

		});

	});

	// Intro
	if( intro ) {

		if( !R.isM ){

			R.hint();

		}

		R.styleApply('input[type="search"]', ['width:50vw']);

		R.attr('.revolver__header h1 a, .revolver__main-contents', {

			style: null

		});

		R.styleApply('.revolver__header h1 a', ['color: #ffffffdb', 'display:inline-block', 'opacity:.1'], () => {

			R.animate('.revolver__header h1 a', ['transform: scale(.5, .5, .5) rotate(360deg, 360deg, 360deg):1500:bouncePast']);
			R.animate('.revolver__header h1 a', ['opacity:.9:1500:bouncePast', 'transform: scale(1, 1, 1) rotate(0deg,0deg,0deg):2000:elastic', 'color:#790a61d6:6000:wobble']);

		});

	}

	R.event('#jump', 'click', () => {

		R.scroll('#RevolverRoot');

	});

	// Store goods covers slider
	setTimeout(() => {

		R.slide('.revolver__store-goods-cover figure img', 3000);

	}, 1500);

	const codeBlocks = R.sel('code');

	if( codeBlocks ) {

		for( let i of codeBlocks ) {

			i.innerHTML = R.syntax( i.innerHTML );

		}

	}

	// Highlight menu
	const menu = R.sel('.revolver__main-menu li');

	if( menu ) {

		for( let e of menu ) {

			let rgxp = document.location.pathname;
			let pass = R.attr( e.children[0], 'href')[0];

			if( (rgxp.includes(pass) && pass !== '/') || (rgxp === '/' && rgxp.includes(pass)) ) {

				void setTimeout(() => {

					R.addClass([ e ], 'route-active');

				}, 300);

			}

		}

	}

	R.event('.revolver-rating li', 'click::lock', (e) => {

		e.preventDefault();

		let paramsBlock = e.target.closest('ul');
		let rateValue	= e.target.dataset.rated;
		let ratingType 	= paramsBlock.dataset.type;

		if( !R.storage('rate-'+ ratingType +'-'+ paramsBlock.dataset.node, 'get') ) {

			R.removeClass(paramsBlock.querySelectorAll('li'), 'point');

			R.addClass([ e.target ], 'point');

			let data = new FormData();

			data.append( btoa('revolver_rating_node'), R.utoa( paramsBlock.dataset.node +'~:::~text~:::~'+ -1) );
			data.append( btoa('revolver_rating_user'), R.utoa( paramsBlock.dataset.user +'~:::~text~:::~'+ -1) );
			data.append( btoa('revolver_rating_value'), R.utoa( rateValue +'~:::~text~:::~'+ -1) );
			data.append( btoa('revolver_rating_type'), R.utoa( paramsBlock.dataset.type +'~:::~text~:::~'+ -1) );

			R.FormData = data;

			// Perform parameterized fetch request
			R.fetch('/rating-d/', 'POST', 'text', true, function() {

				R.storage('rate-'+ ratingType +'-'+ paramsBlock.dataset.node +'=1', 'set');

				R.FormData = null;

				console.log('Node rated :: '+ paramsBlock.dataset.node +'::'+ paramsBlock.dataset.user +'::'+ rateValue);

				setTimeout(() => {

					R.fetchRoute(true);

				}, 500);

			});

		} 
		else {

			console.log('You already rate node '+ paramsBlock.dataset.node);

		}

	});

	if( !R.isM ) {

		setTimeout(() => {

			R.Menu('.revolver__main-menu');

		}, 300);

	}

	/* Quick edit handler */
	R.event('.revolver__quick-edit-handler', 'click', (e) => {

		let articleArea = e.target.closest('article');
		let editorArea  = articleArea.querySelector('.revolver__article-contents, .revolver__comments-contents');

		R.toggleClass([ editorArea ], 'quick-edit-enbaled');

		if( R.isU(e.target.dataset.editing) || e.target.dataset.editing === 'null' ) {

			R.attr(editorArea, { 

				'contenteditable': true

			});

			R.attr(e.target, {

				'data-editing': true

			});

			let meta = editorArea.querySelectorAll('.metahash');

			if( meta ) {

				for( m of meta ) {

					m.outerHTML = m.innerText;

				}

			}

			e.target.innerText = '[ Ok! ]';

			console.log('Enter quick edit mode');

		} 
		else {

			console.log('Exit quick edit mode :: saving ... ');

			let figs = editorArea.querySelectorAll('figure img');

			if( figs ) {

				for( let i of figs ) {

					i.removeAttribute('data-src');
					i.removeAttribute('class');
					i.removeAttribute('style');

				}

			}

			setTimeout(() => {

				R.attr(editorArea, { 

					'contenteditable': false

				});

				R.attr(e.target, {

					'data-editing': null

				});

				let data = new FormData();

				data.append( btoa('revolver_quedit_user'), R.utoa( editorArea.dataset.user +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_quedit_node'), R.utoa( editorArea.dataset.node +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_quedit_data'), R.utoa( editorArea.innerHTML +'~:::~text~:::~'+ -1) );
				data.append( btoa('revolver_quedit_type'), R.utoa( editorArea.dataset.type +'~:::~text~:::~'+ -1) );

				R.FormData = data;

				// Perform parameterized fetch request
				R.fetch('/quedit-d/', 'POST', 'text', true, function() {

					R.FormData = null;

					R.fetchRoute(true);

					console.log('Quick edit mode :: node saved ... ');


				});

				e.target.innerText = '[ Quick Edit ]';

			}, 1500);

		}

	});


	/* Basket handler */
	let in_basket_i = document.querySelector('.basket_indicator');

	let in_basket_h = document.querySelector('.revolver__in-basket-handler');

	let goods_count = 0;

	for( let dc of R.cookie('goods_in_basket', 'get').split('|') ) {

		if( dc.length > 0 ) {

			goods_count++;

		}

	};

	R.event('.basket_handler', 'click', (e) => {

		R.loadURI( '/basket/', e.target.title );

	});

	if( goods_count > 0 ) {

		document.querySelector('.basket_handler').style.display = 'inline-block';

		R.animate([ in_basket_i ], ['opacity:1:1000:wobble'], () => {

			in_basket_i.innerText = goods_count;

			document.querySelector('.basket_icon').style.display = 'inline-block';

		});

	}


	if( in_basket_h ) {

		for( let xc of R.cookie('goods_in_basket', 'get').split('|') ) {

			if( in_basket_h.dataset.goods === xc) {

				in_basket_h.classList.add('active');

				in_basket_h.querySelector('span').innerText = '-';

			}

		};

		R.event('.revolver__in-basket-handler', 'click', (e) => {

			let wrapper = e.target.closest('li');

			for( let xc in R.cookie('goods_in_basket', 'get').split('|') ) {

				if( wrapper.dataset.goods == xc) {

					wrapper.classList.add('active');

				}

			};

			wrapper.classList.toggle('active');

			if( wrapper.classList.contains('active') ) {

				e.target.innerText = '-';

				in_basket_i.innerText = (in_basket_i.innerText - 0) + 1;

				R.cookie('goods_in_basket='+ (R.cookie('goods_in_basket', 'get') + wrapper.dataset.goods +'|').replace('||', '|'), 'set');

				R.tick('cart', .2);

				console.log('Goods added to basket');

			} 
			else {

				e.target.innerText = '+';

				in_basket_i.innerText = (in_basket_i.innerText - 0) - 1;

				let goods_stack = '';

				for( let c of R.cookie('goods_in_basket', 'get').split('|') ) {

					if( wrapper.dataset.goods !== c) {

						goods_stack += c +'|';
					}

				};

				R.cookie('goods_in_basket='+ goods_stack.replace('||', '|'), 'set');

				console.log('Goods removed from basket');

			}

		});

	}

	// Forms styles
	R.formBeautifier();

	// Enable editor
	if( R.sel('textarea') ) {

		R.markupEditor();

	}

	// Tabs
	if( R.sel('#tabs') ) {

		R.tabs('#tabs li.revolver__tabs-tab', '#tabs div');

	}

	// Collapsible elements
	if( R.sel('.collapse dd, .revolver__referers-list li pre') ) {

		for( let i of R.sel('.collapse dd, .revolver__referers-list dd') ) {

			R.toggle( [ i ] );

		}

	}

	R.expand('.collapse dt, .revolver__collapse-form-legend, .revolver__referers-list dt');

	R.event('input[type="submit"]', 'click', (e) => {

		if( e.isTrusted ) {

			if( self.flag ) {

				let m = [];
				let c = 0;

				let draw = R.sel('#drawpane div');

				for( let a of draw ) {

					m[ c ] = ( a.dataset.selected === 'true' ? 1 : 0 ) +':'+ a.dataset.xy;

					c++;

				}

				function encoder( s ) {

					let e = '';

					for ( let j = 0; j < s.length; j++ ) {

						e += String.fromCharCode( s.charCodeAt( j ) ^ 51 );

					}

					return e;

				}

				let s = '';
				let e = encoder( '{\"value\":'+ '"'+ self.oprintvar +'*'+ m.join('|') +'"'+ '}' );

				for ( let i = 0; i < e.length; i++ ) {

					s += e.charCodeAt( i );

					if( i < e.length - 1 ) {

						s += '|';

					}

				}

				R.attr('.revolver__captcha-wrapper input[type="hidden"]', {

					'value': btoa( s ) +'*'+ btoa( self.overprint ) +'*'+ btoa( document.location.pathname )

				});

			}

		}

	});

	// Fetch Submit
	R.fetchSubmit('form.revolver__new-fetch', 'text', function() {

		// Prevent search box fetching
		if( !self.searchAction ) {

			R.sel('#RevolverRoot')[0].innerHTML = '';

			for( let i of R.convertSTRToHTML(this) ) {

				if( i.tagName === 'TITLE' ) {

					var title = i.innerHTML;

				}

				if ( i.id === 'RevolverRoot' ) {

					var contents = i.innerHTML;

				}

				if( i.tagName === 'META') {

					if( i.name === 'host') {

						eval( 'window.route="'+ i.content +'";' );

					}

				}

				if( i.className === 'revolver__privacy-key' ) {

					R.sel('.revolver__privacy-key')[0].dataset.xprivacy = i.dataset.xprivacy;

				}

			}

			R.insert( R.sel('#RevolverRoot'), contents );

			R.location(title, self.route);

			R.scroll();

			R.logging(this, 'body');

			clearInterval( R.notificationsInterval );

			R.notificationsInterval = null;

			R.fetchRoute( true );

		}

	});

	// Meta hash
	R.metahash();

	// Search
	R.event('.revolver__search-box form', 'submit', function(e) {

		e.preventDefault();

		if( e.isTrusted ) {

			// Prevent search box fetching
			self.searchAction = true;

			R.search(this.querySelectorAll('input[type="search"]')[0].value);

		}

	});

	// Terminal fetch
	R.fetchSubmit('form.revolver__terminal-fetch', 'json', function() {

		// Prevent search box fetching
		if( !self.searchAction ) {

			R.new('li', '.revolver__terminal-session-store ul', 'after', {

				html: '<span class="revolver__collapse-form-legend">'+ this.command +'</span><pre class="revolver__collapse-form-contents" style="overflow: hidden; width: 0; height: 0; line-height: 0; display: inline-block;">'+ this.output +'</pre>'

			});

			R.fetch('/secure/?route=/terminal/', 'get', 'json', null, function() {

				R.useCaptcha( this.key );

			});

			R.fetchRoute( true );

		}


	});

	R.loadURI = ( url, title ) => {

		R.fetch(url, 'get', 'html', true, function() {

			R.sel('#RevolverRoot')[0].innerHTML = '';

			for( let i of R.convertSTRToHTML( this ) ) {

				if( i.tagName === 'TITLE' ) {

					var title = i.innerHTML;

				}

				if ( i.id === 'RevolverRoot' ) {

					var contents = i.innerHTML;

				}

				if( i.tagName === 'META') {

					if( i.name === 'host') {

						eval( 'window.route="'+ i.content +'";' );

					}

				}

				if( i.className === 'revolver__privacy-key' ) {

					R.sel('.revolver__privacy-key')[0].dataset.xprivacy = i.dataset.xprivacy;

				}

			}

			R.insert( R.sel('#RevolverRoot'), contents );

			R.location( title, self.route );

			let hash = url.split('#');

			if( !R.isU( hash[ 1 ] ) ) {

				setTimeout(

					R.scroll('#'+ hash[ 1 ] ), 2500

				);

			}
			else {

				R.scroll();

			}

			R.logging(this);

			clearInterval( R.notificationsInterval );

			R.notificationsInterval = null;

			R.fetchRoute( true );

		});

	};

	// History states
	self.onpopstate = void function(e) {

		R.loadURI(

			e.state.url, e.state.title

		);

	}

};

// Perform parametrized fetch query
if( typeof R === 'object' ) {

	R.fetchRoute( true );

}
else {

	console.log(

		decodeURIComponent(

			atob( 'JUYwJTlGJTlCJTkxJTIwSW5zdGFuY2UlMjBub3QlMjBhbGxvd2VkJTIwLi4u' )

		)

	);

}
