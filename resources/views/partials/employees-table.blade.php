{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            Lista de usuarios que han llenado la ficha de contratación
        </h2>
    </x-slot>

    <div class="overflow-x-auto my-8 border border-gray-300 rounded-lg">
        <table class="w-full min-w-[1000px] border-collapse text-sm">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th colspan="2" class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Posición</th>
                    <th class="px-4 py-3 text-left">Correo</th>
                    <th class="px-4 py-3 text-left">Teléfono</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td colspan="2" class="px-4 py-3 whitespace-nowrap">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $user->job_position }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $user->phone_number }}</td>
                        <td class="px-4 py-3 whitespace-nowrap flex gap-2">
                            <button
                                class="btn-detail bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-semibold shadow focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1 transition"
                                data-id="{{ $user->personal_data_id }}">
                                Detalles
                            </button>
                            @if ($user->documents->isNotEmpty())
                                <a href="{{ route('employees.download.all', $user->personal_data_id) }}"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold shadow focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1 transition">
                                    Descargar documentos
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">No hay datos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>


    {{-- MODAL REDISEÑADO --}}
    <div id="userDetailModal"
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full modal-hidden z-50 flex items-center justify-center p-4">
        <div
            class="relative w-full max-w-6xl bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl transform transition-all">

            {{-- Header del Modal - Diseño Premium --}}
            <div
                class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-t-2xl px-8 py-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl backdrop-blur-sm">
                            <i class="fas fa-user-circle text-3xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold via-blue-700 tracking-tight">Perfil del Empleado</h3>
                            <p class="text-blue-100 text-sm mt-1">Información detallada y documentación</p>
                        </div>
                    </div>
                    <button onclick="closeModal()"
                        class="group bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-xl p-2 transition-all duration-200 backdrop-blur-sm">
                        <i class="fas fa-times text-xl group-hover:rotate-90 transition-transform duration-200"></i>
                    </button>
                </div>

                {{-- Contador de documentos en el header --}}
                <div class="absolute -bottom-4 left-8 bg-white rounded-lg shadow-lg px-4 py-2 flex items-center space-x-2"
                    id="documentCount">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span class="text-sm font-semibold text-gray-700">Cargando...</span>
                </div>
            </div>

            {{-- Contenido del Modal con scroll suave --}}
            <div class="mt-8 px-8 py-6 overflow-y-auto" style="max-height: calc(90vh - 200px);"
                id="user-detail-content">
                {{-- Loading spinner mejorado --}}
                <div class="flex flex-col items-center justify-center py-16" id="loadingSpinner">
                    <div class="relative">
                        <div class="spinner-border w-16 h-16 border-4 border-blue-200 rounded-full"></div>
                        <div
                            class="absolute top-0 left-0 w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin">
                        </div>
                    </div>
                    <p class="mt-6 text-gray-600 font-medium">Cargando información completa...</p>
                    <p class="mt-2 text-gray-400 text-sm">Por favor espera un momento</p>
                </div>
            </div>

            {{-- Footer del Modal - Diseño Moderno --}}
            <div
                class="bg-gray-50 border-t border-gray-200 px-8 py-5 rounded-b-2xl flex justify-end items-center space-x-3">
                <button onclick="closeModal()"
                    class="group px-6 py-2.5 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 rounded-xl font-semibold transition-all duration-200 flex items-center space-x-2 shadow-sm hover:shadow">
                    <i class="fas fa-times group-hover:rotate-90 transition-transform duration-200"></i>
                    <span>Cerrar</span>
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- JAVASCRIPT --}}
<script>
    // Agregar Font Awesome para iconos
    if (!document.querySelector('link[href*="font-awesome"]')) {
        const faLink = document.createElement('link');
        faLink.rel = 'stylesheet';
        faLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
        document.head.appendChild(faLink);
    }

    let currentEmployeeId = null;

    // ==================== FUNCIONES DE UTILIDAD ====================

    function formatDate(dateString) {
        if (!dateString) return 'No especificado';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (e) {
            return dateString;
        }
    }

    function getDisplayValue(value, defaultValue = 'No especificado') {
        if (value === null || value === undefined || value === '' || value === 'null') {
            return defaultValue;
        }
        return value;
    }

    function translateValue(value, type) {
        const translations = {
            gender: {
                'male': 'Masculino',
                'female': 'Femenino'
            },
            marital_status: {
                'single': 'Soltero/a',
                'married': 'Casado/a',
                'divorced': 'Divorciado/a',
                'widowed': 'Viudo/a',
                'free union': 'Unión libre'
            },
            account_type: {
                'current': 'Corriente',
                'savings': 'Ahorros',
                'payroll': 'Nómina'
            },
            level: {
                'basic': 'Básico',
                'intermediate': 'Intermedio',
                'advanced': 'Avanzado'
            }
        };

        return translations[type]?.[value] || value;
    }

    // ==================== FUNCIONES PARA CREAR HTML ====================

    function createSection(title, icon, content) {
        return `
        <div class="mb-6 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 flex items-center">
                <div class="bg-blue-600 p-2.5 rounded-lg mr-3 shadow-sm">
                    <i class="${icon} text-white text-lg"></i>
                </div>
                <h4 class="font-bold text-gray-800 text-lg">${title}</h4>
            </div>
            <div class="p-6 bg-white">
                ${content}
            </div>
        </div>
    `;
    }

    function createDataGrid(items, columns = 2) {
        return `
        <div class="grid grid-cols-1 md:grid-cols-${columns} gap-6">
            ${items.join('')}
        </div>
    `;
    }

    function createDataItem(label, value) {
        return `
        <div class="space-y-2 group">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">${label}</p>
            <p class="text-gray-900 font-medium text-base group-hover:text-blue-600 transition-colors">${value}</p>
        </div>
    `;
    }


    // ==================== FUNCIÓN PRINCIPAL DE RENDERIZADO ====================

    function renderCompleteEmployeeData(employee) {
        const modalContent = document.getElementById('user-detail-content');
        const documentCount = document.getElementById('documentCount');

        console.log('Renderizando datos del empleado:', employee);

        let html = '';

        // 1. INFORMACIÓN PERSONAL
        const personalDataItems = [
            createDataItem('Número de cédula', getDisplayValue(employee.dni)),
            createDataItem('Lugar de expedición', getDisplayValue(employee.place_of_issue)),
            createDataItem('Fecha de expedición', formatDate(employee.date_of_issue)),
            createDataItem('Primer nombre', getDisplayValue(employee.first_name)),
            createDataItem('Segundo nombre', getDisplayValue(employee.middle_name)),
            createDataItem('Primer apellido', getDisplayValue(employee.last_name)),
            createDataItem('Segundo apellido', getDisplayValue(employee.second_last_name)),
            createDataItem('Sexo', translateValue(employee.gender, 'gender')),
            createDataItem('Dirección', getDisplayValue(employee.address)),
            createDataItem('Número telefónico', getDisplayValue(employee.phone_number)),
            createDataItem('Correo electrónico', getDisplayValue(employee.email)),
            createDataItem('Fecha de nacimiento', formatDate(employee.birthdate)),
            createDataItem('Estado civil', translateValue(employee.marital_status, 'marital_status')),
            createDataItem('Lugar de nacimiento', getDisplayValue(employee.place_of_birth)),
            createDataItem('Grupo Sanguíneo', getDisplayValue(employee.blood_group)),
            createDataItem('Nacionalidad', getDisplayValue(employee.nationality)),
            createDataItem('EPS', getDisplayValue(employee.eps))
        ];

        html += createSection(
            'Información Personal',
            'fas fa-user',
            createDataGrid(personalDataItems, 2)
        );

        // 2. INFORMACIÓN LABORAL
        const jobDataItems = [
            createDataItem('Cargo', getDisplayValue(employee.job_position)),
            createDataItem('Fecha de contratación', formatDate(employee.hiring_date))
        ];

        html += createSection(
            'Información Laboral',
            'fas fa-briefcase',
            createDataGrid(jobDataItems, 2)
        );

        // 3. DATOS BANCARIOS
        if (employee.bank_accounts && employee.bank_accounts.length > 0) {
            const bank = employee.bank_accounts[0];
            const bankDataItems = [
                createDataItem('Entidad bancaria', getDisplayValue(bank.banking_entity)),
                createDataItem('Número de cuenta bancaria', getDisplayValue(bank.account_number)),
                createDataItem('Tipo de cuenta', translateValue(bank.account_type, 'account_type')),
                createDataItem('Fondo de pensiones', getDisplayValue(bank.pension_fund)),
                createDataItem('Fondo de cesantías', getDisplayValue(bank.severance_pay_fund))
            ];

            html += createSection(
                'Datos Bancarios',
                'fas fa-university',
                createDataGrid(bankDataItems, 2)
            );
        }

        // 4. INFORMACIÓN ACADÉMICA
        if (employee.academic_information && employee.academic_information.length > 0) {
            const academic = employee.academic_information[0];
            const academicItems = [
                createDataItem('Institución', getDisplayValue(academic.academic_institution)),
                createDataItem('Carrera', getDisplayValue(academic.university_career)),
                createDataItem('Fecha de inicio', formatDate(academic.start_date_school)),
                createDataItem('Fecha de fin', formatDate(academic.end_date_school)),
                createDataItem('Grado', getDisplayValue(academic.degree)),
                createDataItem('Número de tarjeta profesional', getDisplayValue(academic.card_number))
            ];

            html += createSection(
                'Información Académica',
                'fas fa-graduation-cap',
                createDataGrid(academicItems, 2)
            );
        }

        // 5. EDUCACIÓN ADICIONAL
        if (employee.additional_educations && employee.additional_educations.length > 0) {
            const additional = employee.additional_educations[0];
            let additionalContent = '';

            // Especialidades
            const specialtyItems = [
                createDataItem('Especialidad', getDisplayValue(additional.course)),
                createDataItem('Fecha de inicio', formatDate(additional.start_date_specialty)),
                createDataItem('Fecha de fin', formatDate(additional.end_date_specialty)),
                createDataItem('Institución', getDisplayValue(additional.specialty_institution)),
                createDataItem('Nivel', translateValue(additional.specialty_level, 'level'))
            ];

            additionalContent += `
            <div class="mb-4">
                <h5 class="font-semibold text-gray-700 mb-2">Especialidades</h5>
                ${createDataGrid(specialtyItems.filter(item => !item.includes('No especificado')), 2)}
            </div>
        `;

            // Tecnologías / Herramientas TI
            if (additional.methodology_name) {
                const techItems = [
                    createDataItem('Tecnología', getDisplayValue(additional.methodology_name)),
                    createDataItem('Nivel', translateValue(additional.proficiency_level, 'level'))
                ];

                additionalContent += `
                <div class="mb-4">
                    <h5 class="font-semibold text-gray-700 mb-2">Tecnologías / Herramientas TI</h5>
                    ${createDataGrid(techItems.filter(item => !item.includes('No especificado')), 2)}
                </div>
            `;
            }

            // Lenguajes
            if (additional.language) {
                const languageItems = [
                    createDataItem('Lenguaje', getDisplayValue(additional.language)),
                    createDataItem('Nivel', translateValue(additional.language_level, 'level'))
                ];

                additionalContent += `
                <div>
                    <h5 class="font-semibold text-gray-700 mb-2">Lenguajes</h5>
                    ${createDataGrid(languageItems.filter(item => !item.includes('No especificado')), 2)}
                </div>
            `;
            }

            html += createSection(
                'Educación Adicional',
                'fas fa-book',
                additionalContent
            );
        }

        // 6. INFORMACIÓN FAMILIAR
        if (employee.family_data && employee.family_data.length > 0) {
            const family = employee.family_data[0];
            const familyItems = [
                createDataItem('Parentesco', getDisplayValue(family.relationship)),
                createDataItem('Nombre', getDisplayValue(family.full_name)),
                createDataItem('Sexo', translateValue(family.gender, 'gender')),
                createDataItem('Edad', getDisplayValue(family.age)),
                createDataItem('Fecha de nacimiento', formatDate(family.birthdate)),
                createDataItem('Número de cédula', getDisplayValue(family.dni))
            ];

            html += createSection(
                'Información Familiar',
                'fas fa-users',
                createDataGrid(familyItems.filter(item => !item.includes('No especificado')), 2)
            );
        }

        // 7. INFORMACIÓN MÉDICA
        if (employee.health_data) {
            const healthItems = [
                createDataItem('Alergias', getDisplayValue(employee.health_data.allergies)),
                createDataItem('Enfermedades', getDisplayValue(employee.health_data.diseases)),
                createDataItem('Medicamentos', getDisplayValue(employee.health_data.medications)),
                createDataItem('Información adicional', getDisplayValue(employee.health_data.additional_information))
            ];

            html += createSection(
                'Información Médica',
                'fas fa-heartbeat',
                createDataGrid(healthItems.filter(item => !item.includes('No especificado')), 2)
            );
        }

        // 8. CONTACTOS DE EMERGENCIA
        if (employee.emergency_contacts && employee.emergency_contacts.length > 0) {
            const contact = employee.emergency_contacts[0];
            const contactItems = [
                createDataItem('Nombre', getDisplayValue(contact.full_name)),
                createDataItem('Número', getDisplayValue(contact.phone_number)),
                createDataItem('Parentesco', getDisplayValue(contact.relationship))
            ];

            html += createSection(
                'Contactos de Emergencia',
                'fas fa-phone-alt',
                createDataGrid(contactItems.filter(item => !item.includes('No especificado')), 2)
            );
        }

        // 9. DOCUMENTOS
        if (employee.documents && employee.documents.length > 0) {
            // Mostrar conteo en el header
            documentCount.innerHTML = `
                <i class="fas fa-file-alt text-blue-600"></i>
                <span class="text-sm font-semibold text-gray-700">${employee.documents.length} documento(s) adjunto(s)</span>
            `;

            // Lista de documentos con diseño mejorado
            let documentsContent = '';
            const documentTypes = {
                'eps': 'EPS',
                'cv': 'Hoja de Vida',
                'nit': 'NIT',
                'bank_cert': 'Certificado Bancario',
                'pension_cert': 'Certificado de Pensión',
                'cesantias_cert': 'Certificado de Cesantías',
                'savings_fund_cert': 'Certificado de Fondo de Ahorro',
                'study_cert': 'Certificado de Estudios',
                'resume': 'Hoja de Vida',
                'id_document': 'Documento de Identidad'
            };

            employee.documents.forEach((doc, index) => {
                const fileName = doc.file_path.split('/').pop();
                const docType = documentTypes[doc.document_type] || doc.document_type;

                documentsContent += `
                    <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all duration-200 ${index > 0 ? 'mt-3' : ''}">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="bg-blue-100 group-hover:bg-blue-600 p-3 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-pdf text-blue-600 group-hover:text-white text-xl transition-colors duration-200"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">${docType}</p>
                                <p class="text-sm text-gray-500 truncate mt-1">${fileName}</p>
                            </div>
                        </div>
                        <a href="/storage/${doc.file_path}"
                           download="${fileName}"
                           class="ml-4 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all duration-200 whitespace-nowrap shadow-sm hover:shadow-md flex items-center space-x-2">
                            <i class="fas fa-download"></i>
                            <span>Descargar</span>
                        </a>
                    </div>
                `;
            });

            html += createSection(
                'Documentos Adjuntos',
                'fas fa-folder-open',
                `<div class="space-y-3">${documentsContent}</div>`
            );
        } else {
            documentCount.innerHTML = `
                <i class="fas fa-file-alt text-gray-400"></i>
                <span class="text-sm font-semibold text-gray-500">Sin documentos</span>
            `;
        }

        // Insertar HTML en el modal
        modalContent.innerHTML = html;
    }

    // ==================== FUNCIONES PRINCIPALES ====================

    function showEmployeeDetails(id) {
        console.log('Solicitando detalles para ID:', id);

        currentEmployeeId = id;
        const modal = document.getElementById('userDetailModal');
        const modalContent = document.getElementById('user-detail-content');
        const documentCount = document.getElementById('documentCount');

        // Resetear
        documentCount.innerHTML = '';
        modal.classList.remove('modal-hidden');
        modalContent.innerHTML = `
        <div class="text-center py-8">
            <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-600"></div>
            <p class="mt-2 text-gray-600">Cargando información completa...</p>
        </div>
    `;

        // Obtener datos
        fetch(`/employee/${id}`)
            .then(response => {
                console.log('Respuesta HTTP:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);

                if (data.success) {
                    renderCompleteEmployeeData(data.data);
                } else {
                    throw new Error(data.message || 'Error del servidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Error:</strong> ${error.message}
                </div>
            `;
            });
    }

    function closeModal() {
        document.getElementById('userDetailModal').classList.add('modal-hidden');
        currentEmployeeId = null;
    }

    function downloadAllDocuments() {
        if (currentEmployeeId) {
            window.open(`/employees/${currentEmployeeId}/download-all`, '_blank');
        }
    }

    // ==================== INICIALIZACIÓN ====================

    document.addEventListener('DOMContentLoaded', function () {
        console.log('Dashboard cargado, inicializando eventos...');

        // Asignar eventos a botones de detalles
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function () {
                const employeeId = this.getAttribute('data-id');
                console.log('Botón clickeado, ID:', employeeId);
                showEmployeeDetails(employeeId);
            });
        });

        // Cerrar modal al hacer click fuera
        const modal = document.getElementById('userDetailModal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Cerrar con tecla Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('modal-hidden')) {
                    closeModal();
                }
            });
        }
    });
</script>

<style>
    /* Estilos mejorados y modernos */
    .spinner-border {
        border-top-color: transparent;
        border-right-color: transparent;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .modal-hidden {
        display: none !important;
    }

    /* Modal con animación de entrada */
    #userDetailModal {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    #userDetailModal>div {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Scrollbar personalizado premium */
    #user-detail-content::-webkit-scrollbar {
        width: 10px;
    }

    #user-detail-content::-webkit-scrollbar-track {
        background: linear-gradient(to bottom, #f7fafc, #edf2f7);
        border-radius: 10px;
        margin: 10px 0;
    }

    #user-detail-content::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #4299e1, #3182ce);
        border-radius: 10px;
        border: 2px solid #f7fafc;
    }

    #user-detail-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #3182ce, #2c5282);
    }

    /* Efectos de hover mejorados */
    .group:hover .group-hover\:rotate-90 {
        transform: rotate(90deg);
    }

    /* Backdrop blur para navegadores que lo soporten */
    @@supports (backdrop-filter: blur(8px)) {
        #userDetailModal {
            backdrop-filter: blur(8px);
        }
    }
</style>