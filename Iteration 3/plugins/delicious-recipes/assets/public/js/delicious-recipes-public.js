jQuery(document).ready(function ($) {
	// Bind Click Handler to Link, then Open Gallery
	$(".del-recipe-gallery-link").on("click", function () {
		$(this).next().magnificPopup("open");
	});

	// Initialize Magnific Popup Gallery + Options
	$(".del-recipe-gallery").each(function () {
		$(this).magnificPopup({
			delegate: "a",
			gallery: {
				enabled: true,
			},
			type: "image",
		});
	});

	// Select all links with hashes
	$('a.dr-smooth-scroll')
		// Remove links that don't actually link to anything
		.not('[href="#"]')
		.not('[href="#0"]')
		.on("click", function (event) {
			// On-page links
			if (
				location.pathname.replace(/^\//, "") ==
				this.pathname.replace(/^\//, "") &&
				location.hostname == this.hostname
			) {
				// Figure out element to scroll to
				var target = $(this.hash);
				target = target.length
					? target
					: $("[name=" + this.hash.slice(1) + "]");
				// Does a scroll target exist?
				if (target.length) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$("html, body").animate(
						{
							scrollTop: target.offset().top,
						},
						1000,
						function () {
							// Callback after animation
							// Must change focus!
							// var $target = $(target);
							// $target.focus();
							// if ($target.is(":focus")) {
							// 	// Checking if the target was focused
							// 	return false;
							// } else {
							// 	$target.attr("tabindex", "-1"); // Adding tabindex for elements not focusable
							// 	$target.focus(); // Set focus again
							// }
						}
					);
				}
			}
		});

	$(function () {
		//configuring math.js

		math.config({
			number: "number",
		});

		$(".dr-scale-ingredients").on("keyup mouseup", function () {
			var new_servings = $(this).val();
			new_servings = math.number(new_servings);
			//console.log('New Servings', new_servings);

			// Get the full address from the original link
			var default_print_lnk = $("a.dr-print-trigger").attr("href");

			if ($(".dr-print-trigger").length > 0) {
				// split it to get the actual file address
				var default_print_attrs = default_print_lnk.split("?");
				// This may need something more complex...
				var new_print_attrs =
					"print_recipe=true&recipe_servings=" + new_servings;
				// This changes the href of the link to the new one.
				$("a.dr-print-trigger").attr(
					"href",
					default_print_attrs[0] + "?" + new_print_attrs
				);
			}

			var recipe = $(this).data("recipe");

			var original_servings = $(this).data("original");
			original_servings = math.number(original_servings);
			//console.log('Original Servings', original_servings);

			$(".ingredient_quantity[data-recipe=" + recipe + "]").each(
				function (index) {
					var quantity = $(this).data("original");
					if (!isFinite(quantity)) {
						try {
							quantity = math.fraction(quantity);
						} catch (e) {
							// console.log(e);
							return;
						}
						// console.log(
						// 	"Fractional Quantity",
						// 	math.format(quantity, { fraction: "ratio" })
						// );

						var new_quantity = math.eval(
							(quantity / original_servings) * new_servings
						);
						//console.log('New Decimal', new_quantity);

						new_quantity = math.fraction(new_quantity);
						//console.log('New Fractional', new_quantity);

						if (
							new_quantity.n == new_quantity.d ||
							new_quantity.d == 1
						) {
							jQuery(this).text(new_quantity.n);
							//console.log('New Quantity =', jQuery(this).text());
						} else if (new_quantity.n > new_quantity.d) {
							var i = parseInt(new_quantity.n / new_quantity.d);
							new_quantity.n -= i * new_quantity.d;
							jQuery(this).text(
								i +
								" " +
								" " +
								new_quantity.n +
								"/" +
								new_quantity.d
							);
							//console.log('New Quantity >', jQuery(this).text());
						} else {
							jQuery(this).text(
								math.format(new_quantity, { fraction: "ratio" })
							);
							//console.log('New Quantity', jQuery(this).text());
						}
					} else {
						try {
							quantity = math.number(quantity);
						} catch (e) {
							// console.log(e);
							return;
						}
						// console.log("Numeric Quantity", quantity);

						var new_quantity = math.eval(
							new_servings * (quantity / original_servings)
						);
						new_quantity = math.format(new_quantity, {
							fraction: "decimal",
							precision: 4,
						});
						0 != new_quantity && jQuery(this).text(new_quantity);
						// console.log("New Quantity", jQuery(this).text());
					}
				}
			);
		});

		$(".dr-instruction-videopop").magnificPopup({
			type: "iframe",
			iframe: {
				markup:
					'<div class="mfp-iframe-scaler">' +
					'<div class="mfp-close"></div>' +
					'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
					"</div>",
				patterns: {
					youtube: {
						index: "youtube.com/",
						id: "v=",
						src: "//www.youtube.com/embed/%id%?autoplay=1",
					},
					vimeo: {
						index: "vimeo.com/",
						id: "/",
						src: "//player.vimeo.com/video/%id%?autoplay=1",
					},
				},
				srcAction: "iframe_src",
			},
		});

		$("#dr-vdo-toggle").on("change", function (e) {
			if ($(this).is(":checked")) {
				$(".dr-instructions-video").slideDown("slow");
			} else {
				$(".dr-instructions-video").slideUp("slow");
			}
		});

		$(document)
			.find(".dr-comment-form-rating")
			.each(function () {
				$(this).rateYo({
					halfStar: true,
					onChange: function (rating, rateYoInstance) {
						// $('.comment-rating-field-message').hide();
						$(this)
							.parent()
							.find('input[name="rating"]')
							.val(rating);
					},
				});
			});
	});

	var rtl;
	if ($('body').hasClass('rtl')) {
		rtl = true;
	} else {
		rtl = false;
	}
	$(".dr-post-carousel").owlCarousel({
		items: 3,
		autoplay: false,
		loop: false,
		nav: true,
		dots: false,
		rewind: true,
		margin: 30,
		autoplaySpeed: 800,
		autoplayTimeout: 3000,
		rtl: rtl,
		navText: [
			'<svg xmlns="http://www.w3.org/2000/svg" width="18.479" height="12.689" viewBox="0 0 18.479 12.689"><g transform="translate(17.729 11.628) rotate(180)"><path d="M7820.11-1126.021l5.284,5.284-5.284,5.284" transform="translate(-7808.726 1126.021)" fill="none" stroke="#232323" stroke-linecap="round" stroke-width="1.5"/><path d="M6558.865-354.415H6542.66" transform="translate(-6542.66 359.699)" fill="none" stroke="#232323" stroke-linecap="round" stroke-width="1.5"/></g></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="18.479" height="12.689" viewBox="0 0 18.479 12.689"><g transform="translate(0.75 1.061)"><path d="M7820.11-1126.021l5.284,5.284-5.284,5.284" transform="translate(-7808.726 1126.021)" fill="none" stroke="#232323" stroke-linecap="round" stroke-width="1.5"/><path d="M6558.865-354.415H6542.66" transform="translate(-6542.66 359.699)" fill="none" stroke="#232323" stroke-linecap="round" stroke-width="1.5"/></g></svg>',
		],
		responsive: {
			0: {
				items: 1,
			},
			768: {
				items: 2,
			},
			1025: {
				items: 3,
			},
		},
	});

	// Search Filters
	$(".js-select2").select2({
		closeOnSelect: false,
		placeholder: delicious_recipes.search_placeholder,
		allowClear: true,
	});

	$("body").on("change", ".dr-search-field select", function () {
		var choices = {};
		$(".dr-search-field select option").each(function () {
			if ($(this).is(":selected")) {
				if (!choices.hasOwnProperty($(this).attr("name"))) {
					choices[$(this).attr("name")] = [];
				}
				var idx = $.inArray(this.value, choices[$(this).attr("name")]);
				if (idx == -1) {
					choices[$(this).attr("name")].push(this.value);
				}
			}
		});

		nonce = $("#dr-search-nonce").val();

		jQuery.ajax({
			url: delicious_recipes.ajax_url,
			data: {
				action: "recipe_search_results",
				search: choices,
				nonce: nonce,
			},
			dataType: "json",
			type: "post",
			beforeSend: function () {
				$(".dr-search-item-wrap").addClass("dr-loading");
			},
			success: function (response) {
				if (response.success) {
					var template = wp.template("search-block-tmp");
					$(".dr-search-item-wrap").html(
						template(response.data.results)
					);
					$(".navigation.pagination .nav-links")
						.addClass("dr-ajax-paginate")
						.html(response.data.pagination);
				}
			},
			complete: function () {
				$(".dr-search-item-wrap").removeClass("dr-loading");
			},
		});
	});

	$(document).on("click", ".dr-ajax-paginate a.page-numbers", function (e) {
		e.preventDefault();
		var choices = {};
		$(".dr-search-field select option").each(function () {
			if ($(this).is(":selected")) {
				if (!choices.hasOwnProperty($(this).attr("name"))) {
					choices[$(this).attr("name")] = [];
				}
				var idx = $.inArray(this.value, choices[$(this).attr("name")]);
				if (idx == -1) {
					choices[$(this).attr("name")].push(this.value);
				}
			}
		});

		nonce = $("#dr-search-nonce").val();

		jQuery.ajax({
			url: delicious_recipes.ajax_url,
			data: {
				action: "recipe_search_results",
				search: choices,
				nonce: nonce,
				paged: $(this).attr("href").split("=")[1],
			},
			dataType: "json",
			type: "post",
			beforeSend: function () {
				$(".dr-search-item-wrap").addClass("dr-loading");
			},
			success: function (response) {
				if (response.success) {
					var template = wp.template("search-block-tmp");
					$(".dr-search-item-wrap").html(
						template(response.data.results)
					);
					$(".navigation.pagination .nav-links")
						.addClass("dr-ajax-paginate")
						.html(response.data.pagination);
				}
			},
			complete: function () {
				$(".dr-search-item-wrap").removeClass("dr-loading");
			},
		});
	});

	//show/hide social share
	$(".post-share a.meta-title").on("click", function (e) {
		e.stopPropagation();
		$(this).siblings(".social-networks").slideToggle();
	});

	$(".post-share").on("click", function (e) {
		e.stopPropagation();
	});

	$("body, html").on("click", function () {
		$(".post-share .social-networks").slideUp();
	});

	//pull recipe category title left
	$(".dr-category a, .post-navigation article .dr-category > span").each(
		function () {
			var recipeCatWidth = $(this).width();
			var recipeCatTitleWidth = $(this)
				.children(".cat-name")
				.outerWidth();
			var catPullValue =
				(parseInt(recipeCatTitleWidth) - parseInt(recipeCatWidth)) / 2;
			$(this).children(".cat-name").css("left", -catPullValue);
			if ($('body').hasClass('rtl')) {
				$(this).children('.cat-name').css({
					'left': 'auto',
					'right': -catPullValue,
				});
			} else {
				$(this).children(".cat-name").css("left", -catPullValue);
			}
		}
	);

	/** Ajax call for recipe like */
	$(document).on("click", ".dr_like__recipe", function (e) {
		e.preventDefault();
		var container = $(this);
		id = container.attr("id").split("-").pop();

		if (container.hasClass('recipe-liked')) {
			container.removeClass('recipe-liked');
			container.addClass("like-recipe");
			var addRemove = 'remove';
		} else {
			container.removeClass("like-recipe");
			container.addClass('recipe-liked');
			var addRemove = 'add';
		}

		$.ajax({
			type: "post",
			url: delicious_recipes.ajax_url,
			data: { action: "recipe_likes", add_remove: addRemove, id: id },
			beforeSend: function () {
				container.addClass("loading");
			},
			success: function (data) {
				container.attr("title", data.data.likes);
				container.find('.dr-likes-total').html(data.data.likes_count);
			},
		}).done(function () {
			container.removeClass("loading");
		});
	});

	/****   Wishlist a Recipe   ****/
	if ($('.dr-recipe-wishlist span.dr-bookmark-wishlist').length) {
		$(document).on('click', '.dr-recipe-wishlist span.dr-bookmark-wishlist', function (e) {

			e.preventDefault();
			var thisHeart = $(this),
				recipeID = thisHeart.data('recipe-id');

			if (thisHeart.hasClass('dr-wishlist-is-bookmarked')) {
				thisHeart.removeClass('dr-wishlist-is-bookmarked');
				var addRemove = 'remove';
			} else {
				thisHeart.addClass('dr-wishlist-is-bookmarked');
				var addRemove = 'add';
			}

			$.ajax({
				type: "post",
				url: delicious_recipes.ajax_url,
				data: { action: "delicious_recipes_wishlist", add_remove: addRemove, recipe_id: recipeID },
				beforeSend: function () {
					thisHeart.addClass("loading");
				},
				success: function (data) {
					thisHeart.find('.dr-wishlist-total').html(data.data.wishlists);
					thisHeart.find('.dr-wishlist-info').html(data.data.message);
				},
			}).done(function () {
				thisHeart.removeClass("loading");
			});

		});
	}
	if ($('.dr-recipe-wishlist span.dr-popup-user__registration').length) {

		// Bind Click Handler to Wishlist Button, then Open Login Popup
		$(".dr-popup-user__registration").each(function () {
			$(this).magnificPopup({
				items: {
					src: "#dr-user__registration-login-popup", // can be a HTML string, jQuery object, or CSS selector
					type: 'inline'
				},
				closeBtnInside: true
			});
		});

		$(document).on("submit", "form[name='dr-form__log-in']", function (e) {
			e.preventDefault();

			var loginform = $(this);
			var username = loginform.find('input[name="username"]').val();
			var password = loginform.find('input[name="password"]').val();
			var rememberme = loginform.find('input[name="rememberme"]').val();
			var login = loginform.find('input[name="login"]').val();
			var nonce = loginform.find('input[name="delicious_recipes_user_login_nonce"]').val();

			$.ajax({
				url: delicious_recipes.ajax_url,
				data: {
					action: "delicious_recipes_process_login",
					username: username,
					password: password,
					rememberme: rememberme,
					login: login,
					delicious_recipes_user_login_nonce: nonce,
					calling_action: 'delicious_recipes_modal_login'
				},
				dataType: "json",
				type: "post",
				beforeSend: function () {
					loginform.addClass("dr-loading");
				},
				success: function (response) {
					if (response.success) {
						$('.dr-recipe-wishlist > span').removeClass('dr-popup-user__registration');
						$('.dr-recipe-wishlist > span').addClass('dr-bookmark-wishlist');
						$('.delicious-recipes-success-msg').html(response.data.success).show();
						location.reload();
					} else {
						console.log(response.data.error);
						$('.delicious-recipes-error-msg').html(response.data.error).show();
					}
				},
				complete: function () {
					loginform.removeClass("dr-loading");
					// $(".dr-popup-user__registration-open .mfp-close").click();
				},
			});
		});

	}

	$("#dr-recipes-clear-filters").on("click", function (e) {
		e.preventDefault();
		$(".dr-advance-search .advance-search-options select").each(
			function () {
				$(this).val(null).trigger("change");
			}
		);
	});

	// recipe instruction
	$('.dr-inst-mark-read input[type="checkbox"]').each(function () {
		$(this).on('click', function () {
			if ($(this).prop('checked') == true) {
				$(this).parents('li').addClass('dr-instruction-checked');
			} else {
				$(this).parents('li').removeClass('dr-instruction-checked');
			}
		})
	})

	$("form[name='dr-form__log-in']").parsley();

	$(document).on('click', '.dr-ud_tab', function () {
		$('.dr-ud__sidebar').removeClass('collapsed');
	})

}); //document close
