gsap.registerPlugin(ScrollTrigger);
gsap.from(".hero h2", { opacity: 0, y: 40, duration: 1, ease: "power2.out" });
gsap.from(".hero p", { opacity: 0, y: 20, duration: 0.8, delay: 0.3 });
gsap.from(".product-card", {
    scrollTrigger: { trigger: ".products", start: "top 80%", toggleActions: "play none none reverse" },
    opacity: 0, y: 50, duration: 0.6, stagger: 0.1, ease: "back.out(0.6)"
});
const circle = document.createElement('div');
circle.style.position = 'fixed';
circle.style.width = '30px';
circle.style.height = '30px';
circle.style.borderRadius = '50%';
circle.style.backgroundColor = 'rgba(0,0,0,0.03)';
circle.style.pointerEvents = 'none';
circle.style.zIndex = '9999';
circle.style.transition = 'transform 0.1s ease-out';
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
