@component('appearance::theme-options.layout')
<x-forms::thumbnail
    label="Logo"
    name="logo"            
    :value="isset($logo) ? uploadPath($logo) : ''"                       
/>
<x-forms::thumbnail
    label="Logo Light"
    name="logo_light"            
    :value="isset($logo_light) ? uploadPath($logo_light) : ''"                       
/>
<x-forms::thumbnail
    label="Favicon"
    name="favicon"            
    :value="isset($favicon) ? uploadPath($favicon) : ''"                       
/>
<x-forms::thumbnail
    label="Page Background"
    name="page_background"            
    :value="isset($page_background) ? uploadPath($page_background) : ''"                       
/>
<x-forms::thumbnail
    label="Title Background"
    name="title_background"            
    :value="isset($title_background) ? uploadPath($title_background) : ''"                       
/>
<x-forms::thumbnail
    label="Thumbnail Background"
    name="thumbnail_background"            
    :value="isset($thumbnail_background) ? uploadPath($thumbnail_background) : ''"                       
/>
<x-forms::thumbnail
    label="Footer Background"
    name="footer_background"            
    :value="isset($footer_background) ? uploadPath($footer_background) : ''"                       
/>
@endcomponent