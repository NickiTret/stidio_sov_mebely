import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import {TweenLite} from "gsap";


const texts = Array.from(document.querySelectorAll('.text'));
const items = document.querySelectorAll(".data");
const circles = document.querySelectorAll('.circle-anim');

const splitText = (el) => {
	el.innerHTML = el.textContent.replace(/(\S*)/g, m => {
  return `<div class="word">` +
			m.replace(/(-|#|@)?\S(-|#|@)?/g, "<div class='letter'>$&</div>") +
			`</div>`;
	});
	return el;
};

const split = texts.forEach((el) => {
    const split = splitText(el);

    function random(min, max){
        return (Math.random() * (max - min)) + min;
      }

      Array.from(split.querySelectorAll('.letter')).forEach((el, idx) => {
        TweenLite.from(el, 2.5, {
              opacity: 0,
              scale: .3,
              x: random(-500, 500),
              y: random(-500, 500),
              z: random(-500, 500),
              delay: idx * 0.05,
              repeat: 0,
          })
      });
});

gsap.from(items, {
  textContent: 0,
  duration: 4,
  snap: { textContent: 1 },
  stagger: 1,
});




