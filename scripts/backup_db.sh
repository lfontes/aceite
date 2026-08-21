#!/bin/bash
# ==============================================================================
# Script de Backup Diario para Base de Datos PostgreSQL (Aceite - FCA UNCUYO)
# ==============================================================================
# Nombre de archivo generado: aceite-DB_[fecha].sql (ej. aceite-DB_2026-08-21.sql)
# Destino: /mnt/bkps
# ==============================================================================

set -euo pipefail

# Configuración
BACKUP_DIR="${BACKUP_DIR:-/mnt/bkps}"
CONTAINER_NAME="${CONTAINER_NAME:-aceite_db}"
DB_USER="${DB_USER:-aceite_user}"
DB_NAME="${DB_NAME:-aceite}"
FECHA="$(date +%Y-%m-%d)"
BACKUP_FILE="${BACKUP_DIR}/aceite-DB_${FECHA}.sql"
TMP_FILE="${BACKUP_DIR}/.aceite-DB_${FECHA}.sql.tmp"

# Log con timestamp
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*"
}

log "=== Iniciando backup de la base de datos PostgreSQL ($DB_NAME) ==="

# 1. Verificar directorio de destino
if [ ! -d "$BACKUP_DIR" ]; then
    log "Creando directorio de destino: $BACKUP_DIR"
    mkdir -p "$BACKUP_DIR"
fi

if [ ! -w "$BACKUP_DIR" ]; then
    log "ERROR: No hay permisos de escritura en el directorio $BACKUP_DIR" >&2
    exit 1
fi

# 2. Verificar que el contenedor de PostgreSQL esté en ejecución
if ! docker ps --filter "name=^/${CONTAINER_NAME}$" --filter "status=running" --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
    log "ERROR: El contenedor '${CONTAINER_NAME}' no está en ejecución." >&2
    exit 1
fi

# 3. Ejecutar pg_dump hacia archivo temporal
log "Generando volcado desde el contenedor '$CONTAINER_NAME'..."
if docker exec "$CONTAINER_NAME" pg_dump -U "$DB_USER" -d "$DB_NAME" > "$TMP_FILE"; then
    # Verificar que el archivo generado no esté vacío
    if [ -s "$TMP_FILE" ]; then
        mv -f "$TMP_FILE" "$BACKUP_FILE"
        chmod 644 "$BACKUP_FILE"
        FILE_SIZE="$(du -h "$BACKUP_FILE" | cut -f1)"
        log "Backup completado exitosamente: $BACKUP_FILE (Tamaño: $FILE_SIZE)"
    else
        log "ERROR: El archivo generado está vacío." >&2
        rm -f "$TMP_FILE"
        exit 1
    fi
else
    log "ERROR: Falló el comando pg_dump." >&2
    rm -f "$TMP_FILE"
    exit 1
fi

log "=== Backup finalizado con éxito ==="
