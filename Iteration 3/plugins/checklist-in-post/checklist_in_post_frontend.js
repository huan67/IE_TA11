/*
Enqueued values from checklist_in_post.php

options.cookies
*/

( function( $ ) {
	"use strict";

	$(document).ready(function() {

		//Edit .checklist-list ul appearance
		$('.checklist_in_post > ul').wrap("<form class='checklist-list'>");

		var $id = 1;
		$('.checklist_in_post > form.checklist-list > ul > li').each(function() {
			var $text = $(this).html() ;
			$(this).html(' ');
			$(this).prepend("<span class='checklist-wrap'><input type=checkbox id='"+ $id +"' /><label for='"+ $id +"' class='checklist-label'><i class='fa fa-check'></i>"+ $text +"</label></span>");
			$id = $id+1;
		});

		
		var $checkedli = $('.checklist_in_post li');
		$checkedli.on('change', function(e){
			$(this).toggleClass('checklist-checked');
			$(this).find('.checklist-label').toggleClass('checklist-label-checked');
			var index = $checkedli.index($(this));
			toggleCookie('checkedLi[' + index + ']');
		});


		//Cookies
		//Check cookie option
		if(options.cookies){
			//Default cookies or existing one for li's:
			$checkedli.each(function(index){
				if(checkCookie('checkedLi[' + index + ']')) {
					$(this).toggleClass( 'todo-checked' );
					$(this).find('.checklist-label').toggleClass('checklist-label-checked');
				}
			});
		}

		/**
		 * Function for getting path of current site
		 * @returns {string}
		 */
		function getAbsolutePath() {
			var loc = window.location;
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			//return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
			return pathName;
		}

		/**
		 * Cookie create function
		 *
		 * @param cname Cookie Name
		 * @param cvalue Cookie Value
		 * @param exdays Expires Date
		 */
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+ d.toUTCString();
			var pathName = getAbsolutePath();
			console.log(pathName);
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=" + pathName;
		}

		/**
		 * Cookie get function
		 * @param cname
		 * @returns {*}
		 */
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}

		/**
		 * Function to check if cookie is 1 or 0
		 * @param cname
		 */
		function toggleCookie(cname) {
			console.log('toggleCookie ' + cname);
			var cookie = getCookie(cname);
			if (cookie === "1") {
				//Change to 0
				setCookie(cname, 0, 0);

			} else {
				setCookie(cname, 1, 365);
			}
		}

		/**
		 * Function to check cookie fast
		 * @param cname
		 * @returns {boolean}
		 */
		function checkCookie(cname){
			console.log('checkCookie ' + cname);
			var cookie = getCookie(cname);
			if(cookie === "1"){
				return true;
			}
			return false;
		}


	} );

})( jQuery );