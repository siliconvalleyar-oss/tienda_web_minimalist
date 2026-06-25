#!/bin/bash

MYSQL="/opt/lampp/bin/mysql"

echo "Configurando base de datos en MySQL (XAMPP)"

$MYSQL -u root < setup.sql

if [ $? -eq 0 ]; then
    echo "Base de datos y tablas creadas correctamente"
else
    echo "Error al conectar con MySQL. Asegúrate de que XAMPP esté corriendo"
    exit 1
fi

echo ""
echo "Instalación completa!"
echo "Acceso al panel admin: http://localhost/animation_web_skill/admin/login.php"
echo "   Usuario: admin | Contraseña: admin123"
echo "Cambia el número de WhatsApp desde el panel"
echo "Tienda: http://localhost/animation_web_skill/"
