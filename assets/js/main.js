$(document).ready(function() {
    // Preloader
    $(window).on('load', function() {
        $('.preloader').fadeOut('slow');
    });

    // Mobile Menu Toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.mobile-menu').addClass('active');
    });

    $('.close-menu').on('click', function() {
        $('.mobile-menu').removeClass('active');
    });

    // Mobile Dropdown Toggle
    $('.mobile-dropdown > a').on('click', function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('active');
    });

    // Quantity Controls
    $('.qty-minus').on('click', function() {
        var input = $(this).siblings('.qty-input');
        var value = parseInt(input.val());
        if (value > 1) {
            input.val(value - 1);
        }
    });

    $('.qty-plus').on('click', function() {
        var input = $(this).siblings('.qty-input');
        var value = parseInt(input.val());
        var max = parseInt(input.attr('max'));
        if (value < max) {
            input.val(value + 1);
        }
    });

    // Add to Cart
    $('.add-to-cart').on('click', function() {
        var productId = $(this).data('id');
        var quantity = $(this).closest('.product-actions').find('.qty-input').val() || 1;

        $.post('add-to-cart.php', {
            product_id: productId,
            quantity: quantity
        }, function(response) {
            if (response.success) {
                $('.cart-count').text(response.cart_count);
                alert('Product added to cart!');
            } else {
                alert(response.message || 'Failed to add product to cart.');
            }
        }, 'json');
    });

    // Product Gallery
    $('.thumbnail').on('click', function() {
        $('.thumbnail').removeClass('active');
        $(this).addClass('active');
        var imgSrc = $(this).find('img').attr('src');
        $('.main-image img').attr('src', imgSrc);
    });

    // Back to Top
    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    $('.back-to-top').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 'slow');
    });

    // Product Tabs
    $('.nav-tabs li').on('click', function() {
        var tab = $(this).data('tab');
        $('.nav-tabs li').removeClass('active');
        $(this).addClass('active');
        $('.tab-pane').removeClass('active');
        $('#' + tab).addClass('active');
    });

    // Checkout Steps
    $('.next-step').on('click', function() {
        var nextStep = $(this).data('next');
        $('.step-content').removeClass('active');
        $('.step-content[data-step="' + nextStep + '"]').addClass('active');
        $('.checkout-steps .step').removeClass('active');
        $('.checkout-steps .step[data-step="' + nextStep + '"]').addClass('active');
    });

    $('.prev-step').on('click', function() {
        var prevStep = $(this).data('prev');
        $('.step-content').removeClass('active');
        $('.step-content[data-step="' + prevStep + '"]').addClass('active');
        $('.checkout-steps .step').removeClass('active');
        $('.checkout-steps .step[data-step="' + prevStep + '"]').addClass('active');
    });

    // Payment Method Tabs
    $('.payment-tabs .tab').on('click', function() {
        var tab = $(this).data('tab');
        $('.payment-tabs .tab').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').removeClass('active');
        $('.tab-content[data-tab="' + tab + '"]').addClass('active');
    });

    // Budget Range Slider
    $('#budget').on('input', function() {
        $('#budget-value').text($(this).val());
    });
});
