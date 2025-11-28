<?php

namespace App\Enums;

enum RolUsuario: string
{
    case ADMINISTRADOR = 'administrador';
    case COORDINADOR = 'coordinador';
    case CLIENTE = 'cliente';
    case ESTUDIANTE = 'estudiante';

    /**
     * Get the label for this role.
     */
    public function label(): string
    {
        return match($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::COORDINADOR => 'Coordinador Académico',
            self::CLIENTE => 'Cliente/Comprador',
            self::ESTUDIANTE => 'Estudiante/Beneficiario',
        };
    }

    /**
     * Get the description for this role.
     */
    public function descripcion(): string
    {
        return match($this) {
            self::ADMINISTRADOR => 'Gestiona catálogos, instructores y configuración del sistema',
            self::COORDINADOR => 'Gestiona pagos, asigna instructores y confirma clases',
            self::CLIENTE => 'Compra paquetes de créditos y distribuye entre beneficiarios',
            self::ESTUDIANTE => 'Consume créditos en clases y consulta saldo',
        };
    }

    /**
     * Get home route for this role.
     */
    public function homeRoute(): string
    {
        return match($this) {
            self::ADMINISTRADOR => 'admin.dashboard',
            self::COORDINADOR => 'coordinador.dashboard',
            self::CLIENTE => 'cliente.dashboard',
            self::ESTUDIANTE => 'estudiante.dashboard',
        };
    }

    /**
     * Get all roles as array.
     */
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
