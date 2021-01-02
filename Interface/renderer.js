
//
// Author : Guillaume Bonnet  - guillaumebonnet.fr
// Refactoring : Dmitry Maltsev - revolvercmf.ru
// MIT license: http://opensource.org/licenses/MIT
// v.1.1

RR.guilloche = ( x = null, y = null, z = null, c = '#b5346a' ) => {

    let gui = {

        figure: {

            majorR: 40,                              // Major Ripple
            minorR: .25,                             // Minor Ripple
            steps: Math.round(Math.random() * 1400), // Divide circle this many times
            radius: 5,                               // Radius,
            angle: 2,                                // Angle multiplier
            amplitude: 3.5,
            Multiplier: 1,
            deg2rad: Math.PI / 180

        },

        appearance: {

            opacity: .8,
            lineColor: '#b5346a',
            backColor: 'rgba(0, 0, 0, 0)',
            lineWidth: .5,
            spirograph: 'Hypotrochoid'

        }

    };

	gui.figure.lineColor = c;

	if( x ) {

		gui.figure.majorR = x;

	}

	if( y ) {

		gui.figure.minorR = y;

	}

	if( z ) {

		gui.figure.amplitude = z;

	}

	let glc = RR.sel('.guilloche');

	for( w of glc ) {

		if( !w.querySelector('canvas') ) {

			w.innerHTML = '<canvas class="guilloche-print"></canvas>';

		}

	}

	let e = R.sel('.guilloche-print');

    gui.section_length = 3;

    let colorNeverChanged = true;

    for( g of e ) {

		/* initialise canvas */
		let ctx = g.getContext('2d');

		ctx.canvas.width =  320; //g.closest('body').offsetWidth;
		ctx.canvas.height = 320; //g.closest('body').offsetHeight;

	    /* cenver element */
	    ctx.translate((ctx.canvas.width / 2) + .5, (ctx.canvas.height / 2) + .5);

	    let clearIt = () => {

	        ctx.clearRect( -1 * (ctx.canvas.width / 2) - 1, -1 * (ctx.canvas.height / 2) - 1, ctx.canvas.width, ctx.canvas.height ); 

	    };

	    let drawIt = () => {

	        clearIt();

	        let l, x, y, oldX, oldY, thetaStep, s, addition, subtraction, radiusGame, choosenSpirograph, entiereTheta, cosVal, sinVal;
	 
	        let sl = theta = 0;

	        if( colorNeverChanged ) {

	            ctx.fillStyle = gui.appearance.backColor;

	            ctx.fillRect(-1 * (ctx.canvas.width), -1 * (ctx.canvas.height), ctx.canvas.width, ctx.canvas.height);

	        }

	        let extract = RR.getRGB(gui.appearance.lineColor);

	        ctx.strokeStyle = 'rgba('+ Math.random() * (255 - extract[0]) + extract[0] +','+ Math.random() * (255 - extract[1]) + extract[1] +','+ Math.random() * (255 - extract[2]) + extract[2] +',.5)';

	        ctx.lineWidth = gui.appearance.lineWidth;
	        ctx.globalAlpha = gui.appearance.opacity;

	        thetaStep = gui.figure.Multiplier * Math.PI / gui.figure.steps;

	        s = (gui.figure.majorR - gui.figure.minorR) / gui.figure.minorR;
	        addition = gui.figure.majorR + gui.figure.minorR;
	        subtraction = gui.figure.majorR - gui.figure.minorR;
	        radiusGame = gui.figure.minorR + gui.figure.radius;

	        choosenSpirograph = gui.appearance.spirograph;

	        for( let t = 0; t <= gui.figure.steps; t++ ) {

	            entiereTheta = gui.figure.angle * theta;

	            cosVal = Math.cos(entiereTheta);
	            sinVal = Math.sin(entiereTheta);            

	            if( choosenSpirograph === 'Hypotrochoid' ) {

	                x = (subtraction * cosVal) + (radiusGame * Math.cos((s) * entiereTheta));
	                y = (subtraction * sinVal) + (radiusGame * Math.sin((s) * entiereTheta));

	            }
	            else if( choosenSpirograph === 'Epitrochoid' ) {

	                x = (addition * cosVal) + (radiusGame * Math.cos(((addition) / gui.figure.minorR) * entiereTheta));
	                y = (addition * sinVal) + (radiusGame * Math.sin(((addition) / gui.figure.minorR) * entiereTheta));

	            }
	            else if( choosenSpirograph === 'Hypocycloid') {

	                x = (subtraction * cosVal) + (gui.figure.minorR * Math.cos((s) * entiereTheta));
	                y = (subtraction * sinVal) + (gui.figure.minorR * Math.sin((s) * entiereTheta));

	            }
	            else {

	                return;

	            }

	            x *= gui.figure.amplitude;
	            y *= gui.figure.amplitude;

	            if( sl === 0 ) {

	                ctx.beginPath();

	                if( t === 0 ) {

	                    ctx.moveTo(x, y);

	                }
	                else {

	                    ctx.moveTo(oldX, oldY);
	                    ctx.lineTo(x, y);

	                }

	                ctx.stroke();

	            } 
	            else {

	                // Append to line section
	                ctx.lineTo(x, y);
	                ctx.stroke();

	            }

	            oldX = x;
	            oldY = y;

	            sl++;

	            theta += thetaStep;

	            if( sl === gui.section_length ) {

	                sl = 0;

	            }

	        }

	    };

	    drawIt();

    }

}
