@if(isset($product))
    @php
        $overallRating = getOverallRating($product->reviews);
        $productUrl = route('product', $product->slug);
        // Correctly determine hover image URL
        $hoverImageUrl = $product->thumbnail_full_url; // Default to thumbnail
        if (!empty($product->images_full_url) && isset($product->images_full_url[1])) {
            $hoverImageUrl = $product->images_full_url[1];
        }
    @endphp

    <div class="card-product style-4 h-100 get-view-by-onclick" data-link="{{ $productUrl }}">
        <div class="card-product-wrapper radius-16 line-2 asp-ratio-0">
            <a href="{{ $productUrl }}" class="product-img">
                <img class="img-product"
                     src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}"
                     alt="{{ $product['name'] }}">
                <img class="img-hover"
                     src="{{ getStorageImages(path: $hoverImageUrl, type: 'product') }}"
                     alt="{{ $product['name'] }} hover">
            </a>

            @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                <div class="on-sale-wrap">
                    <span class="on-sale-item">
                            -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                    </span>
                </div>
            @endif

            <ul class="list-product-btn">
                <li>
                    <a href="javascript:" onclick="addToCart('{{ $product->id }}', '{{ $productUrl }}');"
                       class="bg-surface hover-tooltip tooltip-left box-icon">
                        <span class="icon tio-shopping-cart-outlined"></span>
                        <span class="tooltip">{{ translate('add_to_cart') }}</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:" onclick="addWishlist('{{$product['id']}}','{{ $productUrl }}');"
                       class="bg-surface hover-tooltip tooltip-left box-icon">
                        <span class="icon tio-heart-outlined"></span>
                        <span class="tooltip">{{ translate('add_to_wishlist') }}</span>
                </a>
                </li>
                <li>
                    <a href="javascript:" 
                       data-product-id="{{ $product->id }}" 
                       class="bg-surface hover-tooltip tooltip-left box-icon quickview action-product-quick-view">
                        <span class="icon tio-visible-outlined"></span>
                        <span class="tooltip">{{ translate('quick_view') }}</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:" onclick="addCompareList('{{$product['id']}}','{{ $productUrl }}');"
                       class="bg-surface hover-tooltip tooltip-left box-icon">
                        <span class="icon tio-swap-horizontal"></span>
                        <span class="tooltip">{{ translate('add_to_compare') }}</span>
                </a>
                </li>
            </ul>
            @if($product->product_type == 'physical' && $product->current_stock <= 0)
                <span class="out_of_stock_badge">{{translate('out_of_stock')}}</span>
            @endif
        </div>

        <div class="card-product-info text-center">
            @if($overallRating[0] != 0)
                <div class="rating-show justify-content-center mb-1">
                    <span class="d-inline-block font-size-sm text-body">
                        @for($inc=1;$inc<=5;$inc++)
                            @if ($inc <= (int)$overallRating[0])
                                <i class="tio-star text-warning"></i>
                            @elseif ($overallRating[0] != 0 && $inc <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                <i class="tio-star-half text-warning"></i>
                            @else
                                <i class="tio-star-outlined text-warning"></i>
                            @endif
                        @endfor
                        <label class="badge-style text-muted">({{ count($product->reviews) }})</label>
                    </span>
                </div>
            @endif

            <a href="{{ $productUrl }}" class="name-product link fw-medium text-md font-2">
                {{ Str::limit($product['name'], 25) }}
                </a>

            <p class="price-wrap fw-medium">
                    @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                    <span class="price-old"><del>{{ webCurrencyConverter(amount: $product->unit_price) }}</del></span>
                @endif
                <span class="price-new">{{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}</span>
            </p>

            @php
                $tags = [];
                if ($product->category && $product->category->name) {
                    $tags[] = $product->category->name;
                }
                if ($product->brand && $product->brand->name && count($tags) < 2) {
                    $tags[] = $product->brand->name;
                }
            @endphp

            @if(!empty($tags))
                <ul class="list-color-product list-capacity-product justify-content-center mt-2">
                    @foreach($tags as $tag)
                        <li class="list-color-item">
                            <span class="text-quantity font-2">{{ Str::limit($tag, 15) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div style="height: 29px;" class="mt-2"></div> <!-- Spacer if no tags -->
                    @endif
        </div>
    </div>
@endif


