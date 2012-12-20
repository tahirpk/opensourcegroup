/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.0 Patch Level 3
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
vBulletin.events.systemInit.subscribe(function(){if(vBulletin.elements.vB_Lightbox_Container){for(var B=0;B<vBulletin.elements.vB_Lightbox_Container.length;B++){var A=vBulletin.elements.vB_Lightbox_Container[B];init_postbit_lightbox(A[0],A[1])}vBulletin.elements.vB_Lightbox_Container=null}});var Lightboxes=new Array();var Lightbox_overlay=null;var Lightbox_overlay_select_handler=null;var Lightbox_event_default=null;var Lightbox_current=null;var Lightbox_map={};function vB_Lightbox(B,C,D,A){this.minborder=100;this.mindimension=50;this.event_click=1;this.event_hover=2;this.click_triggered=false;this.events_enabled=false;this.element=B;this.timeout=null;this.imageloader=null;this.status=0;this.active=false;this.ajax_req=null;this.cursor=null;this.link=null;this.date=null;this.time=null;this.name=null;this.html=null;this.loader_link=null;this.loader_height=null;this.loader_width=null;this.lightbox=null;this.closebtn=null;this.img=null;this.uniqueid=C;this.containerid=D;if(A&this.event_hover){YAHOO.util.Event.on(this.element,"mouseover",this.countdown,this,true);YAHOO.util.Event.on(this.element,"mouseout",this.halt,this,true)}if(A&this.event_click){YAHOO.util.Event.on(this.element,"click",this.image_click,this,true)}}vB_Lightbox.prototype.set_status=function(A,B){console.log("vB_Lightbox :: Set status = %d (%s)",A,B);this.status=A};vB_Lightbox.prototype.check_status=function(A){if(this.status>=A){return true}else{console.warn("Checked status for %d, found %d",A,this.status);return false}};vB_Lightbox.prototype.countdown=function(A){if(!this.active){this.set_status(1,"countdown");this.cursor=YAHOO.util.Dom.getStyle(this.element,"cursor");this.element.style.cursor="wait";this.click_triggered=false;this.timeout=setTimeout("Lightboxes['"+this.uniqueid+"'].load_lightbox();",1500)}};vB_Lightbox.prototype.halt=function(A){if(this.status<2){this.set_status(0,"halt")}clearTimeout(this.timeout);this.element.style.cursor=this.cursor};vB_Lightbox.prototype.image_click=function(A){if(A.ctrlKey||A.shiftKey){return true}this.click_triggered=true;this.load_lightbox(A)};vB_Lightbox.prototype.load_lightbox=function(E){if(this.check_status(0)&&!YAHOO.util.Connect.isCallInProgress(this.ajax_req)){this.set_status(2,"load_lightbox 1");if(Lightbox_current&&Lightbox_current.loader_link){Lightbox_current.img.src=Lightbox_current.loader_link;Lightbox_current.img.width=Lightbox_current.loader_width;Lightbox_current.img.height=Lightbox_current.loader_height;center_element(Lightbox_current.lightbox)}if(E){YAHOO.util.Event.stopEvent(E)}if(this.timeout){clearTimeout(this.timeout);this.element.style.cursor=this.cursor}if(this.html==null){var A=this.element.getAttribute("href");var B=A.substr(A.indexOf("?")+1)+"&securitytoken="+SECURITYTOKEN+"&ajax=1&uniqueid="+this.uniqueid;if(Lightbox_map[this.containerid][this.uniqueid+1]==null){B=B+"&last=1"}if(Lightbox_map[this.containerid][this.uniqueid-1]==null){B=B+"&first=1"}B=B+"&total="+Lightbox_map[this.containerid].size();B=B+"&current="+(Lightbox_map[this.containerid].find(this.uniqueid)+1);this.show_overlay();try{this.ajax_req=YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url(A),{success:this.handle_ajax_response,failure:this.handle_ajax_error,scope:this,timeout:vB_Default_Timeout},B)}catch(E){var D=A.substr(0,A.indexOf("?"));var C;if(C=D.match(/\/([^/]*attachment\.php)$/)){this.ajax_req=YAHOO.util.Connect.asyncRequest("POST",fetch_ajax_url(C[1]),{success:this.handle_ajax_response,failure:this.handle_ajax_error,scope:this,timeout:vB_Default_Timeout},B)}else{if(this.click_triggered){window.location=A}}}}else{this.set_status(3,"load_lightbox 2");this.show_lightbox()}}};vB_Lightbox.prototype.handle_ajax_error=function(A){vBulletin_AJAX_Error_Handler(A);if(this.click_triggered){window.location=this.element.getAttribute("href")}};vB_Lightbox.prototype.handle_ajax_response=function(C){if(!this.check_status(2)){return }if(C.responseXML){var E=C.responseXML.getElementsByTagName("error");if(E.length){this.set_status(0,"handle_ajax_response - error");if(E[0].firstChild.nodeValue=="notimage"){console.warn("Attempted to load non-image (.%s) into lightbox. Aborted.",C.responseXML.getElementsByTagName("extension")[0].firstChild.nodeValue)}else{alert(E[0].firstChild.nodeValue.replace(/<(\/|[a-z]+)[^>]+>/g,""))}return false}var B=C.responseXML.getElementsByTagName("link");if(B.length){this.set_status(3,"handle_ajax_response - success");this.show_overlay();this.link=B[0].firstChild.nodeValue;this.imageloader=new Image();YAHOO.util.Event.on(this.imageloader,"load",this.show_lightbox,this,true);var D=new Array("date","time","name","html");for(var A=0;A<D.length;A++){this[D[A]]=C.responseXML.getElementsByTagName(D[A])[0].firstChild.nodeValue}this.lightbox=document.body.appendChild(string_to_node(this.html));this.closebtn=YAHOO.util.Dom.get("lightboxbutton"+this.uniqueid);YAHOO.util.Event.on(this.closebtn,"click",this.hide_lightbox,this,true);this.prevbtn=YAHOO.util.Dom.get("lightboxprevbutton"+this.uniqueid);YAHOO.util.Event.on(this.prevbtn,"click",this.prev_lightbox,this,true);this.nextbtn=YAHOO.util.Dom.get("lightboxnextbutton"+this.uniqueid);YAHOO.util.Event.on(this.nextbtn,"click",this.next_lightbox,this,true);YAHOO.util.Event.on(YAHOO.util.Dom.get("lightboxlink"+this.uniqueid),"click",this.hide_lightbox,this,true);this.img=YAHOO.util.Dom.get("lightboximg"+this.uniqueid);this.loader_link=this.img.src;this.loader_width=this.img.width;this.loader_height=this.img.height;this.imageloader.src=this.link;this.show_lightbox()}else{if(this.click_triggered){window.location=imagelink}}}else{if(this.click_triggered){window.location=imagelink}}};vB_Lightbox.prototype.show_overlay=function(){if(this.check_status(2)){var C=fetch_viewport_info();if(Lightbox_overlay==null){Lightbox_overlay=document.createElement("div");Lightbox_overlay.id="Lightbox_overlay";var A={display:"none",position:"absolute",top:"0px",backgroundColor:"#000000",opacity:0.85,zIndex:1000};if(document.documentElement.dir=="rtl"){A.right="0px"}else{A.left="0px"}for(var B in A){if(YAHOO.lang.hasOwnProperty(A,B)){YAHOO.util.Dom.setStyle(Lightbox_overlay,B,A[B])}}Lightbox_overlay=document.body.appendChild(Lightbox_overlay);Lightbox_overlay_select_handler=new vB_Select_Overlay_Handler(Lightbox_overlay)}YAHOO.util.Dom.setStyle(Lightbox_overlay,"display","");YAHOO.util.Dom.setStyle(Lightbox_overlay,"width",C.dw+"px");YAHOO.util.Dom.setStyle(Lightbox_overlay,"height",C.dh+"px");YAHOO.util.Dom.setXY(Lightbox_overlay,[0,0]);Lightbox_overlay_select_handler.hide()}};vB_Lightbox.prototype.show_lightbox=function(){if(this.check_status(3)){if(Lightbox_current){Lightbox_current.hide_lightbox(false,this,true)}this.show_overlay();if(!this.imageloader.complete&&this.imageloader.readyState!="complete"){YAHOO.util.Event.removeListener(this.imageloader,"load",this.show_lightbox);YAHOO.util.Event.on(this.imageloader,"load",this.show_lightbox,this,true)}else{this.img.src=this.link;this.resize_image();YAHOO.util.Dom.setStyle(this.closebtn,"display","")}YAHOO.util.Dom.setStyle(this.lightbox,"display","inline-block");YAHOO.util.Dom.setStyle(this.lightbox,"zIndex",5000);if(Lightbox_map[this.containerid].size()==1){YAHOO.util.Dom.setStyle(this.prevbtn,"visibility","hidden");YAHOO.util.Dom.setStyle(this.nextbtn,"visibility","hidden")}Lightbox_current=this;this.center_lightbox();this.active=true;this.enable_events()}};vB_Lightbox.prototype.hide_lightbox=function(B,C,A){if(B&&B.type=="keydown"&&B.keyCode!=27){return }if(!A){this.set_status(0,"hide_lightbox")}this.disable_events();this.active=false;YAHOO.util.Dom.setStyle(this.lightbox,"left",0);YAHOO.util.Dom.setStyle(this.lightbox,"display","none");if(!A){YAHOO.util.Dom.setStyle(Lightbox_overlay,"display","none")}Lightbox_overlay_select_handler.show();Lightbox_current=null};vB_Lightbox.prototype.next_lightbox=function(B){var A=null;if(Lightbox_map[this.containerid][this.uniqueid+1]!=null){A=Lightboxes[this.uniqueid+1]}else{A=Lightboxes[Lightbox_map[this.containerid].first()]}A.load_lightbox()};vB_Lightbox.prototype.prev_lightbox=function(B){var A=null;if(Lightbox_map[this.containerid][this.uniqueid-1]!=null){A=Lightboxes[this.uniqueid-1]}else{A=Lightboxes[Lightbox_map[this.containerid].last()]}A.load_lightbox()};vB_Lightbox.prototype.center_lightbox=function(){center_element(this.lightbox,true)};vB_Lightbox.prototype.handle_viewport_change=function(){this.resize_image();this.center_lightbox();this.show_overlay()};vB_Lightbox.prototype.handle_viewport_change_ie=function(){setTimeout("Lightboxes['"+this.uniqueid+"'].handle_viewport_change();",100)};vB_Lightbox.prototype.resize_image=function(){var C=fetch_viewport_info();var A=this.imageloader.width;var B=this.imageloader.height;if(A>C.w-this.minborder){A=C.w-this.minborder;A=(A<this.mindimension?this.mindimension:A);B=Math.ceil(this.imageloader.height*(A/this.imageloader.width))}if(B>C.h-this.minborder){B=C.h-this.minborder;B=(B<this.mindimension?this.mindimension:B);A=Math.ceil(this.imageloader.width*(B/this.imageloader.height))}this.img.setAttribute("width",A);this.img.setAttribute("height",B);this.img.setAttribute("title",this.name+"; \n"+this.imageloader.width+" x "+this.imageloader.height+" (@"+Math.ceil(A/this.imageloader.width*100)+"%)");if(A<this.imageloader.width||B<this.imageloader.height){console.info("vB_Lightbox :: Image original size: %dx%d, resizing to %dx%d",this.imageloader.width,this.imageloader.height,A,B)}};vB_Lightbox.prototype.enable_events=function(){if(!this.events_enabled){YAHOO.util.Event.on(window,"resize",(is_ie?this.handle_viewport_change_ie:this.handle_viewport_change),this,true);YAHOO.util.Event.on(window,"scroll",(is_ie?this.handle_viewport_change_ie:this.handle_viewport_change),this,true);YAHOO.util.Event.on(window,"keydown",this.hide_lightbox,this,true);YAHOO.util.Event.on(Lightbox_overlay,"click",this.hide_lightbox,this,true);this.events_enabled=true}};vB_Lightbox.prototype.disable_events=function(){if(this.events_enabled){YAHOO.util.Event.removeListener(window,"resize",(is_ie?this.handle_viewport_change_ie:this.handle_viewport_change));YAHOO.util.Event.removeListener(window,"scroll",(is_ie?this.handle_viewport_change_ie:this.handle_viewport_change));YAHOO.util.Event.removeListener(window,"keydown",this.hide_lightbox);YAHOO.util.Event.removeListener(Lightbox_overlay,"click",this.hide_lightbox);this.events_enabled=false}};vB_Lightbox_Container=function(){};vB_Lightbox_Container.prototype.size=function(){var B=0;for(var A in this){if(YAHOO.lang.hasOwnProperty(this,A)){B++}}return B};vB_Lightbox_Container.prototype.first=function(){for(var A in this){if(YAHOO.lang.hasOwnProperty(this,A)){return A}}};vB_Lightbox_Container.prototype.last=function(){var B;for(var A in this){if(YAHOO.lang.hasOwnProperty(this,A)){B=A}}return B};vB_Lightbox_Container.prototype.find=function(C){var B=0;for(var A in this){if(YAHOO.lang.hasOwnProperty(this,A)){if(A==C){return B}B++}}return -1};function is_lightbox_element(A){return(typeof (A.getAttribute("rel"))=="string"&&A.getAttribute("rel").match(/Lightbox[_]?(\d*)?/))}function init_postbit_lightbox(D,C,G){var A=userAgent.match(/applewebkit\/([0-9]+)/);if(A&&A[1]<522){return }if(Lightbox_event_default===null){Lightbox_event_default=C}if(typeof (C)=="undefined"||C===false){C=(Lightbox_event_default?Lightbox_event_default:1+2)}var E=YAHOO.util.Dom.getElementsBy(is_lightbox_element,"a",D);for(var B=0;B<E.length;B++){var F=Lightboxes.length;var H=E[B].getAttribute("rel").match(/Lightbox[_]?(\d*)?/).pop();H=(H?H:0);Lightboxes[F]=new vB_Lightbox(E[B],F,H,C);if(!Lightbox_map[H]||G){Lightbox_map[H]=new vB_Lightbox_Container();G=false}Lightbox_map[H][F]=F}};