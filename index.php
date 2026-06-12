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
    <title>Tienda Minimalista</title>
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
        .hero {
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: #fafafa;
        }
        .hero h2 { font-size: 3rem; font-weight: 400; letter-spacing: -0.02em; }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
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
    <section class="hero">
        <h2>Diseño con alma</h2>
        <p>Productos que combinan estética pura y funcionalidad</p>
    </section>
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

<script src="js/cart.js"></script>
<script src="js/animations.js"></script>
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
