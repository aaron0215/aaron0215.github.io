/*jshint esversion: 6 */
// @ts-check

import { draggablePoints } from "./libs/dragPoints.js";
import { RunCanvas } from "./libs/runCanvas.js";

/**
 * Have the array of control points for the track be a
 * "global" (to the module) variable
 *
 * Note: the control points are stored as Arrays of 2 numbers, rather than
 * as "objects" with an x,y. Because we require a Cardinal Spline (interpolating)
 * the track is defined by a list of points.
 *
 * things are set up with an initial track
 */
/** @type Array<number[]> */
let thePoints = [
  [150, 150],
  [150, 450],
  [450, 450],
  [450, 150]
];

/**
 * Draw function - this is the meat of the operation
 *
 * It's the main thing that needs to be changed
 *
 * @param {HTMLCanvasElement} canvas
 * @param {number} param
 */
function draw(canvas, param) {
  let context = canvas.getContext("2d");
  // clear the screen
  context.clearRect(0, 0, canvas.width, canvas.height);

  // draw the control points
  thePoints.forEach(function(pt) {
    context.beginPath();
    context.arc(pt[0], pt[1], 5, 0, Math.PI * 2);
    context.closePath();
    context.fill();
  });

  // now, the student should add code to draw the track and train
  let simple = /** @type{HTMLInputElement} */ (document.getElementById("simple-track"));
  let arclength = /** @type{HTMLInputElement} */ (document.getElementById("arc-length"));
  // let bspline = /** @type{HTMLInputElement} */ (document.getElementById("bspline"));

  // Draw the track
  // Derivative d = s(P(i+1)-P(i-1)) where s = (1-t)/2. we pick t = 0.5
  // calculate all derivatives for further use
  let derivs = [];
  let len = thePoints.length;
  for(let i = 0; i < len; i++){
    derivs[i] = [0.5*(thePoints[(i+1)%len][0]-thePoints[(i-1+len)%len][0]),0.5*(thePoints[(i+1)%len][1]-thePoints[(i-1+len)%len][1])];
  }

  // calculate control points
  let controlPts = [];
  for(let i = 0; i < len; i++){
    let p1x = thePoints[i][0]+1.0/3.0*derivs[i][0];
    let p1y = thePoints[i][1]+1.0/3.0*derivs[i][1];
    let p2x = thePoints[(i+1)%len][0]-1.0/3.0*derivs[(i+1)%len][0];
    let p2y = thePoints[(i+1)%len][1]-1.0/3.0*derivs[(i+1)%len][1];
    controlPts[i] = [p1x,p1y,p2x,p2y];
  }

  // some functions
  let divide = 100*thePoints.length; // divide each side of track (between any two points) into 100 pieces.
  // locate each cabin of train. Formula from Workbook 5-3
  function locate(u, i, j){
    return thePoints[i][j] +
    derivs[i][j] * u +
    (-3 * thePoints[i][j] - 2 * derivs[i][j] + 3 * thePoints[(i+1)%len][j] - derivs[(i+1)%len][j]) * Math.pow(u,2) +
    (2 * thePoints[i][j] + derivs[i][j] - 2 * thePoints[(i+1)%len][j] + derivs[(i+1)%len][j]) * Math.pow(u,3);
  }
  // get speed (velocity) at each position by calculating derivative at that position
  function speed(u, i, j) {
    return derivs[i][j] + 
    2 * (-3 * thePoints[i][j] - 2 * derivs[i][j] + 3 * thePoints[(i+1)%len][j] - derivs[(i+1)%len][j]) * u + 
    3 * (2 * thePoints[i][j] + derivs[i][j] - 2 * thePoints[(i+1)%len][j] + derivs[(i+1)%len][j]) * Math.pow(u,2);
  }
  // Euclid distance between two points
  function distance(point1,point2){
    return Math.sqrt((point2[0] - point1[0]) * (point2[0] - point1[0]) + (point2[1] - point1[1]) * (point2[1] - point1[1]));
  }
  let traveledDist = 0;
  let distances = [];
  for(let d = 0; d < divide; d++){
    let seg = Math.floor(0.01 * d);
    // Distance between two neighbor divided points
    if (d > 0)
      traveledDist += distance([locate(0.01*d-seg, seg, 0), locate(0.01*d-seg, seg, 1)], [locate(0.01*(d-1)-seg, seg, 0), locate(0.01*(d-1)-seg, seg, 1)]);
    else
      traveledDist += 0;
    // Store distance between this divided points and previous one
    distances[d] = [d*0.01, traveledDist];
  }
  function calculateArc(x){
    let position = 0;
    // find which piece (segment) "x" is in and return the divide point which is the arc length
    while (x > distances[position][1] && position < divide) 
      position++;
    return distances[position][0];
  }

  if(simple.checked){
    context.save();
    context.beginPath();
    context.moveTo(thePoints[0][0],thePoints[0][1]);
    for (let i = 0; i < len; i++) 
      context.bezierCurveTo(controlPts[i][0], controlPts[i][1], controlPts[i][2], controlPts[i][3], thePoints[(i+1)%len][0], thePoints[(i+1)%len][1]);
    context.stroke();
    context.closePath();
    context.restore();
  } else { 
    // draw dual track by finding parallel curves
    let railWidth = 6;
    for (let i = 1; i < divide; i++){ // break down the whole route to pieces and draw parallel lines
      context.save();
      context.beginPath();
      let seg1 = Math.floor(0.01*i);
      let [x1,y1] = [locate(0.01*i-seg1, seg1, 0), locate(0.01*i-seg1, seg1, 1)];
      let [sx1,sy1] = [speed(0.01*i - seg1, seg1, 0), speed(0.01*i - seg1, seg1, 1)]; // speed. Use it as direction vector
      let seg2 = Math.floor(0.01*(i-1));
      let [x2,y2] = [locate(0.01*(i-1)-seg2, seg2, 0), locate(0.01*(i-1)-seg2, seg2, 1)];
      let [sx2,sy2] = [speed(0.01*(i-1) - seg2, seg2, 0), speed(0.01*(i-1) - seg2, seg2, 1)];
      let magnitude = Math.sqrt(sx1*sx1 + sy1*sy1); // Make directional vector
      let [d1x,d1y] = [sx1/magnitude*railWidth,sy1/magnitude*railWidth]; // directional vector * desired width = increase/dscrease amount in that direction
      magnitude = Math.sqrt(sx2*sx2 + sy2*sy2);
      let [d2x,d2y] = [sx2/magnitude*railWidth,sy2/magnitude*railWidth];
      context.moveTo(x1+d1y, y1-d1x);
      context.lineTo(x2+d2y, y2-d2x);
      context.moveTo(x1-d1y, y1+d1x);
      context.lineTo(x2-d2y, y2+d2x);
      context.lineWidth = 5;
      context.strokeStyle = "dimgray";
      context.stroke();
      context.closePath();
      context.restore();
    }
    //draw rail ties. Same way to find position as drawing train
    let currDist  = 0;
    while(currDist < traveledDist){
      let u = calculateArc(currDist);
      let i = Math.floor(u);
      context.save();
      context.fillStyle = "sienna";
      context.translate(locate(u-i, i, 0), locate(u-i, i, 1));
      context.rotate(Math.atan2(speed(u-i, i, 1), speed(u-i, i, 0))-Math.PI/2);
      context.fillRect(-6, -3, 12, 6);
      context.restore();
      currDist += traveledDist/50;
    }
  }

  // Draw the train
  for (let c = 0; c < 5; c ++){ // let's give 5 cabins
      let u;
      let currDistance = traveledDist*param/len; // distance at each percent of the whole track
      // calculate u which is the position on each segment accordingly
      if (arclength.checked) 
        u = calculateArc((currDistance-c*52+traveledDist) % traveledDist); // push back by c*52 (52 looks the best) otherwise it will be drew on same position
      else 
        u = (param-c/5+len) % len;
      let i = Math.floor(u);
      // Draw cabins
      context.save();
      context.fillStyle = "LightSkyBlue";
      context.translate(locate(u-i, i, 0), locate(u-i, i, 1));
      // rotation angle is the angle between Y-velocity and X-velocity
      let angle = Math.atan2(speed(u-i, i, 1), speed(u-i, i, 0));
      context.rotate(angle);
      context.fillRect(-20, -10, 40, 20);
      context.strokeRect(-20, -10, 40, 20);
      // connector
      context.fillStyle = "LightSeaGreen";
      context.beginPath();
      context.arc(-25, 0, 5, 0, 2 * Math.PI);
      context.fill();
      context.stroke();
      context.closePath();
      context.beginPath();
      context.arc(25, 0, 5, 0, 2 * Math.PI);
      context.closePath();
      context.fill();
      context.stroke();
      // Add feature of high-speed train (bullet head)
      if (c == 0){
          context.beginPath();
          context.fillStyle = "LightSkyBlue";
          context.moveTo(20, 10);
          context.quadraticCurveTo(50, 0, 20, -10);
          context.fill();
          context.stroke();
          context.closePath();
          // Add headlight
          context.beginPath();
          context.fillStyle = "coral";
          context.moveTo(35, 3);
          context.lineTo(60,8);
          context.lineTo(60,-8);
          context.lineTo(35,-3);
          context.fill();
          context.closePath();
      }
      if (c == 4){
          context.beginPath();
          context.fillStyle = "LightSkyBlue";
          context.moveTo(-20, 10);
          context.quadraticCurveTo(-50, 0, -20, -10);
          context.fill();
          context.stroke();
          context.closePath();
          // Add taillight
          context.beginPath();
          context.fillStyle = "red";
          context.arc(-25, 5, 2, 0, Math.PI);
          context.arc(-25, -5, 2, Math.PI, 0);
          context.fill();
          context.closePath();
      }
      context.restore();
  }

}

/**
 * Setup stuff - make a "window.onload" that sets up the UI and starts
 * the train
 */
let oldOnLoad = window.onload;
window.onload = function() {
  let canvas = /** @type {HTMLCanvasElement} */ (document.getElementById(
    "canvas"
  ));
  let context = canvas.getContext("2d");
  // we need the slider for the draw function, but we need the draw function
  // to create the slider - so create a variable and we'll change it later
  let slider; // = undefined;

  // note: we wrap the draw call so we can pass the right arguments
  function wrapDraw() {
    // do modular arithmetic since the end of the track should be the beginning
    draw(canvas, Number(slider.value) % thePoints.length);
  }
  // create a UI
  let runcavas = new RunCanvas(canvas, wrapDraw);
  // now we can connect the draw function correctly
  slider = runcavas.range;

  // this is a helper function that makes a checkbox and sets up handlers
  // it sticks it at the end after everything else
  // you could also just put checkboxes into the HTML, but I found this more
  // convenient
  function addCheckbox(name, initial = false) {
    let checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox");
    document.getElementsByTagName("body")[0].appendChild(checkbox);
    checkbox.id = name;
    checkbox.onchange = wrapDraw;
    checkbox.checked = initial;
    let checklabel = document.createElement("label");
    checklabel.setAttribute("for", name);
    checklabel.innerText = name;
    document.getElementsByTagName("body")[0].appendChild(checklabel);
  }
  // note: if you add these features, uncomment the lines for the checkboxes
  // in your code, you can test if the checkbox is checked by something like:
  // document.getElementById("simple-track").checked
  // in your drawing code
  //
  // lines to uncomment to make checkboxes
  addCheckbox("simple-track",false);
  addCheckbox("arc-length",true);
  // addCheckbox("bspline",false);

  // helper function - set the slider to have max = # of control points
  function setNumPoints() {
    runcavas.setupSlider(0, thePoints.length, 0.02);
  }

  setNumPoints();
  runcavas.setValue(0);

  // add the point dragging UI
  draggablePoints(canvas, thePoints, wrapDraw, 10, setNumPoints);
};
