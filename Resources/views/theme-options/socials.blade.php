@component('appearance::theme-options.layout')
    <x-forms::group
        mode="input"
        label="Facebook"
        placeholder="https://facebook.com/..."
        name="facebook"
        :value="old('facebook', $facebook)"
        required
    />               
    <x-forms::group
        mode="input"
        label="Twitter"
        placeholder="https://twitter.com/..."
        name="twitter"
        :value="old('twitter', $twitter)"
        required
    />               
    <x-forms::group
        mode="input"
        label="Instagram"
        placeholder="https://instagram.com/..."
        name="instagram"
        :value="old('instagram', $instagram)"
        required
    />               
    <x-forms::group
        mode="input"
        label="Youtube"
        placeholder="https://youtube.com/..."
        name="youtube"
        :value="old('youtube', $youtube)"
        required
    />               
    <x-forms::group
        mode="input"
        label="Linkedin"
        placeholder="https://linkedin.com/..."
        name="linkedin"
        :value="old('linkedin', $linkedin)"
        required
    />               
@endcomponent