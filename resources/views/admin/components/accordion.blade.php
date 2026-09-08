@extends('layouts.admin')
@section('title', 'Accordion - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Accordion
                                </h1>
                                <p>
                                    Build vertically collapsing accordions in combination with our Collapse JavaScript plugin
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                    <p>
                                        Click the accordions below to expand/collapse the accordion content.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"> Accordion Item #1 </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"> Accordion Item #2 </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"> Accordion Item #3 </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;accordion&quot; id=&quot;accordionExample&quot;&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseOne&quot;&gt;          Accordion Item #1        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;collapseOne&quot; class=&quot;accordion-collapse collapse show&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the first item's accordion body.&lt;/strong&gt; It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseTwo&quot;&gt;          Accordion Item #2        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the second item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseThree&quot;&gt;          Accordion Item #3        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;collapseThree&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the third item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Flush
                                    </h3>
                                    <p>
                                        Add <code>.accordion-flush</code> to remove the default <code>background-color</code>, some borders, and some rounded corners to render accordions edge-to-edge with their parent container.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"> Accordion Item #1 </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"> Accordion Item #2 </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"> Accordion Item #3 </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.
                                                </div>
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
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;accordion accordion-flush&quot; id=&quot;accordionFlushExample&quot;&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseOne&quot;&gt;          Accordion Item #1        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;flush-collapseOne&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the first item's accordion body.&lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseTwo&quot;&gt;          Accordion Item #2        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;flush-collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the second item's accordion body. Let's imagine this being filled with some actual content.&lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseThree&quot;&gt;          Accordion Item #3        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;flush-collapseThree&quot; class=&quot;accordion-collapse collapse&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.&lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Always open
                                    </h3>
                                    <p>
                                        Omit the <code>data-bs-parent</code> attribute on each <code>.accordion-collapse</code> to make accordion items stay open when another item is opened.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne"> Accordion Item #1 </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo"> Accordion Item #2 </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree"> Accordion Item #3 </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
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
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;accordion&quot; id=&quot;accordionPanelsStayOpenExample&quot;&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseOne&quot;&gt;          Accordion Item #1        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;panelsStayOpen-collapseOne&quot; class=&quot;accordion-collapse collapse show&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the first item's accordion body.&lt;/strong&gt; It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseTwo&quot;&gt;          Accordion Item #2        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;panelsStayOpen-collapseTwo&quot; class=&quot;accordion-collapse collapse&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the second item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;    &lt;div class=&quot;accordion-item&quot;&gt;      &lt;h2 class=&quot;accordion-header&quot;&gt;        &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseThree&quot;&gt;          Accordion Item #3        &lt;/button&gt;      &lt;/h2&gt;      &lt;div id=&quot;panelsStayOpen-collapseThree&quot; class=&quot;accordion-collapse collapse&quot;&gt;        &lt;div class=&quot;accordion-body&quot;&gt;          &lt;strong&gt;This is the third item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.        &lt;/div&gt;      &lt;/div&gt;    &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
