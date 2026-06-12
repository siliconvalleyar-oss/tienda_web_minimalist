---
name: animation-web-skill
description: |
  Use when working on the Animation Web Skill project -- a minimalist web store
  with GSAP animations, dynamic color theming via admin panel, a floating chatbot
  that stores messages in MySQL and forwards to WhatsApp (wa.me link), and a
  shopping cart. Built with PHP + MySQL + GSAP + vanilla JS.
compatibility:
  - PHP 8+
  - MySQL
  - Apache (XAMPP)
  - GSAP
  - JavaScript (vanilla)
---

# Animation Web Skill - Tienda Minimalista

Tienda web minimalista con animaciones GSAP, panel de administración protegido por contraseña, paleta de colores dinámica y chatbot sincronizado con WhatsApp.

## Project Structure

```
animation_web_skill/
├── index.php              # Tienda principal (colores dinámicos + chatbot embebido)
├── setup.sql              # Script SQL para crear BD y tablas
├── install.sh             # Script de instalación automatizada (Linux)
├── admin/
│   ├── login.php          # Login de administrador
│   ├── auth.php           # Autenticación con sesiones PHP + password_verify
│   ├── dashboard.php      # Panel CRUD para colores (primary/bg/text) y WhatsApp
│   └── logout.php         # Destrucción de sesión
├── includes/
│   └── db.php             # Conexión PDO y helpers getSetting/updateSetting
├── api/
│   └── chatbot.php        # Endpoint POST (JSON): guarda mensaje, responde por keywords, devuelve wa.me link
├── js/
│   ├── cart.js            # Catálogo de 4 productos, carrito simple con contador
│   └── animations.js      # GSAP ScrollTrigger + círculo decorativo que sigue al mouse
├── css/
│   └── style.css          # Estilos base (los colores se inyectan vía PHP en index.php)
└── .opencode/
    └── skills/
        └── SKILL.md       # Este archivo
```

## Database (MySQL)

Base: `tienda_minimal`

| Table            | Columns |
|------------------|---------|
| `admins`         | id, username, password_hash |
| `settings`       | id, setting_key (UNIQUE), setting_value |
| `chat_messages`  | id, visitor_name, message, response, forwarded_to_whatsapp (bool), created_at |

Default settings:
- `primary_color` = `#000000`
- `bg_color` = `#ffffff`
- `text_color` = `#111111`
- `whatsapp_number` = `56912345678` (formato internacional sin +)

## Key Architecture and Conventions

### Colores dinámicos
- `index.php` lee `primary_color`, `bg_color`, `text_color` de la tabla `settings` y los inyecta como CSS custom properties en `:root`
- El panel admin (`dashboard.php`) actualiza estos valores vía `updateSetting()`
- Reflejo inmediato sin necesidad de recargar configuración

### Chatbot
- Endpoint: `POST /api/chatbot.php` con `{ name, message }`
- Responde con keywords vía `strpos`: hola, precio, envío, horario
- Guarda en `chat_messages`, marca `forwarded_to_whatsapp = 1`
- Devuelve `{ response, whatsapp_link }` con enlace `wa.me`
- Frontend en `index.php` maneja UI del chat flotante (toggle, mensajes, scroll)

### Admin Panel
- Sesiones PHP con `$_SESSION['admin_logged']`
- Login: `admin` / `admin123` (hash bcrypt en BD)
- Dashboard: formulario con inputs `type="color"` y texto para WhatsApp

### Animaciones
- GSAP via CDN: animación de hero al cargar, stagger de tarjetas al scroll con ScrollTrigger
- Círculo semitransparente de 30px que sigue al cursor, escala 2x sobre botones/enlaces

### Carrito
- 4 productos hardcodeados en `cart.js` (Silla Eames $320, Lámpara $185, Mesa $99, Espejo $79)
- Imágenes placeholder via picsum.photos
- Carrito en memoria (array), contador en header con animación scale

## API Endpoints

| Method | URL | Body | Response |
|--------|-----|------|----------|
| GET | `/admin/login.php` | - | HTML login |
| POST | `/admin/auth.php` | `username`, `password` | Redirect a dashboard o login?error |
| POST | `/api/chatbot.php` | `{ name, message }` JSON | `{ response, whatsapp_link }` JSON |

## Setup Commands

```bash
# 1. Copiar a htdocs
cp -r animation_web_skill /opt/lampp/htdocs/

# 2. Crear BD (MySQL sin contraseña)
mysql -u root < setup.sql

# O vía script
bash install.sh
```

## URLs

- Tienda: `http://localhost/animation_web_skill/`
- Admin login: `http://localhost/animation_web_skill/admin/login.php`
- Admin credenciales: `admin` / `admin123`

## Dependencies (CDN)

- GSAP 3.12.5 + ScrollTrigger
- Inter font (system-ui fallback)
- picsum.photos (imágenes placeholder)
