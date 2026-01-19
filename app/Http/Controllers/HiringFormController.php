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

        } catch (\Exception $e) {
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
                // PERSONAL DATA (CAMPOS REQUERIDOS SEGÚN MIGRACIÓN)
                'hiring_date' => 'required|date', // ← AGREGADO
                'job_position' => 'required|string|max:50', // ← AGREGADO
                'first_name' => 'required|string|max:30',
                'middle_name' => 'nullable|string|max:30',
                'last_name' => 'required|string|max:30',
                'second_last_name' => 'nullable|string|max:30',
                'dni' => 'required|string|max:20|unique:personal_data,dni',
                'place_of_issue' => 'required|string|max:50',
                'date_of_issue' => 'required|date',
                'birthdate' => 'required|date',
                'place_of_birth' => 'required|string|max:50', // ← AGREGADO
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed,free union',
                'nationality' => 'required|string|max:50', // ← AGREGADO
                'email' => 'required|email|unique:personal_data,email',
                'phone_number' => 'required|string|max:20',
                'address' => 'required|string',
                'eps' => 'required|string|max:50',
                'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

                // BANK
                'banking_entity' => 'nullable|string|max:50',
                'account_number' => 'nullable|string|max:50',
                'account_type' => 'nullable|in:current,savings,payroll',
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
                'card_number' => 'nullable|string',

                // ADDITIONAL EDUCATION
                'specialty_institution' => 'nullable|string|max:100',
                'start_date_specialty' => 'nullable|date',
                'end_date_specialty' => 'nullable|date',
                'course' => 'nullable|string|max:100',
                'specialty_level' => 'nullable|in:basic,intermediate,advanced',
                'methodology_name' => 'nullable|string|max:100',
                'proficiency_level' => 'nullable|in:basic,intermediate,advanced',
                'language' => 'nullable|string|max:100',
                'language_level' => 'nullable|in:basic,intermediate,advanced',

                // FAMILY
                'relationship' => 'nullable|string',
                'family_dni' => 'nullable|string',
                'full_name' => 'nullable|string',
                'age' => 'nullable|integer',
                'family_gender' => 'nullable|in:male,female',
                'family_birthdate' => 'nullable|date',

                // EMERGENCY
                'emergency_contact_full_name' => 'required|string|max:100',
                'emergency_contact_phone_number' => 'required|string|max:20',
                'emergency_contact_relationship' => 'required|string|max:50',

                // DOCUMENTOS
                'documents' => 'nullable|array',
                'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
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



        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en HiringFormController@store: ' . $e->getMessage()); // <--- ADDED LOGGING
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
                        } catch (\Exception $e) {
                            \Log::warning("No se pudo procesar documento para n8n: {$doc->file_path}");
                        }
                    }

                    $payload = [
                        // --- CARPETA DEL EMPLEADO ---
                        'employee_folder_name' => $employeeFolderName,

                        // --- IDENTIFICACION Y CONTACTO ---
                        'employee_id' => $employee->personal_data_id,
                        'full_name' => $employee->first_name . ' ' . $employee->last_name,
                        'first_name' => $employee->first_name,
                        'last_name' => $employee->last_name,
                        'email' => $employee->email,
                        'dni' => $employee->dni,
                        'phone_number' => $employee->phone_number,
                        'address' => $employee->address,
                        'nationality' => $employee->nationality,
                        'birthdate' => $employee->birthdate,
                        'place_of_birth' => $employee->place_of_birth, // Corregido: Agregado y sin duplicar address
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
                        'bank_entity' => $employee->bankAccounts->first()->banking_entity ?? 'N/A',
                        'account_number' => $employee->bankAccounts->first()->account_number ?? 'N/A',
                        'account_type' => $employee->bankAccounts->first()->account_type ?? 'N/A',
                        'pension_fund' => $employee->bankAccounts->first()->pension_fund ?? 'N/A',
                        'severance_pay_fund' => $employee->bankAccounts->first()->severance_pay_fund ?? 'N/A',

                        // --- SALUD ---
                        'allergies' => optional($employee->healthData)->allergies ?? 'N/A',
                        'diseases' => optional($employee->healthData)->diseases ?? 'N/A',
                        'medications' => optional($employee->healthData)->medications ?? 'N/A',
                        'additional_health_info' => optional($employee->healthData)->additional_information ?? 'N/A',

                        // --- FAMILIA ---
                        'family_name' => $employee->familyData->first()->full_name ?? 'N/A',
                        'family_relationship' => $employee->familyData->first()->relationship ?? 'N/A',
                        'family_dni' => $employee->familyData->first()->dni ?? 'N/A',
                        'family_age' => $employee->familyData->first()->age ?? 'N/A',
                        'family_gender' => $employee->familyData->first()->gender ?? 'N/A',
                        'family_birthdate' => $employee->familyData->first()->birthdate ?? 'N/A',

                        // --- ACADEMICO (Último registrado) ---
                        'academic_degree' => $employee->academicInformation->first()->degree ?? 'N/A',
                        'academic_institution' => $employee->academicInformation->first()->academic_institution ?? 'N/A',
                        'academic_start_date' => $employee->academicInformation->first()->start_date_school ?? 'N/A',
                        'academic_end_date' => $employee->academicInformation->first()->end_date_school ?? 'N/A',
                        'academic_career' => $employee->academicInformation->first()->university_career ?? 'N/A',
                        'professional_card_number' => $employee->academicInformation->first()->card_number ?? 'N/A',

                        // --- EDUCACION ADICIONAL ---
                        'additional_institution' => $employee->additionalEducations->first()->specialty_institution ?? 'N/A',
                        'additional_start_date' => $employee->additionalEducations->first()->start_date_specialty ?? 'N/A',
                        'additional_end_date' => $employee->additionalEducations->first()->end_date_specialty ?? 'N/A',
                        'additional_course' => $employee->additionalEducations->first()->course ?? 'N/A',
                        'additional_course_level' => $employee->additionalEducations->first()->specialty_level ?? 'N/A',
                        'additional_methodology' => $employee->additionalEducations->first()->methodology_name ?? 'N/A',
                        'additional_methodology_level' => $employee->additionalEducations->first()->proficiency_level ?? 'N/A',
                        'additional_language' => $employee->additionalEducations->first()->language ?? 'N/A',
                        'additional_language_level' => $employee->additionalEducations->first()->language_level ?? 'N/A',

                        // --- CONTACTO EMERGENCIA ---
                        'emergency_contact_name' => $employee->emergencyContacts->first()->full_name ?? 'N/A',
                        'emergency_contact_phone' => $employee->emergencyContacts->first()->phone_number ?? 'N/A',
                        'emergency_contact_relationship' => $employee->emergencyContacts->first()->relationship ?? 'N/A',

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

                    \Log::info('Enviando payload COMPLETO a n8n para: ' . $payload['full_name']);

                    $response = \Illuminate\Support\Facades\Http::post($webhookUrl, $payload);

                    // Lanzar excepción si el status no es 2xx
                    $response->throw();

                } catch (\Illuminate\Http\Client\RequestException $e) {
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

        } catch (\Exception $e) {
            \Log::error('Error guardando firma: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar la firma. Intente nuevamente.');
        }
    }
}

