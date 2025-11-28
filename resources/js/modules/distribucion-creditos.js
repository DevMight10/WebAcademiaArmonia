/**
 * Validate credit distribution among beneficiaries
 */
export function validarDistribucion(minutosTotal, beneficiarios) {
    const errors = [];

    // Validate beneficiaries count
    if (beneficiarios.length === 0) {
        errors.push('Debe registrar al menos un beneficiario');
    }

    if (beneficiarios.length > 4) {
        errors.push('Máximo 4 beneficiarios permitidos');
    }

    // Calculate total distributed minutes
    const minutosDistribuidos = beneficiarios.reduce((sum, b) => {
        return sum + (parseInt(b.minutos) || 0);
    }, 0);

    // Validate sum equals total
    if (minutosDistribuidos !== minutosTotal) {
        errors.push(
            `La suma de minutos distribuidos (${minutosDistribuidos}) debe ser igual al total (${minutosTotal})`
        );
    }

    // Validate each beneficiary
    beneficiarios.forEach((beneficiario, index) => {
        if (!beneficiario.nombre || beneficiario.nombre.trim() === '') {
            errors.push(`Beneficiario ${index + 1}: El nombre es obligatorio`);
        }

        if (!beneficiario.ci || beneficiario.ci.trim() === '') {
            errors.push(`Beneficiario ${index + 1}: El CI es obligatorio`);
        }

        if (!beneficiario.email || !isValidEmail(beneficiario.email)) {
            errors.push(`Beneficiario ${index + 1}: Email inválido`);
        }

        if (!beneficiario.minutos || beneficiario.minutos <= 0) {
            errors.push(`Beneficiario ${index + 1}: Debe asignar al menos 1 minuto`);
        }
    });

    return {
        valid: errors.length === 0,
        errors,
        minutosDistribuidos,
    };
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Calculate remaining minutes to distribute
 */
export function calcularMinutosRestantes(minutosTotal, beneficiarios) {
    const distribuidos = beneficiarios.reduce((sum, b) => {
        return sum + (parseInt(b.minutos) || 0);
    }, 0);

    return minutosTotal - distribuidos;
}
