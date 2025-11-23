const DatabaseBackup = {
    init() {
        this.setupDragAndDrop();
        this.setupFileInput();
        this.setupFormSubmit();
        this.setupDeleteButtons();
    },

    setupDragAndDrop() {
        const dropZone = document.getElementById('dropZone');
        if (!dropZone) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
            document.body.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-purple-500', 'bg-purple-100');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-purple-500', 'bg-purple-100');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFiles(files);
            }
        }, false);
    },

    setupFileInput() {
        const fileInput = document.getElementById('file');
        if (!fileInput) return;

        fileInput.addEventListener('change', function() {
            DatabaseBackup.handleFiles(this.files);
        });

        const dropZone = document.getElementById('dropZone');
        if (dropZone) {
            dropZone.addEventListener('click', (e) => {
                if (!e.target.closest('button')) {
                    fileInput.click();
                }
            });
        }
    },

    handleFiles(files) {
        if (files.length === 0) return;

        const file = files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileName = file.name;
        const fileExtension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();

        if (fileExtension !== '.sql') {
            this.showError('File harus bertipe .sql');
            const fileInput = document.getElementById('file');
            if (fileInput) fileInput.value = '';
            return;
        }

        if (parseFloat(fileSize) > 100) {
            this.showError('Ukuran file maksimal 100MB');
            const fileInput = document.getElementById('file');
            if (fileInput) fileInput.value = '';
            return;
        }

        const fileInput = document.getElementById('file');
        if (fileInput) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }

        this.showSuccess(fileName, fileSize);
    },

    showError(message) {
        const fileName = document.getElementById('fileName');
        if (!fileName) return;

        fileName.innerHTML = '';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-r animate-slideUp';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-red-700 font-medium text-sm">${message}</span>
            </div>
        `;
        fileName.appendChild(errorDiv);
    },

    showSuccess(name, size) {
        const fileName = document.getElementById('fileName');
        if (!fileName) return;

        fileName.innerHTML = '';
        const previewDiv = document.createElement('div');
        previewDiv.className = 'mt-4 p-4 bg-green-50 border-2 border-green-500 rounded-lg animate-slideUp';
        previewDiv.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-12 h-12 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate mb-1" title="${name}">
                        ${name}
                    </p>
                    <p class="text-xs text-gray-600 mb-2">
                        ${size} MB • SQL
                    </p>
                    
                    <div class="w-full bg-green-200 rounded-full h-2 mb-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-xs text-green-700 font-medium">File siap untuk diimport</span>
                    </div>
                </div>
            </div>
        `;
        fileName.appendChild(previewDiv);
    },

    setupFormSubmit() {
        const importForm = document.getElementById('importForm');
        if (!importForm) return;

        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('file');
            
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                alert('Silakan pilih file SQL terlebih dahulu!');
                return false;
            }

            if (typeof showLoading === 'function') {
                showLoading('Sedang mengimport database...');
            }
            
            setTimeout(() => {
                e.target.submit();
            }, 1500);
        });
    },

    setupDeleteButtons() {
        const deleteButtons = document.querySelectorAll('[onclick*="openDeleteModal"]');
        deleteButtons.forEach(btn => {
            const onclickAttr = btn.getAttribute('onclick');
            if (onclickAttr) {
                btn.removeAttribute('onclick');
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const match = onclickAttr.match(/openDeleteModal\('(.+?)',\s*'(.+?)'\)/);
                    if (match) {
                        DatabaseBackup.openDeleteModal(match[1], match[2]);
                    }
                });
            }
        });
    },

    switchTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.add('hidden'));
        
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('text-gray-600', 'hover:text-gray-800');
        });
        
        document.getElementById(tabName).classList.remove('hidden');
        
        if (tabName === 'tab-export') {
            document.getElementById('btn-tab-export').classList.remove('text-gray-600', 'hover:text-gray-800');
            document.getElementById('btn-tab-export').classList.add('border-primary', 'text-primary');
        } else {
            document.getElementById('btn-tab-import').classList.remove('text-gray-600', 'hover:text-gray-800');
            document.getElementById('btn-tab-import').classList.add('border-primary', 'text-primary');
        }
    },

    openDeleteModal(fileName, deleteUrl) {
        const modal = document.getElementById('deleteModal');
        const fileNameSpan = document.getElementById('deleteFileName');
        const deleteForm = document.getElementById('deleteForm');

        if (fileNameSpan) {
            fileNameSpan.textContent = fileName;
        }
        
        if (deleteForm) {
            deleteForm.action = deleteUrl;
            
            const newForm = deleteForm.cloneNode(true);
            deleteForm.parentNode.replaceChild(newForm, deleteForm);
            
            newForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (typeof showLoading === 'function') {
                    showLoading('Menghapus file backup...');
                }
                
                setTimeout(() => {
                    e.target.submit();
                }, 1500);
            });
        }

        if (typeof openModal === 'function') {
            openModal('deleteModal');
        }
    },

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    DatabaseBackup.init();
    
    if (typeof setupModalBackdropClose === 'function') {
        setupModalBackdropClose('deleteModal');
    }
    
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.transition = 'all 1s ease-in-out';
            successAlert.style.opacity = '0';
            successAlert.style.maxHeight = '0';
            successAlert.style.paddingTop = '0';
            successAlert.style.paddingBottom = '0';
            successAlert.style.marginTop = '0';
            successAlert.style.marginBottom = '0';
            
            setTimeout(function() {
                successAlert.remove();
            }, 1000);
        }, 5000);
    }
    
    if (errorAlert) {
        setTimeout(function() {
            errorAlert.style.transition = 'all 1s ease-in-out';
            errorAlert.style.opacity = '0';
            errorAlert.style.maxHeight = '0';
            errorAlert.style.paddingTop = '0';
            errorAlert.style.paddingBottom = '0';
            errorAlert.style.marginTop = '0';
            errorAlert.style.marginBottom = '0';
            
            setTimeout(function() {
                errorAlert.remove();
            }, 1000);
        }, 7000);
    }
});