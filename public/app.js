$(".blog-carousel").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 3,
        },
    },
});

$(".package-carousel").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    responsive: {
        0: {
            items: 1,
        },
    },
});

$(".head-slider").owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:false,
    responsive: {
        0: {
            items: 1,
        },
    },
});

$(".home-gallery-slider").owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:false,
    responsive: {
        0: {
            items: 1,
        },
    },
});

$(".extra-service-carousel").owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    responsive: {
        0: {
            items: 1,
        },
        768: {
            items: 2,
        },
        991: {
            items: 1,
        },
        1000: {
            items: 2,
        },
    },
});


function openNav() {
    document.getElementById("mySidenav").style.width = "300px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

$('a[href="#logout-form"]').click(function(event) {
    event.preventDefault();
    $("#logout-form").modal({
      escapeClose: false,
      clickClose: false,
      showClose: false
    });
  });


  const isHomepage = window.location.pathname === '/';

//   document.addEventListener('DOMContentLoaded', function() {
//       if (isHomepage) {
//           const whatsappBubble = document.getElementById('whatsapp-bubble-homepage');

//           if (whatsappBubble) {
//               const showBubble = localStorage.getItem('whatsapp_bubble_visible') !== 'false';

//               if (showBubble) {
//                   whatsappBubble.style.display = 'block';
//               }

//               whatsappBubble.addEventListener('click', function() {
//                   localStorage.setItem('whatsapp_bubble_visible', 'false');
//                   this.style.display = 'none';
//               });
//           } else {
//               console.error('Element with ID "whatsapp-bubble-homepage" not found.');
//           }
//       } else {
//           const whatsappBubble = document.getElementById('whatsapp-bubble');

//           if (whatsappBubble) {
//               const showBubble = localStorage.getItem('whatsapp_bubble_visible') !== 'false';

//               if (showBubble) {
//                   whatsappBubble.style.display = 'block';
//               }

//               whatsappBubble.addEventListener('click', function() {
//                   localStorage.setItem('whatsapp_bubble_visible', 'false');
//                   this.style.display = 'none';
//               });
//           } else {
//               console.error('Element with ID "whatsapp-bubble" not found.');
//           }
//       }
//   });



