<?php
require_once 'includes/db.php';
$primary = getSetting('primary_color', $pdo);
$bg = getSetting('bg_color', $pdo);
$text = getSetting('text_color', $pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tienda minimalista con diseño elegante, productos seleccionados y envíos a todo el país.">
    <meta name="keywords" content="tienda, minimalista, diseño, productos, ecommerce, decoración">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Animation Web Skill">
    <meta name="theme-color" content="<?= $primary ?>">
    <meta property="og:title" content="Tienda Minimalista">
    <meta property="og:description" content="Productos que combinan estética pura y funcionalidad">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tienda-minimalista.example.com">
    <meta property="og:image" content="https://picsum.photos/id/2/1200/630">
    <meta name="twitter:card" content="summary_large_image">
    <title>Tienda Minimalista — Diseño con alma</title>
    <link rel="canonical" href="https://tienda-minimalista.example.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
    <style>
        :root {
            --primary: <?= $primary ?>;
            --bg: <?= $bg ?>;
            --text: <?= $text ?>;
            --color-border: #eee;
            --color-gray: #888;
            --color-gray-light: #f5f5f7;
            --transition: all 0.3s ease;
            --shadow: 0 4px 20px rgba(0,0,0,0.04);
            --shadow-hover: 0 20px 30px -12px rgba(0,0,0,0.08);
            --radius: 1rem;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Inter',system-ui,-apple-system,sans-serif;
            background:var(--bg); color:var(--text); line-height:1.6;
        }
        /* Header */
        .header {
            position:fixed; top:0; left:0; width:100%;
            display:flex; justify-content:space-between; align-items:center;
            padding:1rem 2rem;
            background:rgba(255,255,255,0.92);
            backdrop-filter:blur(15px); -webkit-backdrop-filter:blur(15px);
            z-index:100; border-bottom:1px solid var(--color-border);
            transition:var(--transition);
        }
        .header.scrolled { box-shadow:0 5px 20px rgba(0,0,0,0.06); }
        .logo-link { font-weight:600; font-size:1.2rem; color:var(--text); text-decoration:none; letter-spacing:-0.5px; transition:var(--transition); }
        .logo-link:hover { opacity:0.7; }
        .nav-list { display:flex; gap:1.5rem; align-items:center; list-style:none; }
        .nav-link {
            color:var(--text); text-decoration:none; font-size:0.9rem;
            position:relative; padding:0.25rem 0; transition:var(--transition);
            cursor:pointer; background:none; border:none; font-family:inherit;
        }
        .nav-link::after {
            content:''; position:absolute; bottom:0; left:0;
            width:0; height:1.5px; background:var(--primary); transition:var(--transition);
        }
        .nav-link:hover::after { width:100%; }
        .menu-toggle { display:none; flex-direction:column; cursor:pointer; gap:4px; background:none; border:none; padding:4px; }
        .menu-toggle span { display:block; width:22px; height:2px; background:var(--text); transition:var(--transition); }
        .cart-link { position:relative; }
        #cart-count {
            display:inline-block; min-width:18px; height:18px; line-height:18px;
            text-align:center; font-size:0.7rem; font-weight:600;
            background:var(--primary); color:#fff; border-radius:50%;
            transition:transform 0.2s;
        }
        /* Hero */
        .hero {
            display:grid; grid-template-columns:2fr 1fr;
            min-height:100vh; margin-top:76px; position:relative; overflow:hidden;
        }
        .hero-image-wrap { position:relative; overflow:hidden; background:#fafafa; }
        .hero-image-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.4s ease; }
        .hero-overlay {
            position:absolute; top:0; left:0; right:0; bottom:0;
            background:linear-gradient(135deg,rgba(0,0,0,0.05) 0%,transparent 100%);
            pointer-events:none;
        }
        .hero-content {
            display:flex; flex-direction:column; justify-content:center;
            padding:5rem 3.5rem; background:var(--bg);
        }
        .hero-content .hero-title { font-size:2.8rem; font-weight:600; letter-spacing:-0.02em; line-height:1.15; margin-bottom:1.2rem; color:var(--text); }
        .hero-content .hero-desc { font-size:1.05rem; line-height:1.6; color:var(--color-gray); margin-bottom:2rem; max-width:90%; }
        .hero-content .btn-explore {
            display:inline-flex; align-items:center; gap:0.8em;
            background:none; border:none; color:var(--text);
            font-size:0.95rem; font-weight:600; letter-spacing:0.6em;
            text-transform:uppercase; cursor:pointer; padding:0;
            transition:var(--transition); font-family:inherit;
        }
        .hero-content .btn-explore::after { content:'→'; display:inline-block; transition:transform 0.3s ease; font-size:1.2rem; }
        .hero-content .btn-explore:hover { color:var(--color-gray); }
        .hero-content .btn-explore:hover::after { transform:translateX(8px); }
        .hero-angles { display:flex; position:absolute; bottom:0; left:0; z-index:10; }
        .hero-angles button {
            padding:1.2rem 1.6rem; background:#000; border:none; cursor:pointer;
            transition:background 0.3s ease; display:flex; align-items:center; justify-content:center;
        }
        .hero-angles button:hover { background:hsl(0,0%,27%); }
        .hero-angles button svg { width:12px; height:20px; stroke:#fff; fill:none; stroke-width:2; transition:transform 0.3s ease; }
        .hero-angles button:hover svg { transform:scale(1.3); }
        /* Shop bar */
        .shop-bar {
            max-width:1200px; margin:3rem auto 1.5rem; padding:0 2rem;
            display:flex; flex-wrap:wrap; gap:1rem; align-items:center;
        }
        .search-box {
            flex:1; min-width:200px; position:relative;
        }
        .search-box input {
            width:100%; padding:0.7rem 1rem 0.7rem 2.5rem;
            border:1px solid var(--color-border); border-radius:2rem;
            font-size:0.9rem; outline:none; transition:var(--transition);
            background:var(--bg); color:var(--text); font-family:inherit;
        }
        .search-box input:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(0,0,0,0.04); }
        .search-box::before {
            content:'🔍'; position:absolute; left:0.9rem; top:50%;
            transform:translateY(-50%); font-size:0.8rem; opacity:0.4;
        }
        .cat-filters { display:flex; flex-wrap:wrap; gap:0.5rem; }
        .cat-btn {
            padding:0.4rem 1rem; border:1px solid var(--color-border);
            border-radius:2rem; background:var(--bg); color:var(--color-gray);
            font-size:0.8rem; cursor:pointer; transition:var(--transition);
            font-family:inherit;
        }
        .cat-btn:hover { border-color:var(--text); color:var(--text); }
        .cat-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); font-weight:500; }
        /* Products grid */
        .products {
            max-width:1200px; margin:0 auto; padding:0 2rem;
            display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1.5rem;
        }
        .product-card {
            background:white; border-radius:1rem; overflow:hidden;
            transition:transform 0.3s ease,box-shadow 0.3s ease;
            border:1px solid #f0f0f0;
        }
        .product-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-hover); }
        .product-img-wrap {
            position:relative; overflow:hidden; height:200px;
            background:var(--color-gray-light);
        }
        .product-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s ease; }
        .product-card:hover .product-img-wrap img { transform:scale(1.05); }
        .stock-badge {
            position:absolute; top:0.6rem; right:0.6rem;
            background:rgba(255,255,255,0.9); color:var(--text);
            padding:0.2rem 0.6rem; border-radius:1rem; font-size:0.7rem;
            backdrop-filter:blur(4px);
        }
        .product-info { padding:1rem 1.2rem 1.2rem; }
        .product-category { font-size:0.7rem; text-transform:uppercase; letter-spacing:1px; color:var(--color-gray); }
        .product-name { font-size:1rem; font-weight:600; margin:0.3rem 0; color:var(--text); }
        .product-desc { font-size:0.8rem; color:var(--color-gray); line-height:1.4; margin-bottom:0.8rem; }
        .product-bottom { display:flex; justify-content:space-between; align-items:center; }
        .price { font-size:1.2rem; font-weight:500; }
        .btn {
            background:var(--primary); color:white; border:none;
            padding:0.5rem 1.2rem; border-radius:2rem; cursor:pointer;
            transition:var(--transition); font-size:0.85rem;
            font-family:inherit; position:relative; overflow:hidden;
        }
        .btn:hover { opacity:0.88; transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        .btn .ripple {
            position:absolute; border-radius:50%;
            background:rgba(255,255,255,0.5); transform:scale(0);
            animation:rippleAnim 0.5s linear; pointer-events:none;
        }
        @keyframes rippleAnim { to { transform:scale(4); opacity:0; } }
        /* Pagination */
        #pagination {
            display:flex; justify-content:center; gap:0.5rem;
            padding:2rem 2rem 4rem; max-width:1200px; margin:0 auto;
        }
        .page-btn {
            width:36px; height:36px; border-radius:50%;
            border:1px solid var(--color-border); background:var(--bg);
            color:var(--color-gray); cursor:pointer; font-size:0.85rem;
            transition:var(--transition); font-family:inherit;
        }
        .page-btn:hover { border-color:var(--text); color:var(--text); }
        .page-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); font-weight:500; }
        .empty-state {
            grid-column:1/-1; text-align:center; padding:3rem;
            color:var(--color-gray); font-size:0.95rem;
        }
        /* About */
        .about {
            display:grid; grid-template-columns:1fr 1.2fr 1fr; max-width:100%; margin:0;
        }
        .about .about-img { display:flex; align-items:stretch; overflow:hidden; }
        .about .about-img img { width:100%; height:100%; object-fit:cover; display:block; transition:transform 0.6s ease; }
        .about .about-img:hover img { transform:scale(1.03); }
        .about .about-text {
            display:flex; flex-direction:column; justify-content:center;
            padding:4rem 3rem; background:var(--bg);
        }
        .about .about-text h2 { font-size:1.1rem; letter-spacing:4px; text-transform:uppercase; margin-bottom:1rem; font-weight:600; color:var(--text); }
        .about .about-text p { font-size:0.95rem; line-height:1.7; color:var(--color-gray); margin-bottom:0.8rem; }
        .about .about-text .about-detail { font-size:0.8rem; letter-spacing:2px; text-transform:uppercase; opacity:0.6; margin-bottom:1.5rem; }
        .about .about-btn {
            align-self:flex-start; background:var(--primary); color:white;
            padding:0.6rem 1.8rem; border-radius:2rem; font-size:0.85rem;
            text-decoration:none; transition:var(--transition); border:none; cursor:pointer; font-family:inherit;
        }
        .about .about-btn:hover { opacity:0.85; transform:translateY(-1px); }
        /* Cart modal */
        .cart-overlay {
            position:fixed; top:0; left:0; right:0; bottom:0;
            background:rgba(0,0,0,0.3); z-index:200;
            opacity:0; visibility:hidden; transition:var(--transition);
        }
        .cart-overlay.open { opacity:1; visibility:visible; }
        .cart-modal {
            position:fixed; top:0; right:0; width:420px; max-width:100vw;
            height:100vh; background:white; z-index:201;
            transform:translateX(100%); transition:transform 0.35s ease;
            display:flex; flex-direction:column; box-shadow:-10px 0 30px rgba(0,0,0,0.06);
        }
        .cart-overlay.open .cart-modal { transform:translateX(0); }
        .cart-modal-header {
            display:flex; justify-content:space-between; align-items:center;
            padding:1.5rem; border-bottom:1px solid var(--color-border);
        }
        .cart-modal-header h2 { font-size:1.1rem; font-weight:600; }
        .cart-close {
            background:none; border:none; font-size:1.3rem; cursor:pointer;
            color:var(--color-gray); transition:var(--transition); padding:0.3rem;
        }
        .cart-close:hover { color:var(--text); }
        .cart-modal-body { flex:1; overflow-y:auto; padding:1rem 1.5rem; }
        .cart-empty { text-align:center; padding:3rem 0; color:var(--color-gray); font-size:0.9rem; }
        .cart-item {
            display:flex; align-items:center; gap:0.8rem;
            padding:0.8rem 0; border-bottom:1px solid var(--color-border);
        }
        .cart-item-img { width:48px; height:48px; border-radius:0.5rem; object-fit:cover; background:var(--color-gray-light); flex-shrink:0; }
        .cart-item-info { flex:1; min-width:0; }
        .cart-item-name { display:block; font-size:0.85rem; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .cart-item-price { display:block; font-size:0.8rem; color:var(--color-gray); margin-top:2px; }
        .cart-item-qty { display:flex; align-items:center; gap:0.4rem; }
        .qty-btn {
            width:24px; height:24px; border-radius:50%;
            border:1px solid var(--color-border); background:var(--bg);
            cursor:pointer; display:flex; align-items:center; justify-content:center;
            font-size:0.8rem; transition:var(--transition); color:var(--text);
        }
        .qty-btn:hover { border-color:var(--text); }
        .cart-remove {
            background:none; border:none; color:var(--color-gray); cursor:pointer;
            font-size:0.9rem; padding:0.3rem; transition:var(--transition);
        }
        .cart-remove:hover { color:#e74c3c; }
        .cart-modal-footer {
            padding:1.5rem; border-top:1px solid var(--color-border);
        }
        .cart-total-row {
            display:flex; justify-content:space-between; align-items:center;
            margin-bottom:1rem; font-size:1.05rem; font-weight:600;
        }
        .cart-modal-footer .btn { width:100%; padding:0.8rem; font-size:0.95rem; }
        /* Checkout form */
        .checkout-form { display:none; }
        .checkout-form.open { display:block; }
        .checkout-form h3 { font-size:0.95rem; margin-bottom:1rem; }
        .form-group { margin-bottom:0.8rem; }
        .form-group label { display:block; font-size:0.8rem; font-weight:500; margin-bottom:0.3rem; color:var(--color-gray); }
        .form-group input,.form-group textarea {
            width:100%; padding:0.6rem 0.8rem;
            border:1px solid var(--color-border); border-radius:0.5rem;
            font-size:0.85rem; font-family:inherit; outline:none; transition:var(--transition);
            background:var(--bg); color:var(--text);
        }
        .form-group input:focus,.form-group textarea:focus { border-color:var(--primary); }
        .form-group textarea { min-height:60px; resize:vertical; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:0.8rem; }
        .checkout-back {
            background:none; border:none; color:var(--color-gray); cursor:pointer;
            font-size:0.8rem; padding:0.3rem 0; margin-bottom:1rem; display:block;
            transition:var(--transition); font-family:inherit;
        }
        .checkout-back:hover { color:var(--text); }
        .order-success { text-align:center; padding:2rem 0; }
        .order-success svg { width:60px; height:60px; stroke:#4cd964; margin-bottom:1rem; }
        .order-success h3 { font-size:1.2rem; margin-bottom:0.5rem; }
        .order-success p { font-size:0.85rem; color:var(--color-gray); }
        /* Toast */
        .toast {
            position:fixed; bottom:6rem; left:50%; transform:translateX(-50%);
            background:#333; color:#fff; padding:0.6rem 1.2rem; border-radius:2rem;
            font-size:0.85rem; opacity:0; transition:opacity 0.3s; z-index:300;
            pointer-events:none; white-space:nowrap;
        }
        .toast.show { opacity:1; }
        /* Footer */
        .footer { text-align:center; padding:3rem 2rem 2rem; border-top:1px solid var(--color-border); }
        .footer-content {
            display:flex; justify-content:space-between;
            align-items:flex-start; flex-wrap:wrap; gap:2rem;
            max-width:1200px; margin:0 auto 2rem; text-align:left;
        }
        .footer-col h4 { font-size:0.85rem; font-weight:600; margin-bottom:1rem; color:var(--text); }
        .footer-col p,.footer-col a { font-size:0.8rem; color:var(--color-gray); text-decoration:none; line-height:1.8; display:block; }
        .footer-col a:hover { color:var(--primary); }
        .footer-bottom { border-top:1px solid var(--color-border); padding-top:1.5rem; font-size:0.75rem; color:var(--color-gray); }
        /* Back to top */
        .back-to-top {
            position:fixed; bottom:5rem; right:2rem;
            width:44px; height:44px; border-radius:50%;
            background:var(--primary); color:white; border:none;
            cursor:pointer; opacity:0; visibility:hidden;
            transition:var(--transition); z-index:999;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
        }
        .back-to-top.show { opacity:1; visibility:visible; }
        .back-to-top:hover { transform:translateY(-3px); box-shadow:0 6px 16px rgba(0,0,0,0.15); }
        .back-to-top svg { width:18px; height:18px; fill:currentColor; }
        /* Chatbot */
        .chatbot-btn {
            position:fixed; bottom:2rem; right:2rem;
            background:var(--primary); color:white;
            width:60px; height:60px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.15);
            z-index:1000; font-size:1.8rem;
        }
        .chat-window {
            position:fixed; bottom:5rem; right:2rem; width:320px;
            background:white; border-radius:1rem;
            box-shadow:0 10px 30px rgba(0,0,0,0.1);
            display:none; flex-direction:column; z-index:1001;
            overflow:hidden; border:1px solid var(--color-border);
        }
        .chat-header { background:var(--primary); color:white; padding:0.8rem; font-weight:500; }
        .chat-messages {
            height:250px; overflow-y:auto; padding:0.8rem;
            background:#f9f9f9; display:flex; flex-direction:column; gap:8px;
        }
        .message { max-width:80%; padding:8px 12px; border-radius:18px; font-size:0.85rem; }
        .user-msg { align-self:flex-end; background:var(--primary); color:white; }
        .bot-msg { align-self:flex-start; background:#e4e6eb; color:black; }
        .chat-input-area { display:flex; padding:0.5rem; border-top:1px solid var(--color-border); background:white; }
        .chat-input-area input { flex:1; padding:0.5rem; border:1px solid var(--color-border); border-radius:2rem; outline:none; font-family:inherit; }
        .chat-input-area button { background:var(--primary); margin-left:0.5rem; padding:0.5rem 1rem; border-radius:2rem; border:none; color:white; cursor:pointer; font-family:inherit; }
        .close-chat { float:right; cursor:pointer; font-weight:bold; }
        /* Responsive */
        @media (max-width:768px) {
            .nav-list {
                display:none; flex-direction:column;
                position:absolute; top:100%; left:0; right:0;
                background:rgba(255,255,255,0.98); backdrop-filter:blur(15px);
                padding:1rem 2rem; gap:1rem;
                box-shadow:0 10px 20px rgba(0,0,0,0.06);
                border-top:1px solid var(--color-border);
            }
            .nav-list.active { display:flex; }
            .menu-toggle { display:flex; }
            .hero { grid-template-columns:1fr; min-height:auto; }
            .hero-image-wrap { height:45vh; }
            .hero-content { padding:2.5rem 1.8rem; }
            .hero-content .hero-title { font-size:2rem; }
            .hero-content .hero-desc { max-width:100%; font-size:0.95rem; }
            .hero-angles { left:0; }
            .shop-bar { flex-direction:column; align-items:stretch; }
            .about { grid-template-columns:1fr; }
            .about .about-text { padding:2.5rem 1.8rem; }
            .cart-modal { width:100vw; }
            .footer-content { flex-direction:column; text-align:center; }
            .form-row { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<header class="header" id="header">
    <a href="/" class="logo-link">[TU LOGO AQUÍ]</a>
    <nav class="nav">
        <ul class="nav-list" id="navList">
            <li><button class="nav-link" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Productos</button></li>
            <li><button class="nav-link cart-link" onclick="toggleCart()">Carrito (<span id="cart-count">0</span>)</button></li>
        </ul>
    </nav>
    <button class="menu-toggle" id="menuToggle" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</header>

<section class="hero" id="hero">
    <div class="hero-image-wrap">
        <img id="heroImg" src="https://picsum.photos/id/2/1600/900" alt="Diseño con alma">
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
        <h1 class="hero-title" id="heroTitle">Diseño con alma</h1>
        <p class="hero-desc" id="heroDesc">Productos que combinan estética pura y funcionalidad. Descubre nuestra colección.</p>
        <button class="btn-explore" onclick="document.getElementById('shopBar').scrollIntoView({behavior:'smooth'})">Explorar</button>
        <div class="hero-angles">
            <button id="anglePrev" aria-label="Anterior">
                <svg viewBox="0 0 14 24"><polyline points="13,0 1,12 13,24"/></svg>
            </button>
            <button id="angleNext" aria-label="Siguiente">
                <svg viewBox="0 0 14 24"><polyline points="1,0 13,12 1,24"/></svg>
            </button>
        </div>
    </div>
</section>

<div class="shop-bar" id="shopBar">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Buscar productos…" oninput="debounceSearch()">
    </div>
    <div class="cat-filters" id="categoryFilter"></div>
</div>

<section class="products" id="products"></section>
<div id="pagination"></div>

<section class="about" data-aos="fade-up">
    <div class="about-img">
        <img src="https://picsum.photos/id/26/800/600" alt="Interior minimalista" loading="lazy">
    </div>
    <div class="about-text">
        <h2>Sobre nuestra colección</h2>
        <p>Cada pieza es seleccionada por su calidad, diseño atemporal y capacidad de transformar cualquier espacio.</p>
        <p class="about-detail">Materiales sostenibles · Diseño funcional · Envío seguro</p>
        <button class="about-btn" onclick="document.getElementById('shopBar').scrollIntoView({behavior:'smooth'})">Ver productos</button>
    </div>
    <div class="about-img">
        <img src="https://picsum.photos/id/30/800/600" alt="Espacio iluminado" loading="lazy">
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-col">
            <h4>Animation Web Skill</h4>
            <p>Tienda minimalista con animaciones, carrito y chatbot. Código abierto.</p>
        </div>
        <div class="footer-col">
            <h4>Enlaces</h4>
            <a href="#" onclick="document.getElementById('shopBar').scrollIntoView({behavior:'smooth'});return false">Productos</a>
            <a href="admin/login.php">Admin</a>
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <p>WhatsApp: +56 9 1234 5678</p>
            <p>Email: info@example.com</p>
        </div>
        <div class="footer-col">
            <h4>Publicidad</h4>
            <p style="opacity:0.5;">[TU PUBLICIDAD AQUÍ]</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 · Animation Web Skill — Tienda Minimalista</p>
    </div>
</footer>

<button class="back-to-top" id="backToTop" aria-label="Volver arriba">
    <svg viewBox="0 0 24 24"><path d="M12 5l-7 7h14l-7-7z"/></svg>
</button>

<!-- Cart Overlay + Modal -->
<div class="cart-overlay" id="cartOverlay">
    <div class="cart-modal">
        <div class="cart-modal-header">
            <h2>Carrito</h2>
            <button class="cart-close" onclick="toggleCart()">✕</button>
        </div>
        <div class="cart-modal-body" id="cartItems">
            <div class="cart-empty">Tu carrito está vacío</div>
        </div>
        <div class="cart-modal-footer" id="cartFooter">
            <div class="cart-total-row">
                <span>Total</span>
                <span id="cartTotal">$0.00</span>
            </div>
            <button class="btn" id="checkoutBtn" onclick="showCheckout()">Proceder al pago</button>
            <!-- Checkout form -->
            <div class="checkout-form" id="checkoutForm">
                <button class="checkout-back" onclick="hideCheckout()">← Volver al carrito</button>
                <h3>Datos de envío</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="cName" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="cEmail" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" id="cPhone">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea id="cAddress" required></textarea>
                </div>
                <div class="form-group">
                    <label>Notas (opcional)</label>
                    <textarea id="cNotes"></textarea>
                </div>
                <button class="btn" id="placeOrderBtn" onclick="placeOrder()">Confirmar pedido</button>
            </div>
            <div class="order-success" id="orderSuccess" style="display:none">
                <svg viewBox="0 0 24 24" fill="none" stroke="#4cd964" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <h3 id="orderSuccessTitle">¡Pedido confirmado!</h3>
                <p id="orderSuccessMsg">Gracias por tu compra. Te contactaremos a la brevedad.</p>
                <button class="btn" onclick="toggleCart();resetCheckout()" style="margin-top:1rem">Seguir comprando</button>
            </div>
        </div>
    </div>
</div>

<div class="chatbot-btn" id="chatbotToggle">💬</div>
<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <span>🤖 Chat con soporte</span>
        <span class="close-chat" id="closeChat">✕</span>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="message bot-msg">¡Hola! ¿En qué puedo ayudarte? Escribe tu nombre y consulta.</div>
    </div>
    <div class="chat-input-area">
        <input type="text" id="chatInput" placeholder="Tu nombre y mensaje…">
        <button id="sendChat">Enviar</button>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
<script src="js/cart.js" defer></script>
<script src="js/animations.js" defer></script>
<script src="js/main.js" defer></script>
<script>
    const heroData = [
        { img:'https://picsum.photos/id/2/1600/900', title:'Diseño con alma', desc:'Productos que combinan estética pura y funcionalidad. Descubre nuestra colección.' },
        { img:'https://picsum.photos/id/3/1600/900', title:'Calidad superior', desc:'Materiales seleccionados para durar toda la vida. Cada pieza es una inversión.' },
        { img:'https://picsum.photos/id/4/1600/900', title:'Envíos a todo el país', desc:'Recibí tus productos en la puerta de tu casa. Envío seguro y seguimiento en tiempo real.' },
    ];
    let heroIndex = 0;
    const heroImg = document.getElementById('heroImg');
    const heroTitle = document.getElementById('heroTitle');
    const heroDesc = document.getElementById('heroDesc');

    function updateHero(i) {
        heroIndex = (i + heroData.length) % heroData.length;
        const d = heroData[heroIndex];
        heroImg.style.transform = 'translateX(-100%)';
        heroImg.style.transition = 'transform 0.35s ease-in';
        setTimeout(() => {
            heroImg.src = d.img;
            heroTitle.textContent = d.title;
            heroDesc.textContent = d.desc;
            heroImg.style.transition = 'none';
            heroImg.style.transform = 'translateX(0)';
        }, 350);
    }
    document.getElementById('angleNext').addEventListener('click', () => updateHero(heroIndex + 1));
    document.getElementById('anglePrev').addEventListener('click', () => updateHero(heroIndex - 1));

    let searchTimer;
    function debounceSearch() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const q = document.getElementById('searchInput').value;
            loadProducts(1, currentCategory, q);
        }, 300);
    }

    // Checkout
    function showCheckout() {
        const cartData = JSON.parse(localStorage.getItem('tienda_cart') || '[]');
        if (cartData.length === 0) return;
        document.getElementById('checkoutBtn').style.display = 'none';
        document.getElementById('checkoutForm').classList.add('open');
        document.querySelector('.cart-total-row').style.display = 'none';
    }
    function hideCheckout() {
        document.getElementById('checkoutBtn').style.display = 'block';
        document.getElementById('checkoutForm').classList.remove('open');
        document.querySelector('.cart-total-row').style.display = 'flex';
    }
    function resetCheckout() {
        document.getElementById('checkoutBtn').style.display = 'block';
        document.getElementById('checkoutForm').classList.remove('open');
        document.getElementById('orderSuccess').style.display = 'none';
        document.querySelector('.cart-total-row').style.display = 'flex';
    }
    async function placeOrder() {
        const name = document.getElementById('cName').value.trim();
        const email = document.getElementById('cEmail').value.trim();
        const address = document.getElementById('cAddress').value.trim();
        if (!name || !email || !address) { alert('Completá nombre, email y dirección.'); return; }
        const items = JSON.parse(localStorage.getItem('tienda_cart') || '[]');
        if (items.length === 0) return;
        const btn = document.getElementById('placeOrderBtn');
        btn.textContent = 'Procesando…'; btn.disabled = true;
        try {
            const res = await fetch('api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    name, email,
                    phone: document.getElementById('cPhone').value.trim(),
                    address,
                    notes: document.getElementById('cNotes').value.trim(),
                    items
                })
            });
            const data = await res.json();
            if (data.success) {
                localStorage.removeItem('tienda_cart');
                cart = [];
                updateCartUI();
                document.getElementById('checkoutForm').classList.remove('open');
                document.querySelector('.cart-total-row').style.display = 'none';
                document.getElementById('orderSuccess').style.display = 'block';
                document.getElementById('orderSuccessTitle').textContent = '¡Pedido #' + data.order_id + ' confirmado!';
            } else {
                alert(data.error || 'Error al procesar el pedido.');
            }
        } catch(e) {
            alert('Error de conexión. Intentá de nuevo.');
        }
        btn.textContent = 'Confirmar pedido'; btn.disabled = false;
    }

    // Chatbot
    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatWindow = document.getElementById('chatWindow');
    const closeChat = document.getElementById('closeChat');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendChat');

    chatbotToggle.addEventListener('click', () => { chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex'; });
    closeChat.addEventListener('click', () => { chatWindow.style.display = 'none'; });

    function addMessage(text, isUser) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${isUser ? 'user-msg' : 'bot-msg'}`;
        msgDiv.textContent = text;
        chatMessages.appendChild(msgDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    async function sendToBot(message, name) {
        try {
            const res = await fetch('api/chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message, name })
            });
            const data = await res.json();
            if (data.response) {
                addMessage(data.response, false);
                if (data.whatsapp_link) addMessage("📱 Tu mensaje fue enviado a nuestro WhatsApp.", false);
            } else addMessage("Hubo un error. Intenta de nuevo.", false);
        } catch(e) { addMessage("Error de conexión.", false); }
    }
    sendBtn.addEventListener('click', async () => {
        let fullMessage = chatInput.value.trim();
        if (fullMessage === "") return;
        addMessage(fullMessage, true);
        chatInput.value = "";
        let name = "Cliente", messageText = fullMessage;
        const spaceIndex = fullMessage.indexOf(' ');
        if (spaceIndex > 0) { name = fullMessage.substring(0, spaceIndex); messageText = fullMessage.substring(spaceIndex + 1); }
        await sendToBot(messageText, name);
    });
    chatInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendBtn.click(); });
</script>
</body>
</html>
