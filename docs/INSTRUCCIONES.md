# Animation Web Skill

## Requisitos

- **XAMPP** (Apache + MySQL + PHP 8+) — [Descargar](https://www.apachefriends.org/)
- Navegador web moderno (Chrome, Firefox, Edge)

## Instalación paso a paso

### 1. Copiar el proyecto

#### Windows
```cmd
xcopy /E animation_web_skill C:\xampp\htdocs\animation_web_skill\
```

#### Linux
```bash
cp -r animation_web_skill /opt/lampp/htdocs/
```

### 2. Iniciar XAMPP

- Abrir el Panel de Control de XAMPP
- Iniciar **Apache** y **MySQL**

### 3. Crear la base de datos

#### Opción A — Script automático
```bash
cd /ruta/a/animation_web_skill
bash install.sh
```

#### Opción B — phpMyAdmin
1. Abrir http://localhost/phpmyadmin/
2. Crear base de datos `tienda_minimal` (utf8_general_ci)
3. Importar el archivo `setup.sql`

#### Opción C — Línea de comandos
```bash
mysql -u root < setup.sql
```

### 4. Verificar la instalación

| Página | URL | Descripción |
|--------|-----|-------------|
| Tienda | http://localhost/animation_web_skill/ | Catálogo + carrito + chatbot |
| Admin  | http://localhost/animation_web_skill/admin/login.php | Panel de personalización |

## Credenciales por defecto

- **Usuario:** `admin`
- **Contraseña:** `admin123`

## Personalización

### Colores
1. Iniciar sesión en el panel admin
2. Usar los selectores de color para cambiar:
   - Color principal (botones, header del chat, bordes)
   - Color de fondo
   - Color de texto
3. Los cambios se reflejan inmediatamente en la tienda

### Número de WhatsApp
- Ingresar en el panel admin (formato internacional sin `+` ni espacios)
- Ejemplo: `56912345678`

## Uso del chatbot

1. Abrir la tienda
2. Hacer clic en el botón flotante 💬 (abajo a la derecha)
3. Escribir nombre y mensaje (ej: `Juan Quiero saber precios`)
4. El bot responde automáticamente según palabras clave
5. Se genera un enlace `wa.me` para contactar al administrador

## Solución de problemas

| Problema | Posible causa | Solución |
|----------|---------------|----------|
| Error de conexión BD | MySQL no iniciado | Iniciar MySQL en XAMPP |
| Pantalla en blanco | Error PHP | Verificar `error_log` en `/opt/lampp/logs/` |
| Colores no cambian | Sesión no iniciada | Hacer login en `/admin/login.php` |
| 404 al cargar | Proyecto no en htdocs | Copiar carpeta a htdocs |

## Estructura del proyecto

```
animation_web_skill/
├── index.php              # Tienda principal
├── setup.sql              # Script SQL
├── install.sh             # Instalador Linux
├── opencode.json          # Config opencode
├── README.md              # Documentación general
├── INSTRUCCIONES.md       # Esta guía de instalación
├── CONTRIBUTING.md        # Guía para contribuir
├── CHANGELOG.md           # Historial de cambios
├── LICENSE                # Licencia MIT
├── .gitignore
├── admin/
│   ├── login.php
│   ├── auth.php
│   ├── dashboard.php
│   └── logout.php
├── includes/
│   └── db.php
├── api/
│   └── chatbot.php
├── js/
│   ├── cart.js
│   └── animations.js
├── css/
│   └── style.css
└── .opencode/
    └── skills/
        └── SKILL.md
```
