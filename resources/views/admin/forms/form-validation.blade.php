@extends('layouts.admin')
@section('title', 'Validation - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Validation
                                </h1>
                                <p>
                                    Provide valuable, actionable feedback to your users with HTML5 form validation, via browser default behaviors or custom styles and JavaScript.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Form Validation plugin
                                    </h3>
                                    <p>
                                        Validate for with modern and trendy looking feedbacks, with <a target="_blank" href="https://github.com/sha256/Pristine">PristineJS</a>
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form class="row g-3 js-validate floating-feedbacks" novalidate id="jsValidate">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationTextField" class="form-label">Text Field</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationTextField" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationNumberField" class="form-label">Number Field</label>
                                                <div class="form-control-wrap">
                                                    <input type="number" class="form-control" id="validationNumberField" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationEmailField" class="form-label">Email Field</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" id="validationEmailField" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationSelectField" class="form-label">Select Field</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="validationSelectField" required>
                                                        <option selected value="">
                                                            Choose...
                                                        </option>
                                                        <option>
                                                            Option one
                                                        </option>
                                                        <option>
                                                            Option two
                                                        </option>
                                                        <option>
                                                            Option three
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationFileField" class="form-label">File Field</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control" type="file" id="validationFileField" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <label for="validationPasswordField" class="form-label">Password Field</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="validationPasswordField" name="PasswordField" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlInputText7" class="form-label">Multiple Select</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="exampleFormControlInputText7" multiple required>
                                                        <option>
                                                            Open this select menu
                                                        </option>
                                                        <option value="1">
                                                            One
                                                        </option>
                                                        <option value="2">
                                                            Two
                                                        </option>
                                                        <option value="3">
                                                            Three
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="validationTextareaField" class="form-label">Textarea Field</label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" id="validationTextareaField" rows="3" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">CheckBox Fields</label>
                                                <div class="form-control-wrap form-check-wrap">
                                                    <div class="form-inline gap g-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" min="1" name="validationCheckbox" type="checkbox" required id="validationCheckboxOne"><label class="form-check-label" for="validationCheckboxOne"> CheckBox 1 </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" min="1" name="validationCheckbox" type="checkbox" required id="validationCheckboxTwo"><label class="form-check-label" for="validationCheckboxTwo"> CheckBox 2 </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" min="1" name="validationCheckbox" type="checkbox" required id="validationCheckboxThree"><label class="form-check-label" for="validationCheckboxThree"> CheckBox 3 </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Radio Fields</label>
                                                <div class="form-control-wrap form-check-wrap">
                                                    <div class="form-inline gap g-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="validationRadio" type="radio" value="validationRadioOne" id="validationRadioOne" required><label class="form-check-label" for="validationRadioOne"> Radio 1 </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="validationRadio" type="radio" value="validationRadioTwo" id="validationRadioTwo" required><label class="form-check-label" for="validationRadioTwo"> Radio 2 </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="validationRadio" type="radio" value="validationRadioThree" id="validationRadioThree" required><label class="form-check-label" for="validationRadioThree"> Radio 3 </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                    <div class="form-note mt-3">
                                        Use <code>required</code> attribute to every form field to enable default validation.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Validation with Bootstrap
                                    </h3>
                                    <p>
                                        Not interested in third party plagin for validation? just use few lines of vanila js and bootstrap class.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form class="row g-3 form-validate" novalidate>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationCustom01" class="form-label">First name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationCustom01" value="" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationCustom02" class="form-label">Last name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationCustomUsername" class="form-label">Username</label>
                                                <div class="form-control-wrap">
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend">@</span><input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                                                        <div class="invalid-feedback">
                                                            Please choose a username.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="validationCustom03" class="form-label">City</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationCustom03" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid city.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationCustom04" class="form-label">State</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="validationCustom04" required>
                                                        <option selected disabled value="">
                                                            Choose...
                                                        </option>
                                                        <option>
                                                            ...
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a valid state.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationCustom05" class="form-label">Zip</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationCustom05" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid zip.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="validinvalidcheck" id="invalidCheck" required><label class="form-check-label" for="invalidCheck"> Agree to terms and conditions </label>
                                                <div class="invalid-feedback">
                                                    You must agree before submitting.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Browser defaults
                                    </h3>
                                    <p>
                                        Not interested in custom validation feedback messages or writing JavaScript to change form behaviors? All good, you can use the browser defaults.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationDefault01" class="form-label">First name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationDefault01" value="Mark" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationDefault02" class="form-label">Last name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationDefault02" value="Otto" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="validationDefaultUsername" class="form-label">Username</label>
                                                <div class="form-control-wrap">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="inputGroupPrepend2">@</span><input type="text" class="form-control" id="validationDefaultUsername" aria-describedby="inputGroupPrepend2" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="validationDefault03" class="form-label">City</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationDefault03" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationDefault04" class="form-label">State</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-select" id="validationDefault04" required>
                                                        <option selected disabled value="">
                                                            Choose...
                                                        </option>
                                                        <option>
                                                            one
                                                        </option>
                                                        <option>
                                                            two
                                                        </option>
                                                        <option>
                                                            three
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="validationDefault05" class="form-label">Zip</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="validationDefault05" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required><label class="form-check-label" for="invalidCheck2"> Agree to terms and conditions </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                    <div class="form-note mt-3">
                                        Use <code>required</code> attribute to every form field to enable default validation.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
