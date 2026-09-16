@extends('layouts.admin')
@section('title', 'Checks and radios - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Checks and radios
                                </h1>
                                <p>
                                    Create consistent cross-browser and cross-device checkboxes and radios with our completely rewritten checks component.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Checkboxes
                                            </h6>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"><label class="form-check-label" for="flexCheckDefault"> Default </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked><label class="form-check-label" for="flexCheckChecked"> Checked </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDisabled" disabled><label class="form-check-label" for="flexCheckDisabled"> Disabled </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" checked disabled><label class="form-check-label" for="flexCheckCheckedDisabled"> Disabled checked </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Radios
                                            </h6>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="" name="flexRadioDefault" id="flexRadioDefault"><label class="form-check-label" for="flexRadioDefault"> Default </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="" name="flexRadioDefault" id="flexRadioChecked" checked><label class="form-check-label" for="flexRadioChecked"> Checked </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="" name="flexRadioDefaultDisable" id="flexRadioDisabled" disabled><label class="form-check-label" for="flexRadioDisabled"> Disabled </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="" name="flexRadioDefaultDisable" id="flexRadioCheckedDisabled" checked disabled><label class="form-check-label" for="flexRadioCheckedDisabled"> Disabled checked </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Switches
                                            </h6>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="" id="flexSwitchDefault"><label class="form-check-label" for="flexSwitchDefault"> Default </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="" id="flexSwitchChecked" checked><label class="form-check-label" for="flexSwitchChecked"> Checked </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="" id="flexSwitchDisabled" disabled><label class="form-check-label" for="flexSwitchDisabled"> Disabled </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="" id="flexSwitchCheckedDisabled" checked disabled><label class="form-check-label" for="flexSwitchCheckedDisabled"> Disabled checked </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <div class="form-note">
                                                Use <code>checked</code> attribute to <code>input</code> for checked mode, same as <code>disabled</code> for disabled mode. and use <code>.form-switch</code> to <code>.form-check</code> to enable switch mode.
                                            </div>
                                            <div class="form-note">
                                                <strong class="text-black">Note:</strong> you must add <code>type="checkbox"</code> on <code>input</code> when you want to enable switch mode.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            checkbox
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;div class=&quot;form-check&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;flexCheckDefault&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;flexCheckDefault&quot;&gt;    Default  &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            radio
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;div class=&quot;form-check&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; value=&quot;&quot;  name=&quot;flexRadioDefault&quot; id=&quot;flexRadioDefault&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;flexRadioDefault&quot;&gt;    Default  &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            switch
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;div class=&quot;form-check form-switch&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;flexSwitchDefault&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;flexSwitchDefault&quot;&gt;    Default  &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Sizing
                                    </h3>
                                    <p>
                                        Set heights using classes like <code>.form-check-xl</code>, <code>.form-check-lg</code> and <code>.form-check-sm</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Checkboxes
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap align-items-center gap gx-4 gy-2">
                                                <div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckSizeSm"><label class="form-check-label" for="flexCheckSizeSm"> Checkbox Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckSize"><label class="form-check-label" for="flexCheckSize"> Checkbox Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-check-lg">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckSizeLg"><label class="form-check-label" for="flexCheckSizeLg"> Checkbox Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-check-xl">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckSizeXl"><label class="form-check-label" for="flexCheckSizeXl"> Checkbox Label </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Radios
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap align-items-center gap gx-4 gy-2">
                                                <div>
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input" type="radio" value="" name="flexRadioSize" id="flexRadioSizeSm"><label class="form-check-label" for="flexRadioSizeSm"> Radio Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" value="" name="flexRadioSize" id="flexRadioSize"><label class="form-check-label" for="flexRadioSize"> Radio Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-check-lg">
                                                        <input class="form-check-input" type="radio" value="" name="flexRadioSize" id="flexRadioSizeLg"><label class="form-check-label" for="flexRadioSizeLg"> Radio Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-check-xl">
                                                        <input class="form-check-input" type="radio" value="" name="flexRadioSize" id="flexRadioSizeXl"><label class="form-check-label" for="flexRadioSizeXl"> Radio Label </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="overline-title">
                                                Switches
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap align-items-center gap gx-4 gy-2">
                                                <div>
                                                    <div class="form-check form-switch form-check-sm">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchSizeSm"><label class="form-check-label" for="flexSwitchSizeSm"> Switch Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchSize"><label class="form-check-label" for="flexSwitchSize"> Switch Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-switch form-check-lg">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchSizeLg"><label class="form-check-label" for="flexSwitchSizeLg"> Switch Label </label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check form-switch form-check-xl">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexSwitchSizeXl"><label class="form-check-label" for="flexSwitchSizeXl"> Switch Label </label>
                                                    </div>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;div class=&quot;form-check&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;flexCheckSize&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;flexCheckSize&quot;&gt;    Checkbox Label  &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Indeterminate
                                    </h3>
                                    <p>
                                        Checkboxes can utilize the <code>:indeterminate</code> pseudo class when manually set via JavaScript (there is no available HTML attribute for specifying it).
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate"><label class="form-check-label" for="flexCheckIndeterminate"> Indeterminate checkbox </label>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;div class=&quot;form-check&quot;&gt;    &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;flexCheckIndeterminate&quot;&gt;    &lt;label class=&quot;form-check-label&quot; for=&quot;flexCheckIndeterminate&quot;&gt;      Indeterminate checkbox    &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Inline
                                    </h3>
                                    <p>
                                        Group checkboxes or radios on the same horizontal row by adding <code>.form-check-inline</code> to any <code>.form-check</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="g-4">
                                        <li>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1"><label class="form-check-label" for="inlineCheckbox1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2"><label class="form-check-label" for="inlineCheckbox2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled><label class="form-check-label" for="inlineCheckbox3">3 (disabled)</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"><label class="form-check-label" for="inlineRadio1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"><label class="form-check-label" for="inlineRadio2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled><label class="form-check-label" for="inlineRadio3">3 (disabled)</label>
                                            </div>
                                        </li>
                                    </ul>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;div class=&quot;form-check form-check-inline&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; id=&quot;inlineCheckbox1&quot; value=&quot;option1&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;inlineCheckbox1&quot;&gt;1&lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Reverse
                                    </h3>
                                    <p>
                                        Put your checkboxes, radios, and switches on the opposite side with the <code>.form-check-reverse</code> modifier class.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-check-reverse">
                                        <input class="form-check-input" type="checkbox" value="" id="reverseCheck1"><label class="form-check-label" for="reverseCheck1"> Reverse checkbox </label>
                                    </div>
                                    <div class="form-check form-check-reverse">
                                        <input class="form-check-input" type="checkbox" value="" id="reverseCheck2" disabled><label class="form-check-label" for="reverseCheck2"> Disabled reverse checkbox </label>
                                    </div>
                                    <div class="form-check form-switch form-check-reverse">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckReverse"><label class="form-check-label" for="flexSwitchCheckReverse">Reverse switch checkbox input</label>
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
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;div class=&quot;form-check form-check-reverse&quot;&gt;  &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;reverseCheck1&quot;&gt;  &lt;label class=&quot;form-check-label&quot; for=&quot;reverseCheck1&quot;&gt;    Reverse checkbox  &lt;/label&gt;&lt;/div&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
