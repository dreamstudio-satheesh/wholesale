<!-- Create Deposit Category Modal -->
<div class="modal fade" id="createDepositCategoryModal" tabindex="-1" aria-labelledby="createDepositCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDepositCategoryModalLabel">New Deposit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button>
            </div>
            <div class="modal-body">
                <form id="createDepositCategoryForm" action="{{ route('deposit_category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Edit Deposit Category Modal -->
<div class="modal fade" id="editDepositCategoryModal" tabindex="-1" aria-labelledby="editDepositCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepositCategoryModalLabel">Edit Deposit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDepositCategoryForm" method="POST">
                    <!-- Method Spoofing for PUT request -->
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-pill">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
