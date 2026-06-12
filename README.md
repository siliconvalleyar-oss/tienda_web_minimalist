# Animation Web Skill - Tienda Minimalista

Tienda web minimalista con animaciones GSAP, panel de administración, paleta de colores dinámica y chatbot sincronizado con WhatsApp.

## Estructura del proyecto

```
animation_web_skill/
├── index.php              # Tienda principal (colores dinámicos + chatbot)
├── setup.sql              # Script SQL para crear BD y tablas
├── install.sh             # Script de instalación automatizada
├── admin/
│   ├── login.php          # Login de administrador
│   ├── auth.php           # Autenticación con sesiones PHP
│   ├── dashboard.php      # Panel para cambiar colores y WhatsApp
│   └── logout.php         # Cierre de sesión
├── includes/
│   └── db.php             # Conexión PDO y helpers getSetting/updateSetting
├── api/
│   └── chatbot.php        # Endpoint POST del chatbot (guarda en BD y responde)
├── js/
│   ├── cart.js            # Catálogo de productos y carrito de compras
│   └── animations.js      # Animaciones GSAP y efecto de círculo mouse
└── css/
    └── style.css          # Estilos base
```

## Requisitos

- XAMPP (Apache + MySQL + PHP 8+)
- Navegador web moderno

## Instalación

1. Copiar la carpeta `animation_web_skill` a `C:\xampp\htdocs\` (Windows) o `/opt/lampp/htdocs/` (Linux)
2. Iniciar Apache y MySQL desde el panel de XAMPP
3. Opción A — Instalación automatizada:
   ```bash
   bash install.sh
   ```
   Opción B — Manual: importar `setup.sql` en phpMyAdmin
4. Acceder a http://localhost/animation_web_skill/

## Uso

### Tienda principal
- http://localhost/animation_web_skill/
- Catálogo con 4 productos, carrito de compras, animaciones GSAP al scroll y círculo decorativo que sigue al ratón

### Panel de administración
- http://localhost/animation_web_skill/admin/login.php
- **Usuario:** `admin`
- **Contraseña:** `admin123`
- Permite cambiar colores (primario, fondo, texto) y número de WhatsApp en tiempo real

### Chatbot
- Botón flotante en la esquina inferior derecha de la tienda
- Responde automáticamente a palabras clave: hola, precio, envío, horario
- Genera enlace `wa.me` para notificar al administrador
- Los mensajes se almacenan en la tabla `chat_messages`

## Productos de ejemplo

| Producto       | Precio |
|----------------|--------|
| Silla Eames    | $320   |
| Lámpara de pie | $185   |
| Mesa auxiliar  | $99    |
| Espejo redondo | $79    |

## Tecnologías

- **Frontend:** HTML, CSS, JavaScript, GSAP + ScrollTrigger
- **Backend:** PHP 8+ con PDO
- **Base de datos:** MySQL
- **Servidor:** Apache (XAMPP)
