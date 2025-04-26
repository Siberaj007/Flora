$(document).ready(function() {
    // Enhanced Preloader
    const preloader = $('.preloader');
    $(window).on('load', function() {
        preloader.fadeOut(800);
        setTimeout(() => preloader.remove(), 1000);
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

    // Enhanced Add to Cart with Error Handling
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        if (btn.prop('disabled')) return;

        btn.prop('disabled', true);
        const productId = btn.data('id');
        const quantity = btn.closest('.product-card').find('.qty-input').val() || 1;

        $.ajax({
            url: 'ajax/add-to-cart.php',
            type: 'POST',
            data: { product_id: productId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateCartCount(response.cart_count);
                    showNotification('Success', 'Product added to cart!', 'success');
                    animateCartIcon();
                } else {
                    showNotification('Error', response.message || 'Failed to add product', 'error');
                }
            },
            error: function() {
                showNotification('Error', 'Failed to add product to cart', 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });

    function updateCartCount(count) {
        const cartCount = $('.cart-count');
        cartCount.text(count);
        
        if (count > 0) {
            cartCount.addClass('animate__animated animate__bounceIn');
            setTimeout(() => cartCount.removeClass('animate__animated animate__bounceIn'), 1000);
        }
    }

    function animateCartIcon() {
        const cartIcon = $('.cart-icon');
        cartIcon.addClass('animate__animated animate__rubberBand');
        setTimeout(() => cartIcon.removeClass('animate__animated animate__rubberBand'), 1000);
    }

    // Improved Error Handling
    function handleError(error) {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    }

    // Enhanced Notification System
    function showNotification(message, type = 'success') {
        const notification = $(`
            <div class="notification ${type} animate__animated animate__fadeInRight">
                <div class="notification-content">
                    <i class="fas ${getNotificationIcon(type)}"></i>
                    <p>${message}</p>
                </div>
                <div class="notification-progress"></div>
            </div>
        `).appendTo('body');
        
        setTimeout(() => {
            notification.removeClass('animate__fadeInRight')
                       .addClass('animate__fadeOutRight');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Helper Functions
    function getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    // Product Gallery
    $('.thumbnail').on('click', function() {
        const imgSrc = $(this).find('img').attr('src');
        $('.main-image img').fadeOut(200, function() {
            $(this).attr('src', imgSrc).fadeIn(200);
        });
    });

    // Back to Top
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('fast');
        } else {
            $('.back-to-top').fadeOut('fast');
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

    // Add smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate(
            { scrollTop: $($(this).attr('href')).offset().top },
            800
        );
    });

    // Floating Action Button
    $('.floating-action-btn').on('click', function() {
        window.location.href = '#contact';
    });

    // Enhanced Hero Slider
    const heroSlider = {
        slides: $('.hero-slider .slide'),
        dots: $('.slider-dots .dot'),
        currentSlide: 0,
        isAnimating: false,
        autoPlayInterval: null,

        init: function() {
            this.bindEvents();
            this.startAutoPlay();
        },

        bindEvents: function() {
            $('.next-slide').on('click', () => this.nextSlide());
            $('.prev-slide').on('click', () => this.prevSlide());
            this.dots.on('click', (e) => this.goToSlide($(e.target).index()));

            // Pause autoplay on hover
            $('.hero-slider').hover(
                () => clearInterval(this.autoPlayInterval),
                () => this.startAutoPlay()
            );
        },

        goToSlide: function(index) {
            if (this.isAnimating) return;
            this.isAnimating = true;

            this.slides.fadeOut(600).removeClass('active');
            this.dots.removeClass('active');

            this.slides.eq(index).fadeIn(600).addClass('active');
            this.dots.eq(index).addClass('active');

            this.currentSlide = index;
            setTimeout(() => this.isAnimating = false, 600);
        },

        nextSlide: function() {
            const next = (this.currentSlide + 1) % this.slides.length;
            this.goToSlide(next);
        },

        prevSlide: function() {
            const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            this.goToSlide(prev);
        },

        startAutoPlay: function() {
            this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
        }
    };

    // Initialize slider
    heroSlider.init();

    // Add lazy loading for images
    $('img').each(function() {
        const img = $(this);
        img.attr('loading', 'lazy');
    });

    // Fixed quick navigation scroll
    $('.quick-nav-item').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });

    // Parallax effect for hero section
    $(window).scroll(function() {
        const scrolled = $(window).scrollTop();
        $('.hero.parallax').css('background-position-y', -(scrolled * 0.5) + 'px');
    });

    // Enhanced Header Behavior
    const header = $('.header');
    let lastScroll = 0;
    
    $(window).scroll(function() {
        const currentScroll = $(this).scrollTop();
        
        // Add/remove scrolled class
        if (currentScroll > 50) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
        
        // Hide/show header on scroll
        if (currentScroll > lastScroll && currentScroll > 200) {
            header.css('transform', 'translateY(-100%)');
        } else {
            header.css('transform', 'translateY(0)');
        }
        
        lastScroll = currentScroll;
    });

    // Fix Mega Menu Position
    function adjustMegaMenuPosition() {
        $('.mega-menu').each(function() {
            const dropdown = $(this).closest('.dropdown');
            const dropdownOffset = dropdown.offset();
            const windowWidth = $(window).width();
            
            if (dropdownOffset.left + $(this).width() > windowWidth) {
                $(this).css({
                    'left': 'auto',
                    'right': '0',
                    'transform': 'none'
                });
            }
        });
    }

    $(window).on('resize', adjustMegaMenuPosition);
    adjustMegaMenuPosition();

    // Header scroll effect
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.header').addClass('scrolled');
        } else {
            $('.header').removeClass('scrolled');
        }
    });

    // Fix mega menu position
    $('.dropdown').hover(function() {
        const menuWidth = $(this).find('.mega-menu').outerWidth();
        const windowWidth = $(window).width();
        const offset = $(this).offset().left;
        
        if (offset + menuWidth > windowWidth) {
            $(this).find('.mega-menu').css({
                'left': 'auto',
                'right': '0'
            });
        }
    });

    // Live Search
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                $.ajax({
                    url: 'search-suggestions.php',
                    method: 'GET',
                    data: { q: query },
                    success: function(response) {
                        $('#search-suggestions').html(response).addClass('active');
                    }
                });
            }, 300);
        } else {
            $('#search-suggestions').removeClass('active');
        }
    });

    // Close search suggestions on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-form').length) {
            $('#search-suggestions').removeClass('active');
        }
    });

    // Header transparency on scroll
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.header-transparent').removeClass('header-transparent');
        } else if ($(this).scrollTop() === 0) {
            $('.header').addClass('header-transparent');
        }
    });

    // Enhanced Search
    $('.search-trigger').on('click', function() {
        $('.search-overlay').addClass('active');
        setTimeout(() => $('#search-input').focus(), 100);
    });
    
    $('.close-search').on('click', function() {
        $('.search-overlay').removeClass('active');
    });
    
    // Close search on escape key
    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            $('.search-overlay').removeClass('active');
        }
    });

    // Booking Form Enhancements
    // Budget Slider
    const budgetSlider = $('#budget');
    const budgetValue = $('#budget-value');
    
    budgetSlider.on('input', function() {
        const value = $(this).val();
        budgetValue.text(new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(value));
    });

    // Package Selection Animation
    $('.option-card').on('click', function() {
        $('.option-card').removeClass('selected');
        $(this).addClass('selected');
    });

    // Form Validation
    $('#booking-form').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Date Picker Validation
    const eventDate = $('#event-date');
    const today = new Date().toISOString().split('T')[0];
    eventDate.attr('min', today);
});
