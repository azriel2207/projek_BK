/**
 * Popup Utilities - SweetAlert2 Helper Functions
 * Provides elegant and consistent modal dialogs across the application
 */

/**
 * Show confirmation dialog with SweetAlert2
 * @param {string} message - Confirmation message
 * @param {string} icon - Icon type: 'warning', 'info', 'success', 'error', 'question'
 * @param {function} onConfirm - Callback function when confirmed
 * @param {string} title - Optional dialog title
 */
function showConfirm(message, icon = 'warning', onConfirm = null, title = 'Konfirmasi') {
    Swal.fire({
        title: title,
        html: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>Konfirmasi',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            // Enhance button styling
            const confirmBtn = modal.querySelector('.swal2-confirm');
            const cancelBtn = modal.querySelector('.swal2-cancel');
            
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
            cancelBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

/**
 * Show success message
 * @param {string} message - Success message
 * @param {string} title - Optional dialog title
 */
function showSuccess(message, title = 'Berhasil!') {
    Swal.fire({
        title: title,
        html: message,
        icon: 'success',
        confirmButtonColor: '#10b981',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
        allowOutsideClick: false,
        backdrop: true,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    });
}

/**
 * Show error message
 * @param {string} message - Error message
 * @param {string} title - Optional dialog title
 */
function showError(message, title = 'Kesalahan!') {
    Swal.fire({
        title: title,
        html: message,
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    });
}

/**
 * Show warning/info message
 * @param {string} message - Warning message
 * @param {string} title - Optional dialog title
 */
function showWarning(message, title = 'Perhatian!') {
    Swal.fire({
        title: title,
        html: message,
        icon: 'warning',
        confirmButtonColor: '#f59e0b',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    });
}

/**
 * Show delete confirmation with warning
 * @param {string} itemName - Name of item to delete
 * @param {function} onConfirm - Callback function when confirmed
 * @param {string} message - Optional custom message
 */
function showDeleteConfirm(itemName, onConfirm = null, message = null) {
    const customMessage = message || `
        <div class="text-left">
            <p class="text-gray-700 mb-3">Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?</p>
            <p class="text-red-600 text-sm flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Tindakan ini tidak dapat dibatalkan!
            </p>
        </div>
    `;

    Swal.fire({
        title: 'Hapus Item?',
        html: customMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Hapus',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            const cancelBtn = modal.querySelector('.swal2-cancel');
            
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
            cancelBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

/**
 * Show upgrade/important action confirmation
 * @param {string} actionName - Name of action
 * @param {string} details - Details about the action
 * @param {function} onConfirm - Callback function when confirmed
 */
function showImportantActionConfirm(actionName, details = '', onConfirm = null) {
    const html = `
        <div class="text-left">
            <p class="text-gray-700 mb-4">${details}</p>
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-red-700 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Perhatian:</strong> Tindakan ini permanen dan tidak dapat dibatalkan!
            </div>
        </div>
    `;

    Swal.fire({
        title: actionName,
        html: html,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>Lanjutkan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            const cancelBtn = modal.querySelector('.swal2-cancel');
            
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
            cancelBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

/**
 * Submit form with confirmation
 * @param {string} message - Confirmation message
 * @param {HTMLFormElement} form - The form to submit
 * @param {string} title - Optional dialog title
 */
function confirmFormSubmit(message, form, title = 'Konfirmasi') {
    showConfirm(message, 'warning', () => {
        form.submit();
    }, title);
    return false;
}

/**
 * Simple modal dialog with custom content
 * @param {string} title - Dialog title
 * @param {string} html - HTML content
 * @param {string} confirmText - Confirm button text
 * @param {function} onConfirm - Callback function
 */
function showModal(title, html, confirmText = 'OK', onConfirm = null) {
    Swal.fire({
        title: title,
        html: html,
        confirmButtonColor: '#3b82f6',
        confirmButtonText: confirmText,
        allowOutsideClick: false,
        backdrop: true,
        didOpen: (modal) => {
            const confirmBtn = modal.querySelector('.swal2-confirm');
            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
        }
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

/**
 * Close all open SweetAlert dialogs
 */
function closeAllPopups() {
    Swal.close();
}

/**
 * ==========================================
 * Specific Helper Functions for Form Actions
 * ==========================================
 */

/**
 * Confirm delete action
 * @param {string} itemName - Name of item to delete
 */
function confirmDelete(itemName = 'item') {
    showDeleteConfirm(itemName, () => {
        // Find and submit the form that triggered this
        const form = event.target.closest('form');
        if (form) {
            form.submit();
        }
    });
    return false;
}

/**
 * Confirm cancellation action
 * @param {string} itemName - Name of item to cancel
 */
function confirmCancel(itemName = 'janji') {
    showConfirm(
        `Apakah Anda yakin ingin membatalkan <strong>${itemName}</strong>?`,
        'warning',
        () => {
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        },
        'Batalkan ' + itemName.charAt(0).toUpperCase() + itemName.slice(1)
    );
    return false;
}

/**
 * Confirm completion action
 */
function confirmComplete() {
    showConfirm(
        'Tandai konseling ini sebagai <strong>selesai</strong>?<br><br><small class="text-gray-600">Pastikan semua catatan sudah tercatat dengan lengkap.</small>',
        'question',
        () => {
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        },
        'Tandai Selesai'
    );
    return false;
}

/**
 * Confirm student upgrade action
 * @param {string} studentName - Name of student to upgrade
 */
function confirmUpgradeStudent(studentName) {
    showConfirm(
        `<div class="text-center">
            <i class="fas fa-arrow-up text-blue-500 text-3xl mb-3"></i>
            <p class="mt-3">Upgrade <strong>${studentName}</strong> menjadi <strong>Guru BK</strong>?</p>
        </div>`,
        'question',
        () => {
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        },
        'Konfirmasi Upgrade'
    );
    return false;
}

/**
 * Confirm upgrade to guru with warning
 * @param {string} studentName - Name of student to upgrade
 */
function confirmUpgradeToGuru(studentName) {
    const html = `
        <div class="text-left space-y-3">
            <div class="flex items-start space-x-2">
                <i class="fas fa-arrow-up text-blue-600 text-lg mt-1"></i>
                <p>Upgrade <strong>${studentName}</strong> menjadi <strong>Guru BK</strong>?</p>
            </div>
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-red-700 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Peringatan:</strong> Tindakan ini <strong>TIDAK dapat dibatalkan</strong>!
            </div>
            <ul class="bg-blue-50 p-3 rounded text-sm text-gray-700 list-disc list-inside space-y-1">
                <li>Status siswa akan berubah menjadi Guru BK</li>
                <li>Data akan disimpan secara permanen</li>
                <li>Akses dan role akan diperbarui</li>
            </ul>
        </div>
    `;

    showImportantActionConfirm(
        'Upgrade Menjadi Guru BK',
        html,
        () => {
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        }
    );
    return false;
}
