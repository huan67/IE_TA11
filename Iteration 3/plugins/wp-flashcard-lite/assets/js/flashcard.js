var $ = jQuery;
jQuery(window).load(function () {
    jQuery('.full-page').hide();
    jQuery('.flashcard_set-container').show();
    register_carousel();

    // start over button
    $(document).on('click', '.start-over-flashcard', function (e) {
        e.preventDefault();
        $('.owl-carousel').trigger('to.owl.carousel', [0, 1, true]);
    });

    // re inintiate carasoul and flip
    function register_carousel() {
        $(document).on('click', '.flashcard_set-container .owl-prev,.flashcard_set-container .owl-next', function () {
            $(this).addClass('click_disabled');
        });

        $('.owl-carousel').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            navText: ['<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="arrow-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-arrow-left fa-w-14 fa-2x"><path fill="currentColor" d="M229.9 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L94.569 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H94.569l155.13-155.13c4.686-4.686 4.686-12.284 0-16.971L229.9 38.101c-4.686-4.686-12.284-4.686-16.971 0L3.515 247.515c-4.686 4.686-4.686 12.284 0 16.971L212.929 473.9c4.686 4.686 12.284 4.686 16.971-.001z" class=""></path></svg>', '<div class="navControl progressIndex"><span class="UIText"><span class="current-cards-slider-pages">1</span>/<span class="cards-slider-pages"></span></span></div><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="arrow-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-arrow-right fa-w-14 fa-2x"><path fill="currentColor" d="M218.101 38.101L198.302 57.9c-4.686 4.686-4.686 12.284 0 16.971L353.432 230H12c-6.627 0-12 5.373-12 12v28c0 6.627 5.373 12 12 12h341.432l-155.13 155.13c-4.686 4.686-4.686 12.284 0 16.971l19.799 19.799c4.686 4.686 12.284 4.686 16.971 0l209.414-209.414c4.686-4.686 4.686-12.284 0-16.971L235.071 38.101c-4.686-4.687-12.284-4.687-16.97 0z" class=""></path></svg>'],
            items: 1,
            dots: false,
            smartSpeed: 450,
            onInitialized: function (event) {
                var page_count = Math.ceil(event.item.count / event.page.size);
                page_count = parseInt(page_count - 1);
                $('.cards-slider-pages').html(page_count);
                $('.flashcard_set-container .owl-prev').after($('.flashcard_set-container .navControl'));
            },
            onChanged: function (event) {
                var page_count = Math.ceil(event.item.count / event.page.size);
                page_count = parseInt(page_count - 1);
                var current = parseInt(event.item.index) + 1;
                if (current <= page_count) {
                    $('.current-cards-slider-pages').html(current);
                }
                
                setTimeout(function(){
                    $('.flashcard_set-container .owl-prev,.flashcard_set-container .owl-next').removeClass('click_disabled');
                },500);
                
            }
        });
        $(".card-item").flip({
            axis: "x",
            trigger: "click",
            speed: '250',
            autoSize: false
        });

        //scrollbar
        jQuery('.flashcard_set .card-text').scrollbar();
    }

});

