function CarouselPlace(){
  $('.carousel-place').slick({
    centerMode: true,
    prevArrow: '<i class="fas fa-chevron-left slick-prev" aria-label="Previous" type="button"></i>',
    nextArrow: '<i class="fas fa-chevron-right slick-next" aria-label="Next" type="button"></i>',
    adaptiveHeight: true,
    centerPadding: '80px',
    slidesToShow: 2,
    arrows: true,
    infinite: true,
    variableWidth: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          arrows: true,
          centerMode: true,
          centerPadding: '40px',
          slidesToShow: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          arrows: true,
          centerMode: true,
          centerPadding: '40px',
          slidesToShow: 2
        }
      }
    ]
});
}

document.querySelector('.carousel-item').classList.add('active')