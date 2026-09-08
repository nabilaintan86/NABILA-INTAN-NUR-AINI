@extends('layouts.admin')
@section('title', 'Flex - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Flex
                                </h1>
                                <p>
                                    Quickly manage the layout, alignment, and sizing of grid columns, navigation, components, and more with a full suite of responsive flexbox utilities. For more complex implementations, custom CSS may be necessary.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Enable flex behaviors
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex p-2 bg-secondary text-white mb-3">
                                        I'm a flexbox container!
                                    </div>
                                    <div class="d-inline-flex p-2 bg-secondary text-white">
                                        I'm an inline flexbox container!
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;d-flex p-2 bg-secondary text-white mb-3&quot;&gt;I'm a flexbox container!&lt;/div&gt;&lt;div class=&quot;d-inline-flex p-2 bg-secondary text-white&quot;&gt;I'm an inline flexbox container!&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Direction
                                    </h3>
                                    <p>
                                        Use <code>.flex-row</code> to set a horizontal direction (the browser default), or <code>.flex-row-reverse</code> to start the horizontal direction from the opposite side.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item 1
                                        </div>
                                        <div class="p-2">
                                            Flex item 2
                                        </div>
                                        <div class="p-2">
                                            Flex item 3
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row-reverse bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item 1
                                        </div>
                                        <div class="p-2">
                                            Flex item 2
                                        </div>
                                        <div class="p-2">
                                            Flex item 3
                                        </div>
                                    </div>
                                    <p class="mt-5">
                                        Use <code>.flex-column</code> to set a vertical direction, or <code>.flex-column-reverse</code> to start the vertical direction from the opposite side.
                                    </p>
                                    <div class="d-flex flex-column mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item 1
                                        </div>
                                        <div class="p-2">
                                            Flex item 2
                                        </div>
                                        <div class="p-2">
                                            Flex item 3
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column-reverse bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item 1
                                        </div>
                                        <div class="p-2">
                                            Flex item 2
                                        </div>
                                        <div class="p-2">
                                            Flex item 3
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            html
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;d-flex flex-row mb-3&quot;&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 1&lt;/div&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 2&lt;/div&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 3&lt;/div&gt;&lt;/div&gt;&lt;div class=&quot;d-flex flex-row-reverse&quot;&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 1&lt;/div&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 2&lt;/div&gt;&lt;div class=&quot;p-2&quot;&gt;Flex item 3&lt;/div&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Justify content
                                    </h3>
                                    <p>
                                        Use <code>justify-content</code> utilities on flexbox containers to change the alignment of flex items on the main axis (the x-axis to start, y-axis if flex-direction: column). Choose from start (browser default), end, center, between, around, or evenly.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-start mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-around mb-3 bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-evenly bg-secondary text-white">
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                        <div class="p-2">
                                            Flex item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Align items
                                    </h3>
                                    <p>
                                        Use <code>align-items</code> utilities on flexbox containers to change the alignment of flex items on the cross axis (the y-axis to start, x-axis if flex-direction: column). Choose from start, end, center, baseline, or stretch (browser default).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-baseline mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-stretch mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Align self
                                    </h3>
                                    <p>
                                        Use <code>align-self</code> utilities on flexbox items to individually change their alignment on the cross axis (the y-axis to start, x-axis if flex-direction: column). Choose from the same options as align-items: start, end, center, baseline, or stretch (browser default).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border align-self-start">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border align-self-end">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex  mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border align-self-center">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex  mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border align-self-baseline">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                    <div class="d-flex  mb-3 bg-secondary text-white" style="height: 100px">
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                        <div class="p-2 border align-self-stretch">
                                            Flex item
                                        </div>
                                        <div class="p-2 border">
                                            Flex item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Fill
                                    </h3>
                                    <p>
                                        Use the <code> .flex-fill</code> class on a series of sibling elements to force them into widths equal to their content (or equal widths if their content does not surpass their border-boxes) while taking up all available horizontal space.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex bg-secondary text-white">
                                        <div class="p-2 flex-fill border">
                                            Flex item with a lot of content
                                        </div>
                                        <div class="p-2 flex-fill border">
                                            Flex item
                                        </div>
                                        <div class="p-2 flex-fill border">
                                            Flex item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
