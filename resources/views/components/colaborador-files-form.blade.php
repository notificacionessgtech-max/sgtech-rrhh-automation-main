<fieldset class="form-section">
    <legend>DOCUMENTOS REQUERIDOS</legend>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Fotocopia de la cédula de ciudadanía ampliada al 150%</label>
            <input type="file" name="documents[1-Fotocopia-de-la-cédula-de-ciudadanía-ampliada-al-150%]">
            @error('documents.1-Fotocopia-de-la-cédula-de-ciudadanía-ampliada-al-150%')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Fotografía para la firma de correo</label>
            <input type="file" name="documents[9-Fotografía-para-la-firma-de-correo]">

        </div>
    </div>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Certificados de estudios realizados</label>
            <input type="file" name="documents[2-Certificados-de-estudios-realizados]">
        </div>
        <div class="input-group input-small">
            <label for="">Dos últimas certificaciones laborales</label>
            <input type="file" name="documents[3-Dos-últimas-certificaciones-laborales]">
        </div>

    </div>
    <br>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Certificación de afiliación a EPS</label>
            <input type="file" name="documents[4-Certificación-de-afiliación-a-EPS]">
        </div>
        <div class="input-group input-small">
            <label for="">Certificación de afiliación a Fondo de Pensiones</label>
            <input type="file" name="documents[5-Certificación-de-afiliación-a-Fondo-de-Pensiones]">
        </div>
    </div>
    <br>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Certificación bancaria</label>
            <input type="file" name="documents[6-Certificación-bancaria]">
        </div>
        <div class="input-group input-small">
            <label for="">Antecedentes policía, procuraduría y contraloría</label>
            <input type="file" name="documents[7-Antecedentes-policía-procuraduría-y-contraloría]">
        </div>
    </div>
</fieldset>
<fieldset class="form-section">
    <legend>DOCUMENTOS PARA AFILIACION DE BENEFICIARIOS</legend>
    <legend>Hijos</legend>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Registro civil de nacimiento (debe indicar el número
                NUIP)</label>
            <input type="file" name="documents[10-Registro-civil-de-nacimiento]">
        </div>
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Fotocopia de la tarjeta de identidad (Para hijos
            mayores de 7 años)</label>
        <input type="file" name="documents[10.1-Fotocopia-de-la-tarjeta-de-identidad]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Niños mayores de 12 años, presentar certificación del
            plantel donde recibe formación académica.</label>
        <input type="file"
            name="documents[10.2-Niños-mayores-de-12-años-presentar-certificación-del-plantel-donde-recibe-formación-académica.]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Certificado laboral del conyugue indicando: salario,
            tiempo laborado, y si recibe subsidio familiar por parte de la caja a la
            cual se encuentre afiliado.</label>
        <input type="file" name="documents[10.3-Certificado-laboral-del-conyugue]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Copia de la cédula del cónyuge.</label>
        <input type="file" name="documents[10.4-Copia-de-la-cédula-del-cónyuge.]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">En caso de ser madre cabeza de familia, la
            trabajadora debe presentar una declaración extrajuicio argumentando su
            condición.</label>
        <input type="file" name="documents[10.5-declaración-extrajuicio-argumentando-su-condición.]">
    </div>
    <br>
    <legend>Conyuge</legend>
    <div class="input-group input-small">
        <label for="">Fotocopia de cédula de ciudadanía</label>
        <input type="file" name="documents[11-Fotocopia-de-cédula-de-ciudadanía]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Registro civil de matrimonio o declaración
            extrajuicio de convivencia en unión libre</label>
        <input type="file"
            name="documents[11.1-Registro-civil-de-matrimonio-o-declaración-extrajuicio-de-convivencia-en-unión-libre]"
            accept=".pdf">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Certificado laboral de la empresa donde
            labora.</label>
        <input type="file" name="documents[11.2-Certificado-laboral-de-la-empresa-donde-labora]">
    </div>
    <br>
    <legend>PADRES (Afiliación para recibir subsidio familiar: padre y madre mayores de 60 años)</legend>
    <div class="input-group input-small">
        <label for="">Fotocopia de la cédula de ciudadanía</label>
        <input type="file" name="documents[12-Fotocopia-de-la-cédula-de-ciudadanía]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Registro civil de nacimiento del trabajador</label>
        <input type="file" name="documents[12.1-Registro-civil-de-nacimiento-del-trabajador]">
    </div>
    <br>
    <div class="input-group input-small">
        <label for="">Certificación del SISBEN o EPS que sea beneficiario
            del trabajador</label>
        <input type="file" name="documents[12.2-Certificación-del-SISBEN-EPS]">
    </div>
    <br>
    <div class="input-group input-small">
        <p>
            * Si los padres son menores de 60 años, podrán ser afiliados a la caja
            de
            compensación familiar para acceder a los servicios que presta, se debe
            anexar
            únicamente fotocopia de cédula de padre y madre del colaborador.
        </p>
    </div>
</fieldset>
