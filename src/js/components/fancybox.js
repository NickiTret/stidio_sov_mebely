import { Fancybox, Carousel, Panzoom } from "@fancyapps/ui";

const $ = require( "jquery" )( window );


  Fancybox.bind('[data-fancybox="gallery"]', {
    caption: function (fancybox, carousel, slide) {
      return (
        `${slide.index + 1} / ${carousel.slides.length} <br />` + slide.caption
      );    
    },
  });