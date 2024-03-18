<div class="organization-details">
    <div class="row mt-3">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Nom:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationName" class="info-value">{{ $organization->name }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Email:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationEmail" class="info-value">{{ $organization->email }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Numéro de téléphone:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationPhoneNumber" class="info-value">{{ $organization->phone_number }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Rue:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationStreet" class="info-value">{{ $organization->street }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Ville:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationCity" class="info-value">{{ $organization->city }}</span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-5">
            <div class="organization-info-item">
                <span class="info-label"><strong>Code postal:</strong></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="organization-info-item">
                <span id="organizationPostalCode" class="info-value">{{ $organization->postal_code }}</span>
            </div>
        </div>
    </div>
</div>
