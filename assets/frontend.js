jQuery(document).ready(function($) {
    // BuzzHub Frontend JavaScript
    
    // Initialize sliders with a small delay to ensure DOM is ready
    setTimeout(function() {
        // Initialize sliders
        $('.buzzhub-slider-container').each(function() {
            if (!$(this).hasClass('buzzhub-slider-initialized')) {
                $(this).addClass('buzzhub-slider-initialized');
                initSlider($(this));
            }
        });
        
        // Initialize grid sliders
        $('.buzzhub-grid-slider-container').each(function() {
            if (!$(this).hasClass('buzzhub-grid-slider-initialized')) {
                $(this).addClass('buzzhub-grid-slider-initialized');
                initGridSlider($(this));
            }
        });
    }, 100);
    
    function initSlider($container) {
        const $slider = $container.find('.buzzhub-slider');
        const $slides = $slider.find('.buzzhub-slide');
        const $prevBtn = $container.find('.buzzhub-prev');
        const $nextBtn = $container.find('.buzzhub-next');
        const $dots = $container.find('.buzzhub-dot');
        
        let currentSlide = 0;
        const totalSlides = $slides.length;
        let autoplayInterval = null;
        
        console.log('Initializing slider with', totalSlides, 'slides');
        console.log('Slides found:', $slides);
        $slides.each(function(index) {
            console.log('Slide', index, 'content:', $(this).html().substring(0, 100) + '...');
        });
        
        if (totalSlides <= 1) {
            $prevBtn.hide();
            $nextBtn.hide();
            $container.find('.buzzhub-dots').hide();
            console.log('Slider hidden - not enough slides');
            return;
        }
        
        function updateSlider() {
            const translateX = -currentSlide * 100;
            console.log('Moving to slide', currentSlide, 'translateX:', translateX + '%');
            $slider.css('transform', `translateX(${translateX}%)`);
            
            $dots.removeClass('active');
            $dots.eq(currentSlide).addClass('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }
        
        function startAutoplay() {
            if ($container.data('autoplay') === 'true' || $container.data('autoplay') === true) {
                const speed = parseInt($container.data('speed')) || 5000;
                autoplayInterval = setInterval(nextSlide, speed);
            }
        }
        
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }
        
        // Event listeners
        $nextBtn.on('click', function() {
            stopAutoplay();
            nextSlide();
            startAutoplay();
        });
        
        $prevBtn.on('click', function() {
            stopAutoplay();
            prevSlide();
            startAutoplay();
        });
        
        $dots.on('click', function() {
            stopAutoplay();
            currentSlide = $(this).index();
            updateSlider();
            startAutoplay();
        });
        
        // Pause autoplay on hover
        $container.on('mouseenter', stopAutoplay);
        $container.on('mouseleave', startAutoplay);
        
        // Initialize
        updateSlider();
        startAutoplay();
    }
    
    function initGridSlider($container) {
        const $slider = $container.find('.buzzhub-grid-slider');
        const $slides = $slider.find('.buzzhub-grid-slide');
        const $prevBtn = $container.find('.buzzhub-prev');
        const $nextBtn = $container.find('.buzzhub-next');
        const $dots = $container.find('.buzzhub-dot');
        
        let currentSlide = 0;
        const totalSlides = $slides.length;
        let autoplayInterval = null;
        
        console.log('Initializing grid slider with', totalSlides, 'slides');
        console.log('Grid slides found:', $slides);
        $slides.each(function(index) {
            console.log('Grid slide', index, 'content:', $(this).html().substring(0, 100) + '...');
        });
        
        if (totalSlides <= 1) {
            $prevBtn.hide();
            $nextBtn.hide();
            $container.find('.buzzhub-dots').hide();
            console.log('Grid slider hidden - not enough slides');
            return;
        }
        
        function updateGridSlider() {
            const translateX = -currentSlide * 100;
            console.log('Moving to grid slide', currentSlide, 'translateX:', translateX + '%');
            $slider.css('transform', `translateX(${translateX}%)`);
            
            $dots.removeClass('active');
            $dots.eq(currentSlide).addClass('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateGridSlider();
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateGridSlider();
        }
        
        function startAutoplay() {
            if ($container.data('autoplay') === 'true' || $container.data('autoplay') === true) {
                const speed = parseInt($container.data('speed')) || 5000;
                autoplayInterval = setInterval(nextSlide, speed);
            }
        }
        
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }
        
        // Event listeners
        $nextBtn.on('click', function() {
            stopAutoplay();
            nextSlide();
            startAutoplay();
        });
        
        $prevBtn.on('click', function() {
            stopAutoplay();
            prevSlide();
            startAutoplay();
        });
        
        $dots.on('click', function() {
            stopAutoplay();
            currentSlide = $(this).index();
            updateGridSlider();
            startAutoplay();
        });
        
        // Pause autoplay on hover
        $container.on('mouseenter', stopAutoplay);
        $container.on('mouseleave', startAutoplay);
        
        // Initialize
        updateGridSlider();
        startAutoplay();
    }
}); 

// Read More/Less functionality
function buzzhubToggleText(button) {
    const reviewContent = button.closest('.buzzhub-review-content');
    const shortText = reviewContent.querySelector('.buzzhub-text-short');
    const fullText = reviewContent.querySelector('.buzzhub-text-full');
    
    if (shortText && fullText) {
        if (fullText.style.display === 'none') {
            // Show full text
            shortText.style.display = 'none';
            fullText.style.display = 'inline';
            button.textContent = 'Read Less';
        } else {
            // Show short text
            shortText.style.display = 'inline';
            fullText.style.display = 'none';
            button.textContent = 'Read More';
        }
    }
}

// View More functionality
function buzzhubLoadMoreReviews(containerId) {
    const container = document.getElementById(containerId);
    const button = container.querySelector('.buzzhub-view-more-btn');
    const args = JSON.parse(container.dataset.args);
    const offset = parseInt(container.dataset.offset);
    
    // Disable button and show loading state
    button.disabled = true;
    button.textContent = 'Loading...';
    
    // Prepare data for AJAX request
    const data = new FormData();
    data.append('action', 'buzzhub_load_more_reviews');
    data.append('args', JSON.stringify(args));
    data.append('offset', offset);
    data.append('nonce', buzzhub_ajax.nonce);
    
    // Make AJAX request
    fetch(buzzhub_ajax.ajaxurl, {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Get the reviews container
            const reviewsContainer = container.querySelector('.buzzhub-reviews');
            
            // Append new reviews
            reviewsContainer.insertAdjacentHTML('beforeend', result.data.html);
            
            // Update offset
            container.dataset.offset = result.data.new_offset;
            
            // Hide button if no more reviews
            if (!result.data.has_more) {
                button.style.display = 'none';
            } else {
                button.disabled = false;
                button.textContent = 'View More Reviews';
            }
        } else {
            button.textContent = 'No more reviews';
            button.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error loading more reviews:', error);
        button.disabled = false;
        button.textContent = 'View More Reviews';
    });
}