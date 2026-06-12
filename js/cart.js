let cart = JSON.parse(localStorage.getItem('tienda_cart') || '[]');
let allProducts = [];
let allCategories = [];
let currentPage = 1;
let totalPages = 1;
let currentCategory = 0;
let currentSearch = '';

function loadProducts(page, category, search) {
    currentPage = page || 1;
    currentCategory = category || 0;
    currentSearch = search || '';

    const params = new URLSearchParams({
        page: currentPage,
        per_page: 8,
        category: currentCategory,
        search: currentSearch
    });

    fetch(`api/products.php?${params}`)
        .then(r => r.json())
        .then(data => {
            allProducts = data.products;
            allCategories = data.categories;
            totalPages = data.total_pages;
            renderProducts(allProducts);
            renderPagination(data);
            renderCategories(allCategories);
            updateCartUI();
        });
}

function renderProducts(products) {
    const container = document.getElementById('products');
    if (!container) return;

    if (products.length === 0) {
        container.innerHTML = '<div class="empty-state">No se encontraron productos.</div>';
        return;
    }

    container.innerHTML = products.map(p => `
        <div class="product-card" data-product-id="${p.id}">
            <div class="product-img-wrap">
                <img src="${p.image}" alt="${p.name}" loading="lazy">
                ${p.stock < 5 ? '<span class="stock-badge">Quedan ${p.stock}</span>' : ''}
            </div>
            <div class="product-info">
                <span class="product-category">${p.category_name}</span>
                <h3 class="product-name">${p.name}</h3>
                <p class="product-desc">${p.description ? p.description.substring(0, 60) + '…' : ''}</p>
                <div class="product-bottom">
                    <span class="price">$${parseFloat(p.price).toFixed(2)}</span>
                    <button class="btn add-cart" data-id="${p.id}" data-name="${p.name}" data-price="${p.price}" data-image="${p.image}">Agregar</button>
                </div>
            </div>
        </div>
    `).join('');

    document.querySelectorAll('.add-cart').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            addToCart({
                id: parseInt(this.dataset.id),
                name: this.dataset.name,
                price: parseFloat(this.dataset.price),
                image: this.dataset.image
            });
        });
    });
}

function renderPagination(data) {
    const container = document.getElementById('pagination');
    if (!container) return;
    if (data.total_pages <= 1) { container.innerHTML = ''; return; }

    let html = '';
    for (let i = 1; i <= data.total_pages; i++) {
        html += `<button class="page-btn ${i === data.page ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }
    container.innerHTML = html;

    container.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            loadProducts(parseInt(btn.dataset.page), currentCategory, currentSearch);
        });
    });
}

function renderCategories(categories) {
    const container = document.getElementById('categoryFilter');
    if (!container) return;

    let html = `<button class="cat-btn ${currentCategory === 0 ? 'active' : ''}" data-cat="0">Todas</button>`;
    categories.forEach(c => {
        html += `<button class="cat-btn ${currentCategory === c.id ? 'active' : ''}" data-cat="${c.id}">${c.name}</button>`;
    });
    container.innerHTML = html;

    container.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            loadProducts(1, parseInt(btn.dataset.cat), currentSearch);
        });
    });
}

function addToCart(product) {
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    saveCart();
    updateCartUI();
    showToast(`${product.name} agregado al carrito`);
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    updateCartUI();
    renderCartModal();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    saveCart();
    updateCartUI();
    renderCartModal();
}

function getCartTotal() {
    return cart.reduce((sum, item) => sum + item.price * item.qty, 0);
}

function getCartCount() {
    return cart.reduce((sum, item) => sum + item.qty, 0);
}

function saveCart() {
    localStorage.setItem('tienda_cart', JSON.stringify(cart));
}

function updateCartUI() {
    const count = getCartCount();
    const el = document.getElementById('cart-count');
    if (el) {
        el.textContent = count;
        el.style.transform = 'scale(1.3)';
        setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
    }
}

function showToast(msg) {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.className = 'toast show';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => { toast.className = 'toast'; }, 2000);
}

function renderCartModal() {
    const list = document.getElementById('cartItems');
    const total = document.getElementById('cartTotal');
    if (!list) return;

    if (cart.length === 0) {
        list.innerHTML = '<div class="cart-empty">Tu carrito está vacío</div>';
        if (total) total.textContent = '$0.00';
        return;
    }

    list.innerHTML = cart.map(item => `
        <div class="cart-item" data-id="${item.id}">
            <img src="${item.image}" alt="${item.name}" class="cart-item-img">
            <div class="cart-item-info">
                <span class="cart-item-name">${item.name}</span>
                <span class="cart-item-price">$${(item.price * item.qty).toFixed(2)}</span>
            </div>
            <div class="cart-item-qty">
                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                <span>${item.qty}</span>
                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
            </div>
            <button class="cart-remove" onclick="removeFromCart(${item.id})">✕</button>
        </div>
    `).join('');
    if (total) total.textContent = `$${getCartTotal().toFixed(2)}`;
}

function toggleCart() {
    const modal = document.getElementById('cartModal');
    if (modal) {
        modal.classList.toggle('open');
        renderCartModal();
    }
}

loadProducts(1, 0, '');
