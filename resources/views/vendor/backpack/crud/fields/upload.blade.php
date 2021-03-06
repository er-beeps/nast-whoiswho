@php
   if (!isset($field['wrapper']) || !isset($field['wrapper']['data-init-function'])){
        $field['wrapper']['data-init-function'] = 'bpFieldInitUploadElement';
    }

    if (!isset($field['wrapper']) || !isset($field['wrapper']['data-field-name'])) {
        $field['wrapper']['data-field-name'] = $field['name'];
    }
@endphp

<!-- text input -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

	{{-- Show the file name and a "Clear" button on EDIT form. --}}
    @if (!empty($field['value']))
    <div class="well well-sm existing-file">
        @php
            
            $file_path = $field['value'];
            $data = explode('.', $file_path);
            $extension = $data[1];
        @endphp
        @if($extension == 'pdf')
            <div class="file-preview mr-5" style="display:inline-flex">
                <a class="fancybox" data-fancybox-type="iframe" href="{{ isset($field['disk'])?asset(\Storage::disk($field['disk'])->url($file_path)):asset($file_path) }}"> <i class="la la-file-pdf la-5x" style="color:red; position:relative;"></i></a>
                <a href="#" class="file_clear_button btn btn-danger btn-sm float-right file-clear-button" title="Clear file"><i class="la la-remove"></i></a>
            </div>
        @else
            <div class="file-preview mr-5" style="display:inline-flex">
                <a class="fancybox" rel="gallery" href="{{ isset($field['disk'])?asset(\Storage::disk($field['disk'])->url($file_path)):asset($file_path) }}">
                    <img style="max-height:100px; max-width:100x; border-radius:10px;" src="{{ isset($field['disk'])?asset(\Storage::disk($field['disk'])->url($file_path)):asset($file_path) }}" />
                </a>
                <a href="#" class="file_clear_button btn btn-danger btn-sm float-right file-clear-button" title="Clear file"><i class="la la-remove"></i></a>

            </div>
        @endif
    </div>
    @endif

	{{-- Show the file picker on CREATE form. --}}
    <div class="backstrap-file {{ isset($field['value']) && $field['value']!=null?'d-none':'' }}">
        <input
            type="file"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::fields.inc.attributes', ['default_class' =>  isset($field['value']) && $field['value']!=null?'file_input backstrap-file-input':'file_input backstrap-file-input'])
        >
        <label class="backstrap-file-label" for="customFile"></label>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')



{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_styles')
        <style type="text/css">
        	.file-clear-button{
			padding:0 !important;
			font-size:15px !important;
			width:20px !important;
			height:20px !important;
		    }
            .existing-file {
                border: 1px solid rgba(0,40,100,.12);
                border-radius: 5px;
                padding-left: 10px;
                vertical-align: middle;
            }
            .existing-file a {
                padding-top: 5px;
                display: inline-block;
                font-size: 0.9em;
            }
            .backstrap-file {
              position: relative;
              display: inline-block;
              width: 100%;
              height: calc(1.5em + 0.75rem + 2px);
              margin-bottom: 0;
            }

            .backstrap-file-input {
              position: relative;
              z-index: 2;
              width: 100%;
              height: calc(1.5em + 0.75rem + 2px);
              margin: 0;
              opacity: 0;
            }

            .backstrap-file-input:focus ~ .backstrap-file-label {
              border-color: #acc5ea;
              box-shadow: 0 0 0 0rem rgba(70, 127, 208, 0.25);
            }

            .backstrap-file-input:disabled ~ .backstrap-file-label {
              background-color: #e4e7ea;
            }

            .backstrap-file-input:lang(en) ~ .backstrap-file-label::after {
              content: "Browse";
            }

            .backstrap-file-input ~ .backstrap-file-label[data-browse]::after {
              content: attr(data-browse);
            }

            .backstrap-file-label {
              position: absolute;
              top: 0;
              right: 0;
              left: 0;
              z-index: 1;
              height: calc(1.5em + 0.75rem + 2px);
              padding: 0.375rem 0.75rem;
              font-weight: 400;
              line-height: 1.5;
              color: #5c6873;
              background-color: #fff;
              border: 1px solid #e4e7ea;
              border-radius: 0.25rem;
              font-weight: 400!important;
            }

            .backstrap-file-label::after {
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
              z-index: 3;
              display: block;
              height: calc(1.5em + 0.75rem);
              padding: 0.375rem 0.75rem;
              line-height: 1.5;
              color: #5c6873;
              content: "Browse";
              background-color: #f0f3f9;
              border-left: inherit;
              border-radius: 0 0.25rem 0.25rem 0;
            }
        </style>
    @endpush

    @push('crud_fields_scripts')
    <script>
        $(document).ready(function() {
            $(".fancybox").fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                iframe : {
                    preload: false
                }
            });
        });
    </script>

        <!-- no scripts -->
        <script>
            function bpFieldInitUploadElement(element) {
                var fileInput = element.find(".file_input");
                var fileClearButton = element.find(".file_clear_button");
                var fieldName = element.attr('data-field-name');
                var inputWrapper = element.find(".backstrap-file");
                var inputLabel = element.find(".backstrap-file-label");

                fileClearButton.click(function(e) {
                    e.preventDefault();
                    $(this).parent().addClass('d-none');

                    fileInput.parent().removeClass('d-none');
                    fileInput.attr("value", "").replaceWith(fileInput.clone(true));

                    // redo the selector, so we can use the same fileInput variable going forward
                    fileInput = element.find(".file_input");

                    // add a hidden input with the same name, so that the setXAttribute method is triggered
                    $("<input type='hidden' name='"+fieldName+"' value=''>").insertAfter(fileInput);
                });

                fileInput.change(function() {
                    var path = $(this).val();
                    var path = path.replace("C:\\fakepath\\", "");
                    inputLabel.html(path);
                    // remove the hidden input, so that the setXAttribute method is no longer triggered
                    $(this).next("input[type=hidden]").remove();
                });

            }
        </script>
    @endpush
@endif
