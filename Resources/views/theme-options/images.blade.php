@component('appearance::theme-options.layout')
<x-forms::thumbnail
    label="Logo"
    name="logo"            
    :value="isset($logo) ? uploadPath($logo) : ''"                       
/>
<x-forms::thumbnail
    label="Favicon"
    name="favicon"            
    :value="isset($favicon) ? uploadPath($favicon) : ''"                       
/>
@endcomponent