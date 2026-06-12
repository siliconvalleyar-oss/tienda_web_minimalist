gsap.registerPlugin(ScrollTrigger);

gsap.from(".hero-carousel .carousel-item.active .carousel-caption h2", {
    opacity: 0, y: 30, duration: 0.8, ease: "power3.out"
});
gsap.from(".hero-carousel .carousel-item.active .carousel-caption p", {
    opacity: 0, y: 15, duration: 0.6, delay: 0.2, ease: "power3.out"
});

gsap.from(".product-card", {
    scrollTrigger: { trigger: ".products", start: "top 80%", toggleActions: "play none none reverse" },
    opacity: 0, y: 50, duration: 0.6, stagger: 0.1, ease: "back.out(0.7)"
});

const circle = document.createElement('div');
circle.style.position = 'fixed';
circle.style.width = '30px';
circle.style.height = '30px';
circle.style.borderRadius = '50%';
circle.style.backgroundColor = 'rgba(0,0,0,0.03)';
circle.style.pointerEvents = 'none';
circle.style.zIndex = '9999';
circle.style.transition = 'transform 0.15s ease-out, background-color 0.2s ease';
document.body.appendChild(circle);
document.addEventListener('mousemove', (e) => {
    circle.style.left = e.clientX - 15 + 'px';
    circle.style.top = e.clientY - 15 + 'px';
});
document.querySelectorAll('button, a').forEach(el => {
    el.addEventListener('mouseenter', () => {
        circle.style.transform = 'scale(2)';
        circle.style.backgroundColor = 'rgba(0,0,0,0.05)';
    });
    el.addEventListener('mouseleave', () => {
        circle.style.transform = 'scale(1)';
        circle.style.backgroundColor = 'rgba(0,0,0,0.03)';
    });
});
