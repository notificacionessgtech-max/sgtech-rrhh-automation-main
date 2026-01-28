<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de contratación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
<div class="form-container">
    <!-- Div para alertas -->
    <div id="formAlert"></div>

    <form id="hiringForm"
          action="{{ route('hiring.post') }}"
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return false;">
        @csrf
        <input type="hidden" name="invitation_uuid" value="{{ $invitation->uuid }}">

        {{-- ===========================
        DATOS PERSONALES
        =========================== --}}
        <fieldset class="form-section">
            <legend>Datos personales</legend>

            <div class="input-row">
                <div>
                    <label for="hiring_date">Fecha de contratación</label>
                    <input type="date" id="hiring_date" name="hiring_date" value="{{ old('hiring_date') }}" required>
                </div>

                <div>
                    <label for="job_position">Cargo / Puesto</label>
                    <input id="job_position" name="job_position" value="{{ old('job_position') }}" required>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="place_of_birth">Lugar de nacimiento</label>
                    <input id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" required>
                </div>

                <div>
                    <label for="nationality">Nacionalidad</label>
                    <input id="nationality" name="nationality" value="{{ old('nationality') }}" required>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="first_name">Nombre</label>
                    <input id="first_name" name="first_name" value="{{ old('first_name') }}">
                </div>

                <div>
                    <label for="middle_name">Segundo nombre</label>
                    <input id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="last_name">Apellido</label>
                    <input id="last_name" name="last_name" value="{{ old('last_name') }}">
                </div>

                <div>
                    <label for="second_last_name">Segundo apellido</label>
                    <input id="second_last_name" name="second_last_name" value="{{ old('second_last_name') }}">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="dni">Cédula</label>
                    <input id="dni" name="dni" value="{{ old('dni') }}">
                </div>

                <div>
                    <label for="date_of_issue">Fecha de expedición</label>
                    <input type="date" id="date_of_issue" name="date_of_issue" value="{{ old('date_of_issue') }}">
                </div>

                <div>
                    <label for="place_of_issue">Lugar de expedición</label>
                    <input id="place_of_issue" name="place_of_issue" value="{{ old('place_of_issue') }}">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="birthdate">Fecha de nacimiento</label>
                    <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                </div>

                <div>
                    <label for="gender">Sexo</label>
                    <select id="gender" name="gender">
                        <option value="">Seleccione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>

                <div>
                    <label for="marital_status">Estado civil</label>
                    <select id="marital_status" name="marital_status">
                        <option value="">Seleccione</option>
                        <option value="Soltero">Soltero</option>
                        <option value="Casado">Casado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viudo">Viudo</option>
                        <option value="Unión libre">Unión libre</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="email">Correo electrónico</label>
                    <input id="email" name="email" value="{{ old('email') }}">
                </div>

                <div>
                    <label for="phone_number">Teléfono</label>
                    <input id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                </div>

                <div>
                    <label for="address">Dirección</label>
                    <input id="address" name="address" value="{{ old('address') }}">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="eps">EPS</label>
                    <input id="eps" name="eps" value="{{ old('eps') }}">
                </div>

                <div>
                    <label for="blood_group">Grupo sanguíneo</label>
                    <select id="blood_group" name="blood_group">
                        <option value="">Seleccione</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}">{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        DATOS BANCARIOS
        =========================== --}}
        <fieldset class="form-section">
            <legend>Datos bancarios</legend>

            <div class="input-row">
                <div>
                    <label for="banking_entity">Banco</label>
                    <input id="banking_entity" name="banking_entity">
                </div>

                <div>
                    <label for="account_number">Número de cuenta</label>
                    <input id="account_number" name="account_number">
                </div>

                <div>
                    <label for="account_type">Tipo de cuenta</label>
                    <select id="account_type" name="account_type">
                        <option value="">Seleccione</option>
                        <option value="Ahorros">Ahorros</option>
                        <option value="Corriente">Corriente</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="pension_fund">Fondo de Pensiones</label>
                    <input id="pension_fund" name="pension_fund" value="{{ old('pension_fund') }}">
                </div>

                <div>
                    <label for="severance_pay_fund">Fondo de Cesantías</label>
                    <input id="severance_pay_fund" name="severance_pay_fund" value="{{ old('severance_pay_fund') }}">
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        SALUD
        =========================== --}}
        <fieldset class="form-section">
            <legend>Información médica</legend>

            <div class="input-row">
                <div>
                    <label for="allergies">Alergias</label>
                    <input id="allergies" name="allergies">
                </div>

                <div>
                    <label for="diseases">Enfermedades</label>
                    <input id="diseases" name="diseases">
                </div>

                <div>
                    <label for="medications">Medicamentos</label>
                    <input id="medications" name="medications">
                </div>
            </div>

            <div>
                <label for="additional_information">Información adicional</label>
                <textarea id="additional_information" name="additional_information"></textarea>
            </div>
        </fieldset>

        {{-- ===========================
        DATOS FAMILIARES
        =========================== --}}
        <fieldset class="form-section">
            <legend>Información Familiar (Cónyuge/Hijos)</legend>
            <p class="text-sm text-gray-500 mb-4">Si no aplica, deje en blanco.</p>

            <div class="input-row">
                <div>
                    <label for="full_name">Nombre Completo del Familiar</label>
                    <input id="full_name" name="full_name" value="{{ old('full_name') }}">
                </div>

                <div>
                    <label for="relationship">Parentesco</label>
                    <input id="relationship" name="relationship" value="{{ old('relationship') }}" placeholder="Ej: Esposo/a, Hijo/a">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="family_dni">Cédula del Familiar</label>
                    <input id="family_dni" name="family_dni" value="{{ old('family_dni') }}">
                </div>

                <div>
                    <label for="age">Edad</label>
                    <input type="number" id="age" name="age" value="{{ old('age') }}">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="family_gender">Género</label>
                    <select id="family_gender" name="family_gender">
                        <option value="">Seleccione</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>

                <div>
                    <label for="family_birthdate">Fecha de Nacimiento</label>
                    <input type="date" id="family_birthdate" name="family_birthdate" value="{{ old('family_birthdate') }}">
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        CONTACTO DE EMERGENCIA
        =========================== --}}
        <fieldset class="form-section">
            <legend>Contacto de emergencia</legend>

            <div class="input-row">
                <div>
                    <label for="emergency_contact_full_name">Nombre completo</label>
                    <input id="emergency_contact_full_name" name="emergency_contact_full_name" required>
                </div>

                <div>
                    <label for="emergency_contact_phone_number">Teléfono</label>
                    <input id="emergency_contact_phone_number" name="emergency_contact_phone_number" required>
                </div>

                <div>
                    <label for="emergency_contact_relationship">Parentesco</label>
                    <input id="emergency_contact_relationship" name="emergency_contact_relationship" required>
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        INFORMACIÓN ACADÉMICA
        =========================== --}}
        <fieldset class="form-section">
            <legend>Información académica</legend>

            <div class="input-row">
                <div>
                    <label for="academic_institution">Institución</label>
                    <input id="academic_institution" name="academic_institution">
                </div>

                <div>
                    <label for="start_date_school">Fecha inicio</label>
                    <input type="date" id="start_date_school" name="start_date_school">
                </div>

                <div>
                    <label for="end_date_school">Fecha finalización</label>
                    <input type="date" id="end_date_school" name="end_date_school">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="university_career">Carrera</label>
                    <input id="university_career" name="university_career">
                </div>

                <div>
                    <label for="degree">Grado</label>
                    <input id="degree" name="degree">
                </div>

                <div>
                    <label for="card_number">Tarjeta profesional</label>
                    <input id="card_number" name="card_number">
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        EDUCACIÓN ADICIONAL
        =========================== --}}
        <fieldset class="form-section">
            <legend>Educación adicional</legend>

            <div class="input-row">
                <div>
                    <label for="specialty_institution">Institución</label>
                    <input id="specialty_institution" name="specialty_institution">
                </div>

                <div>
                    <label for="start_date_specialty">Fecha inicio</label>
                    <input type="date" id="start_date_specialty" name="start_date_specialty">
                </div>

                <div>
                    <label for="end_date_specialty">Fecha finalización</label>
                    <input type="date" id="end_date_specialty" name="end_date_specialty">
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="course">Curso</label>
                    <input id="course" name="course">
                </div>

                <div>
                    <label for="specialty_level">Nivel</label>
                    <select id="specialty_level" name="specialty_level">
                        <option value="">Seleccione</option>
                        <option value="Básico">Básico</option>
                        <option value="Intermedio">Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="methodology_name">Tecnología / metodología</label>
                    <input id="methodology_name" name="methodology_name">
                </div>

                <div>
                    <label for="proficiency_level">Nivel</label>
                    <select id="proficiency_level" name="proficiency_level">
                        <option value="">Seleccione</option>
                        <option value="Básico">Básico</option>
                        <option value="Intermedio">Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div>
                    <label for="language">Idioma</label>
                    <input id="language" name="language">
                </div>

                <div>
                    <label for="language_level">Nivel</label>
                    <select id="language_level" name="language_level">
                        <option value="">Seleccione</option>
                        <option value="Básico">Básico</option>
                        <option value="Intermedio">Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                    </select>
                </div>
            </div>
        </fieldset>

        {{-- ===========================
        DOCUMENTOS
        =========================== --}}
        <fieldset class="form-section">
            <legend>Documentos</legend>

            @foreach ([
                'eps' => 'EPS',
                'cv' => 'Hoja de vida',
                'nit' => 'NIT',
                'bank_cert' => 'Certificación bancaria',
                'pension_cert' => 'Fondo pensiones',
                'cesantias_cert' => 'Cesantías',
                'savings_fund_cert' => 'Fondo ahorro',
                'study_cert' => 'Certificado estudios'
            ] as $key => $label)
                <div class="input-row">
                    <label>{{ $label }}</label>
                    <input type="file" name="documents[{{ $key }}]">
                </div>
            @endforeach
        </fieldset>

        <button type="submit" class="btn-submit-form" id="submitBtn">
            Enviar Formulario
        </button>
    </form>
</div>

<!-- Agregar Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de contratación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* ====== RESET BÁSICO ====== */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 2rem;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial;
            background: #f4f6f9;
            color: #1f2937;
        }

        /* ====== CONTENEDOR ====== */
        .form-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 2rem 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        /* ====== ALERTAS ====== */
        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .95rem;
        }
        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        /* ====== FIELDSET / SECCIONES ====== */
        .form-section {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .form-section legend {
            padding: 0 .75rem;
            font-weight: 600;
            color: #111827;
        }

        /* ====== GRID DE INPUTS ====== */
        .input-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        /* ====== INPUTS ====== */
        input,
        select,
        textarea {
            width: 100%;
            padding: .65rem .75rem;
            border-radius: .5rem;
            border: 1px solid #d1d5db;
            font-size: .95rem;
            background: #fff;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37,99,235,.15);
        }

        /* ====== ERRORES ====== */
        .field-error {
            margin-top: .25rem;
            font-size: .8rem;
            color: #dc2626;
        }
        .border-error {
            border-color: #dc2626 !important;
        }

        /* ====== DOCUMENTOS ====== */
        .file-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .file-row label {
            font-size: .9rem;
            font-weight: 500;
        }

        /* ====== BOTÓN ====== */
        .btn-submit-form {
            width: 100%;
            padding: .9rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: .6rem;
            border: none;
            cursor: pointer;
            background: #2563eb;
            color: #fff;
            transition: background .2s ease, transform .1s ease;
        }
        .btn-submit-form:hover { background: #1e40af; }
        .btn-submit-form:active { transform: scale(.98); }
        .btn-loading {
            opacity: .7;
            cursor: not-allowed;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 640px) {
            body { padding: 1rem; }
            .form-container { padding: 1.5rem; }
            .file-row { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
<div class="form-container">

    <div id="formAlert"></div>

    <!-- ⚠️ HTML y JS funcional se mantienen intactos -->

    <!-- AQUÍ VA EXACTAMENTE TU FORMULARIO ORIGINAL -->

</div>
</body>
</html>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('hiringForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertDiv = document.getElementById('formAlert');

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // Prevenir recarga normal

                console.log('Formulario enviado via AJAX');

                // Validación básica del lado del cliente
                if (!validateForm()) {
                    return;
                }

                // Mostrar estado de carga
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-loading');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...';

                // Limpiar alertas anteriores
                clearAlerts();
                clearErrors();

                try {
                    // Crear FormData
                    const formData = new FormData(form);

                    // Enviar datos via AJAX
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    console.log('Respuesta del servidor:', data);

                    if (data.success) {
                        // Éxito
                        showAlert('success', data.message || '¡Formulario enviado exitosamente!');

                        // Redirigir después de 1.5 segundos
                        setTimeout(() => {
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                window.location.href = '{{ route("hiring.form.thank_you") }}';
                            }
                        }, 1500);

                    } else {
                        // Error
                        if (data.errors) {
                            // Mostrar errores de validación
                            showValidationErrors(data.errors);
                            showAlert('error', 'Por favor corrige los errores en el formulario.');
                        } else {
                            showAlert('error', data.message || 'Error al enviar el formulario.');
                        }

                        // Restaurar botón
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.innerHTML = originalText;
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showAlert('error', 'Error de conexión: ' + error.message);

                    // Restaurar botón
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // Función para validación del lado del cliente
        function validateForm() {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    markFieldAsError(field, 'Este campo es requerido');
                    isValid = false;
                } else {
                    clearFieldError(field);
                }
            });

            return isValid;
        }

        // Función para marcar campo con error
        function markFieldAsError(field, message) {
            field.classList.add('border-error');

            let errorDiv = field.parentNode.querySelector('.field-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                field.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        // Función para limpiar error de campo
        function clearFieldError(field) {
            field.classList.remove('border-error');
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Función para mostrar alertas
        function showAlert(type, message) {
            alertDiv.innerHTML = `
            <div class="alert alert-${type}">
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            </div>
        `;
            alertDiv.style.display = 'block';
        }

        // Función para limpiar alertas
        function clearAlerts() {
            alertDiv.innerHTML = '';
            alertDiv.style.display = 'none';
        }

        // Función para limpiar todos los errores
        function clearErrors() {
            document.querySelectorAll('.border-error').forEach(el => {
                el.classList.remove('border-error');
            });
            document.querySelectorAll('.field-error').forEach(el => {
                el.remove();
            });
        }

        // Función para mostrar errores de validación del servidor
        function showValidationErrors(errors) {
            Object.keys(errors).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    markFieldAsError(field, errors[fieldName][0]);
                }
            });
        }

        // Auto-remover errores cuando el usuario empieza a escribir
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                clearFieldError(this);
            });
            field.addEventListener('change', function() {
                clearFieldError(this);
            });
        });
    });
</script>
</body>
</html>
