@component('mail::message')

Hi <b>{{$name}}</b>,

Thank you for yor registration.

To choose your password click on the following button.

<br />

@component('mail::button', ['url' => route('activate',$token)])


Reset Password <br/>

@endcomponent



Regards,<br />
DevBlog.

@endcomponent