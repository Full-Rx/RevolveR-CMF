
// Day times future switch

RR.timeFutures = () => {

	let dateTime = new Date();

	let hours = dateTime.getHours();

	let timeStyle = RR.sel('.revolver__time-futures');

	// Morning Sound
	if( hours > 5 && hours <= 11 ) {

		if( !RR.morningSound ) {

			RR.tick('morning-come', .7);

			RR.morningSound = true;

		}

		if( !R.morning ) {

			if( timeStyle ) {

				RR.rem(timeStyle);

				RR.night = null;

				RR.evening = null;

			}

			RR.morningMode();

			console.log('Morning come ...');

		}

	}

	// Day Sound
	if( hours > 11 && hours <= 16 ) {

		if( !RR.daySound ) {

			RR.tick('day-come', .7);

			RR.daySound = true;

		}

		if( timeStyle ) {

			RR.rem(timeStyle);

			RR.morning = null;

			RR.evening = null;

			RR.night = null;

			console.log('Day come ...');

		}

	}

	// Evening Sound
	if( hours > 17 && hours <= 20 ) {

		if( !R.eveningSound ) {

			RR.tick('evening-come', .07);

			RR.eveningSound = true;

		}

		if( !R.evening ) {

			if( timeStyle ) {

				RR.rem(timeStyle);

				RR.night = null;

				RR.morning = null;

			}

		}

		RR.eveningMode();

		RR.evening = true;

		console.log('Evening come ...');

	}

	// Night Sound
	if( hours > 20 || hours <= 5 ) {

		if( !RR.nightSound ) {

			RR.tick('night-come', .07);

			RR.nightSound = true;

		}

		if( !R.night ) {

			if( timeStyle ) {

				RR.rem(timeStyle);

				RR.morning = null;

				RR.evening = null;

			}

			RR.nightMode();

			console.log('Night come ...');

		}

	}

};

/* Morning */
RR.morningMode = () => {

	RR.new('style', 'head', 'in', {

		html: ':root { --body-bg: transparent repeating-linear-gradient(-45deg, transparent, transparent .01vw, #cccccca3 .01vw, #9d9d9d75 .5vw); \
					   --article-bg-color: rgb(195 156 117 / 60%); \
					   --article-text-shadow-color: 0 0 .1vw #000; \
					   --article-text-color: #845c40; \
					   --article-header-bg: linear-gradient(to right, rgba(254, 255, 232, 0) 0%, rgb(167 147 68 / 31%) 100%); \
					   --article-footer-bg: linear-gradient(to left, rgba(254, 255, 232, 0) 0%, rgb(167 147 68 / 33%) 100%); \
					   --comments-article-header: linear-gradient(to right, rgba(254, 255, 232, 0), rgb(214 162 70 / 55%)); \
					   --comments-article-footer: linear-gradient(to left, rgba(254, 255, 232, 0), rgb(214 162 70 / 55%)); \
					   --article-header-text-shadow: #000; \
					   --article-header-text-shadow: #dedede; \
					   --article-header-text-color: #9a4c39; \
					   --simple-shadow: #a0a0a0ed; \
					   --forum-topic-list-bg: #000; \
					   --article-list-item-color: #6b1313; \
					   --related-list-box-shadow: inset .2vw .2vw .2vw #a09696; \
					   --pagination-list-bg: linear-gradient(to bottom, rgba(255, 255, 255, .3) 0, rgb(193 144 80 / 50%) 100%); \
					   --pagination-list-text-color: #fff; \
					   --parallax-bg-color: #949494e3; \
					   --parallax-1-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #6b6b6b .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a); \
					   --parallax-2-bg: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #ffffff45 .1vw, #94949494 .25vw), linear-gradient(to top, #eeeeee5c, #bfbfbf1a); \
					   --tabs-list-items-bg: #5f5b5b; \
					   --tabs-list-items-text-shadow: #b5a8a8d1; \
					   --tabs-body-bg: #b59b79; \
					   --tabs-list-items-hover-bg: linear-gradient(to bottom, rgb(160 119 79 / 92%) 0%, rgb(181 155 121) 100%); \
					   --form-fieldset-bg: linear-gradient(45deg, rgb(193 156 126 / 70%) 0, rgb(162 152 71 / 40%) 60%, rgb(232 212 103 / 20%) 100%); \
					   --form-legend-bg: linear-gradient(45deg, rgb(241 203 87 / 80%) 0%, rgb(233 236 180 / 60%) 60%, rgb(212 189 89 / 40%) 100%); \
					   --form-input-bg: linear-gradient(to bottom, #e0e08e70 0%,#ad880c36 100%); \
					   --form-input-text-color: #ab0606c4; \
					   --form-editor-buttons-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #15171594 .25vw), linear-gradient(to bottom, #eeeeee5c, #2727271a); \
					   --form-editor-buttons-text-color: #fff; \
					   --form-submit-bg: linear-gradient(45deg, rgb(162 150 45 / 69%) 0%, rgb(208 171 90 / 40%) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-input-select-main-opened: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #000 .1vw, #ffffff91 .25vw), linear-gradient(to top, #eeeeee9e, #00000042) !important; \
					   --form-placeholder: #fff; \
					   --form-select-main: linear-gradient(to bottom, #d6b061de 0%, #d4b55dd1 100%); \
					   --form-select-list-active: linear-gradient(to bottom, #d8a667a8 0%, #decf7a 100%); \
					   --article-user-list-header: linear-gradient(to left, rgba(228, 228, 228, 0.24) 0%, rgb(255 255 255 / 74%) 100%); \
					   --terminal-text-color: #fff; \
					   --notice-color: #000; \
					   --related-link-hover: #fff; \
					   --country-list-text-color: #fff; \
					   --search-results-list: #fff; \
					   --search-results-description: #5d3482e0; \
					   --search-results-title: #a9561ce8; \
					   --modal-title-shadow: #000; \
					}',

		attr: {

			'media': 'all',
			'class': 'revolver__time-futures'

		}

	});

	RR.morning = true;

};

/* Evening */
RR.eveningMode = () => {

	RR.new('style', 'head', 'in', {

		html: ':root { --body-bg: transparent repeating-linear-gradient(-45deg, transparent, transparent .01vw, #cccccca3 .01vw, #9d9d9d75 .5vw); \
					   --article-bg-color: rgba(88, 46, 88, .6); \
					   --article-text-shadow-color: 0 0 .1vw #000; \
					   --article-text-color: #c7c7c7; \
					   --article-header-bg: linear-gradient(to right, rgba(254, 255, 232, 0) 0%, rgba(103, 67, 98, .8) 100%); \
					   --article-footer-bg: linear-gradient(to left, rgba(254, 255, 232, 0) 0%, rgba(103, 67, 98, .8) 100%); \
					   --comments-article-header: linear-gradient(to right, rgba(254, 255, 232, 0), rgb(118, 74, 121, .75)); \
					   --comments-article-footer: linear-gradient(to left, rgba(254, 255, 232, 0), rgb(125, 69, 123, .75) 99.58%); \
					   --article-header-text-shadow: #000; \
					   --article-header-text-color: #fdfdfd; \
					   --article-header-hover-text-shadow-color: #000; \
					   --simple-shadow: #a090a0eb; \
					   --related-link-color: #3e093cbd; \
					   --forum-topic-list-bg: #000; \
					   --article-list-item-color: #c7c7c7; \
					   --related-list-box-shadow: inset .2vw .2vw .2vw #6b6161e3; \
					   --pagination-list-bg: linear-gradient(to bottom, rgba(255, 255, 255, .3) 0, rgba(101, 27, 87, .5) 100%); \
					   --pagination-list-text-color: #fff; \
					   --parallax-bg-color: #949494e3; \
					   --parallax-1-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #6b6b6b .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a); \
					   --parallax-2-bg: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #ffffff45 .1vw, #94949494 .25vw), linear-gradient(to top, #eeeeee5c, #bfbfbf1a); \
					   --tabs-list-items-bg: #5f5b5b; \
					   --tabs-list-items-text-shadow: #666; \
					   --tabs-body-bg: #794d78e8; \
					   --tabs-list-items-hover-bg: linear-gradient(to bottom, rgba(206, 139, 202, .9) 0%, rgb(123, 80, 122) 100%); \
					   --form-fieldset-bg: linear-gradient(45deg, rgba(109, 56, 101, .7) 0, rgba(136, 73, 129, .4) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-legend-bg: linear-gradient(45deg, rgba(109, 49, 101, .8) 0%, rgba(183, 136, 173, .60) 60%, rgba(252, 255, 244, .4) 100%); \
					   --form-input-bg: linear-gradient(to bottom, #d5bad8b5 0%, #964598d4 100%); \
					   --form-input-text-color: #0e0e0ef2; \
					   --form-editor-buttons-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #15171594 .25vw), linear-gradient(to bottom, #eeeeee5c, #2727271a); \
					   --form-editor-buttons-text-color: #fff; \
					   --form-submit-bg: linear-gradient(45deg, rgb(163, 81, 167, .7) 0%, rgba(131, 93, 138, .4) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-input-select-main-opened: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #000 .1vw, #ffffff91 .25vw), linear-gradient(to top, #eeeeee9e, #00000042) !important; \
					   --form-placeholder: #fff; \
					   --form-select-main: linear-gradient(to bottom, #7d7e7d 0%, #673165 100%); \
					   --form-select-list-active: linear-gradient(to bottom, #d37dd4a8 0%, #9f3ca2 100%); \
					   --article-user-list-header: linear-gradient(to left, rgba(228, 228, 228, 0.24) 0%, rgb(255 255 255 / 74%) 100%); \
					   --terminal-text-color: #fff; \
					   --notice-color: #000; \
					   --related-link-hover: #fff; \
					   --country-list-text-color: #fff; \
					   --search-results-list: #fff; \
					   --search-results-description: #d2bf37e0; \
					   --search-results-title: #92d084e8; \
					   --modal-title-shadow: #000; \
					   --expander-color: #691049e0; \
					   --article-heading-h2: #71336fe0; \
					   --article-heading-h1: #5c0f61d9; \
					   --tabs-item-color: #611651eb; \
					   --form-elements-color: #2d0c2ce3; \
					   --form-legend-color: #3a0831e6; \
					   --select-target-color: #4e0c49f2; \
					   --select-coosen-option-color: #46093cc4; \
					}',

		attr: {

			'media': 'all',
			'class': 'revolver__time-futures'

		}

	});

	RR.evening = true;

};

/* Night */
RR.nightMode = () => {

	RR.new('style', 'head', 'in', {

		html: ':root { --body-bg: transparent repeating-linear-gradient(-45deg, transparent, transparent .01vw, #cccccca3 .01vw, #9d9d9d75 .5vw); \
					   --article-bg-color: rgba(0, 0, 0, .6); \
					   --article-text-shadow-color: 0 0 .1vw #000; \
					   --article-text-color: #c7c7c7; \
					   --article-header-bg: linear-gradient(to right, rgba(254, 255, 232, 0) 0%, rgb(62 57 57) 100%); \
					   --article-footer-bg: linear-gradient(to left, rgba(254, 255, 232, 0) 0%, rgb(64 66 57) 100%); \
					   --comments-article-header: linear-gradient(to right, rgba(254, 255, 232, 0), rgb(0 0 0)); \
					   --comments-article-footer: linear-gradient(to left, rgba(254, 255, 232, 0), rgb(45 45 45) 99.58%); \
					   --article-header-text-shadow: #000; \
					   --article-header-text-color: #fdfdfd; \
					   --article-header-hover-text-shadow-color: #000; \
					   --simple-shadow: #000; \
					   --forum-topic-list-bg: #000; \
					   --article-list-item-color: #c7c7c7; \
					   --related-list-box-shadow: inset .2vw .2vw .2vw #000; \
					   --pagination-list-bg: linear-gradient(to bottom, rgba(255, 255, 255, .3) 0, rgb(0 0 0 / 50%) 100%); \
					   --pagination-list-text-color: #fff; \
					   --parallax-bg-color: #949494e3; \
					   --parallax-1-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #6b6b6b .25vw), linear-gradient(to bottom, #eeeeee5c, #bfbfbf1a); \
					   --parallax-2-bg: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #ffffff45 .1vw, #94949494 .25vw), linear-gradient(to top, #eeeeee5c, #bfbfbf1a); \
					   --tabs-list-items-bg: #5f5b5b; \
					   --tabs-list-items-text-shadow: #666; \
					   --tabs-body-bg: #272727; \
					   --tabs-list-items-hover-bg: linear-gradient(to bottom, rgb(0, 0, 0) 0%, rgb(47, 47, 47) 100%); \
					   --form-fieldset-bg: linear-gradient(45deg, rgba(35, 35, 35, .7) 0, rgba(78, 78, 77, .4) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-legend-bg: linear-gradient(45deg, rgba(45, 45, 45, .8) 0%, rgba(93, 95, 91, .6) 60%, rgba(252, 255, 244, .4) 100%); \
					   --form-input-bg: linear-gradient(to bottom, #7d7e7d 0%,#0e0e0e 100%); \
					   --form-input-text-color: #c5c1c1f2; \
					   --form-editor-buttons-bg: repeating-linear-gradient(45deg, transparent, transparent .1vw, #ffffff45 .1vw, #15171594 .25vw), linear-gradient(to bottom, #eeeeee5c, #2727271a); \
					   --form-editor-buttons-text-color: #fff; \
					   --form-submit-bg: linear-gradient(45deg, rgb(23 23 23 / 70%) 0%, rgb(45 45 44 / 40%) 60%, rgba(252, 255, 244, .2) 100%); \
					   --form-input-select-main-opened: repeating-linear-gradient(-45deg, transparent, transparent .1vw, #000 .1vw, #ffffff91 .25vw), linear-gradient(to top, #eeeeee9e, #00000042) !important; \
					   --form-placeholder: #fff; \
					   --form-select-main: linear-gradient(to bottom, #7d7e7d 0%, #0e0e0e 100%); \
					   --form-select-list-active: linear-gradient(to bottom, #3c3c3ca8 0%, #848484 100%); \
					   --article-user-list-header: linear-gradient(to left, rgba(228, 228, 228, 0.24) 0%, rgb(255 255 255 / 74%) 100%); \
					   --terminal-text-color: #fff; \
					   --notice-color: #000; \
					   --related-link-hover: #fff; \
					   --country-list-text-color: #fff; \
					   --search-results-list: #fff; \
					   --search-results-description: #d2bf37e0; \
					   --search-results-title: #92d084e8; \
					   --modal-title-shadow: #000; \
					}',

		attr: {

			'media': 'all',
			'class': 'revolver__time-futures'

		}

	});

	RR.night = true;

};