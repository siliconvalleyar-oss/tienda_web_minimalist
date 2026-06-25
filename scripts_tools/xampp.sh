#!/bin/bash

# ============================================
# Script: Gestor de XAMPP
# Descripción: Menú interactivo para iniciar,
#              detener, reiniciar y ver el estado
#              de los servicios de XAMPP.
# ============================================

# --- Configuración ---
XAMPP_PATH="/opt/lampp/lampp"
SCRIPT_NAME="$(basename "$0")"

# --- Colores para output ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # Sin color

# --- Funciones auxiliares ---

# Verificar si XAMPP existe
check_xampp() {
    if [[ ! -f "$XAMPP_PATH" ]]; then
        echo -e "${RED}❌ XAMPP no encontrado en: $XAMPP_PATH${NC}"
        echo -e "${YELLOW}💡 Si está en otra ruta, edita el script y cambia 'XAMPP_PATH'.${NC}"
        exit 1
    fi
}

# Verificar permisos de root
check_root() {
    if [[ $EUID -ne 0 ]]; then
        echo -e "${RED}❌ Este script debe ejecutarse como root (sudo).${NC}"
        echo -e "${YELLOW}💡 Ejecuta: sudo ./$SCRIPT_NAME${NC}"
        exit 1
    fi
}

# Mostrar estado de XAMPP
show_status() {
    echo -e "\n${BLUE}📊 Estado de los servicios XAMPP:${NC}"
    echo "-----------------------------------"
    sudo "$XAMPP_PATH" status
    echo "-----------------------------------"
}

# Iniciar todos los servicios
start_all() {
    echo -e "\n${GREEN}▶️ Iniciando todos los servicios...${NC}"
    sudo "$XAMPP_PATH" start
    echo -e "${GREEN}✅ Servicios iniciados.${NC}"
}

# Detener todos los servicios
stop_all() {
    echo -e "\n${RED}⏹️ Deteniendo todos los servicios...${NC}"
    sudo "$XAMPP_PATH" stop
    echo -e "${RED}✅ Servicios detenidos.${NC}"
}

# Reiniciar todos los servicios
restart_all() {
    echo -e "\n${YELLOW}🔄 Reiniciando todos los servicios...${NC}"
    sudo "$XAMPP_PATH" restart
    echo -e "${YELLOW}✅ Servicios reiniciados.${NC}"
}

# Iniciar solo Apache
start_apache() {
    echo -e "\n${GREEN}▶️ Iniciando Apache...${NC}"
    sudo "$XAMPP_PATH" startapache
    echo -e "${GREEN}✅ Apache iniciado.${NC}"
}

# Iniciar solo MySQL
start_mysql() {
    echo -e "\n${GREEN}▶️ Iniciando MySQL...${NC}"
    sudo "$XAMPP_PATH" startmysql
    echo -e "${GREEN}✅ MySQL iniciado.${NC}"
}

# Detener solo Apache
stop_apache() {
    echo -e "\n${RED}⏹️ Deteniendo Apache...${NC}"
    sudo "$XAMPP_PATH" stopapache
    echo -e "${RED}✅ Apache detenido.${NC}"
}

# Detener solo MySQL
stop_mysql() {
    echo -e "\n${RED}⏹️ Deteniendo MySQL...${NC}"
    sudo "$XAMPP_PATH" stopmysql
    echo -e "${RED}✅ MySQL detenido.${NC}"
}

# --- Menú principal ---
show_menu() {
    clear
    echo -e "${CYAN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║      🚀 GESTOR DE XAMPP                ║${NC}"
    echo -e "${CYAN}╚══════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${BLUE}1.${NC} Ver estado de los servicios"
    echo -e "${GREEN}2.${NC} Iniciar TODOS los servicios"
    echo -e "${RED}3.${NC} Detener TODOS los servicios"
    echo -e "${YELLOW}4.${NC} Reiniciar TODOS los servicios"
    echo ""
    echo -e "${GREEN}5.${NC} Iniciar solo Apache"
    echo -e "${GREEN}6.${NC} Iniciar solo MySQL"
    echo -e "${RED}7.${NC} Detener solo Apache"
    echo -e "${RED}8.${NC} Detener solo MySQL"
    echo ""
    echo -e "${CYAN}9.${NC} Abrir panel gráfico (manager-linux)"
    echo -e "${RED}0.${NC} Salir"
    echo ""
    echo -n "Selecciona una opción: "
}

# --- Lógica principal ---

# Verificaciones iniciales
check_root
check_xampp

# Bucle principal
while true; do
    show_menu
    read -r option

    case $option in
        1) show_status ;;
        2) start_all ;;
        3) stop_all ;;
        4) restart_all ;;
        5) start_apache ;;
        6) start_mysql ;;
        7) stop_apache ;;
        8) stop_mysql ;;
        9)
            echo -e "\n${BLUE}🖥️ Abriendo panel de control gráfico...${NC}"
            sudo /opt/lampp/manager-linux.run &
            ;;
        0)
            echo -e "\n${CYAN}👋 ¡Hasta luego!${NC}"
            exit 0
            ;;
        *)
            echo -e "\n${RED}❌ Opción no válida. Intenta de nuevo.${NC}"
            sleep 1.5
            ;;
    esac

    # Pausa después de cada acción (excepto salir)
    if [[ $option -ne 0 ]]; then
        echo ""
        read -n 1 -s -r -p "Presiona cualquier tecla para continuar..."
    fi
done

