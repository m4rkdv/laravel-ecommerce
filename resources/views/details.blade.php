@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-md-1 pb-md-3"></div>
    <section class="product-single container">
      <div class="row">
        <div class="col-lg-7">
          <div class="product-single__media" data-media-type="vertical-thumbnail">
            <div class="product-single__image">
              <div class="swiper-container">
                <div class="swiper-wrapper">

                  <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $product->image }}" width="674"
                      height="674" alt="" />
                    <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $product->image }}" data-bs-toggle="tooltip"
                      data-bs-placement="left" title="Zoom">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_zoom" />
                      </svg>
                    </a>
                  </div>
                  @foreach (explode(',',$product->images) as $gImg)
                    <div class="swiper-slide product-single__image-item">
                        <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $gImg }}" width="674"
                        height="674" alt="" />
                        <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $gImg }}" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Zoom">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_zoom" />
                        </svg>
                        </a>
                    </div>
                  @endforeach

                  
                </div>
                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_prev_sm" />
                  </svg></div>
                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                    xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_next_sm" />
                  </svg></div>
              </div>
            </div>
            <div class="product-single__thumbnail">
              <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                        src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}" width="104" height="104" alt="{{ $product->name }}" /></div>
                    @foreach (explode(',',$product->images) as $gImg) 
                        <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                            src="{{ asset('uploads/products/thumbnails') }}/{{ $gImg }}" width="104" height="104" alt="" /></div>
                    @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="d-flex justify-content-between mb-4 pb-md-2">
            <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
              <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Incio</a>
              <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
              <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Tienda</a>
            </div><!-- /.breadcrumb -->

            <div
              class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
              <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_md" />
                </svg><span class="menu-link menu-link_us-s">Prev</span></a>
              <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">Sig</span><svg
                  width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_md" />
                </svg></a>
            </div><!-- /.shop-acs -->
          </div>
          <h1 class="product-single__name">{{ $product->name }}</h1>
          <div class="product-single__price">
            <span class="current-price">
                @if ($product->sale_price)
                    <s>${{ $product->regular_price }}</s> ${{ $product->sale_price }}
                @else
                    ${{ $product->regular_price }}                                    
                @endif
            </span>
          </div>
          <div class="product-single__short-desc">
            <p>{{ $product->short_description }}</p>
          </div>
          @if (Cart::instance('cart')->content()->where('id',$product->id)->count()>0)
            <a href="{{ route('cart.index') }}" class="btn btn-warning mb-3">Ir al Carrito</a>
          @else  
          <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
            @csrf
            <div class="product-single__addtocart">
              <div class="qty-control position-relative">
                <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                <div class="qty-control__reduce">-</div>
                <div class="qty-control__increase">+</div>
              </div><!-- .qty-control -->
              <input type="hidden" name="id" value="{{ $product->id }}" />
              <input type="hidden" name="name" value="{{ $product->name }}" />
              <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
              <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">Agregar al Carrito</button>
            </div>
          </form>
          @endif
          <div class="product-single__addtolinks">
            <share-button class="share-button">
              <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
                <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_sharing" />
                </svg>
                <span>Compartir</span>
              </button>
              <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
                <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
                <div id="Article-share-template__main"
                  class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
                  <div class="field grow mr-4">
                    <label class="field__label sr-only" for="url">Link</label>
                    <input type="text" class="field__input w-full" id="url"
                      value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health"
                      placeholder="Link" onclick="this.select();" readonly="">
                  </div>
                  <button class="share-button__copy no-js-hidden">
                    <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" viewBox="0 0 11 13">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                        fill="currentColor"></path>
                    </svg>
                    <span class="sr-only">Copiar link</span>
                  </button>
                </div>
              </details>
            </share-button>
            <script src="js/details-disclosure.html" defer="defer"></script>
            <script src="js/share.html" defer="defer"></script>
          </div>
          <div class="product-single__meta-info">
            <div class="meta-item">
              <label>SKU:</label>
              <span>{{ $product->SKU }}</span>
            </div>
            <div class="meta-item">
              <label>Categorias:</label>
              <span>{{ $product->category->name }}</span>
            </div>
            <div class="meta-item">
              <label>Tags:</label>
              <span>NA</span>
            </div>
          </div>
        </div>
      </div>
      <div class="product-single__details-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
              href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Descripción</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
              href="#tab-additional-info" role="tab" aria-controls="tab-additional-info"
              aria-selected="false">Información adicional</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
            aria-labelledby="tab-description-tab">
            <div class="product-single__description">
              {{ $product->description }}
            </div>
          </div>
          <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
            <div class="product-single__addtional-info">
              <div class="item">
                <label class="h6">Weight</label>
                <span>1.25 kg</span>
              </div>
              <div class="item">
                <label class="h6">Dimensions</label>
                <span>90 x 60 x 90 cm</span>
              </div>
              <div class="item">
                <label class="h6">Size</label>
                <span>XS, S, M, L, XL</span>
              </div>
              <div class="item">
                <label class="h6">Storage</label>
                <span>Relaxed fit shirt-style dress with a rugged</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="products-carousel container">
      <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4"><strong>Productos</strong> Relacionados</h2>

      <div id="related_products" class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
          <div class="swiper-wrapper">
            @foreach ($rproducts as $rProduct)
            <div class="swiper-slide product-card">
                <div class="pc__img-wrapper">
                  <a href="{{ route('shop.products.details',['product_slug'=>$rProduct->slug]) }}">
                    <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $rProduct->image }}" width="330" height="400"
                      alt="{{ $rProduct->name }}" class="pc__img">
                    @foreach (explode(',',$rProduct->images) as $gImg)  
                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $gImg }}" width="330" height="400"
                        alt="{{ $rProduct->name }}" class="pc__img pc__img-second">
                    @endforeach
                  </a>
                  @if (Cart::instance('cart')->content()->where('id',$product->id)->count()>0)
                      <a href="{{ route('cart.index') }}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning mb-3">Completar Compra</a>
                  @else
                      <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                          @csrf
                      <input type="hidden" name="id" value="{{ $product->id }}" />
                      <input type="hidden" name="quantity" value="1" />
                      <input type="hidden" name="name" value="{{ $product->name }}" />
                      <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />    
                      <button
                          type="submit"
                          class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium"
                          data-aside="cartDrawer" title="Add To Cart">Agregar al Carrito</button>
                  </form>    
                  @endif   
                </div>
  
                <div class="pc__info position-relative">
                  <p class="pc__category">{{ $rProduct->category->name }}</p>
                  <h6 class="pc__title"><a href="{{ route('shop.products.details',['product_slug'=>$rProduct->slug]) }}">{{ $rProduct->name }}</a></h6>
                  <div class="product-card__price d-flex">
                    <span class="money price">
                        @if ($product->sale_price)
                            <s>${{ $product->regular_price }}</s> ${{ $product->sale_price }}
                        @else
                            ${{ $product->regular_price }}                                    
                        @endif
                    </span>
                  </div>
                  
                  <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                    title="Add To Wishlist">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_heart" />
                    </svg>
                  </button>
                </div>
              </div>
            @endforeach
            
          </div><!-- /.swiper-wrapper -->
        </div><!-- /.swiper-container js-swiper-slider -->

        <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_prev_md" />
          </svg>
        </div><!-- /.products-carousel__prev -->
        <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_next_md" />
          </svg>
        </div><!-- /.products-carousel__next -->

        <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
        <!-- /.products-pagination -->
      </div><!-- /.position-relative -->

    </section><!-- /.products-carousel container -->
  </main>
@endsection