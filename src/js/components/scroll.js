const smoothScrool = () =>
{
    const smoothScrollElems = document.querySelectorAll(
      'a[href^="#"]:not(a[href=""])'
    );

    smoothScrollElems?.forEach( ( link ) =>
		{
			link.addEventListener( 'click', ( event ) =>
				{
					event.preventDefault();
					const id = link?.getAttribute( 'href' ).slice( 1 );

					document.getElementById( id )?.scrollIntoView(
						{
							behavior: 'smooth',
						}
					);
				}
			);
		}
	);
};

smoothScrool();

const scrollToHashOnLoad = () => {
    if (window.location.hash !== '#contact-form') {
        return;
    }

    const target = document.querySelector(window.location.hash);

    if (!target) {
        return;
    }

    window.requestAnimationFrame(() => {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });
    });
};

scrollToHashOnLoad();

import GraphTabs from 'graph-tabs';
if (document.querySelectorAll('.tabs')) {
    const tabs = new GraphTabs('tab');
}

let menuBtn = document.querySelector('.menu-btn');
let menu = document.querySelector('.menu');
let menuLinks = document.querySelectorAll('.menu a');

if (menuBtn && menu) {
    menuBtn.addEventListener('click', function(){
	    menu.classList.toggle('active');
        menuBtn.classList.toggle('active');
    });

    menuLinks.forEach((link) => {
        link.addEventListener('click', function() {
            menu.classList.remove('active');
            menuBtn.classList.remove('active');
        });
    });
}
