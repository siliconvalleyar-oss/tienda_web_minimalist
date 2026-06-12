gsap.registerPlugin(ScrollTrigger);

gsap.from(".hero-carousel .carousel-item.active .carousel-caption h2", {
    opacity: 0, y: 30, duration: 0.8, ease: "power3.out"
});
gsap.from(".hero-carousel .carousel-item.active .carousel-caption p", {
    opacity: 0, y: 15, duration: 0.6, delay: 0.2, ease: "power3.out"
});

gsap.from(".product-card", {
    scrollTrigger: { trigger: ".products", start: "top 85%", toggleActions: "play none none reverse" },
    opacity: 0, y: 40, duration: 0.5, stagger: 0.08, ease: "power2.out"
});

const circle = document.createElement('div');
circle.style.position = 'fixed';
circle.style.width = '24px';
circle.style.height = '24px';
circle.style.borderRadius = '50%';
circle.style.backgroundColor = 'rgba(0,0,0,0.04)';
circle.style.pointerEvents = 'none';
circle.style.zIndex = '9999';
circle.style.transition = 'transform 0.15s ease, background-color 0.2s ease';
circle.style.transform = 'translate(-50%, -50%)';
document.body.appendChild(circle);

document.addEventListener('mousemove', (e) => {
    circle.style.left = e.clientX + 'px';
    circle.style.top = e.clientY + 'px';
});

document.querySelectorAll('button, a, .product-card').forEach(el => {
    el.addEventListener('mouseenter', () => {
        circle.style.transform = 'translate(-50%, -50%) scale(2)';
        circle.style.backgroundColor = 'rgba(0,0,0,0.06)';
    });
    el.addEventListener('mouseleave', () => {
        circle.style.transform = 'translate(-50%, -50%) scale(1)';
        circle.style.backgroundColor = 'rgba(0,0,0,0.04)';
    });
});
