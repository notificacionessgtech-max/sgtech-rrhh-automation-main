<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de contratación - SGTech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ====== RESET BÁSICO ====== */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 2rem;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        /* ====== CONTENEDOR ====== */
        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        /* ====== TEXTO ====== */
        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2rem;
            text-align: center;
        }

        /* ====== ALERTAS ====== */
        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #22c55e;
            color: #166534;
        }
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        /* ====== FIELDSET / SECCIONES ====== */
        .form-section {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            background: #fff;
        }
        .form-section legend {
            padding: 0 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #334155;
        }

        /* ====== GRID DE INPUTS ====== */
        .input-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
        }
        .required-star {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
            background: #fff;
            color: #1e293b;
            transition: all 0.2s;
        }
        input[type="file"] {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ====== ERRORES ====== */
        .field-error {
            margin-top: 0.375rem;
            font-size: 0.8rem;
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .border-error {
            border-color: #ef4444 !important;
            background-color: #fffafb;
        }

        /* ====== BOTÓN ====== */
        .btn-submit-form {
            width: 100%;
            padding: 1rem;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            background: #2563eb;
            color: #fff;
            transition: all 0.2s;
            margin-top: 1rem;
        }
        .btn-submit-form:hover { background: #1d4ed8; }
        .btn-submit-form:active { transform: scale(0.98); }
        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* ====== DOCUMENTOS (NUEVO DISEÑO) ====== */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        .doc-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            position: relative;
        }
        .doc-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
            transform: translateY(-2px);
        }
        .doc-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .doc-icon {
            width: 40px;
            height: 40px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 1.25rem;
        }
        .doc-label {
            font-weight: 600;
            font-size: 0.95rem;
            color: #1e293b;
        }
        .file-upload-wrapper {
            position: relative;
        }
        .file-input-custom {
            width: 100%;
            font-size: 0.85rem;
            color: #64748b;
            padding: 0.5rem;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .file-input-custom:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }
        .file-name-display {
            font-size: 0.75rem;
            color: #22c55e;
            margin-top: 0.25rem;
            display: none;
            align-items: center;
            gap: 0.25rem;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 640px) {
            body { padding: 1rem; }
            .form-container { padding: 1.5rem; }
            .documents-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Ficha de Contratación</h1>

        <div id="formAlert" style="display: none;"></div>

        <form id="hiringForm"
              action="{{ route('hiring.post') }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            <input type="hidden" name="invitation_uuid" value="{{ $invitation->uuid }}">

            {{-- DATOS PERSONALES --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-user mr-2"></i> Datos Personales</legend>

                <div class="input-row">
                    <div>
                        <label for="hiring_date">Fecha de contratación <span class="required-star">*</span></label>
                        <input type="date" id="hiring_date" name="hiring_date" value="{{ old('hiring_date') }}" required>
                    </div>
                    <div>
                        <label for="job_position">Cargo / Puesto <span class="required-star">*</span></label>
                        <input type="text" id="job_position" name="job_position" value="{{ old('job_position') }}" required>
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="place_of_birth">Lugar de nacimiento <span class="required-star">*</span></label>
                        <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" required>
                    </div>
                    <div>
                        <label for="nationality">Nacionalidad <span class="required-star">*</span></label>
                        <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" required
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="first_name">Nombre <span class="required-star">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                    <div>
                        <label for="middle_name">Segundo nombre</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="last_name">Apellido <span class="required-star">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                    <div>
                        <label for="second_last_name">Segundo apellido</label>
                        <input type="text" id="second_last_name" name="second_last_name" value="{{ old('second_last_name') }}"
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="dni">Cédula <span class="required-star">*</span></label>
                        <input type="text" id="dni" name="dni" value="{{ old('dni') }}" required
                               onkeypress="return /[0-9]/.test(event.key)">
                    </div>
                    <div>
                        <label for="date_of_issue">Fecha de expedición <span class="required-star">*</span></label>
                        <input type="date" id="date_of_issue" name="date_of_issue" value="{{ old('date_of_issue') }}" required>
                    </div>
                    <div>
                        <label for="place_of_issue">Lugar de expedición <span class="required-star">*</span></label>
                        <input type="text" id="place_of_issue" name="place_of_issue" value="{{ old('place_of_issue') }}" required>
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="birthdate">Fecha de nacimiento <span class="required-star">*</span></label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                    </div>
                    <div>
                        <label for="gender">Sexo <span class="required-star">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Seleccione</option>
                            <option value="Masculino" {{ old('gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('gender') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                    <div>
                        <label for="marital_status">Estado civil <span class="required-star">*</span></label>
                        <select id="marital_status" name="marital_status" required>
                            <option value="">Seleccione</option>
                            @foreach(['Soltero','Casado','Divorciado','Viudo','Unión libre'] as $ms)
                                <option value="{{ $ms }}" {{ old('marital_status') == $ms ? 'selected' : '' }}>{{ $ms }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="email">Correo electrónico <span class="required-star">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label for="phone_number">Teléfono <span class="required-star">*</span></label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required
                               onkeypress="return /[0-9]/.test(event.key)">
                    </div>
                    <div>
                        <label for="address">Dirección <span class="required-star">*</span></label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required>
                    </div>
                </div>

                <div class="input-row">
                    <div>
                        <label for="eps">EPS <span class="required-star">*</span></label>
                        <input type="text" id="eps" name="eps" value="{{ old('eps') }}" required>
                    </div>
                    <div>
                        <label for="blood_group">Grupo sanguíneo <span class="required-star">*</span></label>
                        <select id="blood_group" name="blood_group" required>
                            <option value="">Seleccione</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- DATOS BANCARIOS --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-university mr-2"></i> Datos Bancarios</legend>
                <div class="input-row">
                    <div>
                        <label for="banking_entity">Banco</label>
                        <input type="text" id="banking_entity" name="banking_entity" value="{{ old('banking_entity') }}">
                    </div>
                    <div>
                        <label for="account_number">Número de cuenta</label>
                        <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}"
                               onkeypress="return /[0-9]/.test(event.key)">
                    </div>
                    <div>
                        <label for="account_type">Tipo de cuenta</label>
                        <select id="account_type" name="account_type">
                            <option value="">Seleccione</option>
                            <option value="Ahorros" {{ old('account_type') == 'Ahorros' ? 'selected' : '' }}>Ahorros</option>
                            <option value="Corriente" {{ old('account_type') == 'Corriente' ? 'selected' : '' }}>Corriente</option>
                        </select>
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="pension_fund">Fondo de Pensiones</label>
                        <input type="text" id="pension_fund" name="pension_fund" value="{{ old('pension_fund') }}">
                    </div>
                    <div>
                        <label for="severance_pay_fund">Fondo de Cesantías</label>
                        <input type="text" id="severance_pay_fund" name="severance_pay_fund" value="{{ old('severance_pay_fund') }}">
                    </div>
                </div>
            </fieldset>

            {{-- SALUD --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-heartbeat mr-2"></i> Información Médica</legend>
                <div class="input-row">
                    <div>
                        <label for="allergies">Alergias</label>
                        <input type="text" id="allergies" name="allergies" value="{{ old('allergies') }}">
                    </div>
                    <div>
                        <label for="diseases">Enfermedades</label>
                        <input type="text" id="diseases" name="diseases" value="{{ old('diseases') }}">
                    </div>
                    <div>
                        <label for="medications">Medicamentos</label>
                        <input type="text" id="medications" name="medications" value="{{ old('medications') }}">
                    </div>
                </div>
                <div>
                    <label for="additional_information">Información adicional</label>
                    <textarea id="additional_information" name="additional_information">{{ old('additional_information') }}</textarea>
                </div>
            </fieldset>

            {{-- DATOS FAMILIARES --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-users mr-2"></i> Información Familiar (Cónyuge/Hijos)</legend>
                <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 1rem;">Si no aplica, deje en blanco.</p>

                <div class="input-row">
                    <div>
                        <label for="full_name">Nombre Completo del Familiar</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                    <div>
                        <label for="relationship">Parentesco</label>
                        <input type="text" id="relationship" name="relationship" value="{{ old('relationship') }}" placeholder="Ej: Esposo/a, Hijo/a">
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="family_dni">Cédula del Familiar</label>
                        <input type="text" id="family_dni" name="family_dni" value="{{ old('family_dni') }}"
                               onkeypress="return /[0-9]/.test(event.key)">
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
                            <option value="Masculino" {{ old('family_gender') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('family_gender') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>
                    <div>
                        <label for="family_birthdate">Fecha de Nacimiento</label>
                        <input type="date" id="family_birthdate" name="family_birthdate" value="{{ old('family_birthdate') }}">
                    </div>
                </div>
            </fieldset>

            {{-- CONTACTO DE EMERGENCIA --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-ambulance mr-2"></i> Contacto de Emergencia</legend>
                <div class="input-row">
                    <div>
                        <label for="emergency_contact_full_name">Nombre completo <span class="required-star">*</span></label>
                        <input type="text" id="emergency_contact_full_name" name="emergency_contact_full_name" value="{{ old('emergency_contact_full_name') }}" required
                               onkeypress="return /[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/.test(event.key)">
                    </div>
                    <div>
                        <label for="emergency_contact_phone_number">Teléfono <span class="required-star">*</span></label>
                        <input type="text" id="emergency_contact_phone_number" name="emergency_contact_phone_number" value="{{ old('emergency_contact_phone_number') }}" required
                               onkeypress="return /[0-9]/.test(event.key)">
                    </div>
                    <div>
                        <label for="emergency_contact_relationship">Parentesco <span class="required-star">*</span></label>
                        <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" required>
                    </div>
                </div>
            </fieldset>

            {{-- INFORMACIÓN ACADÉMICA --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-graduation-cap mr-2"></i> Información Académica</legend>
                <div class="input-row">
                    <div>
                        <label for="academic_institution">Institución</label>
                        <input type="text" id="academic_institution" name="academic_institution" value="{{ old('academic_institution') }}">
                    </div>
                    <div>
                        <label for="start_date_school">Fecha inicio</label>
                        <input type="date" id="start_date_school" name="start_date_school" value="{{ old('start_date_school') }}">
                    </div>
                    <div>
                        <label for="end_date_school">Fecha finalización</label>
                        <input type="date" id="end_date_school" name="end_date_school" value="{{ old('end_date_school') }}">
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="university_career">Carrera</label>
                        <input type="text" id="university_career" name="university_career" value="{{ old('university_career') }}">
                    </div>
                    <div>
                        <label for="degree">Grado</label>
                        <input type="text" id="degree" name="degree" value="{{ old('degree') }}">
                    </div>
                    <div>
                        <label for="card_number">Tarjeta profesional</label>
                        <input type="text" id="card_number" name="card_number" value="{{ old('card_number') }}">
                    </div>
                </div>
            </fieldset>

            {{-- EDUCACIÓN ADICIONAL --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-certificate mr-2"></i> Educación Adicional</legend>
                <div class="input-row">
                    <div>
                        <label for="specialty_institution">Institución</label>
                        <input type="text" id="specialty_institution" name="specialty_institution" value="{{ old('specialty_institution') }}">
                    </div>
                    <div>
                        <label for="start_date_specialty">Fecha inicio</label>
                        <input type="date" id="start_date_specialty" name="start_date_specialty" value="{{ old('start_date_specialty') }}">
                    </div>
                    <div>
                        <label for="end_date_specialty">Fecha finalización</label>
                        <input type="date" id="end_date_specialty" name="end_date_specialty" value="{{ old('end_date_specialty') }}">
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="course">Curso</label>
                        <input type="text" id="course" name="course" value="{{ old('course') }}">
                    </div>
                    <div>
                        <label for="specialty_level">Nivel</label>
                        <select id="specialty_level" name="specialty_level">
                            <option value="">Seleccione</option>
                            @foreach(['Básico','Intermedio','Avanzado'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('specialty_level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="methodology_name">Tecnología / metodología</label>
                        <input type="text" id="methodology_name" name="methodology_name" value="{{ old('methodology_name') }}">
                    </div>
                    <div>
                        <label for="proficiency_level">Nivel</label>
                        <select id="proficiency_level" name="proficiency_level">
                            <option value="">Seleccione</option>
                            @foreach(['Básico','Intermedio','Avanzado'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('proficiency_level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-row">
                    <div>
                        <label for="language">Idioma</label>
                        <input type="text" id="language" name="language" value="{{ old('language') }}">
                    </div>
                    <div>
                        <label for="language_level">Nivel</label>
                        <select id="language_level" name="language_level">
                            <option value="">Seleccione</option>
                            @foreach(['Básico','Intermedio','Avanzado'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('language_level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- DOCUMENTOS --}}
            <fieldset class="form-section">
                <legend><i class="fas fa-file-upload mr-2"></i> Documentos</legend>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">Cargue los documentos requeridos en formato PDF o Imagen.</p>
                
                <div class="documents-grid">
                    @php
                        $docs = [
                            'eps' => ['label' => 'EPS', 'icon' => 'fa-briefcase-medical'],
                            'cv' => ['label' => 'Hoja de vida', 'icon' => 'fa-file-pdf'],
                            'nit' => ['label' => 'NIT', 'icon' => 'fa-id-card'],
                            'bank_cert' => ['label' => 'Certificación bancaria', 'icon' => 'fa-university'],
                            'pension_cert' => ['label' => 'Fondo pensiones', 'icon' => 'fa-piggy-bank'],
                            'cesantias_cert' => ['label' => 'Cesantías', 'icon' => 'fa-hand-holding-dollar'],
                            'savings_fund_cert' => ['label' => 'Fondo ahorro', 'icon' => 'fa-vault'],
                            'study_cert' => ['label' => 'Certificado estudios', 'icon' => 'fa-graduation-cap']
                        ];
                    @endphp

                    @foreach ($docs as $key => $info)
                        <div class="doc-card">
                            <div class="doc-info">
                                <div class="doc-icon">
                                    <i class="fas {{ $info['icon'] }}"></i>
                                </div>
                                <span class="doc-label">{{ $info['label'] }}</span>
                            </div>
                            <div class="file-upload-wrapper">
                                <input type="file" 
                                       name="documents[{{ $key }}]" 
                                       class="file-input-custom"
                                       onchange="updateFileName(this, '{{ $key }}')">
                                <div id="file-name-{{ $key }}" class="file-name-display">
                                    <i class="fas fa-check-circle"></i> <span class="text-truncate"></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <button type="submit" class="btn-submit-form" id="submitBtn">
                Enviar Formulario y Ir a Firma
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('hiringForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertDiv = document.getElementById('formAlert');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    clearAlerts();
                    clearErrors();

                    if (!validateForm()) {
                        showAlert('error', 'Por favor completa todos los campos obligatorios correctamente.');
                        scrollToFirstError();
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.classList.add('btn-loading');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            showAlert('success', '¡Datos guardados! Redirigiendo a la firma...');
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 1000);
                        } else {
                            if (data.errors) {
                                showValidationErrors(data.errors);
                                showAlert('error', 'Se encontraron errores de validación. Por favor corrígelos.');
                                scrollToFirstError();
                            } else {
                                showAlert('error', data.message || 'Error técnico al procesar el formulario.');
                            }
                            resetBtn(originalText);
                        }
                    } catch (error) {
                        showAlert('error', 'Hubo un problema de conexión. Intenta de nuevo.');
                        resetBtn(originalText);
                    }
                });
            }

            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        markFieldAsError(field, 'Este campo es obligatorio.');
                        isValid = false;
                    }

                    if (field.type === 'email' && field.value.trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(field.value)) {
                            markFieldAsError(field, 'Ingresa un correo electrónico válido.');
                            isValid = false;
                        }
                    }
                });

                return isValid;
            }

            function markFieldAsError(field, message) {
                field.classList.add('border-error');
                let errorDiv = field.parentNode.querySelector('.field-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'field-error';
                    field.parentNode.appendChild(errorDiv);
                }
                errorDiv.innerHTML = `<i class="fas fa-circle-exclamation mr-1"></i> ${message}`;
            }

            function clearErrors() {
                document.querySelectorAll('.border-error').forEach(el => el.classList.remove('border-error'));
                document.querySelectorAll('.field-error').forEach(el => el.remove());
            }

            function showValidationErrors(errors) {
                Object.keys(errors).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) markFieldAsError(field, errors[key][0]);
                });
            }

            function showAlert(type, message) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.innerHTML = `<i class="fas ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i><span>${message}</span>`;
                alertDiv.style.display = 'flex';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function clearAlerts() {
                alertDiv.style.display = 'none';
            }

            function resetBtn(text) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
                submitBtn.innerHTML = text;
            }

            function scrollToFirstError() {
                const firstError = document.querySelector('.border-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function updateFileName(input, key) {
                const nameDisplay = document.getElementById(`file-name-${key}`);
                const span = nameDisplay.querySelector('span');
                if (input.files && input.files[0]) {
                    span.textContent = input.files[0].name;
                    nameDisplay.style.display = 'flex';
                } else {
                    nameDisplay.style.display = 'none';
                }
            }

            window.updateFileName = updateFileName;

            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    field.classList.remove('border-error');
                    const error = field.parentNode.querySelector('.field-error');
                    if (error) error.remove();
                });
            });
        });
    </script>
</body>
</html>
