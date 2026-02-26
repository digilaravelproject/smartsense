@if(count($bannerTypeMainBanner) > 0)
<section class="bg-transparent py-3">
    <div class="full-width-slider" style="padding-left: 15px; padding-right: 15px;">
        <div class="owl-theme owl-carousel hero-slider">
            @foreach($bannerTypeMainBanner as $key=>$banner)
                <a href="{{$banner['url']}}" class="d-block" target="_blank">
                    <img class="w-100  " alt=""
                        style="border-radius: 25px;"
                        src="{{ getStorageImages(path: $banner->photo_full_url, type: 'banner') }}">
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
