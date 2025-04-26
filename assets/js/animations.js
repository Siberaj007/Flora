$(document).ready(function() {
    // Page Load Animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Smooth Scroll
    $('a[href*="#"]').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 100
        }, 500, 'easeInOutExpo');
    });

    // Product Card Hover Effects
    $('.product-card').hover(
        function() {
            $(this).find('.product-actions').slideDown(200);
        },
        function() {
            $(this).find('.product-actions').slideUp(200);
        }
    );

    // Cart Item Removal Animation
    $('.remove-item').click(function(e) {
        e.preventDefault();
        const cartItem = $(this).closest('.cart-item');
        cartItem.addClass('removing');
        setTimeout(() => cartItem.remove(), 300);
    });

    // Form Validation Animation
    $('form').on('submit', function(e) {
        const invalidInputs = $(this).find(':invalid');
        if (invalidInputs.length > 0) {
            invalidInputs.first().focus();
            invalidInputs.each(function() {
                $(this).parent().addClass('shake');
                setTimeout(() => $(this).parent().removeClass('shake'), 600);
            });
        }
    });

    // Image Gallery Zoom Effect
    $('.product-image img').zoom({
        url: $(this).attr('data-zoom-image'),
        magnify: 1.5
    });

    // Quantity Button Animation
    $('.qty-minus, .qty-plus').on('click', function() {
        $(this).addClass('clicked');
        setTimeout(() => $(this).removeClass('clicked'), 200);
    });

    // Alert Dismissal Animation
    $('.alert').click(function() {
        $(this).fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Category Filter Animation
    $('.filter-option').click(function() {
        const category = $(this).data('category');
        $('.product-card').each(function() {
            if (category === 'all' || $(this).data('category') === category) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });
    });

    // Loading State Animation
    $('.btn-loading').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.html('<span class="loading-spinner"></span>');
        setTimeout(() => btn.html(originalText), 2000);
    });
});
