 /*    
 *    Copyright (c) 2013 Anton Bilan, Easy Bits Limited
 *    http://easy-bits.com
 *    This file is part of TIMELINE THUMBNAILS PLUGIN FOR VIDEOJS PLAYER.
 *
 *    This is closed source commercial software:
 *    reverse-engineering, disassembly or modifications are not allowed
 *
 *    This software is distributed WITHOUT ANY WARRANTY;
 *    even the implied warranty of MERCHANTABILITY or FITNESS FOR 
 *    A PARTICULAR PURPOSE.
 *
 */
(function(){var q=false;var p;var o;var n;var m=0;function l(){q=true;p=easybits_Helper();o=easybits_getHTTPMultiStreaming();n=p.time();for(var b=0;b<easybits_FplScrubber.loaders.length;b++){o.addLoader(easybits_FplScrubber.loaders[b])}}function j(e,d){if(typeof d=="undefined"){d="*"}var f=[],c;var b=document.getElementsByTagName(d);for(var c in b){if((" "+b[c].className+" ").indexOf(e)>-1){f.push(b[c])}}return f}function i(d){var b=function(r){for(var k in r){r[k].style.zIndex=-1;r[k].style.width="0px";r[k].style.height="0px"}};var g=function(){var k=j(h.mp4Id,"video");b(k);k=j(h.mp4Id,"canvas");b(k)};var f=function(r,k){g();r.element.style.width=r.renderWidth+"px";r.element.style.height=r.renderHeight+"px";r.element.style.position="absolute";r.element.style.zIndex=1000;r.element.style.left=(k+10)+"px";r.element.style.bottom="60px";r.element.style.visibility="visible"};var e="easybits_swfContainer_"+m++;var c=document.createElement("div");c.id=e;d.q.appendChild(c);var h=easybits_mp4({swfgeneratorurl:easybits_FplScrubber.swfgeneratorurl,swfContainerId:e,httpstreamer:o,url:d.url});h.bootstrap(function(){var k=d.q.getElementsByTagName("div");for(var r in k){if((" "+k[r].className+" ").indexOf("vjs-progress-control")>-1){var s=k[r]}}p.addEvent(s,"mouseout",g);p.addEvent(s,"mousemove",function(u){if(p.time()-n<100){return}else{n=p.time()}var t=u.offsetX==undefined?u.layerX:u.offsetX;if(t<0){t=0}var v=Math.round(100*d.video.duration()*(t/s.offsetWidth))/100;h.getFrameAt({sec:v,onFrame:function(w){f(w,t)},width:easybits_FplScrubber.width,height:easybits_FplScrubber.height})})})}window.onload=function(){l();var c=document.getElementsByTagName("video");for(var b=0;b<c.length;b++){a(c,b)}};function a(c,b){var d=c[b].id;d=d.replace("_html5_api","");_V_(d).ready(function(){var e=this;for(var h=0;h<e.f.sources.length;h++){if(typeof e.f=="undefined"||!e.f.hasOwnProperty("sources")){for(var g in e){if(typeof e[g]=="object"&&e[g].hasOwnProperty("sources")){var f=e[g].sources;break}}}else{f=e.f.sources}if(f[h].type=="video/mp4"){i({video:e,url:f[h].src,q:document.getElementById(d)});break}}})}})();