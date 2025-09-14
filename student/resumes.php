<?php
require_once "student_header.php";
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Resume Templates</h1>
                <p class="text-muted mb-0">Choose from our professional resume templates</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Corporate Template -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <div class="template-preview bg-light rounded mb-3" style="height: 300px; position: relative; overflow: hidden; border: 2px solid #e9ecef;">
                            <iframe src="resume/corporate.php?preview=1" 
                                    style="width: 100%; height: 100%; border: none; transform: scale(0.4); transform-origin: top left; width: 250%; height: 250%;"
                                    title="Corporate Resume Preview">
                            </iframe>
                        </div>
                        <h5 class="card-title">Corporate</h5>
                        <p class="text-muted small">Professional two-column layout with sidebar design. Perfect for corporate environments and formal applications.</p>
                    </div>
                    <div class="mt-auto">
                        <div class="d-grid">
                            <a href="resume/corporate.php" class="btn btn-primary" target="_blank">
                                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i> Use Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minimal Template -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <div class="template-preview bg-light rounded mb-3" style="height: 300px; position: relative; overflow: hidden; border: 2px solid #e9ecef;">
                            <iframe src="resume/minimal.php?preview=1" 
                                    style="width: 100%; height: 100%; border: none; transform: scale(0.4); transform-origin: top left; width: 250%; height: 250%;"
                                    title="Minimal Resume Preview">
                            </iframe>
                        </div>
                        <h5 class="card-title">Minimal</h5>
                        <p class="text-muted small">Clean, modern single-column design. Ideal for creative professionals and tech industry applications.</p>
                    </div>
                    <div class="mt-auto">
                        <div class="d-grid">
                            <a href="resume/minimal.php" class="btn btn-primary" target="_blank">
                                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i> Use Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vibrant Template -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <div class="template-preview bg-light rounded mb-3" style="height: 300px; position: relative; overflow: hidden; border: 2px solid #e9ecef;">
                            <iframe src="resume/vibrant.php?preview=1" 
                                    style="width: 100%; height: 100%; border: none; transform: scale(0.4); transform-origin: top left; width: 250%; height: 250%;"
                                    title="Vibrant Resume Preview">
                            </iframe>
                        </div>
                        <h5 class="card-title">Vibrant</h5>
                        <p class="text-muted small">Eye-catching gradient header with colorful design. Great for showcasing personality and standing out from the crowd.</p>
                    </div>
                    <div class="mt-auto">
                        <div class="d-grid">
                            <a href="resume/vibrant.php" class="btn btn-primary" target="_blank">
                                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i> Use Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.template-preview {
    border: 2px solid #e9ecef;
    transition: border-color 0.2s ease-in-out;
    position: relative;
}

.card:hover .template-preview {
    border-color: #007bff;
}

.template-preview iframe {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.template-preview iframe.loaded {
    opacity: 1;
}

.template-preview::before {
    content: 'Loading preview...';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #6c757d;
    font-size: 14px;
    z-index: 1;
}

.template-preview.loaded::before {
    display: none;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.loading-spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>

<script>
// Initialize Lucide icons
lucide.createIcons();

// Handle iframe loading states
document.addEventListener('DOMContentLoaded', function() {
    const iframes = document.querySelectorAll('.template-preview iframe');
    
    iframes.forEach(function(iframe) {
        const previewContainer = iframe.closest('.template-preview');
        
        iframe.addEventListener('load', function() {
            // Add loaded class to iframe and container
            iframe.classList.add('loaded');
            previewContainer.classList.add('loaded');
        });
        
        iframe.addEventListener('error', function() {
            // Handle iframe loading errors
            previewContainer.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #6c757d;">Preview not available</div>';
        });
    });
});
</script>

<?php require_once "student_footer.php"; ?>
