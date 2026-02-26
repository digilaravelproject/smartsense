@if(count($combinations) > 0)
    <table class="table physical_product_show table-borderless">
        <thead class="thead-light thead-50 text-capitalize">
        <tr>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('SL') }}
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('attribute_Variation') }}
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('variation_Wise_Price') }}
                    ({{ getCurrencySymbol() }})
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('SKU') }}
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('Variant_Image') }}
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('Variant_Description') }}
                </label>
            </th>
            <th class="text-center">
                <label for="" class="control-label">
                    {{ translate('Variation_Wise_Stock') }}
                </label>
            </th>
        </tr>
        </thead>
        <tbody>

        @foreach ($combinations as $key => $combination)
            <tr>
                <td class="text-center">
                    {{ $key+1 }}
                </td>
                <td>
                    <label for="" class="control-label">{{ $combination['type'] }}</label>
                    <input value="{{ $combination['type'] }}" name="type[]" class="d-none">
                </td>
                <td>
                    <input type="number" name="price_{{ $combination['type'] }}"
                           value="{{ usdToDefaultCurrency(amount: $combination['price']) }}" min="0"
                           step="0.01"
                           class="form-control" required placeholder="{{ translate('ex') }}: {{ translate('535') }}">
                </td>
                <td>
                    <input type="text" name="sku_{{ $combination['type'] }}" value="{{ $combination['sku'] }}"
                           class="form-control store-keeping-unit">
                </td>
                <td>                    <div class="custom-file">
                        <input type="file" name="image_{{ $combination['type'] }}" id="image_{{ $combination['type'] }}"
                               class="custom-file-input"
                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                        <label class="custom-file-label"
                               for="image_{{ $combination['type'] }}">{{ translate('choose_file') }}</label>
                    </div>
                    @if(isset($combination['image']) && $combination['image'])
                        @php
                            $variationKey = str_replace('.', '_', $combination['type']);
                        @endphp
                        <div class="mt-2 position-relative d-inline-block variant-image-wrapper">
                            <img src="{{ getStorageImages(path: $combination['image'], type: 'product') }}" alt="" class="img-fluid variant-image-preview" style="height: 50px;">
                            <span class="remove-variant-image position-absolute" 
                                  style="top:0; right:0; cursor:pointer; background:#fff; border-radius:50%; padding:2px 4px; font-weight:bold; line-height:1;">&times;</span>
                            <input type="hidden" name="remove_image_{{ $variationKey }}" value="0" class="remove-image-input">
                        </div>
                    @endif
                </td>
                <td>
                    <textarea name="description_{{ $combination['type'] }}"
                              class="form-control" placeholder="{{ translate('Enter_description') }}"
                              rows="2">{{ $combination['description'] ?? '' }}</textarea>
                </td>
                <td>                    <input type="number" name="qty_{{ $combination['type'] }}"
                           value="{{ $combination['qty'] }}" min="0" max="100000" step="1"
                           class="form-control" placeholder="{{ translate('ex') }}: {{ translate('5') }}"
                           required>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
