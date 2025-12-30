export function setupUserModal() {
    const modal = document.getElementById('userDetailModal');
    const modalClose = document.querySelector('.modal-close');
    const detailButtons = document.querySelectorAll('.btn-detail');

    if (!modal || !modalClose || detailButtons.length === 0) {
        return; // No hacer nada si no está presente en la vista actual
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modal.classList.add('hidden');
        }
    });
    modal.addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            modal.classList.add('hidden');
        }
    });
    detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            modalClose.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
            const userId = button.getAttribute('data-id');
            fetch(`/employee/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        const content = `
                            <div class="modal-flex-container">
                             <section class="modal-section">
                              <h3>Información Personal</h3>
                                <ul>
                                    <li><strong>Número de cédula:</strong> ${user.dni ?? 'No disponible'}</li>
                                    <li><strong>Lugar de expedición:</strong> ${user.place_of_issue ?? 'No disponible'}</li>
                                    <li><strong>Fecha de expedición:</strong> ${user.date_of_issue ?? 'No disponible'}</li>
                                    <li><strong>Primer nombre:</strong> ${user.first_name ?? 'No disponible'}</li>
                                    <li><strong>Segundo nombre:</strong> ${user.last_name ?? 'No disponible'}</li>
                                    <li><strong>Primer apellido:</strong> ${user.middle_name ?? 'No disponible'}</li>
                                    <li><strong>Segundo apellido:</strong> ${user.last_name ?? 'No disponible'}</li>
                                    <li><strong>Sexo:</strong> ${user.gender ?? 'No disponible'}</li>
                                    <li><strong>Dirección:</strong> ${user.address ?? 'No disponible'}</li>
                                    <li><strong>Número telefónico:</strong> ${user.phone_number ?? 'No disponible'}</li>
                                    <li><strong>Número celular:</strong> ${user.cellphone_number ?? 'No disponible'}</li>
                                    <li><strong>Correo electrónico:</strong> ${user.email ?? 'No disponible'}</li>
                                    <li><strong>Fecha de nacimiento:</strong> ${user.birthdate ?? 'No disponible'}</li>
                                    <li><strong>Estado civil:</strong> ${user.marital_status ?? 'No disponible'}</li>
                                    <li><strong>Fecha de nacimiento:</strong> ${user.birthdate ?? 'No disponible'}</li>
                                    <li><strong>Lugar de nacimiento:</strong> ${user.place_of_birthdate ?? 'No disponible'}</li>
                                    <li><strong>Grupo Sanguineo:</strong> ${user.blood_group ?? 'No disponible'}</li>
                                    <li><strong>Nacionalidad:</strong> ${user.nationality ?? 'No disponible'}</li>
                                    <li><strong>Entidad bancaria:</strong> ${user.banking_entity ?? 'No disponible'}</li>
                                    <li><strong>Número de cuenta bancaria:</strong> ${user.account_number ?? 'No disponible'}</li>
                                    <li><strong>EPS:</strong> ${user.eps ?? 'No disponible'}</li>
                                    <li><strong>Fondo de pensiones:</strong> ${user.pension_fund ?? 'No disponible'}</li>
                                    <li><strong>Fondo de cesantías:</strong> ${user.severance_pay_fund ?? 'No disponible'}</li>
                                </ul>
                              </section>
                       
                               <section class="modal-section">
                                   <h3>Información Académica</h3>
                                   <ul>
                                       <li><strong>Institución:</strong> ${user.academic_information?.academic_institution ?? 'No disponible'}</li>
                                       <li><strong>Carrera:</strong> ${user.academic_information?.university_career ?? 'No disponible'}</li>
                                       <li><strong>Fecha de inicio:</strong> ${user.academic_information?.start_date ?? 'No disponible'}</li>
                                       <li><strong>Fecha de fin:</strong> ${user.academic_information?.end_date ?? 'No disponible'}</li>
                                       <li><strong>Grado:</strong> ${user.academic_information?.degree ?? 'No disponible'}</li>
                                       <li><strong>Tarjeta profesional</strong> ${user.academic_information?.professional_card ?? 'No disponible'}</li>
                                       <li><strong>Número de tarjeta profesional:</strong> ${user.academic_information?.card_number ?? 'No disponible'}</li>
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Información Familiar</h3>
                                   <ul>
                                       <li><strong>Parentesco:</strong> ${user.family_data?.relationship ?? 'No disponible'}</li>
                                       <li><strong>Nombre:</strong> ${user.family_data?.full_name ?? 'No disponible'}</li>
                                       <li><strong>Sexo:</strong> ${user.family_data?.gender ?? 'No disponible'}</li>
                                       <li><strong>Edad:</strong> ${user.family_data?.age ?? 'No disponible'}</li>
                                       <li><strong>Fecha de nacimiento:</strong> ${user.family_data?.birthdate ?? 'No disponible'}</li>
                                       <li><strong>Número de cedula:</strong> ${user.family_data?.dni ?? 'No disponible'}</li>
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Información médica</h3>
                                   <ul>
                                       <li><strong>Alergias:</strong> ${user.health_data?.allergies ?? 'No disponible'}</li>
                                       <li><strong>Enfermedades:</strong> ${user.health_data?.diseases ?? 'No disponible'}</li>
                                       <li><strong>Medicamentos:</strong> ${user.health_data?.medications ?? 'No disponible'}</li>
                                       <li><strong>Información adicional:</strong> ${user.health_data?.additional_information ?? 'No disponible'}</li>
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Contactos de emergencia</h3>
                                   <ul>
                                       <li><strong>Nombre:</strong> ${user.emergency_contact?.full_name ?? 'No disponible'}</li>
                                       <li><strong>Número:</strong> ${user.emergency_contact?.phone_number ?? 'No disponible'}</li>
                                       <li><strong>Parentesco:</strong> ${user.emergency_contact?.relationship ?? 'No disponible'}</li>
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Lenguajes</h3>
                                   <ul>
                                       <li><strong>Lenguaje:</strong> ${user.languages?.language ?? 'No disponible'}</li>
                                       <li><strong>Nivel:</strong> ${user.languages?.level ?? 'No disponible'}</li>
                                       
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Tecnologias / Herramientas TI</h3>
                                   <ul>
                                       <li><strong>Tecnologia:</strong> ${user.it_knowledge?.technology ?? 'No disponible'}</li>
                                       <li><strong>Nivel:</strong> ${user.it_knowledge?.level ?? 'No disponible'}</li>
                                       
                                   </ul>
                               </section>
                               <section class="modal-section">
                                   <h3>Especialidades</h3>
                                   <ul>
                                       <li><strong>Especialidad:</strong> ${user.specialties?.course ?? 'No disponible'}</li>
                                       <li><strong>Fecha de inicio:</strong> ${user.specialties?.start_date ?? 'No disponible'}</li>
                                       <li><strong>Fecha de fin:</strong> ${user.specialties?.end_date ?? 'No disponible'}</li>
                                       <li><strong>Institución:</strong> ${user.specialties?.academic_information ?? 'No disponible'}</li>
                                       <li><strong>Nivel:</strong> ${user.specialties?.level ?? 'No disponible'}</li>
                                       
                                   </ul>
                               </section>
                             </div>
                        `;
                        document.getElementById('user-detail-content').innerHTML = content;
                        modal.classList.remove('hidden');
                    } else {
                        alert('No se pudo cargar la información.');
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                    alert('Error al cargar los datos.');
                });
        });
    });
}
