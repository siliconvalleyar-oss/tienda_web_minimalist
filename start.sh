#!/bin/bash
set -e

PROJ_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$PROJ_DIR"

echo "============================================"
echo "  Animation Web Skill - Iniciar Servidor"
echo "============================================"

echo ""
echo "=== 1. Verificando XAMPP ==="
if [ -f /opt/lampp/lampp ]; then
    echo "  XAMPP encontrado en /opt/lampp"
    XAMPP="/opt/lampp"
elif [ -f /opt/lampp/lampp.bak ]; then
    echo "  XAMPP encontrado"
    XAMPP="/opt/lampp"
else
    echo "  [ERROR] XAMPP no encontrado en /opt/lampp"
    echo "  Instala XAMPP primero: https://www.apachefriends.org/"
    exit 1
fi

echo ""
echo "=== 2. Iniciando servicios ==="
echo -n "  Apache... "
$XAMPP/lampp startapache 2>/dev/null && echo "OK" || echo "ya corriendo"

echo -n "  MySQL... "
$XAMPP/lampp startmysql 2>/dev/null && echo "OK" || echo "ya corriendo"

echo ""
echo "=== 3. Verificando servicios ==="
echo -n "  Apache: "
curl -s -o /dev/null -w "%{http_code}" http://localhost/ 2>/dev/null && echo "OK" || echo "FAIL"

echo -n "  MySQL: "
$XAMPP/bin/mysql -u root -e "SELECT 1" >/dev/null 2>&1 && echo "OK" || echo "FAIL"

echo ""
echo "=== 4. Configurando base de datos ==="
echo -n "  Ejecutando setup.sql... "
$XAMPP/bin/mysql -u root < setup.sql 2>/dev/null && echo "OK" || {
    echo "FAIL"
    echo "  Intentando crear BD..."
    $XAMPP/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS tienda;" 2>/dev/null
    $XAMPP/bin/mysql -u root < setup.sql 2>/dev/null && echo "  OK (reintento)" || echo "  FAIL - revisa setup.sql"
}

echo ""
echo "=== 5. Verificando archivos ==="
for f in index.php includes/db.php api/chatbot.php api/checkout.php js/cart.js js/animations.js js/main.js; do
    [ -f "$f" ] && echo "  [OK] $f" || echo "  [--] $f (falta)"
done

echo ""
echo "=== 6. Estado de la BD ==="
echo -n "  Tablas: "
$XAMPP/bin/mysql -u root -e "USE tienda_minimal; SHOW TABLES" 2>/dev/null | tail -n +2 || echo "error"

echo ""
echo "=== Listo ==="
echo "  Tienda:     http://localhost/animation_web_skill/"
echo "  Admin:      http://localhost/animation_web_skill/admin/login.php"
echo "  Usuario:    admin"
echo "  Password:   admin123"
