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
                'family_data_dni' => 'nullable|string',
                'full_name' => 'nullable|string',
                'age' => 'nullable|integer',
                'family_data_gender' => 'nullable|in:male,female',
                'family_data_birthdate' => 'nullable|date',

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
                    'dni' => $validated['family_data_dni'] ?? null,
                    'full_name' => $validated['full_name'],
                    'age' => $validated['age'] ?? null,
                    'gender' => $validated['family_data_gender'] ?? null,
                    'birthdate' => $validated['family_data_birthdate'] ?? null,
                ]);
            }

            // EMERGENCY CONTACT
            EmergencyContact::create([
                'personal_data_id' => $personal->personal_data_id,
                'full_name' => $validated['emergency_contact_full_name'],
                'phone_number' => $validated['emergency_contact_phone_number'],
                'relationship' => $validated['emergency_contact_relationship'],
            ]);

            // DOCUMENTOS - Manejar como array
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $type => $file) {
                    if ($file->isValid()) {
                        $path = $file->store("personal_documents/{$personal->personal_data_id}", 'public');

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
                    'message' => 'Formulario enviado correctamente.'
                ]);
            }

            return redirect()->route('hiring.form.thank_you')
                ->with('success', 'Formulario enviado correctamente.');


        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el formulario.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error al guardar el formulario: ' . $e->getMessage());
            }
        }

    public function PersonalDocument(string $id)
    {


    }
}

