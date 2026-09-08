@extends('layouts.admin')
@section('title', 'Carousel - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Carousel
                                </h1>
                                <p>
                                    A slideshow component for cycling through elements—images or slides of text—like a carousel.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Slides only
                                    </h3>
                                    <p>
                                        Here’s a carousel with slides only. Note the presence of the <code>.d-block</code> and <code>.w-100</code> on carousel images to prevent browser default image alignment.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="image">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="image">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div id=&quot;carouselExampleSlidesOnly&quot; class=&quot;carousel slide&quot; data-bs-ride=&quot;carousel&quot;&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;image&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;image&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;image&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        With controls
                                    </h3>
                                    <p>
                                        Adding in the previous and next controls. We recommend using <code>button</code> elements, but you can also use <code>a</code> elements with <code>role="button"</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="iamge 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="iamge 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="iamge 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div id=&quot;carouselExampleControls&quot; class=&quot;carousel slide&quot; data-bs-ride=&quot;carousel&quot;&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;iamge 1&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;iamge 2&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;iamge 3&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleControls&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleControls&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        With indicators
                                    </h3>
                                    <p>
                                        You can also add the indicators to the carousel, alongside the controls, too.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button><button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button><button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div id=&quot;carouselExampleIndicators&quot; class=&quot;carousel slide&quot; data-bs-ride=&quot;true&quot;&gt;    &lt;div class=&quot;carousel-indicators&quot;&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleIndicators&quot; data-bs-slide-to=&quot;0&quot; class=&quot;active&quot; aria-current=&quot;true&quot; aria-label=&quot;Slide 1&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleIndicators&quot; data-bs-slide-to=&quot;1&quot; aria-label=&quot;Slide 2&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleIndicators&quot; data-bs-slide-to=&quot;2&quot; aria-label=&quot;Slide 3&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleIndicators&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleIndicators&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        With captions
                                    </h3>
                                    <p>
                                        Add captions to your slides easily with the <code> .carousel-caption</code> element within any <code>.carousel-item</code>
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button><button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button><button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        First slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the first slide.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        Second slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the second slide.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        Third slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the third slide.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div id=&quot;carouselExampleCaptions&quot; class=&quot;carousel slide&quot; data-bs-ride=&quot;false&quot;&gt;    &lt;div class=&quot;carousel-indicators&quot;&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleCaptions&quot; data-bs-slide-to=&quot;0&quot; class=&quot;active&quot; aria-current=&quot;true&quot; aria-label=&quot;Slide 1&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleCaptions&quot; data-bs-slide-to=&quot;1&quot; aria-label=&quot;Slide 2&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleCaptions&quot; data-bs-slide-to=&quot;2&quot; aria-label=&quot;Slide 3&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;First slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the first slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;Second slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the second slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;Third slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the third slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleCaptions&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleCaptions&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Crossfade
                                    </h3>
                                    <p>
                                        Add <code>.carousel-fade</code> to your carousel to animate slides with a fade transition instead of a slide.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div id=&quot;carouselExampleFade&quot; class=&quot;carousel slide carousel-fade&quot; data-bs-ride=&quot;carousel&quot;&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleFade&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleFade&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Individual <code>.carousel-item</code> interval
                                    </h3>
                                    <p>
                                        Add <code>data-bs-interval=""</code> to a <code>.carousel-item</code> to change the amount of time to delay between automatically cycling to the next item.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active" data-bs-interval="10000">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item" data-bs-interval="2000">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div id=&quot;carouselExampleInterval&quot; class=&quot;carousel slide&quot; data-bs-ride=&quot;carousel&quot;&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot; data-bs-interval=&quot;10000&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot; data-bs-interval=&quot;2000&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleInterval&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleInterval&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Disable touch swiping
                                    </h3>
                                    <p>
                                        Carousels support swiping left/right on touchscreen devices to move between slides. This can be disabled using the <code>data-bs-touch</code> attribute. The example below also does not include the <code>data-bs-ride</code> attribute so it doesn’t autoplay.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div id=&quot;carouselExampleControlsNoTouching&quot; class=&quot;carousel slide&quot; data-bs-touch=&quot;false&quot;&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleControlsNoTouching&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleControlsNoTouching&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Dark variant
                                    </h3>
                                    <p>
                                        Add <code>.carousel-dark</code> to the <code>.carousel</code> for darker controls, indicators, and captions. Controls have been inverted from their default white fill with the filter CSS property. Captions and controls have additional Sass variables that customize the color and background-color.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button><button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button><button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                        </div>
                                        <div class="carousel-inner">
                                            <div class="carousel-item active" data-bs-interval="10000">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        First slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the first slide.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="carousel-item" data-bs-interval="2000">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        Second slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the second slide.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('admin/images/slider/a.jpg') }}" class="d-block w-100" alt="...">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h5>
                                                        Third slide label
                                                    </h5>
                                                    <p>
                                                        Some representative placeholder content for the third slide.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button><button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div id=&quot;carouselExampleDark&quot; class=&quot;carousel carousel-dark slide&quot; data-bs-ride=&quot;carousel&quot;&gt;    &lt;div class=&quot;carousel-indicators&quot;&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleDark&quot; data-bs-slide-to=&quot;0&quot; class=&quot;active&quot; aria-current=&quot;true&quot; aria-label=&quot;Slide 1&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleDark&quot; data-bs-slide-to=&quot;1&quot; aria-label=&quot;Slide 2&quot;&gt;&lt;/button&gt;      &lt;button type=&quot;button&quot; data-bs-target=&quot;#carouselExampleDark&quot; data-bs-slide-to=&quot;2&quot; aria-label=&quot;Slide 3&quot;&gt;&lt;/button&gt;    &lt;/div&gt;    &lt;div class=&quot;carousel-inner&quot;&gt;      &lt;div class=&quot;carousel-item active&quot; data-bs-interval=&quot;10000&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;First slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the first slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot; data-bs-interval=&quot;2000&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;Second slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the second slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;      &lt;div class=&quot;carousel-item&quot;&gt;        &lt;img src=&quot;https://html.nioboard.themenio.com/images/slider/a.jpg&quot; class=&quot;d-block w-100&quot; alt=&quot;...&quot;&gt;        &lt;div class=&quot;carousel-caption d-none d-md-block&quot;&gt;          &lt;h5&gt;Third slide label&lt;/h5&gt;          &lt;p&gt;Some representative placeholder content for the third slide.&lt;/p&gt;        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;button class=&quot;carousel-control-prev&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleDark&quot; data-bs-slide=&quot;prev&quot;&gt;      &lt;span class=&quot;carousel-control-prev-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Previous&lt;/span&gt;    &lt;/button&gt;    &lt;button class=&quot;carousel-control-next&quot; type=&quot;button&quot; data-bs-target=&quot;#carouselExampleDark&quot; data-bs-slide=&quot;next&quot;&gt;      &lt;span class=&quot;carousel-control-next-icon&quot; aria-hidden=&quot;true&quot;&gt;&lt;/span&gt;      &lt;span class=&quot;visually-hidden&quot;&gt;Next&lt;/span&gt;    &lt;/button&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
