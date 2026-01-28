# Credenciales de Acceso - SGTech RRHH

## 🔐 Usuarios del Sistema

Estas son las credenciales internas para acceder al sistema de gestión de RRHH.

### Usuario Administrador
- **Email**: `admin@gmail.com`
- **Password**: `Adm1n$ecur3P@ssw0rd2026!`
- **Rol**: Admin
- **Permisos**: Acceso completo al sistema

### Usuario RRHH
- **Email**: `rrhh@gmail.com`
- **Password**: `RRHH$ecur3P@ssw0rd2026!`
- **Rol**: RRHH
- **Permisos**: Gestión de empleados e invitaciones

---

## 📝 Notas Importantes

> [!WARNING]
> **Estas credenciales son para uso interno únicamente.**
> - No compartir fuera del equipo de desarrollo
> - Cambiar en producción por credenciales diferentes
> - Los emails no existen realmente, son solo identificadores

## 🚀 Cómo usar

### Primera vez (resetear base de datos)
```bash
# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed
```

### Solo ejecutar seeders
```bash
php artisan db:seed
```

### Acceder al sistema
1. Iniciar la aplicación:
   - **Docker**: `docker-compose up -d` → http://localhost
   - **Local**: `php artisan serve` → http://localhost:8000

2. Usar las credenciales de arriba para login

---

**Última actualización**: 2026-01-28
