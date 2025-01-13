@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Agregar Producto</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Panel de Control</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Productos</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Editar Producto</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.products.update') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}" />
                @method('PUT')
                <div class="wg-box">
                        <fieldset class="name">
                            <div class="body-title mb-10">Nombre <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Ingresar nombre del producto"
                                name="name" tabindex="0" value="{{ $product->name }}" aria-required="true" required="">
                            <div class="text-tiny">No debe exceder los 100 caracteres el nombre del producto</div>
                        </fieldset>
                        @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                        <fieldset class="name">
                            <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Ingresar slug"
                                name="slug" tabindex="0" value="{{ $product->slug }}" aria-required="true" required="">
                            <div class="text-tiny"></div>
                        </fieldset>
                        @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                        <div class="gap22 cols">
                            <fieldset class="category">
                                <div class="body-title mb-10">Categoría <span class="tf-color-1">*</span>
                                </div>
                                <div class="select">
                                    <select class="" name="category_id">
                                        <option>Seleccionar Categoría</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? "selected":"" }}>{{ $category->name }}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                            @error('category_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror 
                            <fieldset class="brand">
                                <div class="body-title mb-10">Marca <span class="tf-color-1">*</span>
                                </div>
                                <div class="select">
                                    <select class="" name="brand_id">
                                        <option>Seleccionar Marca</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? "selected":"" }}>{{ $brand->name }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                            @error('brand_id') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                        </div>

                        <fieldset class="shortdescription">
                            <div class="body-title mb-10">Descipción corta <span
                                    class="tf-color-1">*</span></div>
                            <textarea class="mb-10 ht-150" name="short_description"
                                placeholder="Discripción corta del producto" tabindex="0" aria-required="true"
                                required="">{{ $product->short_description }}</textarea>
                            <div class="text-tiny"></div>
                        </fieldset>
                        @error('short_description') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                        <fieldset class="description">
                            <div class="body-title mb-10">Descripción detallada <span class="tf-color-1">*</span>
                            </div>
                            <textarea class="mb-10" name="description" placeholder="Descripción detallada del producto"
                                tabindex="0" aria-required="true" required="">{{ $product->description }}</textarea>
                            <div class="text-tiny"></div>
                        </fieldset>
                        @error('description') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    </div>
                    <div class="wg-box">
                        <fieldset>
                            <div class="body-title">Cargar imágenes <span class="tf-color-1">*</span>
                            </div>
                            <div class="upload-image flex-grow">
                                @if($product->image)
                                <div class="item" id="imgpreview" >
                                    <img src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                        class="effect8" alt="{{ $product->name }}">
                                </div>
                                @endif
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

                        <fieldset>
                            <div class="body-title mb-10">Cargar galería de imagenes</div>
                            <div class="upload-image mb-16">
                                @if($product->images)
                                    @foreach (explode(',',$product->images) as $img)
                                        <div class="item gitems">
                                            <img src="{{ asset('uploads/products/thumbnails') }}/{{ trim($img) }}" alt="{{$product->name}}">
                                        </div>
                                    @endforeach
                                @endif                                               
                                <div id="galUpload" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="text-tiny">Arrastra las imágenes aquí o <span
                                                class="tf-color">haz clic para buscarlas</span></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*"
                                            multiple="">
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        @error('images') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Precio Regular<span
                                        class="tf-color-1">*</span></div>
                                <input class="mb-10" type="text" placeholder="Ingresar precio de lista"
                                    name="regular_price" tabindex="0" value="{{ $product->regular_price }}" aria-required="true"
                                    required="">
                            </fieldset>
                            @error('regular_price') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                            <fieldset class="name">
                                <div class="body-title mb-10">Precio de Venta <span
                                        class="tf-color-1">*</span></div>
                                <input class="mb-10" type="text" placeholder="Ingresar precio de venta"
                                    name="sale_price" tabindex="0" value="{{ $product->sale_price }}" aria-required="true"
                                    required="">
                            </fieldset>
                            @error('sale_price') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                        </div>


                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10" type="text" placeholder="SKU" name="SKU"
                                    tabindex="0" value="{{ $product->SKU }}" aria-required="true" required="">
                            </fieldset>
                            @error('SKU') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                            <fieldset class="name">
                                <div class="body-title mb-10">Cantidad <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10" type="text" placeholder="Ingresar cantidad"
                                    name="quantity" tabindex="0" value="{{ $product->quantity }}" aria-required="true"
                                    required="">
                            </fieldset>
                            @error('quantity') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                        </div>

                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Stock</div>
                                <div class="select mb-10">
                                    <select class="" name="stock_status">
                                        <option value="instock" {{ $product->stock_status =="instock" ? "selected" : "" }}>En Stock</option>
                                        <option value="outofstock" {{ $product->stock_status =="outofstock" ? "selected" : "" }}>Sin Stock</option>
                                    </select>
                                </div>
                            </fieldset>
                            @error('stock_status') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                            <fieldset class="name">
                                <div class="body-title mb-10">Destacado</div>
                                <div class="select mb-10">
                                    <select class="" name="featured">
                                        <option value="0" {{ $product->featured =="0" ? "selected" : "" }}>No</option>
                                        <option value="1" {{ $product->featured =="1" ? "selected" : "" }}>Si</option>
                                    </select>
                                </div>
                            </fieldset>
                            @error('featured') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                        </div>  
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Guardar</button>
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
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
            $("#gFile").on("change",function(e){
                const photoInp = $("#gFile");
                const gphotos = this.files;
                $.each(gphotos,function(key,val){
                    $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" /></div>`);
                });
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