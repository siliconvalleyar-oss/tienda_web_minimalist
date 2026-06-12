const products = [
    { id: 1, name: "Silla Eames", price: 320, image: "https://picsum.photos/id/20/300/200" },
    { id: 2, name: "Lámpara de pie", price: 185, image: "https://picsum.photos/id/26/300/200" },
    { id: 3, name: "Mesa auxiliar", price: 99, image: "https://picsum.photos/id/30/300/200" },
    { id: 4, name: "Espejo redondo", price: 79, image: "https://picsum.photos/id/89/300/200" }
];

let cart = [];

function renderProducts() {
    const container = document.getElementById('products');
    if (!container) return;
    container.innerHTML = products.map(p => `
        <div class="product-card">
            <img src="${p.image}" alt="${p.name}" style="width:100%; border-radius:0.5rem; margin-bottom:1rem;">
            <h3>${p.name}</h3>
            <div class="price">$${p.price}</div>
            <button onclick="addToCart(${p.id})">Agregar</button>
        </div>
    `).join('');
}

function addToCart(id) {
    const product = products.find(p => p.id === id);
    if (!product) return;
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    updateCartUI();
}

function updateCartUI() {
    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartSpan = document.getElementById('cart-count');
    if (cartSpan) cartSpan.innerText = totalItems;
    const el = document.getElementById('cart-count');
    if (el) {
        el.style.transform = 'scale(1.2)';
        setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
    }
}

renderProducts();
