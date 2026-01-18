import Quill from 'quill';

/**
 * Initialize Quill editor for content textarea
 * Works with Turbo navigation by using document.addEventListener
 */
function initializeQuillEditor() {
    const contentTextarea = document.getElementById('content');
    
    // Only initialize if textarea exists and Quill hasn't been initialized yet
    if (!contentTextarea || contentTextarea.dataset.quillInitialized === 'true') {
        return;
    }

    // Check if editor already exists (prevent duplicates on Turbo navigation)
    const existingEditor = document.getElementById('quill-editor');
    if (existingEditor) {
        return;
    }

    // Create editor container
    const editorContainer = document.createElement('div');
    editorContainer.id = 'quill-editor';
    editorContainer.className = 'quill-editor-container';
    
    // Create hidden input to store HTML content
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'content';
    hiddenInput.id = 'content-hidden';
    
    // Store original value before hiding textarea
    const originalValue = contentTextarea.value || '';
    
    // Insert editor container before textarea
    contentTextarea.parentNode.insertBefore(editorContainer, contentTextarea);
    contentTextarea.parentNode.insertBefore(hiddenInput, contentTextarea);
    
    // Hide original textarea and remove its name so it doesn't submit
    contentTextarea.style.display = 'none';
    contentTextarea.removeAttribute('required');
    contentTextarea.removeAttribute('name'); // Remove name to prevent duplicate submission
    
    // Initialize Quill
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write your post content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Set initial content if exists - use Quill's proper API to preserve formatting
    if (originalValue && originalValue.trim()) {
        // Normalize content to ensure proper block structure
        let normalizedContent = originalValue;
        
        // If content doesn't have proper HTML structure, wrap lines in paragraphs
        // This ensures each line/paragraph is treated as a separate block
        if (!normalizedContent.includes('<p>') && !normalizedContent.includes('<div>') && !normalizedContent.includes('<h')) {
            // Split by newlines and wrap each line in a paragraph
            const lines = normalizedContent.split(/\r?\n/).filter(line => line.trim());
            if (lines.length > 0) {
                normalizedContent = lines.map(line => `<p>${line.trim()}</p>`).join('');
            } else {
                normalizedContent = `<p>${normalizedContent}</p>`;
            }
        }
        
        // Use clipboard API to properly parse HTML and maintain structure
        // This ensures each paragraph/line is treated as a separate block
        try {
            const clipboard = quill.clipboard;
            const delta = clipboard.convert(normalizedContent);
            quill.setContents(delta, 'silent');
            // Move cursor to end after setting content
            quill.setSelection(quill.getLength(), 'silent');
        } catch (e) {
            // Fallback: if clipboard conversion fails, use dangerouslyPasteHTML
            // This will still parse HTML properly and maintain block structure
            quill.clipboard.dangerouslyPasteHTML(normalizedContent, 'silent');
        }
    }

    // Helper function to get clean content (handle Quill's empty state)
    function getCleanContent() {
        const html = quill.root.innerHTML;
        // Quill's empty state is <p><br></p> - treat this as empty
        if (html === '<p><br></p>' || html === '<p></p>' || !quill.getText().trim()) {
            return '';
        }
        return html;
    }

    // Initialize hidden input with current content
    hiddenInput.value = getCleanContent();

    // Sync Quill content to hidden input on text change
    quill.on('text-change', function() {
        hiddenInput.value = getCleanContent();
    });

    // Also sync on form submit to ensure latest content is captured
    const form = contentTextarea.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            hiddenInput.value = getCleanContent();
        });
    }

    // Add custom tooltips to toolbar buttons
    addTooltipsToToolbar(quill);

    // Mark as initialized
    contentTextarea.dataset.quillInitialized = 'true';
}

/**
 * Add custom tooltips to Quill toolbar buttons
 */
function addTooltipsToToolbar(quill) {
    // Use setTimeout to ensure toolbar is fully rendered
    setTimeout(() => {
        const toolbar = quill.getModule('toolbar');
        if (!toolbar) return;
        
        const toolbarElement = toolbar.container;
        if (!toolbarElement) return;

        // Add tooltips to all buttons
        const buttons = toolbarElement.querySelectorAll('button:not(.ql-picker-item)');
        
        buttons.forEach(button => {
            // Find the appropriate tooltip based on button classes
            let tooltipText = null;
            
            // Check for specific button types
            if (button.classList.contains('ql-header')) {
                tooltipText = 'Heading';
            } else if (button.classList.contains('ql-bold')) {
                tooltipText = 'Bold (Ctrl+B)';
            } else if (button.classList.contains('ql-italic')) {
                tooltipText = 'Italic (Ctrl+I)';
            } else if (button.classList.contains('ql-underline')) {
                tooltipText = 'Underline (Ctrl+U)';
            } else if (button.classList.contains('ql-strike')) {
                tooltipText = 'Strikethrough';
            } else if (button.classList.contains('ql-list')) {
                const value = button.getAttribute('value');
                tooltipText = value === 'ordered' ? 'Numbered List' : 'Bullet List';
            } else if (button.classList.contains('ql-color')) {
                tooltipText = 'Text Color';
            } else if (button.classList.contains('ql-background')) {
                tooltipText = 'Background Color';
            } else if (button.classList.contains('ql-align')) {
                tooltipText = 'Text Alignment';
            } else if (button.classList.contains('ql-link')) {
                tooltipText = 'Insert Link';
            } else if (button.classList.contains('ql-image')) {
                tooltipText = 'Insert Image';
            } else if (button.classList.contains('ql-clean')) {
                tooltipText = 'Clear Formatting';
            }
            
            // Add tooltip if found
            if (tooltipText && !button.getAttribute('title')) {
                button.setAttribute('title', tooltipText);
                button.setAttribute('data-tooltip', tooltipText);
            }
        });

        // Also handle picker labels (dropdowns)
        const pickerLabels = toolbarElement.querySelectorAll('.ql-picker-label');
        pickerLabels.forEach(label => {
            const picker = label.closest('.ql-picker');
            if (picker && !label.getAttribute('title')) {
                if (picker.classList.contains('ql-header')) {
                    label.setAttribute('title', 'Heading');
                    label.setAttribute('data-tooltip', 'Heading');
                } else if (picker.classList.contains('ql-color')) {
                    label.setAttribute('title', 'Text Color');
                    label.setAttribute('data-tooltip', 'Text Color');
                } else if (picker.classList.contains('ql-background')) {
                    label.setAttribute('title', 'Background Color');
                    label.setAttribute('data-tooltip', 'Background Color');
                } else if (picker.classList.contains('ql-align')) {
                    label.setAttribute('title', 'Text Alignment');
                    label.setAttribute('data-tooltip', 'Text Alignment');
                }
            }
        });
    }, 100);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initializeQuillEditor);

// Re-initialize on Turbo navigation (for SPA-like behavior)
document.addEventListener('turbo:load', initializeQuillEditor);
document.addEventListener('turbo:render', initializeQuillEditor);
