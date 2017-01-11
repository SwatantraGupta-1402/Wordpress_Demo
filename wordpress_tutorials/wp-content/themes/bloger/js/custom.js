jQuery(document).ready(function($){
        //Home Slider
    $('.home_slider_header').owlCarousel({
    stagePadding: 86.4,
    loop:true,
    margin:0,
    nav:false,
    responsive:{
        0:{
            items:1,
            stagePadding:0,
        },
        600:{
            items:1,
            stagePadding:0,
        },
        1280:{
            items:1,
           stagePadding: 86.4,                                            
        },
        1366:{
            items:1,
            stagePadding: 97,            
        },
        1920:{
            items:1,            
            stagePadding: 376,
                    
        }        
    }
});
//Sickey Sidebar
$('#secondary, #primary').theiaStickySidebar();

$('.menu-toggle').toggle(function() {
    $('ul.nav-menu').addClass('menu-open');
}, function(){
    $('ul.nav-menu').removeClass('menu-open');
});

// for scroll top
$(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
        $('.move_to_top_bloger').fadeIn();
    } else {
        $('.move_to_top_bloger').fadeOut();
    }
});

// scroll body to 0px on click
$('.move_to_top_bloger').click(function() {
    $('body,html').animate({
        scrollTop: 0
    }, 800);
    return false;
});
});