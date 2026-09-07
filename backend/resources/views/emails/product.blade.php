<x-mail::message>
# Introduction

Name : {{ $data['full_name'] ?? '' }} <br>
Position : {{ $data['position'] ?? '' }} <br>
Email : {{ $data['email'] ?? '' }} <br>
Mobile : {{ $data['phone'] ?? '' }} <br>
Company : {{ $data['company'] ?? '' }} <br>
City : {{ $data['city'] ?? '' }} <br>
Country : {{ $data['country'] ?? '' }}<br>
Message : {{ $data['i_message'] ?? '' }}


</x-mail::message>
