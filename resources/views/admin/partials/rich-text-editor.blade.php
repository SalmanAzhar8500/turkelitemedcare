@once
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function plainTextFromEditor(html) {
                    var source = document.createElement('div');
                    source.innerHTML = html
                        .replace(/<br\s*\/?>/gi, '\n')
                        .replace(/<\/(p|div|li|h[1-6])>/gi, '\n');

                    return source.textContent.replace(/\n{3,}/g, '\n\n').trim();
                }

                document.querySelectorAll('.js-rich-text').forEach(function (element) {
                    ClassicEditor.create(element, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
                    }).then(function (editor) {
                        element.closest('form').addEventListener('submit', function () {
                            element.value = plainTextFromEditor(editor.getData());
                        });
                    }).catch(function (error) {
                        console.error('CKEditor could not start:', error);
                    });
                });
            });
        </script>
    @endpush
@endonce
