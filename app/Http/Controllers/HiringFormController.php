<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\PersonalData;
use App\Models\HealthData;
use App\Models\AcademicInformation;
use App\Models\AdditionalEducation;
use App\Models\FamilyData;
use App\Models\EmergencyContact;
use App\Models\PersonalDocument;
use App\Models\InvitationLink;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class HiringFormController extends Controller
{
    /**
     * Genera el nombre de la carpeta del empleado
     * Formato: Nombre_Apellido_DNI
     */
    private function getEmployeeFolderName($employee)
    {
        $firstName = $employee->first_name ?? '';
        $lastName = $employee->last_name ?? '';
        $dni = $employee->dni ?? '';

        // Sanitizar nombres (quitar caracteres especiales y espacios)
        $firstName = preg_replace('/[^A-Za-z0-9]/', '_', $firstName);
        $lastName = preg_replace('/[^A-Za-z0-9]/', '_', $lastName);
        $dni = preg_replace('/[^A-Za-z0-9]/', '_', $dni);

        return "{$firstName}_{$lastName}_{$dni}";
    }

    /**
     * Obtiene información completa del empleado para el modal
     */
    public function getEmployeeInformationForModal($id)
    {
        try {
            $employee = PersonalData::with([
                'familyData',
                'healthData',
                'academicInformation',
                'additionalEducations',
                'emergencyContacts',
                'bankAccounts',
                'documents',
                'invitationLink'
            ])->findOrFail($id);

            // DEBUG: Ver datos en consola
            \Log::info('Employee data:', $employee->toArray());

            return response()->json([
                'success' => true,
                'data' => $employee
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsers()
    {
        $users = PersonalData::orderBy('created_at', 'desc')->paginate(10);
        return view('partials.employees-table', compact('users')); // ← Cambia dashboard por partials.employees-table
    }

    public function getInvitations()
    {
        $invitations = InvitationLink::orderBy('created_at', 'desc')->paginate(10);
        return view('invitations', compact('invitations'));
    }
    public function showHiringForm($uuid)
    {
        $invitation = InvitationLink::where('uuid', $uuid)->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            abort(403, 'Este enlace ha expirado.');
        }

        if ($invitation->status === 'used') {
            abort(403, 'Este enlace ya fue utilizado.');
        }

        return view('hiring-form.register', compact('invitation'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validar que el UUID existe
            $request->validate([
                'invitation_uuid' => 'required|exists:invitation_links,uuid'
            ]);

            $invitation = InvitationLink::where('uuid', $request->invitation_uuid)->firstOrFail();

            // Validar todos los campos
            $validated = $request->validate([
                // PERSONAL DATA
                'hiring_date' => 'required|date',
                'job_position' => 'required|string|max:50',
                'first_name' => 'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:30',
                'middle_name' => 'nullable|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:30',
                'last_name' => 'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:30',
                'second_last_name' => 'nullable|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:30',
                'dni' => 'required|regex:/^[0-9]+$/|max:20|unique:personal_data,dni',
                'place_of_issue' => 'required|string|max:50',
                'date_of_issue' => 'required|date',
                'birthdate' => 'required|date',
                'place_of_birth' => 'required|string|max:50',
                'gender' => 'required|in:Masculino,Femenino',
                'marital_status' => 'required|in:Soltero,Casado,Divorciado,Viudo,Unión libre',
                'nationality' => 'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:50',
                'email' => 'required|email:rfc,dns|unique:personal_data,email',
                'phone_number' => 'required|regex:/^[0-9]+$/|max:20',
                'address' => 'required|string',
                'eps' => 'required|string|max:50',
                'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

                // BANK
                'banking_entity' => 'nullable|string|max:50',
                'account_number' => 'nullable|regex:/^[0-9]+$/|max:50',
                'account_type' => 'nullable|in:Corriente,Ahorros',
                'pension_fund' => 'nullable|string|max:50',
                'severance_pay_fund' => 'nullable|string|max:50',

                // HEALTH
                'allergies' => 'nullable|string',
                'diseases' => 'nullable|string',
                'medications' => 'nullable|string',
                'additional_information' => 'nullable|string',

                // ACADEMIC
                'academic_institution' => 'nullable|string',
                'start_date_school' => 'nullable|date',
                'end_date_school' => 'nullable|date',
                'university_career' => 'nullable|string',
                'degree' => 'nullable|string',
                'card_number' => 'nullable|regex:/^[0-9A-Za-z]+$/',

                // ADDITIONAL EDUCATION
                'specialty_institution' => 'nullable|string|max:100',
                'start_date_specialty' => 'nullable|date',
                'end_date_specialty' => 'nullable|date',
                'course' => 'nullable|string|max:100',
                'specialty_level' => 'nullable|in:Básico,Intermedio,Avanzado',
                'methodology_name' => 'nullable|string|max:100',
                'proficiency_level' => 'nullable|in:Básico,Intermedio,Avanzado',
                'language' => 'nullable|string|max:100',
                'language_level' => 'nullable|in:Básico,Intermedio,Avanzado',

                // FAMILY
                'relationship' => 'nullable|string',
                'family_dni' => 'nullable|regex:/^[0-9]+$/',
                'full_name' => 'nullable|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u',
                'age' => 'nullable|integer',
                'family_gender' => 'nullable|in:Masculino,Femenino',
                'family_birthdate' => 'nullable|date',

                // EMERGENCY
                'emergency_contact_full_name' => 'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u|max:100',
                'emergency_contact_phone_number' => 'required|regex:/^[0-9]+$/|max:20',
                'emergency_contact_relationship' => 'required|string|max:50',

                // DOCUMENTOS
                'documents' => 'nullable|array',
                'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            ], [
                // MENSAJES PERSONALIZADOS EN ESPAÑOL
                'required' => 'El campo :attribute es obligatorio.',
                'date' => 'El campo :attribute debe ser una fecha válida.',
                'email' => 'El correo electrónico debe tener un formato válido.',
                'unique' => 'Este :attribute ya se encuentra registrado.',
                'in' => 'La opción seleccionada para :attribute no es válida.',
                'regex' => 'El formato del campo :attribute no es válido (solo letras para nombres o solo números para documentos/teléfonos).',
                'integer' => 'El campo :attribute debe ser un número entero.',
                'mimes' => 'Los documentos deben estar en formato: pdf, jpg, jpeg, png.',
                'max' => 'El campo :attribute no debe superar los :max caracteres.',
                'numeric' => 'El campo :attribute debe ser numérico.',
                'exists' => 'La invitación seleccionada no es válida.',
            ], [
                // ATRIBUTOS EN ESPAÑOL
                'first_name' => 'Nombre',
                'last_name' => 'Apellido',
                'dni' => 'Cédula',
                'email' => 'Correo electrónico',
                'phone_number' => 'Teléfono',
                'hiring_date' => 'Fecha de contratación',
                'job_position' => 'Cargo',
                'birthdate' => 'Fecha de nacimiento',
                'nationality' => 'Nacionalidad',
                'emergency_contact_full_name' => 'Nombre de contacto de emergencia',
                'emergency_contact_phone_number' => 'Teléfono de contacto de emergencia',
                'family_dni' => 'Cédula del familiar',
                'full_name' => 'Nombre del familiar',
                'account_number' => 'Número de cuenta',
            ]);

            // CREAR PERSONAL DATA
            $personal = PersonalData::create([
                'invitation_link_id' => $invitation->id,
                'hiring_date' => $validated['hiring_date'],
                'job_position' => $validated['job_position'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'second_last_name' => $validated['second_last_name'] ?? null,
                'dni' => $validated['dni'],
                'place_of_issue' => $validated['place_of_issue'],
                'date_of_issue' => $validated['date_of_issue'],
                'birthdate' => $validated['birthdate'],
                'place_of_birth' => $validated['place_of_birth'],
                'gender' => $validated['gender'],
                'marital_status' => $validated['marital_status'],
                'nationality' => $validated['nationality'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
                'eps' => $validated['eps'],
                'blood_group' => $validated['blood_group'],
            ]);

            // BANK ACCOUNT
            BankAccount::create([
                'personal_data_id' => $personal->personal_data_id,
                'banking_entity' => $validated['banking_entity'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'account_type' => $validated['account_type'] ?? null,
                'pension_fund' => $validated['pension_fund'] ?? null,
                'severance_pay_fund' => $validated['severance_pay_fund'] ?? null,
            ]);

            // HEALTH
            \Log::info('Guardando datos de salud para empleado ID: ' . $personal->personal_data_id, [
                'additional_information' => $validated['additional_information'] ?? 'NULL'
            ]);

            HealthData::create([
                'personal_data_id' => $personal->personal_data_id,
                'allergies' => $validated['allergies'] ?? null,
                'diseases' => $validated['diseases'] ?? null,
                'medications' => $validated['medications'] ?? null,
                'additional_information' => $validated['additional_information'] ?? null,
            ]);

            // ACADEMIC
            AcademicInformation::create([
                'personal_data_id' => $personal->personal_data_id,
                'academic_institution' => $validated['academic_institution'] ?? null,
                'start_date_school' => $validated['start_date_school'] ?? null,
                'end_date_school' => $validated['end_date_school'] ?? null,
                'university_career' => $validated['university_career'] ?? null,
                'degree' => $validated['degree'] ?? null,
                'card_number' => $validated['card_number'] ?? null,
            ]);

            // ADDITIONAL EDUCATION
            AdditionalEducation::create([ // ← Cambiado a AdditionalEducation
                'personal_data_id' => $personal->personal_data_id,
                'specialty_institution' => $validated['specialty_institution'] ?? null,
                'start_date_specialty' => $validated['start_date_specialty'] ?? null,
                'end_date_specialty' => $validated['end_date_specialty'] ?? null,
                'course' => $validated['course'] ?? null,
                'specialty_level' => $validated['specialty_level'] ?? null,
                'methodology_name' => $validated['methodology_name'] ?? null,
                'proficiency_level' => $validated['proficiency_level'] ?? null,
                'language' => $validated['language'] ?? null,
                'language_level' => $validated['language_level'] ?? null,
            ]);

            // FAMILY DATA
            if (!empty($validated['full_name'])) {
                FamilyData::create([
                    'personal_data_id' => $personal->personal_data_id,
                    'relationship' => $validated['relationship'] ?? null,
                    'dni' => $validated['family_dni'] ?? null,
                    'full_name' => $validated['full_name'],
                    'age' => $validated['age'] ?? null,
                    'gender' => $validated['family_gender'] ?? null,
                    'birthdate' => $validated['family_birthdate'] ?? null,
                ]);
            }

            // EMERGENCY CONTACT
            EmergencyContact::create([
                'personal_data_id' => $personal->personal_data_id,
                'full_name' => $validated['emergency_contact_full_name'],
                'phone_number' => $validated['emergency_contact_phone_number'],
                'relationship' => $validated['emergency_contact_relationship'],
            ]);

            // DOCUMENTOS - Guardar en carpeta del empleado
            if ($request->hasFile('documents')) {
                // Generar nombre de carpeta del empleado
                $employeeFolderName = $this->getEmployeeFolderName($personal);

                foreach ($request->file('documents') as $type => $file) {
                    if ($file->isValid()) {
                        // Nueva ruta: employees/{Nombre_Apellido_DNI}/documents/
                        $path = $file->store("employees/{$employeeFolderName}/documents", 'public');

                        PersonalDocument::create([
                            'personal_data_id' => $personal->personal_data_id,
                            'document_type' => $type,
                            'file_path' => $path,
                        ]);
                    }
                }
            }

            // ACTUALIZAR INVITATION LINK
            if ($invitation->status !== 'used') {
                $invitation->update([
                    'status' => 'used',
                    'used_at' => now(),
                    'verified_at' => now()
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Formulario enviado correctamente.',
                    'redirect_url' => route('hiring.signature.view', ['id' => $personal->personal_data_id])
                ]);
            }


            return redirect()->route('hiring.signature.view', ['id' => $personal->personal_data_id]);



        }
        catch (ValidationException $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor corrige los errores en el formulario.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en HiringFormController@store: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error técnico: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al guardar el formulario: ' . $e->getMessage());
        }
    }



    public function showSignatureForm($id)
    {
        $employee = PersonalData::findOrFail($id);
        return view('hiring-form.signature', compact('employee'));
    }

    public function saveSignature(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        $employee = PersonalData::findOrFail($id);

        try {
            // 1. Generar nombre de carpeta del empleado
            $employeeFolderName = $this->getEmployeeFolderName($employee);

            // 2. Decodificar y Guardar Imagen en carpeta del empleado
            $image = $request->signature;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'signature.png'; // Nombre fijo para la firma
            $path = "employees/{$employeeFolderName}/{$imageName}";

            \Storage::disk('public')->put($path, base64_decode($image));

            // 3. Guardar en Base de Datos
            \App\Models\Signature::create([
                'personal_data_id' => $employee->personal_data_id,
                'file_path' => $path,
                'signed_at' => now(),
                'ip_address' => $request->ip(),
            ]);

            // 4. Enviar a n8n
            $webhookUrl = env('N8N_SIGNATURE_WEBHOOK_URL');

            if ($webhookUrl) {
                try {
                    // Cargar todas las relaciones necesarias para el reporte completo
                    $employee->load([
                        'familyData',
                        'healthData',
                        'academicInformation',
                        'additionalEducations',
                        'emergencyContacts',
                        'bankAccounts',
                        'documents'
                    ]);

                    // Preparar enlaces de documentos
                    $documentsList = [];
                    foreach ($employee->documents as $doc) {
                        $documentsList[$doc->document_type] = asset('storage/' . $doc->file_path);
                    }

                    // Preparar archivos en Base64 para n8n (Bypass localhost issue)
                    $documentsFiles = [];
                    foreach ($employee->documents as $doc) {
                        try {
                            if (\Storage::disk('public')->exists($doc->file_path)) {
                                $content = \Storage::disk('public')->get($doc->file_path);
                                $mime = \Storage::disk('public')->mimeType($doc->file_path);
                                $base64 = base64_encode($content);

                                // Determinar extensión
                                $extension = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                $fileName = ($doc->document_type ?? 'document') . '.' . $extension;

                                $documentsFiles[] = [
                                    'name' => $doc->document_type,
                                    'filename' => $fileName,
                                    'mime' => $mime,
                                    'content' => $base64
                                ];
                            }
                        }
                        catch (\Exception $e) {
                            \Log::warning("No se pudo procesar documento para n8n: {$doc->file_path}");
                        }
                    }

                    $extraEdu = optional($employee->additionalEducations->first());

                    $payload = [
                        // --- CARPETA DEL EMPLEADO ---
                        'employee_folder_name' => $employeeFolderName,

                        // --- IDENTIFICACION Y CONTACTO ---
                        'employee_id' => $employee->personal_data_id,
                        'full_name' => $employee->first_name . ($employee->middle_name ? ' ' . $employee->middle_name : '') . ' ' . $employee->last_name . ($employee->second_last_name ? ' ' . $employee->second_last_name : ''),
                        'first_name' => $employee->first_name,
                        'middle_name' => $employee->middle_name,
                        'last_name' => $employee->last_name,
                        'second_last_name' => $employee->second_last_name,
                        'email' => $employee->email,
                        'dni' => $employee->dni,
                        'phone_number' => $employee->phone_number,
                        'address' => $employee->address,
                        'nationality' => $employee->nationality,
                        'birthdate' => $employee->birthdate,
                        'place_of_birth' => $employee->place_of_birth,
                        'marital_status' => $employee->marital_status,
                        'gender' => $employee->gender,
                        'blood_group' => $employee->blood_group,
                        'place_of_issue' => $employee->place_of_issue,
                        'date_of_issue' => $employee->date_of_issue,

                        // --- CONTRATACION ---
                        'hiring_date' => $employee->hiring_date,
                        'job_position' => $employee->job_position,
                        'eps' => $employee->eps,

                        // --- FIRMA ---
                        'signature_url' => asset('storage/' . $path),
                        'signature_base64' => $request->signature,
                        'signed_at' => now()->toIso8601String(),

                        // --- BANCARIO ---
                        'bank_entity' => optional($employee->bankAccounts->first())->banking_entity,
                        'account_number' => optional($employee->bankAccounts->first())->account_number,
                        'account_type' => optional($employee->bankAccounts->first())->account_type,
                        'pension_fund' => optional($employee->bankAccounts->first())->pension_fund,
                        'severance_pay_fund' => optional($employee->bankAccounts->first())->severance_pay_fund,

                        // --- SALUD ---
                        'allergies' => optional($employee->healthData)->allergies,
                        'diseases' => optional($employee->healthData)->diseases,
                        'medications' => optional($employee->healthData)->medications,
                        'health_additional_info' => optional($employee->healthData)->additional_information,

                        // --- FAMILIA ---
                        'family_name' => optional($employee->familyData->first())->full_name,
                        'family_relationship' => optional($employee->familyData->first())->relationship,
                        'family_dni' => optional($employee->familyData->first())->dni,
                        'family_age' => optional($employee->familyData->first())->age,
                        'family_gender' => optional($employee->familyData->first())->gender,
                        'family_birthdate' => optional($employee->familyData->first())->birthdate,

                        // --- ACADEMICO (Último registrado) ---
                        'academic_degree' => optional($employee->academicInformation->first())->degree,
                        'academic_institution' => optional($employee->academicInformation->first())->academic_institution,
                        'academic_start_date' => optional($employee->academicInformation->first())->start_date_school,
                        'academic_end_date' => optional($employee->academicInformation->first())->end_date_school,
                        'academic_career' => optional($employee->academicInformation->first())->university_career,
                        'professional_card_number' => optional($employee->academicInformation->first())->card_number,

                        // --- EDUCACION ADICIONAL ---
                        'specialty_institution' => $extraEdu->specialty_institution,
                        'start_date_specialty' => $extraEdu->start_date_specialty,
                        'end_date_specialty' => $extraEdu->end_date_specialty,
                        'course_name' => $extraEdu->course,
                        'specialty_level' => $extraEdu->specialty_level,
                        'methodology_name' => $extraEdu->methodology_name,
                        'proficiency_level' => $extraEdu->proficiency_level,
                        'language' => $extraEdu->language,
                        'language_level' => $extraEdu->language_level,

                        // --- CONTACTO EMERGENCIA ---
                        'emergency_contact_name' => optional($employee->emergencyContacts->first())->full_name,
                        'emergency_contact_phone' => optional($employee->emergencyContacts->first())->phone_number,
                        'emergency_contact_relationship' => optional($employee->emergencyContacts->first())->relationship,

                        // --- DOCUMENTOS (URLs) ---
                        // Enviamos el objeto completo de links para que n8n pueda iterar o mapear específicos
                        'documents_links' => $documentsList,
                        'documents_files' => $documentsFiles, // Array con archivos en Base64

                        // Formato texto para insertar directo en celda de sheet si se prefiere
                        'documents_text_summary' => implode("\n", array_map(
                        function ($v, $k) {
                        return "$k: $v";
                    },
                        $documentsList,
                        array_keys($documentsList)
                    )),
                    ];

                    \Log::info('Enviando payload COMPLETO a n8n para: ' . $payload['full_name'], [
                        'payload' => array_keys($payload),
                        'education_sample' => $payload['specialty_institution'] ?? 'EMPTY',
                        'health_sample' => $payload['health_additional_info'] ?? 'EMPTY'
                    ]);

                    $response = \Illuminate\Support\Facades\Http::post($webhookUrl, $payload);

                    // Lanzar excepción si el status no es 2xx
                    $response->throw();

                }
                catch (\Illuminate\Http\Client\RequestException $e) {
                    // Loguear el error específico de n8n
                    \Log::error('Error de n8n webhook:', [
                        'status' => $e->response->status(),
                        'body' => $e->response->body(),
                        'url' => $webhookUrl
                    ]);
                // Opcional: No bloquear el flujo si n8n falla, pero sí registrarlo
                }
            }

            return redirect()->route('hiring.form.thank_you')
                ->with('success', 'Firma guardada y proceso finalizado correctamente.');

        }
        catch (\Exception $e) {
            \Log::error('Error guardando firma: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar la firma. Intente nuevamente.');
        }
    }
}
