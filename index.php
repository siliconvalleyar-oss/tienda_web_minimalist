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
        }
        * { margin:0; padding:0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            z-index: 100;
            border-bottom: 1px solid #eee;
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
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px -12px rgba(0,0,0,0.05); }
        .price { font-size: 1.25rem; font-weight: 500; margin: 1rem 0; }
        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 2rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        button:hover { opacity: 0.85; }
        footer { text-align: center; padding: 2rem; border-top: 1px solid #eee; color: #888; font-size: 0.8rem; }

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
</head>
<body>

<header class="header">
    <!-- LOGO PLACEHOLDER: Reemplazar por tu logo.
         Ejemplo: <img src="assets/logo.png" alt="Tu Marca" height="32"> -->
    <div class="logo-placeholder">
        <a href="/" style="font-weight:600; font-size:1.2rem; color:var(--text); text-decoration:none; letter-spacing:-0.5px;">[TU LOGO AQUÍ]</a>
    </div>
    <nav>
        <a href="#" style="color:var(--text); text-decoration:none;">Productos</a>
        <a href="#" style="color:var(--text); text-decoration:none; margin-left:1rem;">Carrito (<span id="cart-count">0</span>)</a>
    </nav>
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
    <section class="products" id="products"></section>
</main>

<footer>
    <!-- PUBLICIDAD / SPONSOR PLACEHOLDER
         Reemplazar por tu publicidad, enlaces afiliados o créditos.
         Ejemplo: <a href="https://tusitio.com" target="_blank">Patrocinado por Tu Marca</a>
    -->
    <div class="sponsor" style="margin-bottom:0.5rem; font-size:0.75rem; color:#aaa;">
        [PUBLICIDAD / SPONSOR - Reemplazar por tu contenido]
    </div>
    <p>© 2026 · Inspirado en Aura y Cynthia Ugwu</p>
</footer>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="js/cart.js" defer></script>
<script src="js/animations.js" defer></script>
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
