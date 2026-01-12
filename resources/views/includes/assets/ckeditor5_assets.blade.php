@push('scripts')
    <script src="{{ asset('/assets/teganas/ckeditor5/ckeditor.js') }}"> </</script>

    <script>
        $(function() {
            ClassicEditor.create( document.querySelector('.ckeditor') );

            ClassicEditor.create( document.querySelector( '.ckeditor_basic' ), {
                toolbar: [ 'bold', 'italic', 'bulletedList', 'numberedList' ],
            } )
        });
    </script>
@endpush
