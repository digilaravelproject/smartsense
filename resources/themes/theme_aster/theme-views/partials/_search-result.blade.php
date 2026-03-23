<ul class="list-group list-group-flush">
    @foreach($products as $product)
        <li class="list-group-item bg--light">
            <a href="{{$product->details_url}}" >
                {{ $product['name'] }}
            </a>
        </li>
    @endforeach
</ul>
