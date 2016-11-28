var videos = document.getElementsByTagName("video"),
fraction = 0.8;
var fullpage = document.getElementsByClassName("fullpage"),
fraction = 0.8;
var headertext = document.getElementsByClassName("headertext");
var stile = "top=10, left=10, width=320, height=240, status=no, menubar=no, toolbar=no scrollbars=no";

//rendiamo automatica la gestione dei link di share
function sharer(){
var url = location.href;
document.getElementById("twitter").href = "http://twitter.com/home?status=Share "+url;
document.getElementById("facebook").href = "http://www.facebook.com/sharer.php?u="+url+"&amp;title=Myarticle";
document.getElementById("stumble").href = "http://www.stumbleupon.com/refer.php?url="+url+"&amp;title=Myarticle";
document.getElementById("digg").href = "http://digg.com/submit?phase=2&amp;url="+url+"&amp;title=Myarticle";
document.getElementById("delicious").href = "http://del.icio.us/post?url="+url+"&amp;title=Myarticle";
}


function popitup(url, mystile) {
  var newstile;
  if (mystile=="none") {
    newstile = stile;
  } else {
    newstile = mystile;
  }
  newwindow=window.open(url, 'name', newstile);
  if (window.focus) {newwindow.focus()}
  return false;
}


function playf(){
  var audiobuttons = document.getElementById("audiobuttons").src;
  var sitourl = audiobuttons.substr(0, audiobuttons.lastIndexOf("/")+1);
  
  if (audiobuttons.substr(audiobuttons.lastIndexOf("/")+1) == "play.png") {
    document.getElementById("audiobuttons").src = "pause.png";
    document.getElementById("audiofiles").play();
  } else {
    document.getElementById("audiobuttons").src = "play.png";
    document.getElementById("audiofiles").pause();
  }
  
}

function checkScroll() {
  
  iw = "";
  iw = (window.innerWidth)/4;
  headertext[0].style.fontSize = iw+"%";
  
  
  
  for(var i = 0; i < videos.length; i++) {
    
    var video = videos[i];
    
    var x = video.offsetLeft, y = video.offsetTop, w = video.offsetWidth, h = video.offsetHeight, r = x + w, //right
    b = y + h, //bottom
    visibleX, visibleY, visible;
    
    visibleX = Math.max(0, Math.min(w, window.pageXOffset + window.innerWidth - x, r - window.pageXOffset));
    visibleY = Math.max(0, Math.min(h, window.pageYOffset + window.innerHeight - y, b - window.pageYOffset));
    
    visible = visibleX * visibleY / (w * h);
    
    if (visible > fraction) {
      video.play();
    } else {
      video.pause();
    }
    
  }
  
  for(var i = 0; i < fullpage.length; i++) {
    
    var felement = fullpage[i];
    
    var x = felement.offsetLeft, y = felement.offsetTop, w = felement.offsetWidth, h = felement.offsetHeight, r = x + w, //right
    b = y + h, //bottom
    visibleX, visibleY, visible;
    
    visibleX = Math.max(0, Math.min(w, window.pageXOffset + window.innerWidth - x, r - window.pageXOffset));
    visibleY = Math.max(0, Math.min(h, window.pageYOffset + window.innerHeight - y, b - window.pageYOffset));
    
    visible = visibleX * visibleY / (w * h);
    
    if (visible > fraction) {
      felement.style.opacity = 1; //For real browsers;
      felement.style.filter = "alpha(opacity=100)"; //For IE;
    } else {
      felement.style.opacity = visible; //For real browsers;
      op = (visible)*100;
      felement.style.filter = "alpha(opacity="+op+")"; //For IE;
    }
    
  }
  
}

window.addEventListener('scroll', checkScroll, false);
window.addEventListener('resize', checkScroll, false);