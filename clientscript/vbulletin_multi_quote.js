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
vB_XHTML_Ready.subscribe(function(){mq_init("posts")});function mq_init(D){var C=mq_get_selected();var A=YAHOO.util.Dom.getElementsByClassName("multiquote","a",D);for(var B=0;B<A.length;B++){A[B].onclick=function(F){return mq_click(this.id.substr(3))};var E=A[B].id.substr(3);set_mq_highlight(E,(PHP.in_array(E,C)>-1))}}function mq_get_selected(){var A=fetch_cookie("vbulletin_multiquote");if(A!=null&&A!=""){A=A.split(",")}else{A=new Array()}return A}function mq_click(F){var D=mq_get_selected();var B=new Array();var E=false;for(C in D){if(!YAHOO.lang.hasOwnProperty(D,C)){continue}if(D[C]==F){E=true}else{if(D[C]){B.push(D[C])}}}set_mq_highlight(F,!E);if(!E){B.push(F);if(typeof mqlimit!="undefined"&&mqlimit>0){for(var C=0;C<(B.length-mqlimit);C++){var A=B.shift();set_mq_highlight(A,false)}}}set_cookie("vbulletin_multiquote",B.join(","));return false}function set_mq_highlight(C,B){var A=(is_ie6?"gif":"png");if(B){YAHOO.util.Dom.addClass("mq_"+C,"highlight");YAHOO.util.Dom.get("mq_image_"+C).src=IMGDIR_BUTTON+"/multiquote-selected_40b."+A}else{YAHOO.util.Dom.removeClass("mq_"+C,"highlight");YAHOO.util.Dom.get("mq_image_"+C).src=IMGDIR_BUTTON+"/multiquote_40b."+A}}function mq_unhighlight_all(){var B=fetch_tags(fetch_object("posts"),"img");for(var A=0;A<B.length;A++){if(B[A].id&&B[A].id.substr(0,9)=="mq_image_"){set_mq_highlight(B[A].id.substr(9),false)}}};