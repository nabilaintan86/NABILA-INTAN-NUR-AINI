@extends('layouts.admin')
@section('title', 'Navs and tabs - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Tabs
                                </h1>
                                <p>
                                    Documentation and examples for how to use Bootstrap’s included navigation components.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Custom Tabs
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs mb-3 nav-tabs-s1">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#custom-home-tab-pane" type="button">Home</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#custom-profile-tab-pane" type="button">Profile</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#custom-contact-tab-pane" type="button">Contact</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="custom-home-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
                                        </div>
                                        <div class="tab-pane fade" id="custom-profile-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
                                        </div>
                                        <div class="tab-pane fade" id="custom-contact-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;ul class=&quot;nav nav-tabs mb-3 nav-tabs-s1&quot;&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link active&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#custom-home-tab-pane&quot; type=&quot;button&quot;&gt;Home&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#custom-profile-tab-pane&quot; type=&quot;button&quot;&gt;Profile&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#custom-contact-tab-pane&quot; type=&quot;button&quot;&gt;Contact&lt;/button&gt;  &lt;/li&gt;&lt;/ul&gt;&lt;div class=&quot;tab-content&quot; id=&quot;myTabContent&quot;&gt;  &lt;div class=&quot;tab-pane fade show active&quot; id=&quot;custom-home-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;custom-profile-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;custom-contact-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Default Tabs
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs mb-3">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button">Home</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button">Profile</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button">Contact</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="home-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
                                        </div>
                                        <div class="tab-pane fade" id="profile-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
                                        </div>
                                        <div class="tab-pane fade" id="contact-tab-pane">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;ul class=&quot;nav nav-tabs mb-3&quot;&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link active&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#home-tab-pane&quot; type=&quot;button&quot;&gt;Home&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#profile-tab-pane&quot; type=&quot;button&quot;&gt;Profile&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;tab&quot; data-bs-target=&quot;#contact-tab-pane&quot; type=&quot;button&quot;&gt;Contact&lt;/button&gt;  &lt;/li&gt;&lt;/ul&gt;&lt;div class=&quot;tab-content&quot;&gt;  &lt;div class=&quot;tab-pane fade show active&quot; id=&quot;home-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;profile-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;contact-tab-pane&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur cupiditate dolorem ipsa sequi rerum suscipit, tenetur exercitationem debitis! Incidunt obcaecati dolorem, mollitia est quaerat dignissimos amet placeat soluta eaque quas.  &lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Tabs Pills
                                    </h3>
                                    <p>
                                        Take that same HTML, but use <code>.nav-pills</code> instead:
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-home" type="button">Home</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button">Profile</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button">Contact</button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" disabled>Disabled</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="pills-home">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!
                                        </div>
                                        <div class="tab-pane fade" id="pills-contact">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!
                                        </div>
                                        <div class="tab-pane fade" id="pills-disabled">
                                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;ul class=&quot;nav nav-pills mb-3&quot;&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link active&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#pills-home&quot; type=&quot;button&quot;&gt;Home&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#pills-profile&quot; type=&quot;button&quot;&gt;Profile&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#pills-contact&quot; type=&quot;button&quot;&gt;Contact&lt;/button&gt;  &lt;/li&gt;  &lt;li class=&quot;nav-item&quot;&gt;    &lt;button class=&quot;nav-link&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#pills-disabled&quot; type=&quot;button&quot; disabled&gt;Disabled&lt;/button&gt;  &lt;/li&gt;&lt;/ul&gt;&lt;div class=&quot;tab-content&quot;&gt;  &lt;div class=&quot;tab-pane fade show active&quot; id=&quot;pills-home&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;pills-profile&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;pills-contact&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!  &lt;/div&gt;  &lt;div class=&quot;tab-pane fade&quot; id=&quot;pills-disabled&quot;&gt;    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut exercitationem dicta doloremque excepturi consectetur ipsa error nemo, dolor ad iste harum itaque, cum ipsam nihil deleniti accusamus, blanditiis libero repellat!  &lt;/div&gt;&lt;/div&gt; </code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Vertical pills
                                    </h3>
                                    <p>
                                        And with vertical pills. Ideally, for vertical tabs.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-start g-gs">
                                        <div class="col-lg-3 col-xl-2">
                                            <div class="nav flex-column nav-pills">
                                                <button class="nav-link text-start active" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button">Home</button><button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button">Profile</button><button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" disabled>Disabled</button><button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button">Messages</button><button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button">Settings</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-xl-10">
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="v-pills-home">
                                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-profile">
                                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-disabled">
                                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-messages">
                                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-settings">
                                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.
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
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;row align-items-start g-gs&quot;&gt;  &lt;div class=&quot;col-lg-3 col-xl-2&quot;&gt;    &lt;div class=&quot;nav flex-column nav-pills&quot;&gt;      &lt;button class=&quot;nav-link text-start active&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#v-pills-home&quot; type=&quot;button&quot;&gt;Home&lt;/button&gt;      &lt;button class=&quot;nav-link text-start&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#v-pills-profile&quot; type=&quot;button&quot;&gt;Profile&lt;/button&gt;      &lt;button class=&quot;nav-link text-start&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#v-pills-disabled&quot; type=&quot;button&quot; disabled&gt;Disabled&lt;/button&gt;      &lt;button class=&quot;nav-link text-start&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#v-pills-messages&quot; type=&quot;button&quot;&gt;Messages&lt;/button&gt;      &lt;button class=&quot;nav-link text-start&quot; data-bs-toggle=&quot;pill&quot; data-bs-target=&quot;#v-pills-settings&quot; type=&quot;button&quot;&gt;Settings&lt;/button&gt;    &lt;/div&gt;  &lt;/div&gt;  &lt;div class=&quot;col-lg-9 col-xl-10&quot;&gt;    &lt;div class=&quot;tab-content&quot;&gt;      &lt;div class=&quot;tab-pane fade show active&quot; id=&quot;v-pills-home&quot;&gt;        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.      &lt;/div&gt;      &lt;div class=&quot;tab-pane fade&quot; id=&quot;v-pills-profile&quot;&gt;        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.      &lt;/div&gt;      &lt;div class=&quot;tab-pane fade&quot; id=&quot;v-pills-disabled&quot;&gt;        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.      &lt;/div&gt;      &lt;div class=&quot;tab-pane fade&quot; id=&quot;v-pills-messages&quot;&gt;        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.      &lt;/div&gt;      &lt;div class=&quot;tab-pane fade&quot; id=&quot;v-pills-settings&quot;&gt;        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ab recusandae error architecto ea quos magnam culpa aliquid officiis quidem accusamus eius illum veritatis, facilis vero sit aliquam. Sed, odit est.      &lt;/div&gt;    &lt;/div&gt;  &lt;/div&gt;&lt;/div&gt; </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
