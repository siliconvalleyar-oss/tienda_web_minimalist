# Quick Start — Animation Web Skill

## Abrir el proyecto

```bash
cd /mnt/disk/src/animation_web_skill
```

## Iniciar servidor de desarrollo

```bash
php -S localhost:8080
# Abrir http://localhost:8080/
```

## URLs principales

| Página | URL |
|--------|-----|
| Tienda | http://localhost:8080/ |
| Admin login | http://localhost:8080/admin/login.php |
| Admin productos | http://localhost:8080/admin/products.php |
| Admin pedidos | http://localhost:8080/admin/orders.php |
| API productos | http://localhost:8080/api/products.php |
| API checkout | http://localhost:8080/api/checkout.php |

## Credenciales admin

- **Usuario:** `admin`
- **Contraseña:** `admin123`

## Desplegar en XAMPP

```bash
sudo cp -a /mnt/disk/src/animation_web_skill /opt/lampp/htdocs/
# Abrir http://localhost/animation_web_skill/
```

## Base de datos

- Host: `localhost` (socket: `/opt/lampp/var/mysql/mysql.sock`)
- Base: `tienda_minimal`
- Usuario: `root`
- Contraseña: (vacía)

## Estructura actual

```
/mnt/disk/src/animation_web_skill/
├── index.php              → Frontend completo (hero, productos, carrito, chatbot, checkout)
├── api/
│   ├── products.php       → GET con search/category/page/per_page
│   ├── checkout.php       → POST crea orden + cliente + items
│   └── chatbot.php        → POST responde por keywords + wa.me
├── admin/
│   ├── login.php / auth.php / logout.php
│   ├── dashboard.php      → Colores + WhatsApp
│   ├── products.php       → CRUD productos
│   └── orders.php         → Lista pedidos + cambio de estado
├── js/
│   ├── cart.js            → Carrito con API, localStorage, modal checkout
│   ├── animations.js      → GSAP + cursor circle
│   └── main.js            → Interacciones DOM
└── includes/db.php        → PDO + helpers
```

## Próximos pasos posibles

- [ ] Reemplazar `[TU LOGO AQUÍ]` y `[TU PUBLICIDAD AQUÍ]` en `index.php`
- [ ] Agregar página de detalle de producto
- [ ] Integrar pasarela de pago
- [ ] Subida de imágenes desde admin
- [ ] Rama Git por área (`frontend`, `backend`, `admin`, `database`, `documentation`)
