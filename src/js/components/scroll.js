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

import GraphTabs from 'graph-tabs';
if (document.querySelectorAll('.tabs')) {
    const tabs = new GraphTabs('tab');
}

let menuBtn = document.querySelector('.menu-btn');
let menu = document.querySelector('.menu');
menuBtn.addEventListener('click', function(){
	menu.classList.toggle('active');
    menuBtn.classList.toggle('active');
})
