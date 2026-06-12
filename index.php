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

        /* Logo */
        .logo-link {
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

        /* Mobile nav */
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
            .hero-carousel .carousel-caption {
                display: block !important;
                bottom: 30%; transform: none;
                left: 5%; right: 5%; width: auto;
                padding: 1.2rem;
            }
            .hero-carousel .carousel-caption h2 { font-size: 1.5rem; }
            .hero-carousel .carousel-caption p { font-size: 0.85rem; }
            .hero-carousel .carousel-item { height: 60vh; }
            .footer-content { flex-direction: column; text-align: center; }
        }

        /* BS Carousel hero — transiciones suaves */
        .hero-carousel { margin-top: 76px; }
        .hero-carousel .carousel-item { height: 80vh; background: #fafafa; transition: transform 0.8s ease-in-out, opacity 0.8s ease-in-out; }
        .hero-carousel .carousel-item img { object-fit: cover; height: 100%; }
        .hero-carousel .carousel-caption {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(4px);
            border-radius: 1rem;
            padding: 2rem 3rem;
            bottom: 50%;
            transform: translateY(50%);
            color: var(--text);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .hero-carousel .carousel-caption h2 { font-size: 2.5rem; font-weight: 400; letter-spacing: -0.02em; margin-bottom: 0.5rem; }
        .hero-carousel .carousel-caption p { margin: 0; opacity: 0.7; }
        .hero-carousel .carousel-indicators button { background: var(--primary); width: 10px; height: 10px; border-radius: 50%; transition: opacity 0.3s; }
        .hero-carousel .carousel-indicators button:hover { opacity: 0.6; }
        .hero-carousel .carousel-control-prev-icon,
        .hero-carousel .carousel-control-next-icon { filter: invert(1); width: 2rem; height: 2rem; transition: transform 0.3s ease; }
        .hero-carousel .carousel-control-prev:hover .carousel-control-prev-icon { transform: translateX(-4px); }
        .hero-carousel .carousel-control-next:hover .carousel-control-next-icon { transform: translateX(4px); }

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<main>
    <div id="heroCarousel" class="carousel slide hero-carousel carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://picsum.photos/id/2/1600/900" class="d-block w-100" alt="Diseño con alma — muebles minimalistas" loading="lazy">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Diseño con alma</h2>
                    <p>Productos que combinan estética pura y funcionalidad</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://picsum.photos/id/3/1600/900" class="d-block w-100" alt="Calidad superior — productos seleccionados" loading="lazy">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Calidad superior</h2>
                    <p>Materiales seleccionados para durar toda la vida</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://picsum.photos/id/4/1600/900" class="d-block w-100" alt="Envíos a todo el país" loading="lazy">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Envíos a todo el país</h2>
                    <p>Recibí tus productos en la puerta de tu casa</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <section class="products" id="products" data-aos="fade-up"></section>
</main>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="js/cart.js" defer></script>
<script src="js/animations.js" defer></script>
<script src="js/main.js" defer></script>
<script>
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
