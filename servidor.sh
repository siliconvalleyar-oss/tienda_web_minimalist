#!/bin/bash
# ============================================================
# Animation Web Skill - Iniciar Servidor
# Uso: ./servidor.sh
# Requiere: sudo (para XAMPP)
# ============================================================

set -e

PROJ_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$PROJ_DIR"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

ok()   { echo -e "${GREEN}[OK]${NC} $1"; }
fail() { echo -e "${RED}[FAIL]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }

# ── Puerto del servidor ─────────────────────────────────────
DEFAULT_PORT=80
PORT=$DEFAULT_PORT

echo "============================================"
echo "  Animation Web Skill - Servidor"
echo "============================================"
echo ""
echo "  Puerto del servidor:"
echo ""
echo "    1) Puerto default (80)"
echo "    2) Puerto 8080"
echo "    3) Puerto 3000"
echo "    4) Puerto 8888"
echo "    5) Puerto personalizado"
echo ""
read -p "  Selecciona [1-5] (default: 1): " choice

case "$choice" in
    2) PORT=8080 ;;
    3) PORT=3000 ;;
    4) PORT=8888 ;;
    5)
        read -p "  Ingresa el puerto: " custom_port
        if [[ "$custom_port" =~ ^[0-9]+$ ]] && [ "$custom_port" -ge 1 ] && [ "$custom_port" -le 65535 ]; then
            PORT=$custom_port
        else
            warn "Puerto inválido, usando default (80)"
            PORT=$DEFAULT_PORT
        fi
        ;;
    *) PORT=$DEFAULT_PORT ;;
esac

echo ""
echo -e "  Puerto seleccionado: ${CYAN}$PORT${NC}"

# ── 1. Verificar XAMPP ──────────────────────────────────────
echo ""
echo "1. Verificando XAMPP..."

XAMPP="/opt/lampp"
if [ ! -f "$XAMPP/lampp" ]; then
    fail "XAMPP no encontrado en $XAMPP"
    echo "   Instala: https://www.apachefriends.org/"
    exit 1
fi
ok "XAMPP encontrado"

# ── 2. Configurar puerto Apache ─────────────────────────────
echo ""
echo "2. Configurando puerto Apache..."

APACHE_CONF="$XAMPP/etc/httpd.conf"
if [ -f "$APACHE_CONF" ]; then
    if sudo -n true 2>/dev/null; then
        # Backup del config
        sudo cp "$APACHE_CONF" "${APACHE_CONF}.bak" 2>/dev/null || true

        # Cambiar puerto
        echo -n "   Cambiando Apache a puerto $PORT... "
        if sudo sed -i "s/^Listen 80/Listen $PORT/" "$APACHE_CONF" 2>/dev/null && \
           sudo sed -i "s/^<VirtualHost \*:80>/<VirtualHost *:$PORT>/" "$XAMPP/etc/extra/httpd-vhosts.conf" 2>/dev/null; then
            ok ""
        else
            warn "No se pudo cambiar puerto"
        fi
    else
        warn "Sin permisos sudo, puerto se mantiene en 80"
        PORT=80
    fi
else
    warn "No se encontró httpd.conf"
fi

# ── 3. Iniciar servicios con sudo ───────────────────────────
echo ""
echo "3. Iniciando servicios..."

if sudo -n true 2>/dev/null; then
    echo -n "   Apache... "
    sudo $XAMPP/lampp startapache 2>/dev/null && ok "" || warn "ya corriendo"

    echo -n "   MySQL...  "
    sudo $XAMPP/lampp startmysql 2>/dev/null && ok "" || warn "ya corriendo"
else
    warn "Sin permisos sudo. Intentando sin sudo..."
    echo -n "   Apache... "
    $XAMPP/lampp startapache 2>/dev/null && ok "" || warn "necesita sudo"

    echo -n "   MySQL...  "
    $XAMPP/lampp startmysql 2>/dev/null && ok "" || warn "necesita sudo"
fi

# ── 4. Verificar servicios ──────────────────────────────────
echo ""
echo "4. Verificando servicios..."

echo -n "   Apache (puerto $PORT): "
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost:$PORT/" 2>/dev/null || echo "000")
[ "$HTTP_CODE" = "200" ] && ok "" || warn "HTTP $HTTP_CODE"

echo -n "   MySQL:  "
$XAMPP/bin/mysql -u root -e "SELECT 1" >/dev/null 2>&1 && ok "" || warn "no responde"

# ── 5. Configurar base de datos ─────────────────────────────
echo ""
echo "5. Configurando base de datos..."

echo -n "   Ejecutando setup.sql... "
if $XAMPP/bin/mysql -u root < setup.sql 2>/dev/null; then
    ok ""
else
    warn "Error al ejecutar setup.sql"
fi

# ── 6. Verificar archivos ───────────────────────────────────
echo ""
echo "6. Verificando archivos..."

REQUIRED_FILES=(
    "index.php"
    "includes/db.php"
    "api/chatbot.php"
    "api/checkout.php"
    "js/cart.js"
    "js/animations.js"
    "js/main.js"
    "admin/login.php"
    "admin/dashboard.php"
)

for f in "${REQUIRED_FILES[@]}"; do
    [ -f "$f" ] && ok "$f" || fail "$f (falta)"
done

# ── 7. Estado de la BD ──────────────────────────────────────
echo ""
echo "7. Estado de la base de datos..."

TABLES=$($XAMPP/bin/mysql -u root -e "USE tienda_minimal; SHOW TABLES" 2>/dev/null | tail -n +2)
if [ -n "$TABLES" ]; then
    COUNT=$(echo "$TABLES" | wc -l)
    ok "Base de datos activa ($COUNT tablas)"
else
    warn "No se encontraron tablas"
fi

# ── Resumen ─────────────────────────────────────────────────
echo ""
echo "============================================"
echo "  SERVIDOR LISTO"
echo "============================================"
echo ""
echo "  Puerto:  $PORT"
echo "  Tienda:  http://localhost:$PORT/animation_web_skill/"
echo "  Admin:   http://localhost:$PORT/animation_web_skill/admin/login.php"
echo ""
echo "  Usuario:  admin"
echo "  Password: admin123"
echo ""
echo "  Para detener: sudo $XAMPP/lampp stop"
echo "============================================"
