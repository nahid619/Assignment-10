@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1>Product Catalog</h1>
    <div class="d-flex justify-content-between mb-3">
        <h3>Active Products</h3>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">+ Add New</button>
    </div>

    <table id="productTable" class="table table-bordered">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>UPC</th>
                <th>MPN</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>

    <!-- Modal for adding new product -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        @csrf
                        <!-- Fields for product data -->
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" name="sku" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>UPC</label>
                            <input type="text" name="upc" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>MPN</label>
                            <input type="text" name="mpn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        @csrf
                        @method('PUT')
                        <!-- Fields for editing product data -->
                        <input type="hidden" id="edit_product_id" name="id">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" id="edit_sku" name="sku" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="edit_description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>UPC</label>
                            <input type="text" id="edit_upc" name="upc" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>MPN</label>
                            <input type="text" id="edit_mpn" name="mpn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <input type="text" id="edit_brand" name="brand" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" id="edit_price" name="price" class="form-control" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables
    const table = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('products.data') }}",
            type: 'GET',
            dataType: 'json',
            error: function(xhr, error, thrown) {
                console.error('Error loading data:', error);
            }
        },
        columns: [
            { data: 'sku' },
            { data: 'name' },
            { data: 'description' },
            { data: 'upc' },
            { data: 'mpn' },
            { data: 'brand' },
            { data: 'price' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    $.ajax({
    url: "{{ route('products.data') }}",
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        console.log('AJAX Response:', data);
    },
    error: function(xhr, error, thrown) {
        console.error('Error loading data:', error);
    }
});


    // Handle add product
    $('#addProductForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('products.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addProductModal').modal('hide');
                    table.ajax.reload();
                }
            },
            error: function(error) {
                console.error('Error adding product:', error);
            }
        });
    });

    // Handle edit button click
    $('#productTable').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`products/${id}`, function(product) {
            $('#edit_product_id').val(product.id);
            $('#edit_sku').val(product.sku);
            $('#edit_name').val(product.name);
            $('#edit_description').val(product.description);
            $('#edit_upc').val(product.upc);
            $('#edit_mpn').val(product.mpn);
            $('#edit_brand').val(product.brand);
            $('#edit_price').val(product.price);
            $('#editProductModal').modal('show');
        });
    });

    // Handle edit form submission
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_product_id').val();
        $.ajax({
            url: `products/${id}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editProductModal').modal('hide');
                    table.ajax.reload();
                }
            },
            error: function(error) {
                console.error('Error updating product:', error);
            }
        });
    });

    // Handle delete button click
    $('#productTable').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `products/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                    }
                },
                error: function(error) {
                    console.error('Error deleting product:', error);
                }
            });
        }
    });
});
</script>
@endsection
