# Contexto del Proyecto: Sistema de Gestión de Aceite (FCA - UNCUYO)

Documento de referencia técnica para agentes de Inteligencia Artificial y desarrolladores. Contiene el contexto mínimo necesario, arquitectura, patrones de diseño, reglas de negocio y comandos esenciales para mantener y evolucionar la aplicación.

---

## 1. Visión General del Proyecto

* **Objetivo:** Sistema web para la administración y control de inventario de aceite de oliva en la **Facultad de Ciencias Agrarias (UNCUYO)**. Permite gestionar clientes (productores), registrar movimientos (ingresos y extracciones/salidas), aplicar mermas porcentuales (individuales o masivas) y generar reportes de saldos en formato PDF para liquidación.
* **Dominio / Producción:** `https://aceite.fca.uncu.edu.ar` (desplegado en servidor `webs` `172.22.8.4` sobre puerto `8080` detrás de un Reverse Proxy con SSL).

---

## 2. Stack Tecnológico

| Capa | Tecnología | Versión | Notas clave |
| :--- | :--- | :--- | :--- |
| **Runtime** | PHP | `8.3.x` | Base Docker `php:8.3-apache` con extensiones `pdo_pgsql`, `gd`, `bcmath`, `zip`. |
| **Framework** | Laravel | `12.x` | Modernizado con autenticación Jetstream / Fortify / Sanctum. |
| **Componentes Reactivos** | Livewire | `3.8.x` | Componentes en `App\Livewire`, eventos `$this->dispatch()`, `wire:model.live`. |
| **Base de Datos** | PostgreSQL | `15` (Docker) | Contenedor `aceite_db`, base `aceite`, usuario `aceite_user`. |
| **Build System / Frontend** | Vite 5 + Bootstrap 5 | `5.x` | Compilación SCSS/JS con Vite (`@vite`), Bootstrap 5 (`data-bs-*`), FontAwesome 5. |
| **Generador de PDF** | DomPDF | `3.1.x` | Paquete `barryvdh/laravel-dompdf` para reportes de saldos. |
| **Infraestructura** | Docker & Docker Compose | `Compose v5+` | Servicios `app` (puerto configurable `APP_PORT`) y `db` (PostgreSQL). |

---

## 3. Estructura de Directorios Clave

```text
aceite/
├── app/
│   ├── Http/Controllers/
│   │   ├── Mermas.php               # Controlador para cálculo y aplicación de mermas
│   │   └── ReportController.php     # Generación de reportes PDF (/saldos)
│   ├── Livewire/
│   │   ├── Clientes.php             # CRUD y búsqueda de clientes (App\Livewire)
│   │   ├── Movimientos.php          # CRUD, filtros combinados y sumatorias de movimientos
│   │   └── SelectCliente.php        # Selector auxiliar de clientes
│   ├── Models/
│   │   ├── Cliente.php              # Modelo Cliente (hasMany Movimiento)
│   │   ├── Movimiento.php           # Modelo Movimiento (belongsTo Cliente)
│   │   └── User.php                 # Modelo Usuario (autenticación)
│   └── Providers/
│       └── AppServiceProvider.php   # Registro de componentes Blade y forzado HTTPS en prod
├── docker/
│   └── entrypoint.sh                # Script de inicio del contenedor app (permisos y setup)
├── resources/
│   ├── js/                          # bootstrap.js (Popper, jQuery, Bootstrap) y app.js
│   ├── sass/app.scss                # Estilos Sass y Bootstrap 5
│   └── views/
│       ├── layouts/app.blade.php    # Layout principal (@vite, @livewireScripts, modal listeners)
│       ├── layouts/guest.blade.php  # Layout público / login (@vite)
│       ├── livewire/
│       │   ├── clientes/            # Vistas Blade de clientes (view, create, update)
│       │   └── movimientos/         # Vistas Blade de movimientos (view, create, update)
│       ├── mermas/index.blade.php   # Formulario de mermas masivas e individuales
│       └── reportes/saldosreport.blade.php # Plantilla HTML/PDF para reporte de saldos
├── routes/web.php                   # Rutas web protegidas con middleware auth
├── vite.config.js                   # Configuración del bundler Vite
├── Dockerfile                       # Definición de imagen PHP 8.3 Apache
└── docker-compose.yml               # Orquestación de servicios app y db
```

---

## 4. Reglas de Negocio y Convenciones Técnicas

### 4.1. Movimientos y Cantidades
* **Tipos de movimiento:** `'Ingreso'` y `'Salida'`.
* **Convención de signos:**
  * Los **Ingresos** se almacenan con valor positivo (ej. `500.00`).
  * Las **Salidas** se almacenan con valor negativo (ej. `-100.00`).
  * En la interfaz de edición (`app/Livewire/Movimientos.php`), el valor se muestra en valor absoluto (`abs($cantidad)`) para facilitar la lectura del usuario, y el backend ajusta el signo automáticamente antes de guardar según el `tipo_mov` seleccionado.
* **Cálculo de Totales:** La suma total (`$tott`) y saldos se calculan como la sumatoria aritmética directa de la columna `cantidad` (`DB::raw('SUM(cantidad)')`), respetando los filtros activos.

### 4.2. Filtros en la Tabla de Movimientos
* `Movimientos.php` implementa filtros reactivos que reinician la paginación (`$this->resetPage()`):
  * `filtro_cliente_id`: Filtra por el ID del cliente.
  * `filtro_tipo_mov`: Filtra por `'Ingreso'` o `'Salida'`.
  * `fecha_desde` y `fecha_hasta`: Rango de fechas inclusivo (`whereDate`).
  * `keyWord`: Búsqueda de texto en detalle, nombre de cliente o código FCA (`ILIKE` en PostgreSQL).
  * `limpiarFiltros()`: Restablece todos los filtros a vacío.

### 4.3. Modales y Bootstrap 5
* Todos los modales utilizan atributos compatibles con Bootstrap 5 (`data-bs-toggle="modal"`, `data-bs-target="#updateModal"`, `data-bs-dismiss="modal"`, `data-bs-backdrop="static"`).
* **Ciclo de Edición:**
  1. El usuario hace clic en **Editar** en el menú de acciones (`wire:click="edit($id)"`).
  2. El método `edit($id)` en el componente Livewire busca el registro, puebla las propiedades y dispara el evento `$this->dispatch('showUpdateModal')`.
  3. El listener en `layouts/app.blade.php` abre el modal con `bootstrap.Modal.getOrCreateInstance(...)`.
  4. Al guardar (`update()`), se valida, se actualiza el registro en la BD y se dispara `$this->dispatch('closeModal')`.

### 4.4. Base de Datos y Modelos
* **Llaves Foráneas:** `movimientos.cliente_id` referencia a `clientes.id`.
* **Campos Nullables en Clientes:** `domicilio`, `telefono`, `email`, `contacto`, `rut` tienen validación `nullable` en Livewire y guardan fallback `?? ''` para satisfacer las restricciones `NOT NULL` de la tabla en PostgreSQL.

---

## 5. Comandos de Desarrollo y Operación

### 5.1. Entorno Local (Docker)
```bash
# Iniciar contenedores locales
docker compose up -d

# Reconstruir contenedores tras cambios en Dockerfile o dependencias
docker compose up -d --build

# Ejecutar comandos de Artisan
docker compose exec app php artisan <comando>

# Limpiar todas las cachés
docker compose exec app php artisan optimize:clear

# Compilar frontend con Vite
npm run build       # Producción
npm run dev         # Desarrollo con hot-reload

# Entrar a tinker para pruebas
docker compose exec app php artisan tinker
```

### 5.2. Despliegue en Servidor Remoto (`webs`)
```bash
# Acceso SSH al servidor
ssh webs

# Ubicación del proyecto en producción
cd /home/lfontes/apps/aceite

# Levantar contenedores en producción (puerto 8080)
docker compose up -d

# Optimizar aplicación en producción
docker compose exec app php artisan optimize
```

---

## 6. Checklist para Futuras Modificaciones

1. **Si agregas campos a Movimientos o Clientes:**
   * Crear la migración correspondiente (`php artisan make:migration ...`).
   * Actualizar `$fillable` en el Modelo (`Cliente.php` o `Movimiento.php`).
   * Actualizar las propiedades públicas, `validate()`, `resetInput()`, `store()`, `edit()` y `update()` en el componente `App\Livewire\...`.
   * Agregar los inputs correspondientes tanto en `create.blade.php` como en `update.blade.php`.
2. **Si modificas estilos o JavaScript:**
   * Ejecutar siempre `npm run build` para actualizar el manifest y los bundles en `public/build/`.
3. **Mantenimiento de Livewire:**
   * Utilizar siempre `$this->dispatch()` para eventos (Livewire 3 no usa `$this->emit()`).
   * Para inputs de búsqueda en tiempo real usar `wire:model.live.debounce.300ms`.
