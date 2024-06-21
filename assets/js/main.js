$(function(){


	var slider = $("#slider");
    slider.owlCarousel({
        singleItem: true,
        items: 1,
        slideSpeed: 1000,
        pagination: false,
        navigation: false,
        autoPlay: 3500,
        dots: false,
        nav: false,
        navigationText: ['<i class="solok"></i>', '<i class="sagok"></i>'],
        responsiveRefreshRate: 200
    });

    var hikaye = $("#hikaye");
    hikaye.owlCarousel({
        items: 10,
        slideSpeed: 1000,
        pagination: false,
        navigation: true,
        autoPlay: 3500,
        dots: false,
        nav: true,
        navigationText: ['<i class="solok"></i>', '<i class="sagok"></i>'],
        responsiveRefreshRate: 200,
        itemsMobile: [600,3]
    });

     $('#hikaye div a').click(function(){
        if($(this).attr('data-buyuk-img') == ''){
            window.location.href = $(this).attr('data-link');
        }else{

            $('#hikaye-popup #icerik').html('<a href="'+$(this).attr('data-link')+'"><img src="upload/'+$(this).attr('data-buyuk-img')+'" style="max-width:600px" class="img-responsive"></a>');
            $('#hikaye-popup').modal('show');

            $('#saniye').animate({
              width: '100%'
            }, 5000, function() {
              $('#hikaye-popup').modal('hide');
              $('#saniye').css('width','0px');
            });

        }
    });

});