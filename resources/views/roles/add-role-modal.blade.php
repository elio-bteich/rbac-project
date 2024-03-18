<!-- add role modal -->
<div class="modal" id="addChildRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter Role</h5>
            </div>

            <form id="addRoleForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roleName">Nom du Role</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>
                    <input type="hidden" name="parent_role_id" id="parentRoleID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>