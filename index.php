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
    <meta name="description" content="Tienda minimalista con diseño elegante, productos seleccionados y envíos a todo el país. Animaciones GSAP, carrito de compras y chatbot integrado.">
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
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Store",
        "name": "Tienda Minimalista",
        "description": "Productos que combinan estética pura y funcionalidad",
        "url": "https://tienda-minimalista.example.com",
        "address": { "@type": "PostalAddress", "addressCountry": "AR" }
    }
    </script>
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
        * { margin:0; padding:0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 2rem;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            z-index: 100;
            border-bottom: 1px solid var(--color-border);
            transition: var(--transition);
        }
        .header.scrolled { box-shadow: 0 5px 20px rgba(0,0,0,0.06); }
        .nav-list { display: flex; gap: 2rem; align-items: center; list-style: none; }
        .nav-link {
            color: var(--text);
            text-decoration: none;
            font-size: 0.9rem;
            position: relative;
            padding: 0.25rem 0;
            transition: var(--transition);
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 1.5px;
            background: var(--primary);
            transition: var(--transition);
        }
        .nav-link:hover::after { width: 100%; }
        .menu-toggle {
            display: none; flex-direction: column; cursor: pointer; gap: 4px;
            background: none; border: none; padding: 4px; border-radius: 4px;
        }
        .menu-toggle span {
            display: block; width: 22px; height: 2px;
            background: var(--text); transition: var(--transition);
        }
        .products {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }
        .product-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        .product-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-hover); }
        .price { font-size: 1.25rem; font-weight: 500; margin: 1rem 0; }
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 2rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            font-size: 0.9rem;
        }
        .btn:hover { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn .ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.5);
            transform: scale(0); animation: rippleAnim 0.5s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* Hero — room-style split layout */
        .hero {
            display: grid;
            grid-template-columns: 2fr 1fr;
            min-height: 100vh;
            margin-top: 76px;
            position: relative;
            overflow: hidden;
        }
        .hero-image-wrap {
            position: relative;
            overflow: hidden;
            background: #fafafa;
        }
        .hero-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.05) 0%, transparent 100%);
            pointer-events: none;
        }
        .hero-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 5rem 3.5rem;
            background: var(--bg);
        }
        .hero-content .hero-title {
            font-size: 2.8rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.15;
            margin-bottom: 1.2rem;
            color: var(--text);
        }
        .hero-content .hero-desc {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--color-gray);
            margin-bottom: 2rem;
            max-width: 90%;
        }
        .hero-content .btn-explore {
            display: inline-flex;
            align-items: center;
            gap: 0.8em;
            background: none;
            border: none;
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.6em;
            text-transform: uppercase;
            cursor: pointer;
            padding: 0;
            transition: var(--transition);
            font-family: inherit;
        }
        .hero-content .btn-explore::after {
            content: '→';
            display: inline-block;
            transition: transform 0.3s ease;
            font-size: 1.2rem;
        }
        .hero-content .btn-explore:hover {
            color: var(--color-gray);
        }
        .hero-content .btn-explore:hover::after {
            transform: translateX(8px);
        }

        /* Angle nav buttons — overlapping (room style) */
        .hero-angles {
            display: flex;
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 10;
        }
        .hero-angles button {
            padding: 1.2rem 1.6rem;
            background: #000;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-angles button:hover { background: hsl(0, 0%, 27%); }
        .hero-angles button svg {
            width: 12px; height: 20px;
            stroke: #fff; fill: none; stroke-width: 2;
            transition: transform 0.3s ease;
        }
        .hero-angles button:hover svg { transform: scale(1.3); }

        /* About section — room style 3-column grid */
        .about {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1fr;
            max-width: 100%;
            margin: 0;
        }
        .about .about-img {
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }
        .about .about-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }
        .about .about-img:hover img { transform: scale(1.03); }
        .about .about-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 3rem;
            background: var(--bg);
        }
        .about .about-text h2 {
            font-size: 1.1rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 1rem;
            font-weight: 600;
            color: var(--text);
        }
        .about .about-text p {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--color-gray);
            margin-bottom: 0.8rem;
        }
        .about .about-text .about-detail {
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.6;
            margin-bottom: 1.5rem;
        }
        .about .about-btn {
            align-self: flex-start;
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            text-decoration: none;
            transition: var(--transition);
        }
        .about .about-btn:hover { opacity: 0.85; transform: translateY(-1px); }

        .products {
            font-weight: 600; font-size: 1.2rem;
            color: var(--text); text-decoration: none;
            letter-spacing: -0.5px;
            transition: var(--transition);
        }
        .logo-link:hover { opacity: 0.7; }

        /* Footer */
        .footer {
            text-align: center; padding: 3rem 2rem 2rem;
            border-top: 1px solid var(--color-border);
        }
        .footer-content {
            display: flex; justify-content: space-between;
            align-items: flex-start; flex-wrap: wrap; gap: 2rem;
            max-width: 1200px; margin: 0 auto 2rem; text-align: left;
        }
        .footer-col h4 { font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem; color: var(--text); }
        .footer-col p, .footer-col a { font-size: 0.8rem; color: var(--color-gray); text-decoration: none; line-height: 1.8; display: block; }
        .footer-col a:hover { color: var(--primary); }
        .footer-bottom {
            border-top: 1px solid var(--color-border);
            padding-top: 1.5rem; font-size: 0.75rem; color: var(--color-gray);
        }

        /* Back to top */
        .back-to-top {
            position: fixed; bottom: 5rem; right: 2rem;
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--primary); color: white; border: none;
            cursor: pointer; opacity: 0; visibility: hidden;
            transition: var(--transition); z-index: 999;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .back-to-top.show { opacity: 1; visibility: visible; }
        .back-to-top:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }
        .back-to-top svg { width: 18px; height: 18px; fill: currentColor; }

        /* Mobile nav + responsive */
        @media (max-width: 768px) {
            .nav-list {
                display: none; flex-direction: column;
                position: absolute; top: 100%; left: 0; right: 0;
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(15px);
                padding: 1rem 2rem; gap: 1rem;
                box-shadow: 0 10px 20px rgba(0,0,0,0.06);
                border-top: 1px solid var(--color-border);
            }
            .nav-list.active { display: flex; }
            .menu-toggle { display: flex; }
            .hero {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .hero-image-wrap { height: 50vh; }
            .hero-content { padding: 2.5rem 1.8rem; }
            .hero-content .hero-title { font-size: 2rem; }
            .hero-content .hero-desc { max-width: 100%; font-size: 0.95rem; }
            .hero-angles { left: 0; }
            .about {
                grid-template-columns: 1fr;
            }
            .about .about-text { padding: 2.5rem 1.8rem; }
            .footer-content { flex-direction: column; text-align: center; }
        }

        .chatbot-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            font-size: 1.8rem;
        }
        .chat-window {
            position: fixed;
            bottom: 5rem;
            right: 2rem;
            width: 320px;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: none;
            flex-direction: column;
            z-index: 1001;
            overflow: hidden;
            border: 1px solid #eee;
        }
        .chat-header {
            background: var(--primary);
            color: white;
            padding: 0.8rem;
            font-weight: 500;
        }
        .chat-messages {
            height: 250px;
            overflow-y: auto;
            padding: 0.8rem;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .message {
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 18px;
            font-size: 0.85rem;
        }
        .user-msg {
            align-self: flex-end;
            background: var(--primary);
            color: white;
        }
        .bot-msg {
            align-self: flex-start;
            background: #e4e6eb;
            color: black;
        }
        .chat-input-area {
            display: flex;
            padding: 0.5rem;
            border-top: 1px solid #ddd;
            background: white;
        }
        .chat-input-area input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 2rem;
            outline: none;
        }
        .chat-input-area button {
            background: var(--primary);
            margin-left: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
        }
        .close-chat {
            float: right;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
</head>
<body>

<header class="header" id="header">
    <div class="logo-placeholder">
        <a href="/" class="logo-link">[TU LOGO AQUÍ]</a>
    </div>
    <nav class="nav">
        <ul class="nav-list" id="navList">
            <li><a href="#" class="nav-link">Productos</a></li>
            <li><a href="#" class="nav-link">Carrito (<span id="cart-count">0</span>)</a></li>
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
        <p class="hero-desc" id="heroDesc">Productos que combinan estética pura y funcionalidad. Descubre nuestra colección cuidadosamente seleccionada.</p>
        <button class="btn-explore" id="heroCta" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Explorar</button>
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

<section class="products" id="products" data-aos="fade-up">
    <!-- rendered by cart.js -->
</section>

<section class="about" data-aos="fade-up">
    <div class="about-img">
        <img src="https://picsum.photos/id/26/800/600" alt="Interior minimalista" loading="lazy">
    </div>
    <div class="about-text">
        <h2>Sobre nuestra colección</h2>
        <p>Cada pieza es seleccionada por su calidad, diseño atemporal y capacidad de transformar cualquier espacio. Creemos en el poder de lo simple.</p>
        <p class="about-detail">Materiales sostenibles · Diseño funcional · Envío seguro</p>
        <a href="#" class="about-btn" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Conocer más</a>
    </div>
    <div class="about-img">
        <img src="https://picsum.photos/id/30/800/600" alt="Espacio iluminado" loading="lazy">
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-col">
            <h4>Animation Web Skill</h4>
            <p>Tienda minimalista con animaciones, carrito y chatbot. Código abierto y personalizable.</p>
        </div>
        <div class="footer-col">
            <h4>Enlaces</h4>
            <a href="#">Productos</a>
            <a href="admin/login.php">Admin</a>
        </div>
        <div class="footer-col">
            <h4>Contacto</h4>
            <p>WhatsApp: +56 9 1234 5678</p>
            <p>Email: info@example.com</p>
        </div>
        <div class="footer-col">
            <h4>Publicidad</h4>
            <!-- PUBLICIDAD PLACEHOLDER: Reemplazar por tu contenido -->
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
        <input type="text" id="chatInput" placeholder="Tu nombre y mensaje...">
        <button id="sendChat">Enviar</button>
    </div>
</div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
<script src="js/cart.js" defer></script>
<script src="js/animations.js" defer></script>
<script src="js/main.js" defer></script>
<script>
    // Hero carousel — room-homepage style (slide + swap)
    const heroData = [
        { img: 'https://picsum.photos/id/2/1600/900', title: 'Diseño con alma', desc: 'Productos que combinan estética pura y funcionalidad. Descubre nuestra colección cuidadosamente seleccionada.' },
        { img: 'https://picsum.photos/id/3/1600/900', title: 'Calidad superior', desc: 'Materiales seleccionados para durar toda la vida. Cada pieza es una inversión en tu espacio.' },
        { img: 'https://picsum.photos/id/4/1600/900', title: 'Envíos a todo el país', desc: 'Recibí tus productos en la puerta de tu casa. Envío seguro y seguimiento en tiempo real.' },
    ];
    let heroIndex = 0;
    const heroImg = document.getElementById('heroImg');
    const heroTitle = document.getElementById('heroTitle');
    const heroDesc = document.getElementById('heroDesc');
    const anglePrev = document.getElementById('anglePrev');
    const angleNext = document.getElementById('angleNext');

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

    angleNext.addEventListener('click', () => updateHero(heroIndex + 1));
    anglePrev.addEventListener('click', () => updateHero(heroIndex - 1));

    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatWindow = document.getElementById('chatWindow');
    const closeChat = document.getElementById('closeChat');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendChat');

    chatbotToggle.addEventListener('click', () => {
        chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
    });
    closeChat.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

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
                if (data.whatsapp_link) {
                    addMessage("📱 Tu mensaje también fue enviado a nuestro WhatsApp. Te contactaremos pronto.", false);
                }
            } else {
                addMessage("Hubo un error. Intenta de nuevo.", false);
            }
        } catch (err) {
            addMessage("Error de conexión.", false);
        }
    }

    sendBtn.addEventListener('click', async () => {
        let fullMessage = chatInput.value.trim();
        if (fullMessage === "") return;
        addMessage(fullMessage, true);
        chatInput.value = "";
        let name = "Cliente";
        let messageText = fullMessage;
        const spaceIndex = fullMessage.indexOf(' ');
        if (spaceIndex > 0) {
            name = fullMessage.substring(0, spaceIndex);
            messageText = fullMessage.substring(spaceIndex + 1);
        }
        await sendToBot(messageText, name);
    });

    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendBtn.click();
    });
</script>
</body>
</html>
