@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slide</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Panel de control</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.slides') }}">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Nuevo Slider</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Tagline <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Eslogan" name="tagline"
                            tabindex="0" value="{{ old('tagline') }}" aria-required="true" required="">
                        @error('tagline')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Titulo <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="titulo" name="title"
                            tabindex="0" value="{{ old('title') }}" aria-required="true" required="">
                        @error('title')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Subtitulo <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Subtitulo" name="subtitle"
                            tabindex="0" value="{{ old('subtitle') }}" aria-required="true" required="">
                        @error('subtitle')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Link <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Links" name="link"
                            tabindex="0" value="{{ old('link') }}" aria-required="true" required="">
                        @error('link')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Cargar imagenes <span class="tf-color-1">*</span>
                        </div>
                        @error('image')
                                <span class="alert alert-danger text-center">{{ $message }}</span>
                            @enderror
                        <div class="upload-image flex-grow">
                            
                            <div class="item" id="imgpreview" style="display:none;">
                                <img src="sample.jpg" class="effect8" alt="">
                            </div>
                            <div class="item up-load">
                                
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Suelta tus imágenes aquí o selecciona.<span
                                            class="tf-color">click para buscar</span></span>
                                    <input type="file" id="myFile" name="image">
                                </label>
                              
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="category">
                        <div class="body-title">Estado</div>
                        <div class="select flex-grow">
                            <select class="" name="status">
                                <option>Seleccionar</option>
                                <option value="1" @if(old('status') == "1") selected @endif>Activo</option>
                                <option value="0" @if(old('status') == "0") selected @endif>Inactivo</option>
                            </select>
                            @error('status')
                                <span class="alert alert-danger text-center">{{ $message }}</span>
                            @enderror
                        </div>
                    </fieldset>
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
            <!-- /new-category -->
        </div>
        <!-- /main-content-wrap -->
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
        });
    </script>
@endpush