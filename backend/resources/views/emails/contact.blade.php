<x-mail::message>
# New Contact form message has been received

Full Name : {{ $data['full_name'] ?? '' }} <br>
Company Name : {{ $data['company'] ?? '' }} <br>
Email : {{ $data['email'] ?? '' }} <br>
Phone : {{ $data['phone'] ?? '' }} <br>
Position : {{ $data['position'] ?? '' }} <br>
Company Website : {{ $data['website'] ?? '' }} <br>
Message : {{ $data['i_message'] ?? '' }}


</x-mail::message>
