
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

let tl_1 = gsap.timeline();
let tl_2 = gsap.timeline();
let tl_4 = gsap.timeline();
let tl_5 = gsap.timeline();



tl_1
  .from(".header", { duration: 1, opacity: 0 })
  .from(".hero h1", { duration: 1, x: "100%", opacity: 0 })
  .from(".hero h4", { duration: 1, x: "100%", opacity: 0 }, '-=.75')
  .from(".hero p", { duration: 1, x: "100%", opacity: 0 }, '-=1')
  .from(".hero .btn", { duration: 1, x: "100%", opacity: 0 }, '-=1')



tl_2
  .from(".individual__image", { duration: 1, x: "-100%", opacity: 0 })
  .from(".individual__text", { duration: 1, x: "100%", opacity: 0 }, "-=1")

ScrollTrigger.create({
  animation: tl_2,
  trigger: ".individual",
  start: "top top",
  end: "bottom",
  scrub: true,
  pin: true,
});

tl_4
  .from(".realish h2", { duration: 1, y: "-100%", opacity: 0 })

  .from(".realish__image", { duration: 1, x: "-100%", opacity: 0 })

  .from(".realish__text", { duration: 1, x: "100%", opacity: 0 });

ScrollTrigger.create({
  animation: tl_4,
  trigger: ".realish",
  start: "top top",
  end: "bottom",
  scrub: true,
  pin: true,
});

let newsItem = document.querySelectorAll(".posts__item");

newsItem.forEach((el) => {
  tl_5.from(el, { duration: .1 , y: "-100%", opacity: 0 });
});

