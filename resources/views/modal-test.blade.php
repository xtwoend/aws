@extends('layouts.tabler')

@section('title', 'Modal Test')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Bootstrap Modal Test</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <h3>Bootstrap Modal Test</h3>
                <p>Click the button below to test Bootstrap modal functionality:</p>
                
                <button type="button" class="btn btn-primary" id="testModalBtn">
                    Test Bootstrap Modal
                </button>
                
                <button type="button" class="btn btn-danger ms-2" id="testDeleteBtn">
                    Test Delete Modal (User Test)
                </button>
                
                <div class="mt-3">
                    <h4>Debug Info:</h4>
                    <ul id="debugInfo">
                        <!-- Will be populated by JavaScript -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal modal-blur fade" id="testModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-success icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 12l5 5l10 -10" />
                </svg>
                <h3>Bootstrap Modal Works!</h3>
                <div class="text-muted">The Bootstrap modal is functioning correctly.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Copy of the Delete Modal from users page -->
<div class="modal modal-blur fade" id="deleteModalTest" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 9v4" />
                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                    <path d="M12 16h.01" />
                </svg>
                <h3>Test Delete Function</h3>
                <div class="text-muted">This tests the same function used in user management.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="confirmTestDelete">Test Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Test script to verify Bootstrap functionality
document.addEventListener('DOMContentLoaded', function() {
    // Populate debug information
    const debugInfo = document.getElementById('debugInfo');
    
    const debugItems = [
        `jQuery available: ${typeof window.$ !== 'undefined'}`,
        `Bootstrap available: ${typeof window.bootstrap !== 'undefined'}`,
        `Bootstrap Modal: ${window.bootstrap && typeof window.bootstrap.Modal !== 'undefined'}`,
        `DataTables available: ${typeof window.$ !== 'undefined' && window.$ && typeof window.$.fn.DataTable !== 'undefined'}`,
        `Tabler available: ${typeof window.Tabler !== 'undefined'}`,
        `Alpine available: ${typeof window.Alpine !== 'undefined'}`
    ];
    
    debugItems.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item;
        debugInfo.appendChild(li);
    });
    
    // Test Bootstrap Modal button
    document.getElementById('testModalBtn').addEventListener('click', function() {
        try {
            const modal = new window.bootstrap.Modal(document.getElementById('testModal'));
            modal.show();
            console.log('✅ Bootstrap Modal test successful');
        } catch (error) {
            console.error('❌ Bootstrap Modal test failed:', error);
            alert('Bootstrap Modal test failed. Check console for details.');
        }
    });
    
    // Test Delete Modal button (using same function as users page)
    document.getElementById('testDeleteBtn').addEventListener('click', function() {
        deleteUserTest('test-user-123');
    });
    
    // Test confirm delete
    document.getElementById('confirmTestDelete').addEventListener('click', function() {
        const modalElement = document.getElementById('deleteModalTest');
        let modal = null;
        
        if (window.bootstrap && window.bootstrap.Modal) {
            modal = window.bootstrap.Modal.getInstance(modalElement);
        }
        
        // Hide modal
        if (modal) {
            modal.hide();
        } else {
            // Manual hide
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.getElementById('manual-modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
        
        alert('Test delete confirmed! (No actual deletion performed)');
    });
});

// Copy of the deleteUser function from users page for testing
function deleteUserTest(userId) {
    console.log('Testing deleteUser function with userId:', userId);
    
    // Get the modal element
    const modalElement = document.getElementById('deleteModalTest');
    if (!modalElement) {
        console.error('Delete modal element not found');
        return;
    }
    
    // Try to use Bootstrap Modal
    if (window.bootstrap && window.bootstrap.Modal) {
        try {
            const modal = new window.bootstrap.Modal(modalElement);
            modal.show();
            console.log('✅ Bootstrap Modal created and shown successfully');
        } catch (error) {
            console.error('❌ Error creating Bootstrap modal:', error);
            // Fallback to manual modal display
            showModalManually(modalElement);
        }
    } else {
        console.warn('⚠️ Bootstrap Modal not available, using fallback');
        // Fallback to manual modal display
        showModalManually(modalElement);
    }
}

function showModalManually(modalElement) {
    console.log('🔧 Using manual modal display fallback');
    
    // Manual modal display as fallback
    modalElement.style.display = 'block';
    modalElement.classList.add('show');
    modalElement.setAttribute('aria-modal', 'true');
    modalElement.setAttribute('role', 'dialog');
    document.body.classList.add('modal-open');
    
    // Create backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'manual-modal-backdrop';
    document.body.appendChild(backdrop);
    
    // Close modal functionality
    const closeModal = () => {
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
        modalElement.removeAttribute('aria-modal');
        modalElement.removeAttribute('role');
        document.body.classList.remove('modal-open');
        const manualBackdrop = document.getElementById('manual-modal-backdrop');
        if (manualBackdrop) {
            manualBackdrop.remove();
        }
    };
    
    // Add event listeners for close buttons
    const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
    
    // Close on backdrop click
    backdrop.addEventListener('click', closeModal);
}
</script>
@endpush
