/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import profilePictureDefault  from './images/Default.pfp.png';
import carPictureDefault  from './images/electricCar.png';
import nature  from './images/nature.jpeg';
import plug  from './images/plug.jpeg';
import bornes  from './images/bornes.jpeg';

let html;
html = ` < img src = "${profilePictureDefault}" alt = "profile Picture Default" > `;
html = ` < img src = "${carPictureDefault}" alt = "car Picture Default" > `;
html = ` < img src = "${nature}" alt = "ecology" > `;
html = ` < img src = "${plug}" alt = "plugs recharge cars" > `;
html = ` < img src = "${bornes}" alt = "recharge machine" > `;
