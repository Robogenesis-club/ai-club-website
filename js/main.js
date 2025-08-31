// main.js — GSAP + small UI behaviors + accordion

// GSAP ScrollReveal for sections
window.addEventListener('load', () => {
  if (window.gsap && window.gsap.registerPlugin) {
    gsap.registerPlugin(gsap.ScrollTrigger || {});
    gsap.utils.toArray('section').forEach(sec => {
      gsap.from(sec, {
        scrollTrigger: {
          trigger: sec,
          start: 'top 80%',
          toggleActions: 'play none none none',
        },
        opacity: 0,
        y: 36,
        duration: 0.9,
        ease: 'power2.out',
        stagger: 0.08
      });
    });
  }
});

// Simple accordion for objectives
document.addEventListener('DOMContentLoaded', () => {
  const headers = document.querySelectorAll('.accordion-header');
  headers.forEach(h => {
    h.addEventListener('click', () => {
      const body = h.nextElementSibling;
      const open = body.style.display === 'block';
      // close all
      document.querySelectorAll('.accordion-body').forEach(b => b.style.display = 'none');
      if (!open) body.style.display = 'block';
    });
  });

  // AOS init is triggered from index.php after load
});

// Optional: add small nav active-link on scroll
(function navActiveOnScroll(){
  const links = document.querySelectorAll('.nav-links a');
  const sections = Array.from(links).map(a => document.querySelector(a.getAttribute('href')));
  function onScroll(){
    const sc = window.scrollY + window.innerHeight/3;
    let idx = sections.findIndex(sec => sec && sc <= sec.offsetTop + sec.offsetHeight);
    if(idx === -1) idx = sections.length -1;
    links.forEach((a,i)=> a.classList.toggle('active', i === idx));
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
})();