@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Información de la Categoría</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Panel de Control</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{route('admin.categories')}}">
                            <div class="text-tiny">Categorías</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Nueva Categoría</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{route('admin.categories.store')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf    
                    <fieldset class="name">
                        <div class="body-title">Nombre <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nombre" name="name"
                            tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                    </fieldset>
                    @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title">Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="categoria-slug-ejemplo" name="slug"
                            tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                    </fieldset>
                    @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <fieldset>
                        <div class="body-title">Subir Imágenes <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="upload-1.html" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Arrastra las imágenes aquí o <span
                                            class="tf-color">haz clic para buscarlas</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $("#myFile").on("change",function(e){
                const photoInp = $("#myFile");
                const [file] = this.files;
                if(file)
                {
                    $("#imgpreview img").attr('src',URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
            $("input[name='name']").on("change",function()
                {
                    $("input[name='slug']").val(StringToSlug($(this).val()));
                });
        });

        function StringToSlug(Text){
            return Text.toLowerCase()
                .replace(/[^\w ]+/g,"")
                .replace(/ +/g,"-");
        }
    </script>
@endpush