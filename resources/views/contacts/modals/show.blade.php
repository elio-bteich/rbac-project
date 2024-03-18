<div class="contact-details">
    <div class="row mt-3">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Nom:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactName" class="info-value">{{ $contact->name }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Email perso:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactPersonalEmail" class="info-value">{{ $contact->email }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Numéro tel perso:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactPersonalPhoneNumber" class="info-value">{{ $contact->phone_number }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Fonction:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactJob" class="info-value">{{ $contact->job ?? 'pas défini' }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Nom struct:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactOrganizationName" class="info-value">{{ $contact->organization ? $contact->organization->name : '' }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Numéro tel struct:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactOrganizationPhoneNumber" class="info-value">{{ $contact->organization ? $contact->organization->phone_number : '' }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Email struct:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactOrganizationEmail" class="info-value">{{ $contact->organization ? $contact->organization->email : '' }}</span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-5">
            <div class="contact-info-item">
                <span class="info-label"><strong>Commentaires:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="contact-info-item">
                <span id="contactComments" class="info-value">{{ $contact->comments }}</span>
            </div>
        </div>
    </div>
</div>
