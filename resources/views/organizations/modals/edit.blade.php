<form id="edit-organization-form">
    @csrf

    <div class="form-group mb-3 mt-4">
        <label for="name">Nom de la structure:</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="email">Email de la structure:</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="phone_number">Numéro de téléphone de la structure:</label>
        <input type="tel" maxlength="10" name="phone_number" id="phone_number" class="form-control">
    </div>

    <div class="form-group mb-3">
        <label for="street">Rue:</label>
        <input type="text" name="street" id="street" class="form-control">
    </div>

    <div class="form-group mb-3">
        <label for="city">Ville:</label>
        <input type="text" name="city" id="city" class="form-control">
    </div>

    <div class="form-group mb-4">
        <label for="postal_code">Code Postal:</label>
        <input type="text" maxlength="5" name="postal_code" id="postal_code" class="form-control">
    </div>

</form>
