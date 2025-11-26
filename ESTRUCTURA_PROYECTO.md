# Estructura del Proyecto - Academia Armonía

## 📁 Arquitectura del Proyecto

Este proyecto sigue una arquitectura **MVC mejorada** con Laravel, organizada por módulos funcionales.

---

## 🗂️ Estructura de Carpetas

### **Backend (app/)**

```
app/
├── Enums/                          # Enumeraciones del sistema
│   ├── CategoriaInstrumento.php    # Categorías de instrumentos (básico, intermedio, etc.)
│   ├── CategoriaInstructor.php     # Categorías de instructores (regular, premium, invitado)
│   ├── EstadoCompra.php            # Estados del flujo de compra
│   ├── MetodoPago.php              # Métodos de pago disponibles
│   ├── ModalidadClase.php          # Modalidades de clase (individual, dúo, grupal)
│   └── RolUsuario.php              # ⭐ Roles de usuario del sistema
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                   # 🔒 Autenticación
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   ├── ForgotPasswordController.php
│   │   │   └── ResetPasswordController.php
│   │   │
│   │   ├── Admin/                  # 🔐 Funciones de Administrador
│   │   │   ├── InstrumentoController.php   # RF-05.1: Gestión de instrumentos
│   │   │   └── InstructorController.php    # RF-05.2: Gestión de instructores
│   │   │
│   │   ├── Cliente/                # 👤 Funciones de Cliente/Comprador
│   │   │   ├── PaqueteController.php       # RF-01.1: Ver paquetes
│   │   │   └── CompraController.php        # RF-01.2: Solicitar compra
│   │   │
│   │   ├── Coordinador/            # 📋 Funciones de Coordinador
│   │   │   └── PagoController.php          # RF-01.3, RF-01.4: Gestión de pagos
│   │   │
│   │   └── Estudiante/             # 🎓 Funciones de Estudiante/Beneficiario
│   │       └── CreditoController.php       # RF-02.1: Consultar créditos
│   │
│   ├── Middleware/                 # ⭐ Middleware de autorización
│   │   ├── CheckAdministrador.php
│   │   ├── CheckCliente.php
│   │   ├── CheckCoordinador.php
│   │   ├── CheckEstudiante.php
│   │   └── CheckRole.php           # Middleware genérico para múltiples roles
│   │
│   └── Requests/                   # Validaciones de formularios
│       ├── Auth/                   # ⭐ Validaciones de autenticación
│       │   ├── LoginRequest.php
│       │   └── RegisterRequest.php
│       ├── Admin/
│       │   ├── StoreInstrumentoRequest.php
│       │   ├── UpdateInstrumentoRequest.php
│       │   ├── StoreInstructorRequest.php
│       │   └── UpdateInstructorRequest.php
│       ├── Cliente/
│       │   └── StoreCompraRequest.php
│       └── Coordinador/
│           └── VerificarPagoRequest.php
│
├── Models/                         # Modelos Eloquent
│   ├── User.php                    # ⭐ Usuario con relaciones a Role, Cliente, Beneficiario
│   ├── Role.php                    # ⭐ Roles del sistema
│   ├── Instrumento.php
│   ├── Instructor.php
│   ├── InstructorEspecialidad.php
│   ├── InstructorHorario.php
│   ├── Cliente.php
│   ├── Beneficiario.php
│   ├── Compra.php
│   ├── DistribucionCredito.php
│   └── Pago.php
│
└── Services/                       # Lógica de negocio compleja
    ├── CalculoDescuentoService.php     # Cálculo de descuentos escalonados
    ├── DistribucionCreditoService.php  # Distribución de créditos
    └── ConsumoCreditoService.php       # Consumo de créditos en clases
```

---

### **Frontend (resources/)**

```
resources/
├── views/
│   ├── auth/                       # ⭐ Autenticación
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   ├── forgot-password.blade.php
│   │   └── reset-password.blade.php
│   │
│   ├── layouts/                    # Plantillas base
│   │   ├── app.blade.php           # Layout principal
│   │   ├── navigation.blade.php    # Barra de navegación
│   │   ├── admin.blade.php         # Layout para administrador
│   │   └── cliente.blade.php       # Layout para cliente
│   │
│   ├── components/                 # Componentes reutilizables
│   │   ├── alert.blade.php         # Alertas (success, error, warning, info)
│   │   ├── button.blade.php        # Botones (primary, secondary, danger)
│   │   ├── card.blade.php          # Tarjetas con contenido
│   │   └── table.blade.php         # Tablas responsivas
│   │
│   ├── admin/
│   │   ├── instrumentos/           # Gestión de instrumentos
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   └── instructores/           # Gestión de instructores
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       ├── edit.blade.php
│   │       └── show.blade.php
│   │
│   ├── cliente/
│   │   ├── paquetes/               # Ver paquetes disponibles
│   │   │   └── index.blade.php
│   │   └── compras/                # Proceso de compra
│   │       ├── create.blade.php
│   │       └── confirmacion.blade.php
│   │
│   ├── coordinador/
│   │   └── pagos/                  # Gestión de pagos
│   │       ├── index.blade.php     # Lista de órdenes pendientes
│   │       ├── solicitar.blade.php # Solicitar pago
│   │       └── verificar.blade.php # Verificar y confirmar pago
│   │
│   └── estudiante/
│       └── creditos/               # Consulta de créditos
│           ├── saldo.blade.php     # Saldo disponible
│           └── historial.blade.php # Historial de consumo
│
└── js/
    └── modules/                    # Módulos JavaScript
        ├── calcular-descuento.js   # Cálculo de descuentos en tiempo real
        └── distribucion-creditos.js # Validación de distribución
```

---

### **Base de Datos (database/)**

```
database/
├── migrations/                     # ⏳ PENDIENTE - Se creará después
│   └── (Migraciones se agregarán luego)
│
├── seeders/                        # Datos de prueba
│   ├── InstrumentoSeeder.php
│   ├── InstructorSeeder.php
│   └── DatabaseSeeder.php
│
└── factories/                      # Factories para testing
    ├── InstrumentoFactory.php
    └── InstructorFactory.php
```

---

## 🎯 Funcionalidades de la Parte 1

| # | Requerimiento | Controlador | Vistas |
|---|---------------|-------------|---------|
| 1 | **RF-05.1**: Gestión de instrumentos | `Admin\InstrumentoController` | `admin/instrumentos/*` |
| 2 | **RF-05.2**: Gestión de instructores | `Admin\InstructorController` | `admin/instructores/*` |
| 3 | **RF-01.1**: Ver paquetes | `Cliente\PaqueteController` | `cliente/paquetes/index` |
| 4 | **RF-01.2**: Solicitar compra | `Cliente\CompraController` | `cliente/compras/*` |
| 5 | **RF-01.3**: Solicitar pago | `Coordinador\PagoController` | `coordinador/pagos/solicitar` |
| 6 | **RF-01.4**: Verificar pago | `Coordinador\PagoController` | `coordinador/pagos/verificar` |
| 7 | **RF-02.1**: Consultar créditos | `Estudiante\CreditoController` | `estudiante/creditos/saldo` |

---

## 📊 Modelo de Datos (Relaciones)

```
User (Laravel default)
  └── hasOne → Cliente
  └── hasOne → Beneficiario

Cliente
  └── hasMany → Compra

Compra
  ├── belongsTo → Cliente
  ├── hasMany → DistribucionCredito
  └── hasOne → Pago

DistribucionCredito
  ├── belongsTo → Compra
  └── belongsTo → Beneficiario

Beneficiario
  └── hasMany → DistribucionCredito

Instrumento
  └── hasMany → InstructorEspecialidad

Instructor
  ├── hasMany → InstructorEspecialidad
  └── hasMany → InstructorHorario

InstructorEspecialidad
  ├── belongsTo → Instructor
  └── belongsTo → Instrumento
```

---

## 🔧 Servicios Implementados

### **CalculoDescuentoService**
- `calcularPorcentajeDescuento(int $minutos): float`
- `calcularPrecioCompra(int $minutos): array`
- `obtenerPaquetesDisponibles(): array`

### **DistribucionCreditoService**
- `distribuirCreditos(Compra $compra, array $beneficiariosData): void`
- `transferirCreditos(Beneficiario $origen, Beneficiario $destino, int $minutos): bool`

### **ConsumoCreditoService**
- `calcularConsumo(int $duracion, float $factorInst, float $factorMod, float $factorInstr): int`
- `tieneCreditosSuficientes(Beneficiario $beneficiario, int $minutos): bool`

---

## 🎨 Stack Tecnológico

- **Backend**: Laravel 12 + PHP 8.2
- **Frontend**: Blade Templates + TailwindCSS v4
- **Build**: Vite 7
- **Base de datos**: SQLite
- **JavaScript**: Vanilla JS con módulos ES6

---

## 📝 Próximos Pasos

1. ✅ Estructura de carpetas creada
2. ✅ Modelos creados
3. ✅ Controladores creados
4. ✅ Vistas creadas
5. ✅ Requests (validaciones) creados
6. ✅ Services creados
7. ✅ Enums creados
8. ⏳ **PENDIENTE**: Crear migraciones
9. ⏳ **PENDIENTE**: Definir rutas en `routes/web.php`
10. ⏳ **PENDIENTE**: Implementar lógica en controladores
11. ⏳ **PENDIENTE**: Diseñar vistas con TailwindCSS

---

## 📌 Notas Importantes

- Los archivos están **organizados pero vacíos** (excepto estructura base)
- Las **migraciones NO fueron creadas** como solicitaste
- Todos los controladores tienen métodos base del Resource Controller
- Las validaciones están definidas en las clases Request
- Los Enums incluyen factores de costo según requerimientos
- Las vistas usan componentes reutilizables (alert, button, card, table)

---

**Fecha de creación**: 2024-11-25
**Versión**: 1.0 - Estructura Base
