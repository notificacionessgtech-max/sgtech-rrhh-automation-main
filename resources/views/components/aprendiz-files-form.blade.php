<fieldset class="form-section">
    <legend>Documentos requeridos</legend>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Fotocopia de la cédula de ciudadanía ampliada al 150%</label>
            <input type="file" name="documents[1-Fotocopia-de-la-cédula-de-ciudadanía-ampliada-al-150%]"
                accept=".pdf, image/jpg, image/png">
            @error('documents.1-Fotocopia-de-la-cédula-de-ciudadanía-ampliada-al-150%')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Certificación de afiliación a EPS</label>
            <input type="file" name="documents[2-Certificación-de-afiliación-a-EPS]" accept=".pdf">
            @error('documents.2-Certificación-de-afiliación-a-EPS')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Certificación bancaria</label>
            <input type="file" name="documents[6-Certificación-bancaria]" accept=".pdf">
            @error('documents.6-Certificación-bancaria')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="input-row">

    </div>
    <div class="input-row">
        <div class="input-group input-small">
            <label for="">Antecedentes policía, procuraduría y contraloría</label>
            <input type="file" name="documents[7-Antecedentes-policía-procuraduría-y-contraloría]" accept=".pdf">
            @error('documents.7-Antecedentes-policía-procuraduría-y-contraloría')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="input-row">

        <div class="input-group input-small">
            <label for="">Fotografía para la firma de correo</label>
            <input type="file" name="documents[9-Fotografía-para-la-firma-de-correo]">
            @error('documents.9-Fotografía-para-la-firma-de-correo')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>
</fieldset>
