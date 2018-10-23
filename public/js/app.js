//@prepros-prepend "./modules/popupManager.js";

$(document).ready(function () {
    
    const popupManager = new PopupManager();

    if ($(".news-popup").length > 0) {
        $(".news-popup__slider").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false,
            customPaging: function(){
                return "";
            }
        });
    }
    
});